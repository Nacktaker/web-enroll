<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\Models\EnrollmentSetting;
use Illuminate\Database\QueryException;

class EnrollmentSettingsController extends Controller
{
    public function edit(): View
    {
        Gate::authorize('adminMenu', Auth::user());

        // Get current enrollment period
        $settings = EnrollmentSetting::getCurrentPeriod();

        return view('settings.enrollment', [
            'startDate' => $settings->start_date->format('Y-m-d'),
            'endDate' => $settings->end_date->format('Y-m-d'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('adminMenu', Auth::user());

        $data = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        try {
            // Update enrollment period in database
            EnrollmentSetting::create([
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);

            return redirect()->route('home')->with('status', 'ตั้งค่าช่วงเวลาลงทะเบียนเรียบร้อย');
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
            ]);
        }
    }
}