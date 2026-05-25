<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model {
    protected $fillable = ['appointment_id','patient_id','doctor_id','medicine_id','dosage','frequency','duration_days','instructions'];
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function medicine() { return $this->belongsTo(Medicine::class); }
}
