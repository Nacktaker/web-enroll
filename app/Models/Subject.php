<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    protected $casts = [
        'teacher_code' => 'string',
    ];
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
    public function teacher(): BelongsTo
{
    // A. ถ้าคอลัมน์ในตาราง 'subjects' ชื่อ 'teacher_id' 
    //    โค้ดนี้ถูกต้อง
    return $this->belongsTo(Teacher::class, 'teacher_code', 'teacher_code');

    // B. แต่ถ้าคอลัมน์ในตาราง 'subjects' ชื่ออื่น
    //    เช่น 'teacher' หรือ 'teacher_code' คุณต้องระบุเอง
    // return $this->belongsTo(Teacher::class, 'teacher_code'); 
}
}
