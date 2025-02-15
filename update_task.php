<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Toggle the completed status
    $sql = "UPDATE tasks SET completed = 1 - completed WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);

    header('Location: index.php'); // Redirect back to the main page
    exit;
}
?>