<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Pendingregister;
use App\Models\Pendingwithdraw;
use App\Models\StudentSubject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show registration approval form with all pending registrations
     */
    public function showapproveform(): View
    {
    Gate::authorize('adminMenu', Auth::user());
        // Get all pending registrations with related data
        $pen = Pendingregister::with([
            'student.user',
            'subject.teacher.user'
        ])->orderBy('created_at', 'desc')->get();

        return view('admins.add-approve-form', compact('pen'));
    }

    /**
     * Show withdrawal approval form with all pending withdrawals
     */
    public function showdropform(): View
    {
    Gate::authorize('adminMenu', Auth::user());
        // Get all pending withdrawals with related data
        $pen = Pendingwithdraw::with([
            'student.user',
            'subject.teacher.user'
        ])->orderBy('created_at', 'desc')->get();

        return view('admins.drop-approve-form', compact('pen'));
    }

    /**
     * Approve a subject registration
     */
    public function addapprove(Request $request, $id)
    {
    Gate::authorize('adminMenu', Auth::user());
        DB::beginTransaction();
        try {
            $data = $request->all();
            $subid = $data['sub'];
            $pending = Pendingregister::findOrFail($subid);

            // Create the approved enrollment
            StudentSubject::create([
                'stu_id' => $pending->student_id,
                'subject_id' => $pending->subject_id,
            ]);

            // Delete the pending request
            $pending->delete();

            DB::commit();
            return redirect()->back()->with('status', 'Add Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to approve registration: ' . $e->getMessage());
        }
    }

    /**
     * Reject a subject registration
     */
    public function dropApprove(Request $request, $id)
    {
    Gate::authorize('adminMenu', Auth::user());
        try {
            $pending = Pendingregister::findOrFail($id);
            $pending->delete();
            
            return redirect()->back()->with('status', 'Registration rejected');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject registration: ' . $e->getMessage());
        }
    }

    /**
     * Approve a subject withdrawal
     */
    public function confirmDrop(Request $request, $id)
    {
    Gate::authorize('adminMenu', Auth::user());
        DB::beginTransaction();
        try {
            $pending = Pendingwithdraw::findOrFail($id);

            // Find and delete the enrollment
            StudentSubject::where([
                'stu_id' => $pending->student_id,
                'subject_id' => $pending->subject_id,
            ])->delete();

            // Delete the pending withdrawal request
            $pending->delete();

            DB::commit();
            return redirect()->back()->with('status', 'Withdrawal approved successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to approve withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Reject a subject withdrawal
     */
    public function rejectDrop(Request $request, $id)
    {
        Gate::authorize('adminMenu', Auth::user());
        try {
            $pending = Pendingwithdraw::findOrFail($id);
            $pending->delete();
            
            return redirect()->back()->with('status', 'Withdrawal rejected');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject withdrawal: ' . $e->getMessage());
        }
    }
}