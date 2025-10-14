@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-credit-card-2-front me-2"></i>RFID Scanner</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">RFID Scan</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-credit-card-2-front me-2"></i>Scan RFID Card</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="rfid_input" class="form-label">RFID Card Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-lg" id="rfid_input"
                                           placeholder="Scan or enter RFID card number..." autocomplete="off">
                                    <button class="btn btn-primary" type="button" id="scan_btn">
                                        <i class="bi bi-upc-scan me-2"></i>Lookup User
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Enter the RFID card number manually or use an RFID scanner if connected.
                                </div>
                            </div>

                            <!-- User Info Display -->
                            <div id="user_info" class="d-none">
                                <h5>User Found:</h5>
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-3 text-center">
                                                <img id="user_photo" src="" alt="Profile" class="rounded-circle mb-3" width="80" height="80">
                                                <h6 id="user_name" class="mb-0"></h6>
                                                <small id="user_role" class="text-muted"></small>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <p class="mb-1"><strong>Email:</strong></p>
                                                        <p id="user_email" class="mb-3"></p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p class="mb-1"><strong>Student ID:</strong></p>
                                                        <p id="user_student_id" class="mb-3"></p>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button class="btn btn-success" id="borrow_for_user">
                                                        <i class="bi bi-person-plus me-2"></i>Borrow Books for this User
                                                    </button>
                                                    <button class="btn btn-info" id="view_history">
                                                        <i class="bi bi-clock-history me-2"></i>View Borrowing History
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- No User Found -->
                            <div id="no_user" class="d-none">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>No user found with this RFID card.</strong>
                                    <br>
                                    <span id="no_user_message"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Stats -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>RFID Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h3 class="text-primary mb-1">{{ \App\Models\User::whereNotNull('rfid_card')->count() }}</h3>
                                <p class="text-muted mb-3">Users with RFID Cards</p>

                                <h5 class="text-info mb-1">{{ \App\Models\User::whereNull('rfid_card')->count() }}</h5>
                                <p class="text-muted">Users without RFID Cards</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-people me-2"></i>Manage Users
                                </a>

                                <button class="btn btn-outline-secondary" onclick="clearScan()">
                                    <i class="bi bi-x-circle me-2"></i>Clear Scan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('scan_btn').addEventListener('click', function() {
    const rfidInput = document.getElementById('rfid_input');
    const rfidCard = rfidInput.value.trim();

    if (!rfidCard) {
        alert('Please enter an RFID card number.');
        return;
    }

    // Show loading state
    this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Looking up...';
    this.disabled = true;

    // Make AJAX request to lookup user
    fetch('{{ route("admin.rfid.lookup") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: 'rfid_card=' + encodeURIComponent(rfidCard)
    })
    .then(response => response.json())
    .then(data => {
        // Reset button
        document.getElementById('scan_btn').innerHTML = '<i class="bi bi-upc-scan me-2"></i>Lookup User';
        document.getElementById('scan_btn').disabled = false;

        if (data.success) {
            showUserInfo(data.user);
        } else {
            showNoUser(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Reset button
        document.getElementById('scan_btn').innerHTML = '<i class="bi bi-upc-scan me-2"></i>Lookup User';
        document.getElementById('scan_btn').disabled = false;
        alert('Error looking up user. Please try again.');
    });
});

function showUserInfo(user) {
    document.getElementById('user_info').classList.remove('d-none');
    document.getElementById('no_user').classList.add('d-none');

    document.getElementById('user_photo').src = user.profile_photo;
    document.getElementById('user_name').textContent = user.name;
    document.getElementById('user_email').textContent = user.email;
    document.getElementById('user_student_id').textContent = user.student_id || 'N/A';

    const roleBadge = document.getElementById('user_role');
    const roleColors = {
        'admin': 'danger',
        'teacher': 'warning',
        'student': 'info'
    };
    roleBadge.className = `badge bg-${roleColors[user.role] || 'secondary'}`;
    roleBadge.textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);

    // Set up button actions
    document.getElementById('borrow_for_user').onclick = function() {
        alert('Borrow for User functionality has been removed. Please use the regular borrowing system.');
    };

    document.getElementById('view_history').onclick = function() {
        window.location.href = '{{ route("borrowings.my_history", "") }}/' + user.id;
    };
}

function showNoUser(message) {
    document.getElementById('user_info').classList.add('d-none');
    document.getElementById('no_user').classList.remove('d-none');
    document.getElementById('no_user_message').textContent = message;
}

function clearScan() {
    document.getElementById('rfid_input').value = '';
    document.getElementById('user_info').classList.add('d-none');
    document.getElementById('no_user').classList.add('d-none');
}

// Auto-focus RFID input on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('rfid_input').focus();
});
</script>
@endsection
