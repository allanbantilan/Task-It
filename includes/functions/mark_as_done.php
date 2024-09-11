<?php

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $taskId = $_POST['doneId'];

    $stmt = $conn->prepare("UPDATE tasks SET task_is_done = 1, task_done_date = now() WHERE task_id = ?");
    $stmt->bind_param('i' , $taskId);

    if ($stmt->execute()) {
        echo "<script>
        alert('Task is edited Succesfully');
        window.location.href = '../../main/dashboard.php';
         </script>";
    } else {
        echo "Error : " . $stmt->error;
    }

    $conn->close();
    $stmt->close();
}
