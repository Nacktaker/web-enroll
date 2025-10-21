<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Psr\Http\Message\ServerRequestInterface;
use Illuminate\View\View;
use App\Models\Subject;


class SubjectController extends Controller
{
    function list(): View
    {
        $subjects = Subject
            ::orderBy('subject_id')
            ->get();

        return view('subjects.list', [
            'subjects' => $subjects,
        ]);
    }
    function view(string $subject_id): View
    {
        $subject = Subject
            ::where('subject_id', $subject_id)
            ->firstOrFail();

        return view('subjects.view', [
            'subject' => $subject,
        ]);
    }
}