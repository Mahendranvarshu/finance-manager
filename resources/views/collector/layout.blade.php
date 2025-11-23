<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Collector Portal')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    body{ background:#f8f9fa; }
    .navbar-brand { font-size: 1.25rem; }
  </style>
  @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="{{ route('collector.dashboard') }}">
      <i class="bi bi-person-badge"></i> Collector Portal
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collector.dashboard')) active fw-semibold @endif" href="{{ route('collector.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collector.collection.create')) active fw-semibold @endif" href="{{ route('collector.collection.create') }}">
            <i class="bi bi-plus-circle"></i> New Collection
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collector.collection.index')) active fw-semibold @endif" href="{{ route('collector.collection.index') }}">
            <i class="bi bi-list-ul"></i> My Collections
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collector.collection.report')) active fw-semibold @endif" href="{{ route('collector.collection.report') }}">
            <i class="bi bi-file-earmark-text"></i> Reports
          </a>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <span class="navbar-text me-3">
            <i class="bi bi-person"></i> {{ auth('collector')->user()->name }}
          </span>
        </li>
        <li class="nav-item">
          <form action="{{ route('collector.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </form>
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

