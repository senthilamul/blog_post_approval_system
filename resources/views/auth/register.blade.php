<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Post Approval System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .register-card {
            width: 100%;
            max-width: 450px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 40px 60px;
            text-align: center;
            position: relative;
        }
        .register-header h2 {
            color: #fff;
            font-weight: 600;
            margin: 0;
        }
        .register-header p {
            color: rgba(255,255,255,0.8);
            margin: 10px 0 0;
        }
        .register-body {
            padding: 40px;
            margin-top: -30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }
        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .error-text {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <h2><i class="fas fa-user-plus"></i> Create Account</h2>
            <p>Join Post Approval System</p>
        </div>
        <div class="register-body">
            <form id="registerForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
                    <div class="error-text" id="nameError"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                    <div class="error-text" id="emailError"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Create a password">
                    <div class="error-text" id="passwordError"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password">
                    <div class="error-text" id="passwordConfirmationError"></div>
                </div>
                <button type="submit" class="btn btn-primary btn-register" id="registerBtn">
                    <span id="registerBtnText">Create Account</span>
                    <span id="registerBtnLoading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Creating...</span>
                </button>
            </form>
            <div class="login-link">
                Already have an account? <a href="/login">Sign in</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            if (localStorage.getItem('token')) {
                window.location.href = '/dashboard';
                return;
            }

            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                
                $('.error-text').text('');
                $('.form-control').removeClass('is-invalid');
                
                const name = $('#name').val();
                const email = $('#email').val();
                const password = $('#password').val();
                const password_confirmation = $('#password_confirmation').val();
                
                let hasError = false;
                
                if (!name) {
                    $('#nameError').text('Name is required');
                    $('#name').addClass('is-invalid');
                    hasError = true;
                }
                
                if (!email) {
                    $('#emailError').text('Email is required');
                    $('#email').addClass('is-invalid');
                    hasError = true;
                }
                
                if (!password) {
                    $('#passwordError').text('Password is required');
                    $('#password').addClass('is-invalid');
                    hasError = true;
                } else if (password.length < 6) {
                    $('#passwordError').text('Password must be at least 6 characters');
                    $('#password').addClass('is-invalid');
                    hasError = true;
                }
                
                if (!password_confirmation) {
                    $('#passwordConfirmationError').text('Please confirm your password');
                    $('#password_confirmation').addClass('is-invalid');
                    hasError = true;
                } else if (password !== password_confirmation) {
                    $('#passwordConfirmationError').text('Passwords do not match');
                    $('#password_confirmation').addClass('is-invalid');
                    hasError = true;
                }
                
                if (hasError) return;
                
                $('#registerBtn').prop('disabled', true);
                $('#registerBtnText').hide();
                $('#registerBtnLoading').show();
                
                $.ajax({
                    url: '/api/auth/register',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ name, email, password, password_confirmation }),
                    success: function(response) {
                        localStorage.setItem('token', response.token);
                        localStorage.setItem('user', JSON.stringify(response.user));
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Welcome!',
                            text: 'Account created successfully. Redirecting...',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '/dashboard';
                        });
                    },
                    error: function(xhr) {
                        $('#registerBtn').prop('disabled', false);
                        $('#registerBtnText').show();
                        $('#registerBtnLoading').hide();
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.name) {
                                $('#nameError').text(errors.name[0]);
                                $('#name').addClass('is-invalid');
                            }
                            if (errors.email) {
                                $('#emailError').text(errors.email[0]);
                                $('#email').addClass('is-invalid');
                            }
                            if (errors.password) {
                                $('#passwordError').text(errors.password[0]);
                                $('#password').addClass('is-invalid');
                            }
                            if (errors.password_confirmation) {
                                $('#passwordConfirmationError').text(errors.password_confirmation[0]);
                                $('#password_confirmation').addClass('is-invalid');
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Registration Failed',
                                text: 'Something went wrong. Please try again.'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
