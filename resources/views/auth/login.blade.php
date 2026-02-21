<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Post Approval System</title>
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
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 40px 60px;
            text-align: center;
            position: relative;
        }
        .login-header h2 {
            color: #fff;
            font-weight: 600;
            margin: 0;
        }
        .login-header p {
            color: rgba(255,255,255,0.8);
            margin: 10px 0 0;
        }
        .login-body {
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
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover {
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
    <div class="login-card">
        <div class="login-header">
            <h2><i class="fas fa-shield-alt"></i> Post Approval</h2>
            <p>Sign in to your account</p>
        </div>
        <div class="login-body">
            <form id="loginForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                    <div class="error-text" id="emailError"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                    <div class="error-text" id="passwordError"></div>
                </div>
                <button type="submit" class="btn btn-primary btn-login" id="loginBtn">
                    <span id="loginBtnText">Sign In</span>
                    <span id="loginBtnLoading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
                </button>
            </form>
            <div class="register-link">
                Don't have an account? <a href="/register">Create one</a>
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

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                $('.error-text').text('');
                $('.form-control').removeClass('is-invalid');
                
                const email = $('#email').val();
                const password = $('#password').val();
                
                let hasError = false;
                
                if (!email) {
                    $('#emailError').text('Email is required');
                    $('#email').addClass('is-invalid');
                    hasError = true;
                }
                
                if (!password) {
                    $('#passwordError').text('Password is required');
                    $('#password').addClass('is-invalid');
                    hasError = true;
                }
                
                if (hasError) return;
                
                $('#loginBtn').prop('disabled', true);
                $('#loginBtnText').hide();
                $('#loginBtnLoading').show();
                
                $.ajax({
                    url: '/api/auth/login',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ email, password }),
                    success: function(response) {
                        localStorage.setItem('token', response.token);
                        localStorage.setItem('user', JSON.stringify(response.user));
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Welcome!',
                            text: 'Login successful. Redirecting...',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '/dashboard';
                        });
                    },
                    error: function(xhr) {
                        $('#loginBtn').prop('disabled', false);
                        $('#loginBtnText').show();
                        $('#loginBtnLoading').hide();
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.email) {
                                $('#emailError').text(errors.email[0]);
                                $('#email').addClass('is-invalid');
                            }
                            if (errors.password) {
                                $('#passwordError').text(errors.password[0]);
                                $('#password').addClass('is-invalid');
                            }
                        } else if (xhr.responseJSON && xhr.responseJSON.email) {
                            $('#passwordError').text(xhr.responseJSON.email[0]);
                            $('#password').addClass('is-invalid');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: 'Invalid credentials. Please try again.'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
