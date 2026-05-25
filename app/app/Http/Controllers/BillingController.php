<?php

namespace App\Http\Controllers;

use App\Models\{Bill, BillItem, Payment, Patient, Appointment};
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with(['patient']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bill_number', 'like', "%$search%")
                  ->orWhereHas('patient', fn($p) => $p->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%"));
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);

        $bills = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_billed' => Bill::sum('total'),
            'total_paid'   => Bill::sum('paid_amount'),
            'total_pending'=> Bill::whereIn('status', ['sent','partial','overdue'])->sum('balance'),
        ];

        return view('billing.index', compact('bills', 'stats'));
    }

    public function create()
    {
        $patients = Patient::where('status', 'active')->get();
        $appointments = Appointment::with(['patient','doctor'])
            ->where('payment_status', 'pending')
            ->where('status', 'completed')
            ->get();
        return view('billing.create', compact('patients', 'appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'due_date'       => 'required|date',
            'items'          => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.category'    => 'required|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        $items = $request->items;
        $subtotal = collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $tax      = $request->tax ?? 0;
        $discount = $request->discount ?? 0;
        $total    = $subtotal + $tax - $discount;

        $bill = Bill::create([
            'patient_id'     => $request->patient_id,
            'appointment_id' => $request->appointment_id,
            'bill_date'      => today(),
            'due_date'       => $request->due_date,
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'discount'       => $discount,
            'total'          => $total,
            'paid_amount'    => 0,
            'balance'        => $total,
            'status'         => 'sent',
            'notes'          => $request->notes,
        ]);

        foreach ($items as $item) {
            BillItem::create([
                'bill_id'     => $bill->id,
                'description' => $item['description'],
                'category'    => $item['category'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('billing.show', $bill)
            ->with('success', "Bill {$bill->bill_number} created successfully.");
    }

    public function show(Bill $billing)
    {
        $billing->load(['patient', 'items', 'payments', 'appointment.doctor']);
        return view('billing.show', compact('billing'));
    }

    public function recordPayment(Request $request, Bill $billing)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:0.01|max:' . $billing->balance,
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,insurance,online',
            'transaction_id' => 'nullable|string',
            'notes'          => 'nullable|string',
        ]);

        Payment::create([
            'bill_id'        => $billing->id,
            'patient_id'     => $billing->patient_id,
            'amount'         => $request->amount,
            'payment_date'   => $request->payment_date,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'notes'          => $request->notes,
        ]);

        $newPaid   = $billing->paid_amount + $request->amount;
        $newBalance= $billing->total - $newPaid;
        $status    = $newBalance <= 0 ? 'paid' : 'partial';

        $billing->update(['paid_amount' => $newPaid, 'balance' => max(0, $newBalance), 'status' => $status]);

        return redirect()->route('billing.show', $billing)->with('success', 'Payment recorded successfully.');
    }

    public function destroy(Bill $billing)
    {
        $billing->update(['status' => 'cancelled']);
        return redirect()->route('billing.index')->with('success', 'Bill cancelled.');
    }
}
