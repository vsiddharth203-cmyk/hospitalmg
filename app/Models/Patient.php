<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'first_name', 'last_name', 'date_of_birth', 'gender',
        'blood_group', 'phone', 'email', 'address', 'city', 'state',
        'postal_code', 'emergency_contact_name', 'emergency_contact_phone',
        'emergency_contact_relation', 'medical_history', 'allergies',
        'insurance_provider', 'insurance_number', 'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // Auto-generate patient ID
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->patient_id)) {
                $last = static::withTrashed()->latest('id')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->patient_id = 'PAT-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
