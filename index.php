<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

// Fetch logged-in user's details
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get the first letter of the username
$username = $user['username'];
$first_letter = strtoupper(substr($username, 0, 1)); // Get the first letter and capitalize it

// Fetch tasks for the logged-in user
$sql = "SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Circular Avatar */
        .avatar {
            width: 40px;
            height: 40px;
            background-color: #007bff; /* Blue background */
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
        }

        /* Align avatar and username */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
    <script>
        // Automatically dismiss alerts after 3 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 3000);
    </script>
</head>
<body>
    <div class="container mt-5">
        <!-- Display Logged-in User's Avatar and Username -->
        <div class="text-end mb-4 user-info">
            <div class="avatar"><?php echo $first_letter; ?></div>
            <span><?php echo htmlspecialchars($username); ?></span>
        </div>

        <!-- Display Alerts -->
        <?php if (isset($_SESSION['login_success'])): ?>
            <div class="alert alert-success">Login successful!</div>
            <?php unset($_SESSION['login_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['task_added'])): ?>
            <div class="alert alert-success">Task added successfully!</div>
            <?php unset($_SESSION['task_added']); ?>
        <?php endif; ?>

        <h1 class="text-center">To-Do List</h1>

        <!-- Add Task Form -->
        <form action="add_task.php" method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="task_name" class="form-control" placeholder="Enter a new task" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="due_date" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="priority" class="form-select">
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Add</button>
                </div>
            </div>
        </form>

        <!-- Display Tasks -->
        <div class="list-group">
            <?php if (empty($tasks)): ?>
                <div class="list-group-item">No tasks found.</div>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center <?php echo $task['completed'] ? 'completed' : ''; ?>">
                        <div>
                            <span><?php echo htmlspecialchars($task['task_name']); ?></span>
                            <small class="text-muted ms-3">Due: <?php echo $task['due_date']; ?></small>
                            <small class="text-muted ms-3">Priority: <?php echo $task['priority']; ?></small>
                        </div>
                        <div>
                            <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="update_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-success"><?php echo $task['completed'] ? 'Undo' : 'Complete'; ?></a>
                            <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Logout Button -->
        <div class="mt-4 text-center">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>