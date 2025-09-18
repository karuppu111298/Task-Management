<!DOCTYPE html>
<html>
<head>
  <title>Task Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Custom style to change navbar background -->
  <style>
    .bg-blue-custom {
      background-color: #957df7; /* Deep blue, you can change this hex */
    }
  </style>
</head>
<body>

<!-- Apply custom class -->
<nav class="navbar navbar-expand-lg navbar-dark bg-blue-custom mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('task_list') }}">Task Manager</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        @auth
        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-link nav-link">Logout</button>
          </form>
        </li>
        @endauth
        @guest
        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
        <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
