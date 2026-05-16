<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'age',
        'professional_title',
        'location',
        'short_description',
        'about_intro',
        'journey',
        'goals',
        'github_url',
        'linkedin_url',
        'whatsapp_number',
        'email',
        'phone',
        'avatar',
        'cv',
    ];
}
