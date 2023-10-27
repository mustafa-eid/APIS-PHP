<?php
// Connect to the database
include "../connectDB.php";
include "../token.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors = [];

    if (empty($email)) {
        $errors[] = 'Email is required';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    if (!empty($errors)) {
        echo json_encode(['errors' => $errors, 'message' => '', 'data'=>[]]);
    } else {
        $query = $con->prepare("SELECT * FROM `users` WHERE `email` = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        $data = ['id'=>$user['id'], 'username'=>$user['username'], 'email'=>$email, 'password'=>$password, 'token'=>$token];

        if ($user) {
            if ($password === $user['password']) {
                echo json_encode(['errors' => $errors, 'message' => 'Login successfully', 'data'=>$data]);
            } else {
                echo json_encode(['errors' => $errors, 'message' => 'Login failed', 'data'=>[]]);
            }
        } else {
            echo json_encode(['errors' => $errors, 'message' => 'User not found', 'data'=>[]]);
        }
    }
}
