<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inboxes extends Model
{
    use HasFactory;

    protected $table = 'inboxes';

    protected $fillable = [
        'user1_id',
        'user2_id',
    ];

}
