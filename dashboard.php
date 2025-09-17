<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard - MicroTask</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
        }
        header {
            background: #2a5298;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .dashboard {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .dashboard h2 {
            color: #2a5298;
        }
        .task-list, .earnings {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background: #2a5298;
            color: white;
        }
        .review-form {
            margin-top: 10px;
        }
        .review-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
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
        .error, .success {
            text-align: center;
            padding: 10px;
        }
        .error { color: red; }
        .success { color: green; }
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Worker Dashboard</h1>
        <button onclick="window.location.href='marketplace.php'">Browse Tasks</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </header>
    <div class="container">
        <div class="dashboard">
            <h2>Your Tasks</h2>
            <div class="task-list">
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    session_start();
                    include 'db.php';
                    $worker_id = $_SESSION['user_id'];
                    $sql = "SELECT t.id, t.title, t.payment, ta.status, ta.rating, ta.review FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE ta.worker_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $worker_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . ucfirst($row['status']) . "</td>";
                            echo "<td>$" . number_format($row['payment'], 2) . "</td>";
                            echo "<td><a href='task_details.php?id=" . $row['id'] . "'>View</a>";
                            if ($row['status'] == 'completed' && !$row['rating']) {
                                echo "<form method='POST' action='rate_task.php' class='review-form'>";
                                echo "<input type='hidden' name='task_id' value='" . $row['id'] . "'>";
                                echo "<textarea name='review' placeholder='Leave a review' required></textarea>";
                                echo "<select name='rating' required>";
                                echo "<option value='5'>5 Stars</option><option value='4'>4 Stars</option><option value='3'>3 Stars</option><option value='2'>2 Stars</option><option value='1'>1 Star</option>";
                                echo "</select>";
                                echo "<button type='submit'>Submit Review</button>";
                                echo "</form>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No tasks assigned.</td></tr>";
                    }
                    $stmt->close();
                    ?>
                </table>
            </div>
            <div class="earnings">
                <h2>Earnings Summary</h2>
                <?php
                $sql = "SELECT SUM(t.payment) as total_earnings FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE ta.worker_id = ? AND ta.status = 'completed'";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $worker_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $earnings = $result->fetch_assoc()['total_earnings'] ?? 0;
                echo "<p><strong>Total Earnings:</strong> $" . number_format($earnings, 2) . "</p>";
                echo "<button onclick=\"window.location.href='withdraw.php'\">Withdraw Earnings</button>";
                $stmt->close();
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>
