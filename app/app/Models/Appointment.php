<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_number', 'patient_id', 'doctor_id', 'appointment_date',
        'appointment_time', 'duration_minutes', 'type', 'status', 'reason',
        'notes', 'diagnosis', 'prescription', 'fee', 'payment_status',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'fee' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->appointment_number)) {
                $last = static::latest('id')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->appointment_number = 'APT-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'scheduled'  => 'bg-info',
            'confirmed'  => 'bg-primary',
            'in_progress'=> 'bg-warning',
            'completed'  => 'bg-success',
            'cancelled'  => 'bg-danger',
            'no_show'    => 'bg-secondary',
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }
}
