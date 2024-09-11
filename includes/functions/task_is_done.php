<?php

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the taskId from POST data
    $taskId = $_POST['taskId'];

    // Determine the new state based on the task's current state
    $sql = "SELECT task_is_done FROM tasks WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $currentState = $row['task_is_done'];
        $newState = $currentState == 1 ? 0 : 1; // Toggle the state
        $newNumber = $currentState == 1 ? 0 : 1; // Toggle the state

        // Set the task_done_date based on the new state
        if ($newState == 1) {
            $taskDoneDate = 'NOW()';
        } else {
            $taskDoneDate = 'NULL';
        }

        if ($newNumber == 1) {
            $taskNumber = 1;
        } else {
            $taskNumber = 0;
        }

        // Update the task state and task_done_date in the database
        $updateSql = "UPDATE tasks SET task_is_done = ?, task_done_date = $taskDoneDate, task_done_number =  $taskNumber  WHERE task_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $newState, $taskId);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            echo "<script>
            alert('Task is edited successfully');
            window.location.href = '../../main/task.php';
             </script>";
        } else {
            echo "Error updating task: " . $updateStmt->error;
        }
    } else {
        echo "Task not found.";
    }

    $stmt->close();
    $conn->close();
}
