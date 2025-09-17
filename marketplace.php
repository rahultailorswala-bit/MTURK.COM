<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Marketplace - MicroTask</title>
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
        .filters {
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .filters select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .task-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .task-card {
            background: white;
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
        @media (max-width: 768px) {
            .task-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Task Marketplace</h1>
        <button onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
    </header>
    <div class="container">
        <div class="filters">
            <label for="category">Filter by Category:</label>
            <select id="category" onchange="window.location.href='marketplace.php?category='+this.value">
                <option value="">All</option>
                <option value="data_entry">Data Entry</option>
                <option value="survey">Survey</option>
                <option value="transcription">Transcription</option>
            </select>
        </div>
        <div class="task-grid">
            <?php
            session_start();
            include 'db.php';
            $category = isset($_GET['category']) ? $_GET['category'] : '';
            $sql = "SELECT * FROM tasks WHERE status = 'open'";
            if ($category) {
                $sql .= " AND category = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $category);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $conn->query($sql);
            }
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='task-card'>";
                    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p><strong>Category:</strong> " . ucfirst($row['category']) . "</p>";
                    echo "<p><strong>Payment:</strong> $" . number_format($row['payment'], 2) . "</p>";
                    echo "<p><strong>Deadline:</strong> " . $row['deadline'] . "</p>";
                    echo "<button onclick=\"window.location.href='task_details.php?id=" . $row['id'] . "'\">Apply for Task</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>No tasks available in this category.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
