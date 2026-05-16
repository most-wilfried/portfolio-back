<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'path',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];
}
