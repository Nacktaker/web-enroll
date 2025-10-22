<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
}
