<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'programme_id',
        'course_name',
        'location'
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
