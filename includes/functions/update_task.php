<?php

include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['editId'];
    $title = $_POST['editTaskTitle'];
    $category = $_POST['editTaskCategory'];
    $description = $_POST['editDescription'];

    $stmt = $conn->prepare("UPDATE tasks SET task_name = ?, task_category = ?, task_description =? WHERE task_id = ?");
    $stmt->bind_param('sssi', $title, $category, $description, $id);


    if ($stmt->execute()) {
        header('Location: ../../main/task.php?status=edited');
        exit();
    } else {
        echo "Error updating task" . $stmt->error;
    }

    $conn->close();
    $stmt->close();
}
