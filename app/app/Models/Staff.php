<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model {
    use SoftDeletes;
    protected $fillable = ['staff_id','first_name','last_name','role','department','phone','email','join_date','salary','shift','status','user_id'];
    protected $casts = ['join_date'=>'date','salary'=>'decimal:2'];

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->staff_id)) {
                $last = static::withTrashed()->latest('id')->first();
                $nextId = $last ? $last->id + 1 : 1;
                $model->staff_id = 'STF-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getFullNameAttribute(): string { return $this->first_name . ' ' . $this->last_name; }
    public function user() { return $this->belongsTo(User::class); }
}
