<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    public function pendingStudents()
{
    return $this->belongsToMany(Student::class, 'pending_register','subject_id', 'student_id' );
}
public function student() 
    {
        return $this->belongsToMany(
            Student::class,
            'pending_registers',
            'subject_id',
            'student_id',
            'id',
            'code'
        );
    }
}
