<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Finance Manager')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    :root {
      --accent: #0d6efd;
    }
    body {
      background: #f8f9fa;
    }
    main.container-fluid {
      padding: 0.5rem 0.5rem !important;
    }
    .navbar-brand {
      font-size: 1.25rem;
    }
    @media (max-width: 768px) {
      .main-navbar, nav.navbar { display: none !important; }
      .mobile-footer-nav {
        display: flex;
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #fff;
        border-top: 1px solid #e2e8f0;
        box-shadow: 0 -2px 12px 0 rgba(0,0,0,.02);
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
        font-size: 0.88rem;
        text-decoration: none;
        padding: 4px 0 2px 0;
        transition: color 0.13s;
        background: transparent;
        border-top: 2px solid transparent;
      }
      .mobile-footer-nav a.active,
      .mobile-footer-nav a:active,
      .mobile-footer-nav a:focus {
        color: #0d6efd;
        font-weight: 600;
        background: #f1fcfc;
        border-top: 2.5px solid #0d6efd;
      }
      .mobile-footer-nav i {
        font-size: 1.48rem;
        margin-bottom: 2px;
      }
      main.container-fluid { margin-bottom: 80px !important; }
    }
    @media (min-width: 769px) {
      .mobile-footer-nav { display: none !important; }
    }
    .icon-nav-logo {
      font-size: 2.0rem;
      margin-right: 0.2rem;
      vertical-align: middle;
    }
    /* For desktop nav look */
    .main-navbar .navbar-nav .nav-link {
      display: flex;
      align-items: center;
    }
    .main-navbar .navbar-nav .nav-link i {
      font-size: 1.17rem;
      margin-right: 6px;
    }
  </style>
  @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm main-navbar">
  <div class="container-fluid p-2">
    <a class="navbar-brand fw-bold mx-auto text-center" style="width:fit-content;" href="{{ route('dashboard') }}">
      <span class="icon-nav-logo"><i class="bi bi-cash-stack"></i></span>
      <span style="vertical-align:middle;font-size:1.14rem;font-weight:600;">Finance Collection</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('dashboard')) active fw-semibold @endif" href="{{ route('dashboard') }}">
            <i class="bi bi-house"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->routeIs('collections.*')) active fw-semibold @endif" href="{{ route('collections.index') }}">
            <i class="bi bi-list-ul"></i> Collections
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
          <a class="nav-link @if(request()->routeIs('report.*')) active fw-semibold @endif" href="{{ route('report.daily') }}">
            <i class="bi bi-file-earmark-text"></i> Reports
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
            <i class="bi bi-shop"></i> Shops
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container-fluid my-2">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show px-2 py-1" role="alert">
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

<!-- Mobile bottom nav UI, all menu, styled same as the last UI but with all options -->
<nav class="mobile-footer-nav">
    <a href="{{ route('dashboard') }}"
       class="@if(request()->routeIs('dashboard')) active @endif text-center">
        <i class="bi bi-house{{ request()->routeIs('dashboard') ? '-fill' : '' }}"></i>
        <span>Home</span>
    </a>
    <a href="{{ route('collections.index') }}"
       class="@if(request()->routeIs('collections.index')) active @endif text-center">
        <i class="bi bi-list-ul"></i>
        <span>Collections</span>
    </a>
    <a href="{{ route('parties.index') }}"
       class="@if(request()->routeIs('parties.index')) active @endif text-center">
        <i class="bi bi-people"></i>
        <span>Parties</span>
    </a>
    <a href="{{ route('collectors.index') }}"
       class="@if(request()->routeIs('collectors.index')) active @endif text-center">
        <i class="bi bi-person-badge"></i>
        <span>Collectors</span>
    </a>
    <a href="{{ route('report.daily') }}"
       class="@if(request()->routeIs('report.*')) active @endif text-center">
        <i class="bi bi-file-earmark-text"></i>
        <span>Reports</span>
    </a>
    <a href="#" class="text-center disabled" tabindex="-1" aria-disabled="true" style="pointer-events:none;opacity:.6;">
        <i class="bi bi-shop"></i>
        <span>Shops</span>
    </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
