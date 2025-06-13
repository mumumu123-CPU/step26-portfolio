<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Hospital; 

class Specialty extends Model
{
    use HasFactory;
    public function hospitals() {
        return $this->belongsToMany(Hospital::class, 'specialty_hospital');
        //return $this->belongsToMany(Hospital::class);
    }
}

// 仮説立てる。なんで動いてるのか。質問する