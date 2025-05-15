<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .registration-container {
            width: 100%;
            max-width: 400px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .error-message {
            color: red;
            font-size: 0.9rem;
        }
        .strength-meter {
            height: 10px;
            width: 100%;
            margin-top: 5px;
            border-radius: 5px;
        }
        .weak {
            background-color: red;
        }
        .medium {
            background-color: orange;
        }
        .strong {
            background-color: green;
        }
        .strength-text {
            font-size: 0.9rem;
            font-weight: bold;
        }
        .strength-description {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2 class="text-center">Register</h2>

        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<p class="error-message text-center">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="registration.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="notelp" class="form-label">Telephone No</label>
                <input type="text" id="notelp" name="notelp" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" onkeyup="checkPasswordStrength()" required>
                <div id="password-strength" class="strength-meter"></div>
                <div id="password-strength-text" class="strength-text"></div>
            </div>
            <div class="mb-3">
                <label for="repassword" class="form-label">Re-enter Password</label>
                <input type="password" id="repassword" name="repassword" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="securityQuestion" class="form-label">Security Question</label>
                <select id="securityQuestion" name="securityQuestion" class="form-select" required>
                    <option value="1">What is the name of your father?</option>
                    <option value="2">What was your dream job as a child?</option>
                    <option value="3">What is the name of your favorite teacher?</option>
                    <option value="4">What is the title of your favorite book?</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="securityAnswer" class="form-label">Security Answer</label>
                <input type="text" id="securityAnswer" name="securityAnswer" class="form-control" required>
            </div>
            <div class="g-recaptcha" data-sitekey="6LfRLZgqAAAAAF8f8YA1gzkbTu6oqh_NxgKTaAuK"></div>
            <input type="hidden" name='csrf_token' value ='<?php echo $_SESSION['csrf_token']; ?>'>
            <button type="submit" class="btn btn-primary w-100">Submit</button>

        </form>

        <p class="text-center mt-3">
            Already have an account? <a href="../login/index.php">Login</a>
        </p>
    </div>

    <script>
        // Check the password strength as the user types
        function checkPasswordStrength() {
            var password = document.getElementById("password").value;
            var strengthMeter = document.getElementById("password-strength");
            var strengthText = document.getElementById("password-strength-text");

            // Regular expressions to check for different password characteristics
            var strongPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;  // At least 8 characters, one uppercase, one lowercase, one digit, one special character
            var mediumPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$/;  // At least 6 characters, one uppercase, one lowercase, one digit

            // Evaluate the strength of the password
            if (strongPattern.test(password)) {
                strengthMeter.className = 'strength-meter strong';
                strengthText.innerHTML = 'Strong: Great password!';
            } else if (mediumPattern.test(password)) {
                strengthMeter.className = 'strength-meter medium';
                strengthText.innerHTML = 'Medium: Could be stronger!';
            } else {
                strengthMeter.className = 'strength-meter weak';
                strengthText.innerHTML = 'Weak: Try adding more complexity.';
            }
        }
    </script>

</body>
</html>
