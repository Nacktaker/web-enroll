<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    /**
     * Explicit table name: some databases use singular table names.
     */
    protected $table = 'teacher';

    

    protected $fillable = [
        'u_id',
        'teacher_code',
        'faculty',
    ];

    /**
     * The teacher belongs to a user record (u_id -> users.id)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }
    public function subjects(): HasMany
    {
        // ถ้าคอลัมน์ในตาราง subjects คือ 'teacher_id'
        // Laravel จะรู้ได้เองอัตโนมัติ
        return $this->hasMany(Subject::class,
    'teacher_code','teacher_code'); 
    }
}
