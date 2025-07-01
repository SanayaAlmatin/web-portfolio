<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Definisikan atribut yang bisa diisi secara massal
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'project_url',
    ];
}
