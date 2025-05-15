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

$email = filter_var(trim($_POST["forgot-email"]), FILTER_SANITIZE_EMAIL);
$securityAnswer = htmlspecialchars(trim($_POST['securityAnswer']));
$securityQuestion = htmlspecialchars(trim($_POST['securityQuestion']));

// Validate email format
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email or missing information.";
    header("Location: index.php");
    exit();
}

// check email in databse
$stmt = $connection->prepare("SELECT id FROM user WHERE email = :email");
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "Invalid!";
    header("Location: index.php");
    exit();
}


//check the selected question matches in different table
$stmt = $connection->prepare("SELECT id FROM security_questions WHERE id = :question_id");
$stmt->bindParam(':question_id', $securityQuestion, PDO::PARAM_INT);
$stmt->execute();
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    $_SESSION['error'] = "Invalid!";
    header("Location: index.php");
    exit();
}

//retrieve the answer hash from different database and comopare it to user input
$stmt = $connection->prepare("SELECT answer_hash FROM user_security_answers WHERE user_id = :user_id AND question_id = :question_id");
$stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
$stmt->bindParam(':question_id', $question['id'], PDO::PARAM_INT);
$stmt->execute();
$storedAnswerHash = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$storedAnswerHash){
    $_SESSION['error'] = "Invalid.";
}

if (!password_verify($securityAnswer, $storedAnswerHash['answer_hash'])) {
    $_SESSION['error'] = "Invalid.";
    header("Location: index.php");
    exit();
}


//Continue to another page
$_SESSION['reset_email'] = $email;
header("Location: ../reset-password/index.php");
exit();






