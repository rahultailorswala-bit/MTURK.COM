<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details - MicroTask</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        h2 {
            color: #2a5298;
        }
        .task-details p {
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
            background: #2a5298;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #1e3c72;
        }
        .submission-form {
            margin-top: 20px;
        }
        .submission-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom Marisa Merino 15px 0;
        }
        .error, .success {
            text-align: center;
            padding: 10px;
        }
        .error { color: red; }
        .success { color: green; }
        @media (max-width: 480px) {
            .container {
                margin: 10px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        include 'db.php';
        $task_id = $_GET['id'];
        $sql = "SELECT * FROM tasks WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $task = $result->fetch_assoc();
            echo "<h2>" . htmlspecialchars($task['title']) . "</h2>";
            echo "<div class='task-details'>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($task['description']) . "</p>";
            echo "<p><strong>Category:</strong> " . ucfirst($task['category']) . "</p>";
            echo "<p><strong>Payment:</strong> $" . number_format($task['payment'], 2) . "</p>";
            echo "<p><strong>Deadline:</strong> " . $task['deadline'] . "</p>";
            echo "</div>";
            if (isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'worker') {
                $sql = "SELECT * FROM task_assignments WHERE task_id = ? AND worker_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $task_id, $_SESSION['user_id']);
                $stmt->execute();
                $assignment = $stmt->get_result()->fetch_assoc();
                if (!$assignment) {
                    echo "<form method='POST' action='apply_task.php'>";
                    echo "<input type='hidden' name='task_id' value='$task_id'>";
                    echo "<button type='submit'>Apply for Task</button>";
                    echo "</form>";
                } elseif ($assignment['status'] == 'accepted') {
                    echo "<div class='submission-form'>";
                    echo "<form method='POST' action='submit_task.php'>";
                    echo "<input type='hidden' name='task_id' value='$task_id'>";
                    echo "<textarea name='submission' rows='5' placeholder='Enter your submission here' required></textarea>";
                    echo "<button type='submit'>Submit Task</button>";
                    echo "</form>";
                    echo "</div>";
                } elseif ($assignment['status'] == 'submitted') {
                    echo "<p class='success'>Task submitted, awaiting approval.</p>";
                }
            }
        } else {
            echo "<p class='error'>Task not found.</p>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
