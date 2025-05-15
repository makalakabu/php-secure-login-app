<?php
session_start();
require '../config.php';

try{

    $newPassword = password_hash($_SESSION['newpassword'] , PASSWORD_BCRYPT);

    $stmt = $connection->prepare("UPDATE user SET password = :password WHERE email = :email");
    $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
    $stmt->bindParam(':email', $_SESSION['reset_email'], PDO::PARAM_STR);
    $stmt->execute();

    header("Location: ../login/index.php");
    exit();
}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>