@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-people me-2"></i>User Management</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </nav>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-2">
                    <div class="card text-center border-primary">
                        <div class="card-body">
                            <div class="fs-2 text-primary mb-2"><i class="bi bi-people"></i></div>
                            <h3 class="mb-0 text-primary">{{ $stats['total'] }}</h3>
                            <small class="text-muted">Total Users</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center border-info">
                        <div class="card-body">
                            <div class="fs-2 text-info mb-2"><i class="bi bi-mortarboard"></i></div>
                            <h3 class="mb-0 text-info">{{ $stats['students'] }}</h3>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center border-warning">
                        <div class="card-body">
                            <div class="fs-2 text-warning mb-2"><i class="bi bi-person-workspace"></i></div>
                            <h3 class="mb-0 text-warning">{{ $stats['teachers'] }}</h3>
                            <small class="text-muted">Teachers</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center border-danger">
                        <div class="card-body">
                            <div class="fs-2 text-danger mb-2"><i class="bi bi-shield-check"></i></div>
                            <h3 class="mb-0 text-danger">{{ $stats['admins'] }}</h3>
                            <small class="text-muted">Admins</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center border-success">
                        <div class="card-body">
                            <div class="fs-2 text-success mb-2"><i class="bi bi-check-circle"></i></div>
                            <h3 class="mb-0 text-success">{{ $stats['active_borrowers'] }}</h3>
                            <small class="text-muted">Active Borrowers</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center border-secondary">
                        <div class="card-body">
                            <div class="fs-2 text-secondary mb-2"><i class="bi bi-exclamation-triangle"></i></div>
                            <h3 class="mb-0 text-secondary">{{ $stats['overdue_borrowers'] }}</h3>
                            <small class="text-muted">Overdue</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filters & Search</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or student ID">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role">
                                <option value="">All Roles</option>
                                <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Registered Users</h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Student ID</th>
                                        <th>RFID Card</th>
                                        <th>Role</th>
                                        <th>Borrowing Status</th>
                                        <th>Books Borrowed</th>
                                        <th>Member Since</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $users->firstItem() + $loop->index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($user->profile_photo)
                                                        <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="Profile" class="rounded-circle me-2" width="32" height="32">
                                                    @else
                                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                            <i class="bi bi-person text-white"></i>
                                                        </div>
                                                    @endif
                                                    <strong>{{ $user->name }}</strong>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->student_id ?? 'N/A' }}</td>
                                            <td>
                                                @if($user->rfid_card)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-credit-card-2-front me-1"></i>{{ $user->rfid_card }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'teacher' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $activeBorrowings = $user->borrowings->count();
                                                    $overdueCount = $user->borrowings->where('due_date', '<', now())->count();
                                                @endphp
                                                @if($overdueCount > 0)
                                                    <span class="badge bg-danger">Overdue ({{ $overdueCount }})</span>
                                                @elseif($activeBorrowings > 0)
                                                    <span class="badge bg-success">Active ({{ $activeBorrowings }})</span>
                                                @else
                                                    <span class="badge bg-secondary">No Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $user->borrowings->count() }}</span>
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.users.borrow_for_user', $user->id) }}" class="btn btn-sm btn-outline-success" title="Borrow Books for User">
                                                        <i class="bi bi-book"></i> Borrow
                                                    </a>
                                                    <a href="{{ route('admin.users.view_history', $user->id) }}" class="btn btn-sm btn-outline-primary" title="View Borrowing History">
                                                        <i class="bi bi-clock-history"></i> History
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted mb-3"></i>
                            <h4 class="text-muted">No users found</h4>
                            <p class="text-muted">Try adjusting your search or filter criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
