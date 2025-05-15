<?php

//xss prevention
include '../xss-prevention.php';

session_start();

//CSRF Prevention
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])){
        die('Invalid CSRF token');
    }

    // Check reCAPTCHA response
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = "6LfRLZgqAAAAAClfSq2AmHhfQuKqWvwSXj2fw4XC";
    
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $response = file_get_contents($verifyUrl . "?secret=" . $secretKey . "&response=" . $recaptchaResponse);
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        $_SESSION['error'] = 'reCAPTCHA verification failed. Please try again.';
        header('Location: index.php');
        exit();
    }

}

require '../config.php';

//retrive from user input
$email = trim($_POST['email']);
$password = trim($_POST['password']);

//check any empty boxes
if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Please Fill in All Fields';
    header("Location: index.php");
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email format';
    header("Location: index.php");
    exit();
}

// attempts for brute force attack
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
    $_SESSION['lockedTime'] = null;
}

//check if the user still locked for 60s after get banned
if($_SESSION['attempts'] >= 5) {
    $time = time();
    if(($time - $_SESSION['lockedTime']) < 60){
        $_SESSION['error'] =  'Too many failed login attempts. Try again later.';
        header("Location: index.php");
        exit();
    }
    //if it already 60 second, reset the attempts and time
    else{
        $_SESSION['attempts'] = 0;
        $_SESSION['lockedTime'] = null;
    }
}


//retrieve email and password from databsae
try{

    $stmt = $connection->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        // matched password, beggin to start session
        session_regenerate_id(true);
        $_SESSION['temp_user_id'] = $user['id'];


        // reset attempts
        $_SESSION['attempts'] = 0; 
        $_SESSION['lockedTime'] = null;

        //2Fa
        $_SESSION['source'] = 'login';
        $_SESSION['otp_email'] = $email;
        header('Location: ../email-authentication/generatepin.php');
    }
    else{
        
        //if wrong, increment the flag and send error message. (intentionally )
        $_SESSION['attempts']++;

        if($_SESSION['attempts'] >= 5) {
            $_SESSION['lockedTime'] = time();
        }

        $_SESSION['error'] = 'Wrong Email or Password';
        header("Location: index.php");
        exit();
    }

}
catch(PDOExecption $e){
    die($e);
}


exit();
?>