<?php
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'worker') {
    $task_id = $_POST['task_id'];
    $worker_id = $_SESSION['user_id'];
    $sql = "INSERT INTO task_assignments (task_id, worker_id, status) VALUES (?, ?, 'accepted')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $worker_id);
    if ($stmt->execute()) {
        echo "<script>alert('Task accepted!'); window.location.href='task_details.php?id=$task_id';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='marketplace.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>
