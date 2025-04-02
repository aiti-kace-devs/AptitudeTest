<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'branch_id',
        'status'
    ];

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function programme(){
        return $this->belongsToMany(Programme::class, 'courses');
    }
}
