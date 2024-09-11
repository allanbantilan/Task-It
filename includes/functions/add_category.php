<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare('INSERT INTO category (category_name, user_id) VALUES (?,?)');
    $stmt->bind_param('si', $category, $user_id);

    if ($stmt->execute()) {
        header('Location: ../../main/categories.php?status=addCategory');
        exit();
    } else {
        echo "error ." . $stmt->error;
    }

    $conn->close();
    $stmt->close();
}
