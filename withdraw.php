<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Earnings - MicroTask</title>
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
        .withdraw-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .withdraw-container h2 {
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
        .form-group input {
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
        .error, .success {
            text-align: center;
            padding: 10px;
        }
        .error { color: red; }
        .success { color: green; }
        @media (max-width: 480px) {
            .withdraw-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="withdraw-container">
        <h2>Withdraw Earnings</h2>
        <?php
        session_start();
        include 'db.php';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $amount = $_POST['amount'];
            $method = $_POST['method'];
            $worker_id = $_SESSION['user_id'];
            $sql = "SELECT SUM(t.payment) as total_earnings FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE ta.worker_id = ? AND ta.status = 'completed'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $worker_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $earnings = $result->fetch_assoc()['total_earnings'] ?? 0;
            if ($amount <= $earnings) {
                $sql = "INSERT INTO withdrawals (worker_id, amount, method, status) VALUES (?, ?, ?, 'pending')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ids", $worker_id, $amount, $method);
                if ($stmt->execute()) {
                    echo "<p class='success'>Withdrawal request submitted!</p>";
                } else {
                    echo "<p class='error'>Error: " . $conn->error . "</p>";
                }
            } else {
                echo "<p class='error'>Insufficient funds.</p>";
            }
            $stmt->close();
            $conn->close();
        }
        ?>
        <form method="POST">
            <div class="form-group">
                <label for="amount">Amount ($)</label>
                <input type="number" id="amount" name="amount" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="method">Withdrawal Method</label>
                <input type="text" id="method" name="method" placeholder="PayPal, Bank, etc." required>
            </div>
            <button type="submit">Request Withdrawal</button>
        </form>
    </div>
</body>
</html>
