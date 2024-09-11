<?php

include 'db_connect.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //from users
    $taskTitle = $_POST['taskTitle'];
    $description = $_POST['description'];
    $dueDate = $_POST['dueDate'];
    $taskCategory = $_POST['taskCategory'];
    // auto
    $taskAddedDate = date('Y-m-d H:i:s');
    $taskIsDone = 0;
    $taskNumber = 0;

    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (task_name, task_description, task_due_date, task_date_added, task_is_done, task_category, task_done_number, user_id) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssisii', $taskTitle, $description, $dueDate, $taskAddedDate, $taskIsDone, $taskCategory, $taskNumber, $userId);

    if ($stmt->execute()) {
        header('Location: ../../main/task.php?status=success');
        exit();
    } else {
        echo "Error Adding Task :" . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
