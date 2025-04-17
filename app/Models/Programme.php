<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_category_id',
        'title',
        'duration',
        'start_date',
        'end_date',
        'status',
        'image',
        'description',
        'content',
        'slug'
    ];

    public function course_category(){
        return $this->belongsTo(CourseCategory::class);
    }

    public function centre(){
        return $this->belongsToMany(Centre::class, 'courses');
    }
}
