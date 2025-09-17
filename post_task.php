<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Task - MicroTask</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .post-task-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
        }
        .post-task-container h2 {
            color: #2a5298;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
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
        .error {
            color: red;
            text-align: center;
        }
        @media (max-width: 480px) {
            .post-task-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="post-task-container">
        <h2>Post a New Task</h2>
        <?php
        session_start();
        include 'db.php';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $category = $_POST['category'];
            $payment = $_POST['payment'];
            $deadline = $_POST['deadline'];
            $requester_id = $_SESSION['user_id'];
            $sql = "INSERT INTO tasks (title, description, category, payment, deadline, requester_id, status) VALUES (?, ?, ?, ?, ?, ?, 'open')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdis", $title, $description, $category, $payment, $deadline, $requester_id);
            if ($stmt->execute()) {
                echo "<p>Task posted successfully! <a href='marketplace.php'>View Marketplace</a></p>";
            } else {
                echo "<p class='error'>Error: " . $conn->error . "</p>";
            }
            $stmt->close();
            $conn->close();
        }
        ?>
        <form method="POST">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="data_entry">Data Entry</option>
                    <option value="survey">Survey</option>
                    <option value="transcription">Transcription</option>
                </select>
            </div>
            <div class="form-group">
                <label for="payment">Payment ($)</label>
                <input type="number" id="payment" name="payment" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" id="deadline" name="deadline" required>
            </div>
            <button type="submit">Post Task</button>
        </form>
    </div>
</body>
</html>
