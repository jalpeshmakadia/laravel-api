<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Urls extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'url',
        'is_scraped'
    ];


}
