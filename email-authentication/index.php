<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validate PIN</title>
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
        .container {
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
    <div class="container">
        <h2 class="text-center mb-4">Validate Your PIN</h2>
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="validatepin.php" method="POST">
            <div class="mb-3">
                <label for="userpin" class="form-label">Enter the PIN sent to your email</label>
                <input type="text" class="form-control" id="userpin" name="userpin" required>
                <input type="hidden" name='csrf_token' value ='<?php echo $_SESSION['csrf_token']; ?>'>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>

        <p class="text-center mt-3">
            <a href="../login/Login.html">Back to Login</a>
        </p>
    </div>
</body>
</html>
