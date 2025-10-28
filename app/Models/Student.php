<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    /**
     * Explicit table name: some databases use singular table names.
     */
    protected $table = 'student';

    protected $fillable = [
        'u_id',
        'stu_code',
        'faculty',
        'department',
        'year',
    ];

    /**
     * The student belongs to a user record (u_id -> users.id)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }
    public function subject()
    {
        return $this->belongsToMany(Subject::class);
    }
    public function pendingSubjects()
    {
        return $this->belongsToMany(
            Subject::class,       // 1. โมเดลเป้าหมาย
            'pending_register',   // 2. ชื่อตาราง Pivot
            'student_id',         // 3. Foreign key ในตาราง Pivot ที่อ้างอิงถึง Student
            'subject_id'          // 4. Foreign key ในตาราง Pivot ที่อ้างอิงถึง Subject
        );
    }
    public function studentsubjects()
    {
        return $this->belongsToMany(
            Subject::class,       // 1. โมเดลเป้าหมาย
            'pending_register',   // 2. ชื่อตาราง Pivot
            'student_id',         // 3. Foreign key ในตาราง Pivot ที่อ้างอิงถึง Student
            'subject_id'          // 4. Foreign key ในตาราง Pivot ที่อ้างอิงถึง Subject
        );
    }
}
