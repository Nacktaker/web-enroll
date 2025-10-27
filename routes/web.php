<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

// Public account creation page (Thai: สร้างบัญชี)
Route::get('/create', function () {
    return view('users.create');
});

Route::post('/create', [\App\Http\Controllers\RegistrationController::class, 'store']);

Route::get('/home', function () {
    return view('indexs.home');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Users listing and view (requires authenticated user)
// Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'list'])->name('users.list');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'view'])->name('users.view');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Teachers
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.list');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{id}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{id}/approveform', [TeacherController::class, 'showapproveform'])->name('teachers.add-approve-form');
    Route::post('/teachers/{id}/approve', [TeacherController::class, 'addapprove'])->name('teachers.add-approve');
    Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    //Subject

// Group route สำหรับ subject
Route::controller(SubjectController::class)
    ->prefix('subjects')
    ->name('subjects.')
    ->group(function () {
        Route::get('/', 'list')->name('list');            // Route /subjects
        Route::get('/{subject}', 'view')->name('view');    // Route /subjects/{subject}
    });

    // Students
    Route::controller(StudentController::class)
    ->prefix('/students')
    ->name('students.')
    ->group(static function (): void {
    Route::get('', 'index')->name('list');
    Route::get('/create', 'create')->name('create');
    Route::post('','store')->name('store');
    Route::prefix('/{id}')->group(static function (): void {
        Route::get('', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
        Route::get('/addsubform', 'showaddsubform')->name('add-subject-form');
        Route::post('/addsub', 'addsub')->name('add-subject');
        Route::Get('/schedule', 'schedule')->name('schedule');
        Route::post('/waitingdelete', 'removewaiting')->name('removewaiting');
    });
});
    
// });

// Temporary debug route: shows whether student(s)/teacher(s) tables exist and row counts
Route::get('/debug-tables', function () {
    $tables = [
        'students', 'student',
        'teachers', 'teacher',
        'users'
    ];

    $result = [];
    foreach ($tables as $t) {
        $exists = Schema::hasTable($t);
        $count = null;
        if ($exists) {
            try {
                $count = DB::table($t)->count();
            } catch (\Throwable $e) {
                $count = 'error: ' . $e->getMessage();
            }
        }
        $result[$t] = ['exists' => $exists, 'count' => $count];
    }

    return response()->json($result);
});
Route::get('/debug-teacher', function() {
    // ---- ใส่ u_id ของครูที่คุณ "มั่นใจ" ว่ามี user ----
    $test_u_id = 122; // <--- แก้เลข 50 นี้
    // ------------------------------------------

    echo "<h1>กำลังทดสอบ...</h1>";

    // 1. หาครูด้วย u_id
    $teacher = App\Models\Teacher::where('u_id', $test_u_id)->first();
    if (!$teacher) {
        return "<b>[ล้มเหลว]</b> ไม่เจอครูที่มี u_id = $test_u_id";
    }
    echo "<b>[สำเร็จ]</b> 1. เจอครู: " . $teacher->teacher_code . "<br>";
    echo " - teachers.u_id คือ: " . $teacher->u_id . " (Data Type: " . gettype($teacher->u_id) . ")<br>";

    // 2. หา user ด้วย id
    $user = App\Models\User::find($test_u_id);
    if (!$user) {
        return "<b>[ล้มเหลว]</b> ไม่เจอ User ที่มี id = $test_u_id (นี่คือปัญหา!)";
    }
    echo "<b>[สำเร็จ]</b> 2. เจอ User: " . $user->name . "<br>";
    echo " - users.id คือ: " . $user->id . " (Data Type: " . gettype($user->id) . ")<br>";

    // 3. ทดสอบ Relationship
    $user_from_relation = $teacher->user;

    if ($user_from_relation) {
         echo "<b>[สำเร็จ]</b> 3. teacher->user ทำงาน! ชื่อที่ได้คือ: " . $user_from_relation->name;
    } else {
         echo "<b>[ล้มเหลว]</b> 3. teacher->user เป็น null <br>";
         echo "นี่คือจุดที่แปลกมาก เพราะข้อ 1 และ 2 สำเร็จ แต่ Relationship ล้มเหลว";
    }
});