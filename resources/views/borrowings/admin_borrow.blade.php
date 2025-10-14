@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="bi bi-person-plus text-primary me-2"></i>Borrow for Student</h2>
                    <p class="text-muted mb-0">Find a student to borrow books for them</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            <!-- Student Search Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-search me-2"></i>Find Student</h5>
                </div>
                <div class="card-body">
                    <!-- RFID Search -->
                    <div class="mb-4">
                        <h6 class="text-muted">RFID Card Scanner</h6>
                        <form method="POST" action="{{ route('borrowings.admin_borrow.post') }}" class="d-inline">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" name="rfid_card"
                                       placeholder="Scan or enter RFID card number..." required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-credit-card-2-front me-2"></i>Find by RFID
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Manual Search -->
                    <div class="mb-4">
                        <h6 class="text-muted">Manual Search</h6>
                        <form method="POST" action="{{ route('borrowings.admin_borrow.post') }}" class="d-inline">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" name="search_query"
                                       placeholder="Enter student name, email, or ID..." required>
                                <button class="btn btn-info" type="submit">
                                    <i class="bi bi-search me-2"></i>Find Student
                                </button>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if($selectedUser)
                        <!-- Student Found - Show Borrowing Interface -->
                        <div class="card mt-4 border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Student Selected</h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center">
                                        <img src="{{ $selectedUser->profile_photo_url }}" alt="Profile" class="rounded-circle mb-2" width="80" height="80">
                                        <h6 class="mb-0">{{ $selectedUser->name }}</h6>
                                        <small class="text-muted">{{ ucfirst($selectedUser->role) }}</small>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Email:</strong></p>
                                                <p class="mb-3">{{ $selectedUser->email }}</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Student ID:</strong></p>
                                                <p class="mb-3">{{ $selectedUser->student_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-success" id="borrow-books-btn">
                                                <i class="bi bi-book me-2"></i>Borrow Books for {{ $selectedUser->name }}
                                            </button>
                                            <a href="{{ route('borrowings.admin_borrow') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-repeat me-2"></i>Find Different Student
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Book Selection Form (Initially Hidden) -->
                        <div id="book-selection-section" class="card mt-4" style="display: none;">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-books me-2"></i>Select Books to Borrow</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('borrowings.store') }}" id="borrow-book-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $selectedUser->id }}" id="borrow_user_id">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="book_select" class="form-label">Select Book</label>
                                            <select class="form-select" name="book_id" id="book_select" required onchange="updateBookInfo()">
                                                <option value="">Choose a book...</option>
                                                @foreach($books as $book)
                                                    <option value="{{ $book->id }}"
                                                            data-title="{{ $book->title }}"
                                                            data-author="{{ $book->author->name ?? 'Unknown' }}"
                                                            data-available="{{ $book->available_quantity ?? ($book->quantity ?? 1) }}">
                                                        {{ $book->title }} by {{ $book->author->name ?? 'Unknown' }}
                                                        (Available: {{ $book->available_quantity ?? ($book->quantity ?? 1) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-success d-block w-100">
                                                <i class="bi bi-check-circle me-2"></i>Borrow Selected Book
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Book Information Display -->
                                    <div id="book-info" class="mt-3" style="display: none;">
                                        <div class="alert alert-info">
                                            <h6>Book Information:</h6>
                                            <div id="book-details"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Instructions -->
                    <div class="alert alert-info mt-4">
                        <h6><i class="bi bi-info-circle me-2"></i>How to use:</h6>
                        <ol class="mb-0">
                            <li>Enter RFID card number or search for student by name/email/ID</li>
                            <li>Click the search button to find the student</li>
                            <li>If student is found, click "Borrow Books" to proceed</li>
                            <li>On the next page, select books to borrow for the student</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
console.log('=== ADMIN BORROW PAGE SCRIPT LOADED ===');
let selectedStudent = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== ADMIN BORROW PAGE LOADED ===');

    // Test if button exists and add click handler immediately
    const testButton = document.getElementById('borrow-books-btn');
    if (testButton) {
        console.log('✅ Borrow Books button found on page load');
        testButton.addEventListener('click', function() {
            console.log('📚 Borrow Books button clicked (direct listener)');
            showBookSelection();
        });
    } else {
        console.log('❌ Borrow Books button NOT found on page load');
    }

    // RFID Form Handler
    const rfidForm = document.getElementById('rfid-form');
    if (rfidForm) {
        rfidForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('📡 RFID form submitted');

            const rfidCard = document.getElementById('rfid_card').value.trim();
            if (!rfidCard) {
                showRfidError('Please enter an RFID card number.');
                return;
            }

            console.log('🔍 Looking up RFID:', rfidCard);

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Searching...';
            submitBtn.disabled = true;

            // Clear previous results
            clearAllResults();

            // Make AJAX request
            fetch('{{ route("borrowings.admin_rfid_lookup") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ rfid_card: rfidCard })
            })
            .then(response => {
                console.log('RFID Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('RFID Response data:', data);
                if (data.success) {
                    showRfidUserInfo(data.user);
                } else {
                    showRfidError(data.message || 'Student not found with this RFID card.');
                }
            })
            .catch(error => {
                console.error('RFID Error:', error);
                showRfidError('Error looking up RFID card. Please try again.');
            })
            .finally(() => {
                submitBtn.innerHTML = '<i class="bi bi-search me-2"></i>Find Student';
                submitBtn.disabled = false;
            });
        });
    }

    // Manual Search Form Handler
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search_query');

    if (searchForm && searchInput) {
        // Real-time autocomplete as user types
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            // Clear previous timeout
            clearTimeout(searchTimeout);

            // Hide previous results if query is too short
            if (query.length < 2) {
                document.getElementById('search-results').classList.add('d-none');
                document.getElementById('manual-user-info').classList.add('d-none');
                document.getElementById('search-error-message').classList.add('d-none');
                return;
            }

            // Set new timeout for debounced search
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300); // Wait 300ms after user stops typing
        });

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('🔍 Manual search form submitted');

            const query = searchInput.value.trim();
            if (!query) {
                showSearchError('Please enter a student name, email, or student ID.');
                return;
            }

            console.log('🔍 Force searching for:', query);
            performSearch(query, true); // Force search even if less than 2 chars
        });
    }

    function performSearch(query, force = false) {
        if (!force && query.length < 2) return;

        console.log('🔍 Searching for:', query);

        // Show loading state
        const submitBtn = document.getElementById('search-form').querySelector('button[type="submit"]');
        if (submitBtn && !force) {
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Searching...';
            submitBtn.disabled = true;
        }

        // Clear previous results
        clearAllResults();

        // Make AJAX request
        fetch('{{ route("borrowings.admin_user_search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ q: query })
        })
        .then(response => {
            console.log('Search Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Search Response data:', data);
            if (data.error) {
                if (force) showSearchError(data.error);
            } else if (data.multiple) {
                showSearchResults(data.users);
            } else if (data.id) {
                showManualUserInfo(data);
            } else if (force) {
                showSearchError('Student not found. Please check the name, email, or student ID.');
            }
        })
        .catch(error => {
            console.error('Search Error:', error);
            if (force) showSearchError('Error searching for student. Please try again.');
        })
        .finally(() => {
            if (submitBtn && !force) {
                submitBtn.innerHTML = '<i class="bi bi-search me-2"></i>Search Student';
                submitBtn.disabled = false;
            }
        });
    }

    // Borrow Book Form Handler
    const borrowForm = document.getElementById('borrow-book-form');
    if (borrowForm) {
        borrowForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('📚 Borrow form submitted');

            const userId = document.getElementById('borrow_user_id').value;
            const bookId = document.getElementById('book_select').value;

            if (!userId) {
                alert('Please find and select a student first.');
                return;
            }

            if (!bookId) {
                alert('Please select a book to borrow.');
                return;
            }

            console.log('Borrowing book', bookId, 'for student', userId);

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Borrowing...';
            submitBtn.disabled = true;

            // Submit form normally (not AJAX) to handle validation and errors properly
            this.submit();
        });
    }

    // Enter key support
    document.getElementById('rfid_card')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            rfidForm.dispatchEvent(new Event('submit'));
        }
    });

    document.getElementById('search_query')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchForm.dispatchEvent(new Event('submit'));
        }
    });

    // Borrow Books button click handler
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'borrow-books-btn') {
            console.log('📚 Borrow Books button clicked');
            showBookSelection();
        }
    });
});

function showRfidUserInfo(user) {
    console.log('✅ RFID student found:', user);
    selectedStudent = user;

    // Update RFID user display
    document.getElementById('rfid_user_photo').src = user.profile_photo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&background=003366&color=fff&size=128';
    document.getElementById('rfid_user_name').textContent = user.name;
    document.getElementById('rfid_user_email').textContent = user.email;
    document.getElementById('rfid_user_student_id').textContent = user.student_id || 'N/A';

    // Set role badge
    const roleBadge = document.getElementById('rfid_user_role');
    const roleColors = {
        'admin': 'danger',
        'teacher': 'warning',
        'student': 'info'
    };
    roleBadge.className = `badge bg-${roleColors[user.role] || 'secondary'}`;
    roleBadge.textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);

    // Show RFID user info
    document.getElementById('rfid-user-info').classList.remove('d-none');
}

function showManualUserInfo(user) {
    console.log('✅ Manual search student found:', user);
    selectedStudent = user;

    // Update manual search user display
    document.getElementById('manual_user_photo').src = user.profile_photo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&background=003366&color=fff&size=128';
    document.getElementById('manual_user_name').textContent = user.name;
    document.getElementById('manual_user_email').textContent = user.email;
    document.getElementById('manual_user_student_id').textContent = user.student_id || 'N/A';

    // Set role badge
    const roleBadge = document.getElementById('manual_user_role');
    const roleColors = {
        'admin': 'danger',
        'teacher': 'warning',
        'student': 'info'
    };
    roleBadge.className = `badge bg-${roleColors[user.role] || 'secondary'}`;
    roleBadge.textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);

    // Show manual user info
    document.getElementById('manual-user-info').classList.remove('d-none');
}

function showSearchResults(users) {
    console.log('📋 Multiple students found:', users);

    const resultsList = document.getElementById('results-list');
    resultsList.innerHTML = '';

    users.forEach(user => {
        const item = document.createElement('button');
        item.className = 'list-group-item list-group-item-action text-start';
        item.type = 'button';
        item.innerHTML = `
            <div><strong>${user.name}</strong> <small class="text-muted">${user.email}</small></div>
            <div class="text-muted">${user.student_id || 'No ID'}</div>
        `;
        item.addEventListener('click', () => selectStudent(user));
        resultsList.appendChild(item);
    });

    document.getElementById('search-results').classList.remove('d-none');
}

function selectStudent(user) {
    console.log('👤 Student selected:', user);
    selectedStudent = user;

    // Hide search results
    document.getElementById('search-results').classList.add('d-none');

    // Show as manual user info
    showManualUserInfo(user);
}

function borrowForRfidUser() {
    console.log('📋 Borrowing for RFID student');
    if (selectedStudent) {
        prepareBorrowingForm(selectedStudent);
    }
}

function borrowForManualUser() {
    console.log('📋 Borrowing for manual search student');
    if (selectedStudent) {
        prepareBorrowingForm(selectedStudent);
    }
}

function prepareBorrowingForm(user) {
    console.log('📋 Preparing borrowing form for:', user);

    // Set user ID for borrowing
    document.getElementById('borrow_user_id').value = user.id;

    // Update selected student summary
    document.getElementById('selected-student-summary').innerHTML = `
        <p class="mb-1"><strong>${user.name}</strong></p>
        <p class="mb-1">${user.email}</p>
        <small class="text-muted">Student ID: ${user.student_id || 'N/A'}</small>
    `;

    // Show borrowing section
    document.getElementById('borrowing-section').classList.remove('d-none');
}

function showRfidError(message) {
    console.log('❌ RFID Error:', message);
    const errorDiv = document.getElementById('rfid-error-message');
    errorDiv.textContent = message;
    errorDiv.classList.remove('d-none');
}

function showSearchError(message) {
    console.log('❌ Search Error:', message);
    const errorDiv = document.getElementById('search-error-message');
    errorDiv.textContent = message;
    errorDiv.classList.remove('d-none');
}

function clearRfidUser() {
    console.log('🗑️ Clearing RFID user');
    document.getElementById('rfid-form').reset();
    document.getElementById('rfid-user-info').classList.add('d-none');
    document.getElementById('rfid-error-message').classList.add('d-none');
    selectedStudent = null;
}

function clearManualUser() {
    console.log('🗑️ Clearing manual search user');
    document.getElementById('search-form').reset();
    document.getElementById('manual-user-info').classList.add('d-none');
    document.getElementById('search-results').classList.add('d-none');
    document.getElementById('search-error-message').classList.add('d-none');
    selectedStudent = null;
}

function clearAllResults() {
    console.log('🗑️ Clearing all results');
    document.getElementById('rfid-user-info').classList.add('d-none');
    document.getElementById('manual-user-info').classList.add('d-none');
    document.getElementById('search-results').classList.add('d-none');
    document.getElementById('borrowing-section').classList.add('d-none');
    document.getElementById('rfid-error-message').classList.add('d-none');
    document.getElementById('search-error-message').classList.add('d-none');
    selectedStudent = null;
}

function showBookSelection() {
    console.log('📚 Showing book selection interface');
    const bookSelectionSection = document.getElementById('book-selection-section');
    if (bookSelectionSection) {
        bookSelectionSection.style.display = 'block';
        bookSelectionSection.scrollIntoView({ behavior: 'smooth' });
    }
}

function updateBookInfo() {
    const bookSelect = document.getElementById('book_select');
    const selectedOption = bookSelect.options[bookSelect.selectedIndex];
    const bookInfoDiv = document.getElementById('book-info');
    const bookDetailsDiv = document.getElementById('book-details');

    if (selectedOption.value && selectedOption.value !== '') {
        const title = selectedOption.getAttribute('data-title');
        const author = selectedOption.getAttribute('data-author');
        const available = selectedOption.getAttribute('data-available');

        bookDetailsDiv.innerHTML = `
            <p><strong>Title:</strong> ${title}</p>
            <p><strong>Author:</strong> ${author}</p>
            <p><strong>Available Copies:</strong> ${available}</p>
        `;
        bookInfoDiv.style.display = 'block';
    } else {
        bookInfoDiv.style.display = 'none';
    }
}

function startOver() {
    console.log('🔄 Starting over');
    clearAllResults();

    // Hide book selection section
    const bookSelectionSection = document.getElementById('book-selection-section');
    if (bookSelectionSection) {
        bookSelectionSection.style.display = 'none';
    }

    // Reset all forms
    document.getElementById('rfid-form').reset();
    document.getElementById('search-form').reset();
    document.getElementById('borrow-book-form').reset();
}

// Debug functions
function testRfidLookup() {
    console.log('🧪 Testing RFID lookup...');
    fetch('{{ route("borrowings.admin_rfid_lookup") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ rfid_card: 'test' })
    })
    .then(r => r.json())
    .then(d => console.log('RFID test response:', d))
    .catch(e => console.log('RFID test error:', e));
}

function testUserSearch() {
    console.log('🧪 Testing user search...');
    fetch('{{ route("borrowings.admin_user_search") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ q: 'admin' })
    })
    .then(r => r.json())
    .then(d => console.log('Search test response:', d))
    .catch(e => console.log('Search test error:', e));
}

// Debug panel removed for production
</script>
