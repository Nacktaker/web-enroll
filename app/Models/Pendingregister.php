<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pendingregister extends Model
{
    protected $fillable = [
        'stu_id',
        'subject_id',
    ];
    public function student(): BelongsTo
    {
           return $this->belongsTo(Student::class, 'stu_id', 'stu_code');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
        // subject_id = คอลัมน์ใน pendingregisters
        // subject_code = คอลัมน์ใน subjects
    }
}
