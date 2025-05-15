<?php
session_start();
require '../config.php';

 // Hash the password and security answer
 $password = password_hash($_SESSION['password'], PASSWORD_BCRYPT);
 $securityAnswer = password_hash($_SESSION['securityAnswer'], PASSWORD_BCRYPT);

 try {
     //inset into database all the user credential
     $stmt = $connection->prepare("INSERT INTO user (name, email, notelp, password) VALUES (:name, :email, :notelp, :password)");
     $stmt->bindParam(':name', $_SESSION['name'], PDO::PARAM_STR);
     $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
     $stmt->bindParam(':notelp', $_SESSION['notelp'], PDO::PARAM_STR);
     $stmt->bindParam(':password', $password, PDO::PARAM_STR);
     $stmt->execute();

    //Initiate the role and user id
    $userId = $connection->lastInsertId();
    $_SESSION['user_id'] = $userId; // Store the user ID in the session
    if($_SESSION['user_id'] === 1){
        $_SESSION['role'] = 'admin';
    }
    else{
        $_SESSION['role'] = 'user';
    }

     //store security answer and question to database
     $stmt = $connection->prepare("INSERT INTO user_security_answers (user_id, question_id, answer_hash) VALUES (:user_id, :question_id, :answer_hash)");
     $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
     $stmt->bindParam(':question_id', $_SESSION['securityQuestion'], PDO::PARAM_INT);
     $stmt->bindParam(':answer_hash', $securityAnswer, PDO::PARAM_STR);
     $stmt->execute();


    header("Location: ../request-evaluation-upload/index.php");
    exit();

 } 
 catch (PDOException $e) {
         echo "Error: " . $e->getMessage();
 }

?>