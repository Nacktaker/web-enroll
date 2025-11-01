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
    protected $fillable = [
        'subject_id',
        'subject_name',
        'subject_place',
        'subject_day',
        'subject_start_time',
        'subject_end_time',
        'teacher_code',
        'subject_description',
    ];
    public function pendingStudents()
{
    return $this->belongsToMany(Student::class, 'pending_register','subject_id', 'student_id' );
}
    public function studentsubject()
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

        /**
         * Students enrolled in this subject.
         *
         * There is a pivot table named `studentsubject` with columns:
         *  - subject_id (references subjects.subject_id)
         *  - stu_id (stores the student's stu_code)
         *
         * We map the pivot accordingly: pivot (subject_id, stu_id) -> (subjects.subject_id, student.stu_code).
         */
        public function students()
        {
            return $this->belongsToMany(
                Student::class,
                'studentsubject',    // pivot table
                'subject_id',         // foreign key on pivot referencing this Subject
                'stu_id',             // related key on pivot referencing Student (stores stu_code)
                'subject_id',         // local key on subjects table
                'stu_code'            // local key on students table
            );
        }
}
