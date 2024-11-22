<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>HR Document Monitoring System</title>
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
        width: 690px ;
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

/* Optional: Add a hover effect to remove blur on hover */
.blurred-image:hover {
}

</style>

</head>

<body>

    <div id="app" class="login-container" style="
            color:#233268;">
        <section class="section d-flex align-items-center" >
        <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <!-- Combined Card View: Image on Left, Login Form on Right -->
    <div class="combined-card d-flex w-100">
        <!-- Image Section -->
        <div >
        <img src="{{ asset('assests/image/cover.jpg') }}" alt="Logo" class="img-fluid blurred-image">
sss
        </div>

        <!-- Login Form Section -->
        <div class="login-card w-50 p-4">
            <div class="text-center" style="
            color:#233268; margin-bottom:10%; margin-top:10%;">
                <h4>Sign In Form</h4>
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

            @if(session('warning'))
            <div class="alert alert-warning w-100 text-center mb-3 rounded-3">
                {{ session('warning') }}
            </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.login.post') }}" method="post" class="w-100">
                @csrf
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="h5 text-dark">Email</label>
                    <input id="email" type="email" class="form-control w-100 input-shadow" name="email" value="{{ old('email') }}" required placeholder="Enter your email" tabindex="1">
                    @error('email')
                    <div class="alert alert-danger mt-2 rounded-3">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password (if applicable) -->
                @if(session('warning') == 'Set up your new password.')
                    <div class="form-group">
                        <label for="new_password" class="h5 text-dark">New Password</label>
                        <input id="new_password" type="password" class="form-control w-100 input-shadow" name="new_password" required placeholder="Enter your new password" tabindex="2">
                        <div class="alert alert-danger mt-2" id="new_password_pattern_error"></div>
                        @error('new_password')
                        <div class="alert alert-danger mt-2 rounded-3">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-group">
                        <label for="password_confirmation" class="h5 text-dark">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-control w-100 input-shadow" name="new_password_confirmation" required placeholder="Confirm your new password" tabindex="3">
                        <div class="alert alert-danger mt-2" id="new_password_confirmation_pattern_error"></div>
                        @error('new_password_confirmation')
                        <div class="alert alert-danger mt-2 rounded-3">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <div class="form-group">
                        <label for="password" class="h5 text-dark">Password</label>
                        <input id="password" type="password" class="form-control w-100 input-shadow" name="password" required placeholder="Enter your password" tabindex="2">
                        @error('password')
                        <div class="alert alert-danger mt-2 rounded-3">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" id="loginBtn" class="btn btn-primary btn-lg btn-block w-100 btn-hover-shadow" tabindex="4">Login</button>
                </div>
            </form>

            <!-- Registration Link -->
            @if(!$hasAdmin)
                <div class="mt-3 fw-bold text-center">
                    <a href="{{ route('admin.register') }}" class="text-dark">Register Config Admin</a>
                </div>
            @endif
        </div>
    </div>
</div>

                </div>
            </div>
        </section>
    </div>
</div>

{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script> --}}

<script>
  // Function to check the password criteria
function validatePasswordCriteria(password) {
    const errors = [];

    if (!/(?=.*[A-Z])/.test(password)) {
        errors.push("Required one uppercase letter");
    }

    if (!/(?=.*\d)/.test(password)) {
        errors.push("Required one number");
    }

    if (!/(?=.*[@$!%*?&#;])/.test(password)) {
        errors.push("Required one special character");
    }

    return errors;
}

// Function to validate the form inputs
function validateForm() {
    const passwordErrors = validatePasswordCriteria(passwordField.value);
    const confirmPasswordErrors = validatePasswordCriteria(confirmPasswordField.value);

    const passwordErrorDiv = document.getElementById('new_password_pattern_error');
    const confirmPasswordErrorDiv = document.getElementById('new_password_confirmation_pattern_error');

    // Show errors for the password field
    if (passwordErrors.length > 0) {
        passwordErrorDiv.innerHTML = passwordErrors.join('<br>');
        passwordErrorDiv.style.display = 'block';
    } else {
        passwordErrorDiv.innerHTML = '';
        passwordErrorDiv.style.display = 'none';
    }

    // Show errors for the confirm password field
    if (confirmPasswordErrors.length > 0) {
        confirmPasswordErrorDiv.innerHTML = confirmPasswordErrors.join('<br>');
        confirmPasswordErrorDiv.style.display = 'block';
    } else {
        confirmPasswordErrorDiv.innerHTML = '';
        confirmPasswordErrorDiv.style.display = 'none';
    }

    // Enable the button only if both fields are valid
    loginBtn.disabled = !(passwordErrors.length === 0 && confirmPasswordErrors.length === 0);

    console.log(passwordErrors, confirmPasswordErrors, passwordField.value, confirmPasswordField.value);
}

// Get the input fields and the button
const passwordField = document.getElementById('new_password');
const confirmPasswordField = document.getElementById('password_confirmation');
const loginBtn = document.getElementById('loginBtn');

// Add event listeners to trigger validation on keyup
passwordField.addEventListener('keyup', validateForm);
confirmPasswordField.addEventListener('keyup', validateForm);

// Initial validation to ensure the button is disabled on page load if the inputs are empty
validateForm();


</script>
</body>

</html>