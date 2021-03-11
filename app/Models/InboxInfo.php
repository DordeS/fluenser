<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboxInfo extends Model
{
    use HasFactory;

    protected $table = 'inbox_info';

    protected $fillable = [
        'content',
        'upload',
    ];
}
