<?php


//xss prevention
include '../xss-prevention.php';


session_start();

//check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to submit a request.";
    header("Location: ../login/index.php");
    exit();
}


//CSRF Prevention
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

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

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token'])){
        die('Invalid CSRF token');
    }
}


require '../config.php';

//retrive all information from user input
$userId = $_SESSION['user_id'];
$objectName = htmlspecialchars(trim($_POST['object-name']));
$description = htmlspecialchars(trim($_POST['description']));
$contactMethod = htmlspecialchars(trim($_POST['contact-method']));



//check object name
if (empty($objectName) ) {
    $_SESSION['error'] =  "Invalid name.";
   header("Location: index.php");
   exit();
}

//check description
if (empty($description) ) {
    $_SESSION['error'] =  "Invalid description.";
   header("Location: index.php");
   exit();
}

//check contact method
if (empty($contactMethod) ) {
    $_SESSION['error'] =  "Invalid contact method.";
   header("Location: index.php");
   exit();
}


//check file
if (!isset($_FILES["upload-file"])) {
    $_SESSION['error'] = 'invalid file.';
    header('Location: index.php');
 }

 $filepath = $_FILES['upload-file']['tmp_name'];
 $fileSize = filesize($filepath);
 $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
 $filetype = finfo_file($fileinfo, $filepath);

 //check if it's empty
if ($fileSize === 0) {
    $_SESSION['error'] = 'invalid file';
    header('Location: index.php');
    exit();
}

//limitate the size
if ($fileSize > 10000000) { //roughly 10 mb
    $_SESSION['error'] = 'invalid file';
    header('Location: index.php');
    exit();
 }

 //limitate the type of the file
$allowedTypes = ['image/png' => 'png','image/jpeg' => 'jpg'];

if(!in_array($filetype, array_keys($allowedTypes))) {
    $_SESSION['error'] = 'invalid file';
    header('Location: index.php');
    exit();
}

//make a file path to store the img in the server
$extension = $allowedTypes[$filetype];
$targetDirectory = '../img'; 
$newFilepath = $targetDirectory . "/" . $objectName . "." . $extension;

//check the copied file
if (!copy($filepath, $newFilepath)) {
    $_SESSION['error'] = 'invalid file';
    header('Location: index.php');
    exit();
}
unlink($filepath); 


try{
    //input to database
    $stmt = $connection->prepare("INSERT INTO evaluation (object_name, description, contact_method, user_id, img_path) VALUES (:object_name, :description, :contact_method, :user_id, :img_path)");
    $stmt->bindParam(':object_name', $objectName, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':contact_method', $contactMethod, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':img_path', $newFilepath, PDO::PARAM_STR);

    $stmt->execute();
    $_SESSION['success'] = "Successfull";
}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>