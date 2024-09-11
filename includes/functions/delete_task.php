<?php

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['deleteId'];

    $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header('Location: ../../main/task.php?status=deleted');
        exit();
    } else {
        echo "Error updating task" . $stmt->error;
    }

    $conn->close();
    $stmt->close();
}
