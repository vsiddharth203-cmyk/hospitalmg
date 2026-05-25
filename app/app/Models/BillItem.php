<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BillItem extends Model {
    protected $fillable = ['bill_id','description','category','quantity','unit_price','total'];
    protected $casts = ['unit_price'=>'decimal:2','total'=>'decimal:2'];
    public function bill() { return $this->belongsTo(Bill::class); }
}
