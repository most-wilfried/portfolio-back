<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'issuer',
        'date',
        'url',
        'file',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
