<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
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
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2 class="text-center mb-3">Set New Password</h2>

        <!-- Display error message if it exists -->
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<p class="error-message">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }

        //check if it already pass the backup question
        if(isset($_SESSION['reset_password'])){
        header('Location: ../forgot_question/index.php');
        exit();
        }
        ?>

        <form action="reset-password.php" method="POST">
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" id="newPassword" name="newPassword" class="form-control">
            </div>
            <div class="mb-3">
                <label for="repassword" class="form-label">Re-type Password</label>
                <input type="password" id="new_repassword" name="new_repassword" class="form-control" >
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
            <input type="hidden" name='csrf_token' value ='<?php echo $_SESSION['csrf_token']; ?>'>
        </form>
        <p class="text-center mt-3">
            Remember your credentials carefully to ensure account security.
        </p>
    </div>
</body>
</html>
