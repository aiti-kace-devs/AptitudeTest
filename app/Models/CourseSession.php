<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSession extends Model
{
    use HasFactory;

    public $fillable = ['name', 'course_id', 'course', 'session', 'course_time', 'status'];

    protected $table = 'course_sessions';

    public function slotLeft()
    {
        $used = UserAdmission::where('session', $this->id)->whereNotNull('confirmed')->count();
        return $this->limit - $used;
    }
}
