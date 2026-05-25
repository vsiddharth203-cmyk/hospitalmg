<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model {
    protected $fillable = ['name','type','total_beds','description','status'];
    public function rooms() { return $this->hasMany(Room::class); }
    public function availableBeds(): int {
        return $this->rooms()->where('status','available')->sum('bed_count');
    }
}

// -----

