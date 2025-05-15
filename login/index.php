<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> 
</head>
<body>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .login-container {
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
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center ">Login</h2>

        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<p class="error-message text-center">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="login.php" method="POST">
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control">
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control">
                <input type="hidden" name='csrf_token' value ='<?php echo $_SESSION['csrf_token']; ?>'>
            </div>

            <div class="g-recaptcha" data-sitekey="6LfRLZgqAAAAAF8f8YA1gzkbTu6oqh_NxgKTaAuK"></div>

            <button type="submit" class="btn btn-primary w-100">Login</button>

        </form>

        <a href="../forgot_question/index.php" class="btn btn-link w-100 text-center">Forgot Password?</a>

        <p class="text-center">
            Don't have an account? <a href="../registration/index.php">Register</a>
        </p>
    </div>

</body>
</html>
