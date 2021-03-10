<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfluencerInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'state',
        'follows',
        'followings',
        'posts',
        'avatar',
        'back-img',
        'arg_rate',
        'bf_rate',
        'tm_rate',
        'm_rate',
    ];

    protected $table = 'influencers_info';
}