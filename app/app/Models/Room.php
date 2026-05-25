<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Room extends Model {
    protected $fillable = ['room_number','ward_id','type','bed_count','rate_per_day','status'];
    protected $casts = ['rate_per_day'=>'decimal:2'];
    public function ward() { return $this->belongsTo(Ward::class); }
    public function admissions() { return $this->hasMany(Admission::class); }
}
