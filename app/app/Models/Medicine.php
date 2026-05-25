<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model {
    protected $fillable = ['name','generic_name','category','manufacturer','unit','price','stock_quantity','expiry_date','status'];
    protected $casts = ['expiry_date'=>'date','price'=>'decimal:2'];
    public function prescriptions() { return $this->hasMany(Prescription::class); }
}
