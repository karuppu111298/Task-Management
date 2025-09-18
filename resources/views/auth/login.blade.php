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

<div class="d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #dae3ecff;">
        <div class="card shadow-sm rounded-3" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h3 class="mb-4 text-center">Login</h3>
                <form id="loginform" name="loginform">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username">
                        <div class="invalid-feedback" id="username_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
                        <div class="invalid-feedback" id="password_error"></div>
                    </div>
                </form>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-center">
                        <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-primary">Register</a></p>
                    </div>

                    <button type="button" class="btn btn-success px-4" id="login_btn">Login</button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $('#login_btn').on('click', function () {
            let username = $('#username').val().trim();
            let password = $('#password').val().trim();

            $('#username_error').text('');
            $('#password_error').text('');
            $('#username').removeClass('is-invalid');
            $('#password').removeClass('is-invalid');

            if (!username) { 
                $('#username').addClass('is-invalid');
                $('#username_error').text('⚠️ Username is required');
                return;
            }
            if (!password) { 
                $('#password').addClass('is-invalid');
                $('#password_error').text('⚠️ Password is required');
                return;
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('login') }}",
                data: {
                    email: username,
                    password: password
                },
                success: function (data) {
                    window.location.href = '/task_list';
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.email) {
                            $('#username').addClass('is-invalid');
                            $('#username_error').text(errors.email[0]);
                        }

                        if (errors.password) {
                            $('#password').addClass('is-invalid');
                            $('#password_error').text(errors.password[0]);
                        }
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                }
            });
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>