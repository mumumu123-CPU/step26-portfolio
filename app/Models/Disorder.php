<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Hospital; 

class Disorder extends Model
{
    use HasFactory;
    public function hospitals () {
        return $this->belongsToMany(Hospital::class);
    }
}
