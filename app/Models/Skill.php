<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'level',
        'icon',
        'color',
        'percentage',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'percentage' => 'integer',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
