<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number', 'patient_id', 'appointment_id', 'admission_id',
        'bill_date', 'due_date', 'subtotal', 'tax', 'discount', 'total',
        'paid_amount', 'balance', 'status', 'notes',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date'  => 'date',
        'subtotal'  => 'decimal:2',
        'tax'       => 'decimal:2',
        'discount'  => 'decimal:2',
        'total'     => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance'   => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->bill_number)) {
                $last = static::latest('id')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->bill_number = 'BILL-' . date('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function patient() { return $this->belongsTo(Patient::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function admission() { return $this->belongsTo(Admission::class); }
    public function items() { return $this->hasMany(BillItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
