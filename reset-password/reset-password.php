<?php
//xss prevention
include '../xss-prevention.php';


session_start();

//check if it already pass the backup question
if(!$_SESSION['reset_password'] || !isset($_SESSION['reset_password'])){
    header('Location: ../forgot_question/index.php');
    exit();
}

//CSRF Prevention
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])){
        die('Invalid CSRF token');
    }
}


require_once '../config.php';

//retrive from user input
$newPassword = $_POST['newPassword'];
$new_repassword = $_POST['new_repassword'];

function checkPassword($newPassword){

   // Check minimum length
   if (strlen($password) < 8) {
    $_SESSION['error'] =  "Password must be at least 8 characters long.";
    header("Location: index.php");
    exit();
}
// Check for uppercase letter
if (!preg_match('/[A-Z]/', $password)) {
    $_SESSION['error'] =  "Password must include at least one uppercase letter.";
    header("Location: index.php");
    exit();
}
// Check for lowercase letter
if (!preg_match('/[a-z]/', $password)) {
    $_SESSION['error'] =  "Password must include at least one lowercase letter.";
    header("Location: index.php");
    exit();
}
// Check for a number
if (!preg_match('/[0-9]/', $password)) {
    $_SESSION['error'] =  "Password must include at least one number.";
    header("Location: index.php");
    exit();
}
// Check for a special character
if (!preg_match('/[\W_]/', $password)) {
    $_SESSION['error'] =  "Password must include at least one special character.";
    header("Location: index.php");
    exit();
}

}


// check the password
if (empty($newPassword) || empty($new_repassword)) {
    $_SESSION['error'] = "Invalid password format.";
    header("Location: index.php");
    exit();
 }
 else{
    //check if the password were typed identically
    if ($newPassword != $new_repassword){
        $_SESSION['error'] = "Password or not the same!";
        header("Location: index.php");
        exit();
    }
    else{
        checkPassword($newPassword)
        //2Fa
        $_SESSION['newpassword'] = $newPassword;
        $_SESSION['source'] = 'forgot-password';
        $_SESSION['otp_email'] = $_SESSION['reset_email'];
        header('Location: ../email-authentication/generatepin.php');
        exit();
    
    }
 }
 
?>