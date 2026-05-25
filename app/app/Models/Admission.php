<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'admission_number', 'patient_id', 'doctor_id', 'room_id',
        'admission_date', 'discharge_date', 'admission_reason', 'diagnosis',
        'treatment', 'discharge_notes', 'status', 'total_charges',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'total_charges'  => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->admission_number)) {
                $last = static::latest('id')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->admission_number = 'ADM-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function room() { return $this->belongsTo(Room::class); }
    public function bill() { return $this->hasOne(Bill::class); }

    public function getDurationAttribute(): ?int
    {
        if ($this->discharge_date) {
            return $this->admission_date->diffInDays($this->discharge_date);
        }
        return $this->admission_date->diffInDays(now());
    }
}
