<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = $_POST['task_name'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Insert new task into the database
    $sql = "INSERT INTO tasks (task_name, due_date, priority, user_id) VALUES (:task_name, :due_date, :priority, :user_id)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'task_name' => $task_name,
        'due_date' => $due_date,
        'priority' => $priority,
        'user_id' => $user_id
    ]);

    header('Location: index.php'); // Redirect back to the main page
    exit;
}
?>