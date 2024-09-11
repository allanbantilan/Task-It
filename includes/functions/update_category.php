<?php

include 'db_connect.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $categoryName = $_POST['categoryEdit'];
    $editId = $_POST['categoryEditId'];

    $stmt = $conn->prepare("UPDATE category SET category_name = ? WHERE category_id = ?");
    $stmt->bind_param('si', $categoryName, $editId);

    if ($stmt->execute()) {
        header('Location: ../../main/categories.php?status=editCategory');
        exit();
    } else {
        echo "ERROR :" . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
