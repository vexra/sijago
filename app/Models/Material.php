<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'link',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function uploader() // Guru yang mengunggah
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
