<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $settings = SystemSetting::all()->keyBy('key');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            if (!auth()->user()->isAdmin()) {
                return redirect()->back()->with('error', 'Unauthorized access. Admin privileges required.');
            }

            $request->validate([
                'borrowing_duration_days' => 'required|integer|min:1|max:365',
                'max_renewals' => 'required|integer|min:0|max:10',
                'fine_per_day' => 'required|numeric|min:0|max:1000',
                'max_books_per_user' => 'required|integer|min:1|max:20',
                'grace_period_days' => 'required|integer|min:0|max:30',
                'max_overdue_days' => 'required|integer|min:1|max:365',
                'weekend_due_dates' => 'required|string|in:allow,move_to_monday,move_to_friday',
                'holiday_handling' => 'required|string|in:extend,strict',
                'self_service_enabled' => 'required|string|in:true,false',
                'email_notifications_enabled' => 'required|string|in:true,false',
            ]);

            $settings = [
                'borrowing_duration_days' => $request->borrowing_duration_days,
                'max_renewals' => $request->max_renewals,
                'fine_per_day' => $request->fine_per_day,
                'max_books_per_user' => $request->max_books_per_user,
                'grace_period_days' => $request->grace_period_days,
                'max_overdue_days' => $request->max_overdue_days,
                'weekend_due_dates' => $request->weekend_due_dates,
                'holiday_handling' => $request->holiday_handling,
                'self_service_enabled' => $request->input('self_service_enabled', 'false'),
                'email_notifications_enabled' => $request->input('email_notifications_enabled', 'false'),
            ];

            foreach ($settings as $key => $value) {
                $type = 'string'; // Default type

                // Numeric fields
                if (in_array($key, ['borrowing_duration_days', 'max_renewals', 'fine_per_day', 'max_books_per_user', 'grace_period_days', 'max_overdue_days'])) {
                    $type = 'number';
                }
                // Boolean fields
                elseif (in_array($key, ['self_service_enabled', 'email_notifications_enabled'])) {
                    $type = 'boolean';
                }
                // String fields (dropdowns)
                elseif (in_array($key, ['weekend_due_dates', 'holiday_handling'])) {
                    $type = 'string';
                }

                SystemSetting::set($key, $value, $type);
            }

            return redirect()->back()->with('success', 'System settings updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('System Settings Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating settings. Please try again.');
        }
    }
}
