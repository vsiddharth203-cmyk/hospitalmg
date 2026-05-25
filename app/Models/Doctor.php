<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'doctor_id', 'first_name', 'last_name', 'specialization', 'qualification',
        'experience_years', 'phone', 'email', 'license_number', 'consultation_fee',
        'bio', 'photo', 'status', 'available_days', 'available_from', 'available_to', 'user_id',
    ];

    protected $casts = [
        'available_days' => 'array',
        'consultation_fee' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->doctor_id)) {
                $last = static::withTrashed()->latest('id')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->doctor_id = 'DOC-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return 'Dr. ' . $this->first_name . ' ' . $this->last_name;
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
