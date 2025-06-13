<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disorder; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Specialty; 
use App\Models\Review;

class Hospital extends Model
{
    use HasFactory;
// PDO
    // App\Models\Hospital.php
    protected $fillable = [
        'name', 'address', 'type', 'homepage_url', 'map_url',
        'prefecture', 'station', 'day_of_week',
        'am_open', 'pm_open', 'treatment', 'feature',
        'phone',
    ];
    // 関数名＝リレーション名
    public function specialties() {
        // 多対多
        return $this->belongsToMany(Specialty::class, 'specialty_hospital');
        
    }
    
    public function disorders () {
        return $this->belongsToMany(Disorder::class);
    }

    public function reviews () {
        // １対多
        return $this->hasMany(Review::class);
    }
}
