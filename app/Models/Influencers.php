<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Influencers extends Model
{
    use HasFactory;

    protected $table = "influencers";

    protected $fillable = [
        'user_id',
    ];
}
