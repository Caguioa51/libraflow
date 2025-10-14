<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $query = User::with(['borrowings' => function($q) {
            $q->where('status', 'borrowed');
        }]);

        // Search functionality
        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->role) {
            $query->where('role', $role);
        }

        // Filter by status
        if ($status = $request->status) {
            if ($status === 'active') {
                $query->whereDoesntHave('borrowings', function($q) {
                    $q->where('status', 'borrowed')->where('due_date', '<', now());
                });
            } elseif ($status === 'overdue') {
                $query->whereHas('borrowings', function($q) {
                    $q->where('status', 'borrowed')->where('due_date', '<', now());
                });
            }
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'active_borrowers' => User::whereHas('borrowings', function($q) {
                $q->where('status', 'borrowed');
            })->count(),
            'overdue_borrowers' => User::whereHas('borrowings', function($q) {
                $q->where('status', 'borrowed')->where('due_date', '<', now());
            })->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function borrowForUser(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Redirect to admin borrow page with user pre-selected
        return redirect()->route('borrowings.admin_borrow', ['user_id' => $user->id]);
    }

    public function viewHistory(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $borrowings = \App\Models\Borrowing::with(['book', 'user'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.users.history', compact('borrowings', 'user'));
    }
}
