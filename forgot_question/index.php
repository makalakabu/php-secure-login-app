<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> 
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .reset-container {
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
    <div class="reset-container">
        <h2 class="text-center">Reset Password</h2>

        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<p class="error-message text-center">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="forgot-question.php" method="post">
            <div class="mb-3">
                <label for="forgot-email" class="form-label">Email</label>
                <input type="email" id="forgot-email" name="forgot-email" class="form-control">
            </div>
            <div class="mb-3">
                <label for="securityQuestion" class="form-label">Security Question</label>
                <select id="securityQuestion" name="securityQuestion" class="form-select">
                    <option value="1">What is the name of your father?</option>
                    <option value="2">What was your dream job as a child?</option>
                    <option value="3">What is the name of your favorite teacher?</option>
                    <option value="4">What is the title of your favorite book?</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="securityAnswer" class="form-label">Security Answer</label>
                <input type="text" id="securityAnswer" name="securityAnswer" class="form-control">
                <input type="hidden" name='csrf_token' value ='<?php echo $_SESSION['csrf_token']; ?>'>
            </div>
            <div class="g-recaptcha" data-sitekey="6LfRLZgqAAAAAF8f8YA1gzkbTu6oqh_NxgKTaAuK"></div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>


    </div>
</body>
</html>
