<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
}
