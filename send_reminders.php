<?php
include 'db.php';

// Fetch tasks due in the next 24 hours
$sql = "SELECT tasks.*, users.username, users.email 
        FROM tasks 
        JOIN users ON tasks.user_id = users.id 
        WHERE due_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 DAY) 
        AND completed = 0";
$stmt = $conn->query($sql);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tasks as $task) {
    $to = $task['email'];
    $subject = "Reminder: Task Due Soon";
    $message = "Hi " . $task['username'] . ",\n\n"
             . "Your task '" . $task['task_name'] . "' is due on " . $task['due_date'] . ".\n\n"
             . "Please complete it soon!\n";
    $headers = "From: no-reply@yourdomain.com";

    mail($to, $subject, $message, $headers);
}

echo "Reminders sent!";
?>