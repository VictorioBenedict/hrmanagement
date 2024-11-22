<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for eye icon -->
    <title>HR Document Monitoring System - Admin Registration</title>

    <style>
        @keyframes slideFromTopToBottom {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-color: #357CAF;
            color: #233268;
            background-size: cover;
        }

        .image-card img {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        /* Container to center the card vertically and horizontally */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Combined Card View: Flexbox container for side-by-side layout */
        .combined-card {
            display: flex;
            width: 100%;
            max-width: 1000px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Login Card Section: 50% width of the container, padding for form */
        .login-card {
            width: 100% !important;
            padding: 40px;
            background-color: #fff;
        }

        .form-control {
            border-radius: 4px;
        }

        /* Button styling */
        .btn-block {
            width: 100%;
        }

        /* Styling for the login form card */
        .login-card {
            width: 690px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            height: auto;
            background-color: white;
        }

        .login-card h4 {
            font-size: 1.25rem;
            font-weight: 500;
            color: #233268;
            text-align: center;
        }

        .form-group input {
        }

        .btn-primary {
            background-color: #3D5A5C;
            border-color: #004080;
        }
                /* Positioning for password input container */
        .password-container {
            position: relative;
        }

        /* Container for the input and icon */
        .input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        /* Input styling: added padding-right to create space for the icon */
        .input-container input {
            width: 100%;
            padding-right: 40px; /* Add space for the icon inside the input */
        }

        /* Eye icon styling */
        .eye-icon {
            position: absolute;
            right: 15px; 
            top: 50%;   
            cursor: pointer;
        }


        /* Responsive Design */
        @media (max-width: 1200px) {
            /* On medium screens (tablets) or below */
            .combined-card {
                flex-direction: column;
                align-items: center;
            }

            .login-card {
                width: 100%;
                padding: 20px;
            }

            .image-card {
                max-width: 100%;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 767px) {
            /* On mobile screens */
            .container {
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }

            .combined-card {
                flex-direction: column;
                align-items: center;
            }

            .image-card {
                max-width: 100%;
                margin-bottom: 30px;
            }

            .login-card {
                width: 90%;  /* Adjust width for smaller screens */
                padding: 15px;
            }

            .form-group input {
                font-size: 14px;
            }

            .btn-block {
                font-size: 16px;
            }

            .login-card h4 {
                font-size: 1.2rem;
            }
        }

        h4 {
            color: #233268;
        }

        .blurred-image {
            width: 100% !important;
            height: 100%;
            object-fit: cover; /* Ensures the image covers the container without distortion */
            transition: filter 0.3s ease-in-out; /* Optional: Smooth transition for hover effect */
        }

        /* Eye icon position */
        .eye-icon {
            position: absolute;
            right: 15px;
            top: 10px;
            cursor: pointer;
        }

        .password-container {
            position: relative;
        }
    </style>
</head>

<body>
    <div id="app" class="login-container">
        <section class="section d-flex align-items-center">
            <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
                <div class="combined-card d-flex w-100">
                    <!-- Image Section (Optional, if you want to reuse the image) -->
                    <div>
                        <img src="{{ asset('assests/image/cover.jpg') }}" alt="Logo" class="img-fluid blurred-image">
                    </div>

                    <!-- Registration Form Section -->
                    <div class="login-card w-50 p-4">
                        <div class="text-center" style="color:#233268; margin-bottom:10%; margin-top:10%;">
                            <h4>Admin Registration Form</h4>
                        </div>

                        <!-- Alerts for Success, Error, Warning -->
                        @if(session('success'))
                            <div class="alert alert-success w-100 text-center mb-3 rounded-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger w-100 text-center mb-3 rounded-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Registration Form -->
                        <form action="{{ route('admin.register.post') }}" method="POST" class="w-100">
                            @csrf

                            <!-- Name Field -->
                            <div class="form-group">
                                <label for="name" class="h5 text-dark">Name</label>
                                <input id="name" type="text" class="form-control w-100 input-shadow" name="name"
                                    value="{{ old('name') }}" required placeholder="Enter your name">
                                @error('name')
                                    <div class="alert alert-danger mt-2 rounded-3">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="email" class="h5 text-dark">Email</label>
                                <input id="email" type="email" class="form-control w-100 input-shadow" name="email"
                                    value="{{ old('email') }}" required placeholder="Enter your email">
                                @error('email')
                                    <div class="alert alert-danger mt-2 rounded-3">{{ $message }}</div>
                                @enderror
                            </div>

                          <!-- Password Field -->
                            <div class="form-group password-container">
                                <label for="password" class="h5 text-dark">Password</label>
                                <div class="input-container">
                                    <input id="password" type="password" class="form-control w-100 input-shadow" name="password"
                                        required placeholder="Enter your password">
                                    <i class="fas fa-eye eye-icon" id="togglePassword"></i> <!-- Eye icon -->
                                </div>
                                @error('password')
                                    <div class="alert alert-danger mt-2 rounded-3">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="form-group password-container">
                                <label for="password_confirmation" class="h5 text-dark">Confirm Password</label>
                                <div class="input-container">
                                    <input id="password_confirmation" type="password" class="form-control w-100 input-shadow"
                                        name="password_confirmation" required placeholder="Confirm your password">
                                    <i class="fas fa-eye eye-icon" id="toggleConfirmPassword"></i>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block w-100">Register</button>
                            </div>
                        </form>

                        <!-- Login Link -->
                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.login') }}" class="text-dark">Already have an account? Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <script>
        // Password visibility toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        togglePassword.addEventListener('click', function () {
            const type = password.type === 'password' ? 'text' : 'password';
            password.type = type;
            this.classList.toggle('fa-eye-slash');
        });

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('password_confirmation');
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPassword.type === 'password' ? 'text' : 'password';
            confirmPassword.type = type;
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
