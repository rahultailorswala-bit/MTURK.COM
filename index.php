<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MicroTask - Earn Money with Small Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        header {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .intro, .featured-tasks {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .intro h2, .featured-tasks h2 {
            color: #2a5298;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background: #2a5298;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }
        .buttons button:hover {
            background: #1e3c72;
        }
        .task-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .task-card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .task-card:hover {
            transform: translateY(-5px);
        }
        .task-card h3 {
            margin: 0;
            color: #2a5298;
        }
        @media (max-width: 768px) {
            .task-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to MicroTask</h1>
        <p>Earn money by completing small tasks from anywhere!</p>
    </header>
    <div class="container">
        <div class="intro">
            <h2>How It Works</h2>
            <p>Join MicroTask as a worker to complete small tasks like surveys, data entry, or transcription and earn money. Or become a requester to post tasks and get work done efficiently!</p>
            <div class="buttons">
                <button onclick="window.location.href='signup.php?type=worker'">Sign Up as Worker</button>
                <button onclick="window.location.href='signup.php?type=requester'">Sign Up as Requester</button>
            </div>
        </div>
        <div class="featured-tasks">
            <h2>Featured Tasks</h2>
            <div class="task-grid">
                <?php
                include 'db.php';
                $sql = "SELECT * FROM tasks WHERE status = 'open' LIMIT 3";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='task-card'>";
                        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                        echo "<p><strong>Payment:</strong> $" . number_format($row['payment'], 2) . "</p>";
                        echo "<p><strong>Deadline:</strong> " . $row['deadline'] . "</p>";
                        echo "<button onclick=\"window.location.href='task_details.php?id=" . $row['id'] . "'\">View Task</button>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No tasks available at the moment.</p>";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>
