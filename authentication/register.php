<?php
// Connect to the database
include "../connectDB.php";

$errors = [];

$email = $_POST['email'];
$password = $_POST['password'];
$username = $_POST['username'];
$device_name = $_POST['device_name'];

    if (empty($email)) {
        $errors[] = 'Email is required';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    if (empty($username)) {
        $errors[] = 'Username is required';
    }


$checkEmailQuery = $con->prepare("SELECT * FROM `users` WHERE `email` = ?");
$checkEmailQuery->execute(array($email));
$data = ['username' => $username, 'email' => $email, 'password' => $password, 'device_name'=>$device_name];

// Check if there are any errors
if (!empty($errors)) {
    echo json_encode(array("error" => $errors, "message" => "", "data" => []));
} else {
    if ($checkEmailQuery->rowCount() > 0) {
        // Email is already in use, add an error message to the errors array
        $errors[] = "Email is already in use";
        echo json_encode(array("error" => $errors, "message" => "", "data" => []));
    } else {
        // Email is unique, proceed with the insertion
        $insertQuery = $con->prepare("INSERT INTO `users`(`username`, `email`, `password`, `device_name`) VALUES (?, ?, ?, ?)");
        $insertQuery->execute(array($username, $email, $password, $device_name));

        if ($insertQuery->rowCount() > 0) {
            // User added successfully
            echo json_encode(array("error" => $errors, "message" => "User added successfully", "data" => $data));
        } else {
            // Insertion failed for some reason, add an error message to the errors array
            $errors[] = "Failed to insert user";
            echo json_encode(array( "error" => $errors, "message" => "Failed to insert user",  "data" => []));
        }
    }
}
?>
