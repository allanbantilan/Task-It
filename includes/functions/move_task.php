<?php

// Include your database connection
include 'db_connect.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the task ID from POST data
    $taskId = isset($_POST['deleteId']) ? intval($_POST['deleteId']) : 0;

    if ($taskId > 0) {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Prepare and execute the insertion into tasks_done
            $stmt = $conn->prepare("UPDATE tasks SET task_done_number = 1 WHERE task_id = ?");
               
            $stmt->bind_param("i", $taskId);
            $stmt->execute();

            // Commit transaction
            $conn->commit();
        } catch (Exception $e) {
            // Rollback transaction if there's an error
            $conn->rollback();
            die("Error: " . $e->getMessage());
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        echo "<script>
        alert('Task is edited Succesfully');
        window.location.href = '../../main/dashboard.php';
         </script>";
        exit();
    } else {
        die("Invalid task ID.");
    }
} else {
    die("Invalid request method.");
}
