<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Finance Manager')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    :root{ --accent:#0d6efd; }
    body{ background:#f8f9fa; }
    .hero{ background: linear-gradient(90deg, rgba(13,110,253,0.08), rgba(13,110,253,0.02)); border-radius: .75rem; }
    .card-stats .card-body{ padding: 1rem 1rem; }
    .quick-action{min-width:140px}
    .table-responsive{max-height:340px; overflow:auto}
    .navbar-brand { font-size: 1.25rem; }
  </style>
  @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
      <i class="bi bi-cash-stack"></i> FinanceManager
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('dashboard')) active fw-semibold @endif" href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('parties.*')) active fw-semibold @endif" href="{{ route('parties.index') }}">
            <i class="bi bi-people"></i> Parties
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collectors.*')) active fw-semibold @endif" href="{{ route('collectors.index') }}">
            <i class="bi bi-person-badge"></i> Collectors
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collections.*')) active fw-semibold @endif" href="{{ route('collections.index') }}">
            <i class="bi bi-cash-coin"></i> Collections
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle @if(request()->routeIs('report.*')) active fw-semibold @endif" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-file-earmark-text"></i> Reports
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('report.daily') }}">Daily Report</a></li>
            <li><a class="dropdown-item" href="{{ route('report.weekly') }}">Weekly Report</a></li>
            <li><a class="dropdown-item" href="{{ route('report.monthly') }}">Monthly Report</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container-fluid my-4">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle"></i> <strong>Please fix the following errors:</strong>
      <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

