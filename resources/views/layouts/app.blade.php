<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'To-Do List')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- App Theme Overrides -->
    <style>
        :root{
            --mint-50:#ecf9f5;
            --mint-100:#d6f3ea;
            --mint-200:#b6e8db;
            --mint-300:#8fd9c7;
            --mint-400:#67c8b0;
            --mint-500:#3fb89a; /* primary */
            --mint-600:#2f9d83;
            --warning-400:#ffd59e;
            --orange-500:#ff9f43;
            --success-500:#22c55e;
            --text-900:#0f172a;
            --text-600:#475569;
            --card-radius:22px;
            --btn-radius:16px;
            --shadow-soft:0 10px 24px rgba(16,24,40,.06), 0 2px 6px rgba(16,24,40,.04);
        }
        html,body{height:100%}
        body{
            font-family:'Inter', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji', sans-serif;
            color:var(--text-900);
            background:linear-gradient(135deg, var(--mint-50), #e6f6ff 60%);
        }
        /* Navbar */
        .navbar{
            border:0;
            background:linear-gradient(135deg, var(--mint-500), var(--mint-400));
            box-shadow:var(--shadow-soft);
        }
        .navbar .navbar-brand,.navbar .nav-link{color:#fff !important; font-weight:600}
        .navbar .nav-link:hover{opacity:.9}

        /* Cards */
        .card{border:0; border-radius:var(--card-radius); box-shadow:var(--shadow-soft)}
        .card .card-header{border:0; background:transparent}

        /* Buttons */
        .btn{border-radius:var(--btn-radius);}
        .btn-primary{background:linear-gradient(135deg, var(--mint-500), var(--mint-400)); border:0}
        .btn-outline-primary{color:var(--mint-600); border-color:var(--mint-300)}
        .btn-outline-primary:hover{background:var(--mint-100); border-color:var(--mint-400)}
        .btn-danger{background:linear-gradient(135deg, #ef4444, #f97316); border:0}
        .btn-secondary{background:#e2e8f0; color:#0f172a; border:0}

        /* Badges (status) */
        .badge{border-radius:999px; padding:.5rem .75rem}
        .badge.bg-success{background:rgba(34,197,94,.15)!important; color:var(--success-500)}
        .badge.bg-warning{background:rgba(255,159,67,.16)!important; color:var(--orange-500)}
        .badge.bg-secondary{background:rgba(15,23,42,.08)!important; color:var(--text-600)}

        /* Table */
        .table> :not(caption)>*>*{padding:1rem 1.25rem}
        .table thead th{color:var(--text-600); font-weight:600}
        .table-hover tbody tr:hover{background:var(--mint-50)}
        .table-light{--bs-table-bg: #fff}
        .table-responsive{border-radius:16px}

        /* Forms */
        .form-control,.form-select{border-radius:14px; border:1px solid #e2e8f0}
        .form-control:focus,.form-select:focus{border-color:var(--mint-400); box-shadow:0 0 0 .25rem rgba(63,184,154,.15)}

        /* Pagination */
        .pagination{gap:.5rem}
        .page-link{border-radius:12px!important; border:1px solid var(--mint-200); color:var(--text-600); padding:.5rem .85rem; box-shadow:none}
        .page-link:hover{background:var(--mint-100); color:var(--text-900); border-color:var(--mint-300)}
        .page-item.active .page-link{background:linear-gradient(135deg, var(--mint-500), var(--mint-400)); border-color:transparent; color:#fff}
        .page-item.disabled .page-link{background:#f1f5f9; color:#94a3b8; border-color:#e2e8f0}

        /* Toast */
        .toast{border-radius:14px; box-shadow:var(--shadow-soft)}

        /* Utilities */
        .text-muted{color:var(--text-600)!important}
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('tasks.index') }}">
                <i class="fas fa-tasks me-2"></i>To-Do List
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tasks.index') }}">
                            <i class="fas fa-list me-1"></i>All Tasks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tasks.create') }}">
                            <i class="fas fa-plus me-1"></i>Add Task
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-info-circle text-primary me-2"></i>
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toast-body">
                <!-- Toast message will be inserted here -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @stack('scripts')
</body>
</html>
