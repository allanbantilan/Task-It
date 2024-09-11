<?php
include('db_connect.php');


// Get and sanitize the task ID
$tasksId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

header('Content-Type: application/json');

// Check if the ID is valid
if ($tasksId > 0) {
    // Prepare the query
    $completedTasksQuery = "SELECT task_done_name, task_done_category, task_done_description, task_done_date_added, task_done_due_date, task_
    FROM tasks_done WHERE  done_task_is_done = 1 AND task_id = ?";
    $stmt = $conn->prepare($completedTasksQuery);
    
    if ($stmt) {
        $stmt->bind_param('i', $tasksId);
        $stmt->execute();
        $result = $stmt->get_result();

        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }

        echo json_encode($tasks);
    } else {
        echo json_encode(["error" => "Query preparation failed."]);
    }
} else {
    echo json_encode(["error" => "Invalid task ID."]);
}

