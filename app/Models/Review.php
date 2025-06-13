<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Hospital;

class Review extends Model
{
    use HasFactory;

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
