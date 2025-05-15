<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Evaluation</title>
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
        .container {
            width: 100%;
            max-width: 400px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Request Evaluation</h2>
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "You must be logged in to access this page.";
            header("Location: ../login/index.php");
            exit();
        }
        ?>
        <form action="request-evaluation-upload.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="object-name" class="form-label">Object Name</label>
                <input type="text" id="object-name" name="object-name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="contact-method" class="form-label">Contact Method</label>
                <select name="contact-method" id="contact-method" class="form-select" required>
                    <option value="telephone-method">Telephone</option>
                    <option value="email-method">Email</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="upload-file" class="form-label">Upload File</label>
                <input type="file" id="upload-file" name="upload-file" class="form-control" accept=".jpg, .jpeg, .png" required>
            </div>
            <div class="g-recaptcha" data-sitekey="6LfRLZgqAAAAAF8f8YA1gzkbTu6oqh_NxgKTaAuK"></div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
        </form>
        <div class="text-center mt-3">
            <form action="../logout/logout.php" method="POST">
                <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        </div>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <div class="text-center mt-3">
                <a href="../list-evaluation/index.php" class="btn btn-info w-100">View Evaluation List</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
