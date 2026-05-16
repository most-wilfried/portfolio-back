<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'project_type',
        'year',
        'status',
        'technologies',
        'github_url',
        'demo_url',
        'image',
    ];

    protected $casts = [
        'technologies' => 'array',
        'year' => 'integer',
    ];
}
