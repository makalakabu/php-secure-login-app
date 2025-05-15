<?php
session_start(); // Start the session

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the main page if not an admin
    header("Location: ../request-evaluation-upload/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
            height: 100vh;
            margin: 0;
        }
        .listing-container {
            width: 100%;
            max-width: 800px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .table img {
            max-height: 100px;
            width: auto;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="listing-container">
        <h2 class="text-center">Evaluation Requests</h2>
        
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="text-danger text-center">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Object Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Submitted By</th>
                    <th>Contact Method</th>
                </tr>
            </thead>
            <tbody>
                <?php include 'list-evaluation.php'; ?>
            </tbody>
        </table>
        <div class="button-container">
            <a href="../request-evaluation-upload/index.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
