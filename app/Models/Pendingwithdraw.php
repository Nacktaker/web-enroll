<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendingwithdraw extends Model
{
    protected $table = 'pendingwithdraw';
    protected $fillable = [
        'stu_id',
        'subject_id',
    ];
    public function student(): BelongsTo
    {
        // pendingwithdraw.stu_id stores Student.stu_code — connect accordingly
        return $this->belongsTo(Student::class, 'stu_id', 'stu_code');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
        // subject_id = คอลัมน์ใน pendingregisters
        // subject_code = คอลัมน์ใน subjects
    }
}
