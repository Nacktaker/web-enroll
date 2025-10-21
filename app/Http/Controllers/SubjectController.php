<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Subject;

class SubjectController extends Controller
{
    // แสดงรายชื่อวิชา
    public function list(): View
    {
        $subjects = Subject::orderBy('subject_id')->get();

        return view('subjects.list', [
            'subjects' => $subjects,
        ]);
    }

    // แสดงรายละเอียดวิชา
    public function view(string $subject): View
    {
        $subject = Subject::where('subject_id', $subject)->firstOrFail();

        return view('subjects.view', [
            'subject' => $subject,
        ]);
    }
}
