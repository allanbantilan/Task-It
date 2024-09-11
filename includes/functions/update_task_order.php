<?php


// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to send JSON response
function sendJsonResponse($success, $message = '', $data = null)
{
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Log function
function logError($message)
{
    error_log(date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 3, __DIR__ . '/error.log');
}

// Wrap the entire script in a try-catch block
try {
    logError("Script started");

    if (!file_exists('db_connect.php')) {
        throw new Exception('db_connect.php file not found');
    }

    require_once('db_connect.php');
    logError("db_connect.php included");

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
    }

    $json = file_get_contents('php://input');
    logError("Received JSON: " . $json);

    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON decode error: ' . json_last_error_msg());
    }

    if (!isset($data['taskOrder']) || !is_array($data['taskOrder']) || empty($data['taskOrder'])) {
        throw new Exception('Invalid or empty taskOrder received: ' . print_r($data, true));
    }

    $conn->begin_transaction();
    logError("Transaction begun");

    $sql = "INSERT INTO tasks (task_id, task_order) VALUES (?, ?) ON DUPLICATE KEY UPDATE task_order = VALUES(task_order)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    foreach ($data['taskOrder'] as $index => $taskId) {
        if (!is_numeric($taskId)) {
            logError("Invalid task ID: " . $taskId);
            continue;
        }
        logError("Executing SQL: " . $sql . " with params: taskId=" . $taskId . ", index=" . $index);
        $stmt->bind_param("ii", $taskId, $index);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed for taskId $taskId: " . $stmt->error);
        }
        $affected_rows = $stmt->affected_rows;
        logError("Affected rows for taskId $taskId: " . $affected_rows);
    }
    $stmt->close();

    $commit_result = $conn->commit();
    if ($commit_result === false) {
        throw new Exception("Failed to commit transaction: " . $conn->error);
    }
    logError("Transaction committed successfully");
    sendJsonResponse(true, 'Task order updated successfully');
} catch (Exception $e) {
    logError('Caught exception: ' . $e->getMessage());
    if (isset($conn) && $conn->connect_errno == 0) {
        $conn->rollback();
        logError('Transaction rolled back');
    }
    sendJsonResponse(false, 'An error occurred: ' . $e->getMessage());
}

if (isset($conn) && $conn->connect_errno == 0) {
    $conn->close();
    logError("Database connection closed");
}

logError("Script ended");
