<?php

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email  = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (user_first_name, user_last_name, user_email, user_password)
                            VALUES (?,?,?,?)");
    $stmt->bind_param('ssss', $firstName, $lastName, $email, $hashed_password);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_first_name'] = $firstName;
        $_SESSION['user_last_name'] = $lastName;


        header("Location: ../../main/dashboard.php?status=loggedin");
        exit();
    } else {
        echo "ERROR : " . $stmt->error;
    }

    $conn->close();
    $stmt->close();
}
