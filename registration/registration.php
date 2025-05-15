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


// Retrieve all attributes from the user
$email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
$name = htmlspecialchars(trim($_POST['name']));
$notelp = trim($_POST['notelp']);
$password = $_POST['password'];
$repassword = $_POST['repassword'];
$securityQuestion = htmlspecialchars(trim($_POST['securityQuestion']));
$securityAnswer = htmlspecialchars(trim($_POST['securityAnswer']));


// Function to check the database
function checkDatabase($connection, $tag, $boxname) {

    $stmt = $connection->prepare("SELECT COUNT(*) FROM user WHERE $boxname = :$boxname");
    $stmt->bindParam(":$boxname", $tag, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $_SESSION['error'] =  "The $boxname already registered.";
        header("Location: index.php");
        exit();

    }
}

//function to check banned email
function isEmailBanned($connection, $email){

    $stmt = $connection->prepare("SELECT COUNT(*) FROM banned_emails WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {

        $_SESSION['error'] =  "The $email ia banned.";
        header("Location: index.php");
        exit();
    }
}

//Function to check password
function checkPassword($password){

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



// Check whether the email is empty or invalid
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] =  "Invalid email format.";
    header("Location: index.php");
    exit();
} else {
    isEmailBanned($connection, $email);
    checkDatabase($connection, $email, "email");
}


// Do the same with name
if (empty($name) ) {
    $_SESSION['error'] =  "Invalid name format.";
   header("Location: index.php");
   exit();
}
else{
    checkDatabase($connection, $name, "name");
}


// notelp
if (empty($notelp) ) {
    $_SESSION['error'] =  "Invalid no telp format";
   header("Location: index.php");
   exit();
}
else{
    checkDatabase($connection, $notelp, "notelp");
}


// password
if (empty($password) || empty($repassword)) {
    $_SESSION['error'] =  "Invalid password format.";
   header("Location: index.php");
   exit();
}
else{
   //check if the password were typed identically
   if ($password != $repassword){
    $_SESSION['error'] = "Password or not the same!";
   }
   else{
    checkPassword($password);
   }
}

//Security Question
if (empty($securityQuestion) ) {
    $_SESSION['error'] =  "pick one Question";
    header("Location: index.php");
    exit();
 }

//security answer
if (empty($securityAnswer) ) {
    $_SESSION['error'] =  "Invalid answer format";
    header("Location: index.php");
    exit();
 }


 //If all corrcect, Stored the credential temporarily and continute to another page
 $_SESSION['email'] = $email;
 $_SESSION['otp_email'] = $email;
 $_SESSION['name'] = $name;
 $_SESSION['notelp'] = $notelp;
 $_SESSION['password'] = $password;
 $_SESSION['securityAnswer'] = $securityAnswer;
 $_SESSION['securityQuestion'] = $securityQuestion;


// email authentication
$_SESSION['source'] = 'regis';
 header("Location: ../email-authentication/generatepin.php");
 exit();

?>