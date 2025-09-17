<?php
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'worker') {
    $task_id = $_POST['task_id'];
    $worker_id = $_SESSION['user_id'];
    $submission = $_POST['submission'];
    $sql = "UPDATE task_assignments SET status = 'submitted', submission = ? WHERE task_id = ? AND worker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $submission, $task_id, $worker_id);
    if ($stmt->execute()) {
        echo "<script>alert('Task submitted successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='task_details.php?id=$task_id';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>
