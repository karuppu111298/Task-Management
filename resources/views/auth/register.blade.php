    
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
    
<div class="py-5 d-flex justify-content-center align-items-center" style="height: 100vh;background-color: #dae3ecff;">
        <div class="card shadow-sm rounded-3" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h3 class="mb-4 text-center">Create an Account</h3>
                <form id="registerform" name="registerform">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Enter your full name">
                        <div class="invalid-feedback" id="full_name_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                        <div class="invalid-feedback" id="email_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
                        <div class="invalid-feedback" id="password_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm your password">
                        <div class="invalid-feedback" id="confirm_password_error"></div>
                    </div>
                </form>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-center">
                        <p class="mb-0">Allready account? <a href="{{ route('login') }}" class="text-primary">Login</a></p>
                    </div>

                    <button type="button" class="btn btn-primary px-4" id="register_btn">Register</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $('#register_btn').on('click', function () {
            let full_name = $('#full_name').val().trim();
            let email = $('#email').val().trim();
            let password = $('#password').val().trim();
            let confirm_password = $('#confirm_password').val().trim();

            $('#full_name_error').text('');
            $('#email_error').text('');
            $('#password_error').text('');
            $('#confirm_password_error').text('');
            $('#full_name').removeClass('is-invalid');
            $('#email').removeClass('is-invalid');
            $('#password').removeClass('is-invalid');
            $('#confirm_password').removeClass('is-invalid');
           
            if (!full_name) { 
                $('#full_name').addClass('is-invalid');
                $('#full_name_error').text('Full Name is required');
                return;
            }
            if (!email) { 
                $('#email').addClass('is-invalid');
                $('#email_error').text('Email is required');
                return;
            }
            if (!password) { 
                $('#password').addClass('is-invalid');
                $('#password_error').text('Password is required');
                return;
            }
            if (!confirm_password) { 
                $('#confirm_password').addClass('is-invalid');
                $('#confirm_password_error').text('Please confirm your password');
                return;
            }
            if (password !== confirm_password) { 
                $('#confirm_password').addClass('is-invalid');
                $('#confirm_password_error').text('⚠️ Passwords do not match');
                return;
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('register') }}",
                data: {
                    name: full_name,
                    email: email,
                    password: password,
                    confirm_password: confirm_password
                },
                success: function (data) {
                    alert('Registration successful');
                    window.location.href = data.redirect_url;
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.name) {
                            $('#full_name').addClass('is-invalid');
                            $('#full_name_error').text(errors.name[0]);
                        }

                        if (errors.email) {
                            $('#email').addClass('is-invalid');
                            $('#email_error').text(errors.email[0]);
                        }

                        if (errors.password) {
                            $('#password').addClass('is-invalid');
                            $('#password_error').text(errors.password[0]);
                        }

                        if (errors.confirm_password) {
                            $('#confirm_password').addClass('is-invalid');
                            $('#confirm_password_error').text(errors.confirm_password[0]);
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