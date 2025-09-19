<!DOCTYPE html>
<html>
<head>
  <title>Task Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  
  <!-- Custom style to change navbar background -->
  <style>
    .bg-blue-custom {
      background-color: #957df7; /* Deep blue, you can change this hex */
    }
    .drag-handle {
        cursor: pointer;   /* only pointer cursor */
        color: #6c757d;
    }
    .drag-handle:hover {
        color: #000;       /* hover highlight optional */
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
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->name }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item">Logout</button>
              </form>
            </li>
          </ul>
        </li>
        @endauth

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
