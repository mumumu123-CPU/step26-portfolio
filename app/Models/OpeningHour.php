<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hospital;

class OpeningHour extends Model
{
    use HasFactory;

    public function hospital () {
        return $this->belongsTo(Hospital::class);
    }
}
