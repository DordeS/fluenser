<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestInfo extends Model
{
    use HasFactory;

    protected $table = 'requestinfo';

    protected $fillable = [
        'content',
        'amount',
        'brand',
        'status',
        'accepted',
    ];
}