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

    /* Mobile bottom nav styles */
    @media (max-width: 768px) {
      .main-navbar, nav.navbar { display: none;}
      .mobile-footer-nav {
        display: flex;
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #fff;
        border-top: 1px solid #e2e8f0;
        box-shadow: 0 -2px 12px 0 rgba(0,0,0,0.02);
        justify-content: space-between;
        z-index: 1030;
        height: 62px;
      }
      .mobile-footer-nav a {
        flex: 1 1 0%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #475569;
        font-size: 0.82rem;
        text-decoration: none;
        padding: 4px 0 2px 0; /* smaller padding for small size */
        transition: color 0.13s;
      }
      .mobile-footer-nav a.active,
      .mobile-footer-nav a:active,
      .mobile-footer-nav a:focus {
        color: #0891b2;
        font-weight: 600;
        background: #f1fcfc;
        border-top: 2.5px solid #0891b2;
      }
      .mobile-footer-nav i {
        font-size: 1.3rem;
        margin-bottom: 2px;
      }
      main.container-fluid { margin-bottom: 76px !important; }
    }

    @media (min-width: 769px) {
      .mobile-footer-nav { display: none!important; }
    }

    /* Compact the padding for main content on all screen sizes for 'small' look */
    main.container-fluid {
      padding: 0.5rem 0.5rem !important;
    }
  </style>
  @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm main-navbar">
  <div class="container-fluid p-2"> <!-- reduce padding (was default, now p-2 for small) -->
    <a class="navbar-brand fw-bold" href="{{ route('collector.dashboard') }}">
      <i class="bi bi-person-badge"></i> Collector Portal
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collector.dashboard')) active fw-semibold @endif px-2 py-1" href="{{ route('collector.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collector.collection.create')) active fw-semibold @endif px-2 py-1" href="{{ route('collector.collection.create') }}">
            <i class="bi bi-plus-circle"></i> New Collection
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collector.collection.index')) active fw-semibold @endif px-2 py-1" href="{{ route('collector.collection.index') }}">
            <i class="bi bi-list-ul"></i> My Collections
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collector.collection.report')) active fw-semibold @endif px-2 py-1" href="{{ route('collector.collection.report') }}">
            <i class="bi bi-file-earmark-text"></i> Reports
          </a>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <span class="navbar-text me-2">
            <i class="bi bi-person"></i> {{ auth('collector')->user()->name }}
          </span>
        </li>
        <li class="nav-item">
          <form action="{{ route('collector.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container-fluid my-2"> <!-- was my-4, now more compact margin -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show px-2 py-1" role="alert"> <!-- compact alert padding -->
      <i class="bi bi-check-circle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show px-2 py-1" role="alert">
      <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show px-2 py-1" role="alert">
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

<!-- Mobile footer nav for bottom tabs look like mobile app -->
<nav class="mobile-footer-nav">
    <a href="{{ route('collector.dashboard') }}"
       class="@if(request()->routeIs('collector.dashboard')) active @endif text-center">
       <i class="bi bi-house{{ request()->routeIs('collector.dashboard') ? '-fill' : '' }}"></i>
       <span>Home</span>
    </a>
    <a href="{{ route('collector.collection.index') }}"
       class="@if(request()->routeIs('collector.collection.index')) active @endif text-center">
       <i class="bi bi-list-ul"></i>
       <span>Collections</span>
    </a>
    <a href="{{ route('collector.parties.index') }}"
       class="@if(request()->routeIs('collector.parties.index')) active @endif text-center">
       <i class="bi bi-people"></i>
       <span>Parties</span>
    </a>
    <a href="{{ route('collector.collection.report') }}"
       class="@if(request()->routeIs('collector.collection.report')) active @endif text-center">
       <i class="bi bi-journal-bookmark"></i>
       <span>Reports</span>
    </a>
    <a href="{{ route('collector.collection.create') }}"
       class="
         @if(request()->routeIs('collector.collection.create')) active @endif
         text-center"
       style="font-weight: bold;">
       <i class="bi bi-plus-circle"></i>
       <span> 💵 cash</span>
    </a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
