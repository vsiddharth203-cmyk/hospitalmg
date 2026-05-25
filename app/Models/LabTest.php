<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model {
    protected $fillable = ['test_number','patient_id','doctor_id','test_name','test_type','ordered_date','result_date','results','result_file','status','cost','notes'];
    protected $casts = ['ordered_date'=>'date','result_date'=>'date','cost'=>'decimal:2'];
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
}
