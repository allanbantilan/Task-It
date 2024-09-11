<?php

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a query to fetch user data
    $stmt = $conn->prepare("SELECT user_id, user_password, user_first_name, user_last_name FROM users WHERE user_email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $firstName, $lastName);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_first_name'] = $firstName;
            $_SESSION['user_last_name'] = $lastName;

            // Redirect to dashboard
            header("Location: ../../main/dashboard.php?status=loggedin");
            exit();
        } else {
            echo "<script>
            alert('Incorrect Password.');
            window.location.href = '../../index.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Records did not match in our database.');
            window.location.href = '../../index.php';
            </script>";
    }

    $stmt->close();
    $conn->close();
}
