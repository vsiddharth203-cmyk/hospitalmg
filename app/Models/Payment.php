<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_number', 'bill_id', 'patient_id', 'amount',
        'payment_date', 'payment_method', 'transaction_id', 'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->payment_number)) {
                $last = static::latest('id')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->payment_number = 'PAY-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function bill() { return $this->belongsTo(Bill::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
}
