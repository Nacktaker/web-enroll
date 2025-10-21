<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{

    use HasFactory;

    protected $fillable = [
        'subject_id',
        'subject_name',
        'subject_place',
        'subject_time',
        'teacher_first_name',
        'teacher_last_name',
        'teacher_code'
    ];
}
