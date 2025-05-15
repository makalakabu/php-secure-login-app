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
}


require '../config.php';
$userpin = $_POST['userpin'];



//check timeout
if(time() > $_SESSION['time']){
    $_SESSION['tries']--;
    checkFlag($connection);
    $_SESSION['error'] =  "runout of time, sending a new pin to your email";
    header("Location: generatepin.php");
    exit();
}
else{

    //check if the pin are the same
    if((int)$userpin !== (int)$_SESSION['pin']){

        $_SESSION['wrong']--;

        //check the flag
        if($_SESSION['wrong'] < 0){
            $_SESSION['tries']--;
            checkFlag($connection);
            $_SESSION['error'] =  "Incorrect pin, sending a new pin to your email";
            header("Location: generatepin.php");
            exit();
        }


        $_SESSION['error'] = "Incorrect pin. attemps until new pin = " .$_SESSION['wrong'];
        header("Location: index.php");
        exit();
    }
    else{
     // If the pin correct continue 
        if($_SESSION['source'] === 'regis'){
            header("Location: ../registration/input-database.php");
            exit();
        }
        elseif($_SESSION['source'] === 'forgot-password'){

            header("Location: ../reset-password/input-database.php");
            exit();
        }
        elseif($_SESSION['source'] === 'login'){
            //admin is the first user in the databse
            $_SESSION['user_id'] = $_SESSION['temp_user_id'];
            if($_SESSION['user_id'] === 1){
                $_SESSION['role'] = 'admin';
            }
            else{
                $_SESSION['role'] = 'user';
            }
            header("Location: ../request-evaluation-upload/index.php");
            exit();
        }
       
    }

}


// Function to Ban Email
function banEmail($connection, $email) {
    $stmt = $connection->prepare("INSERT INTO banned_emails (email) VALUES (:email)");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
}

function checkFlag($connection){
    //Check flag. If the server already send the pin 3 times, email banned
    if($_SESSION['tries'] < 0){
        banEmail($connection, $_SESSION['otp_email']);
        session_unset();
        session_destroy();
        die("Your email has been baned");
    }
}
?>