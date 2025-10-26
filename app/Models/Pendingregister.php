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
        // ถ้า student_id อ้างอิงถึง 'code' ของ Student
        return $this->belongsTo(Student::class, 'student_id', 'u_id');

        // ถ้า student_id อ้างอิงถึง 'id' ของ Student (แบบปกติ)
        // return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function subject(): BelongsTo
    {
        // สมมติว่า subject_id อ้างอิงถึง 'id' ของ Subject (แบบปกติ)
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }
}
