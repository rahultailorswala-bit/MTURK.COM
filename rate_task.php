<?php
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'worker') {
    $task_id = $_POST['task_id'];
    $worker_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $sql = "UPDATE task_assignments SET rating = ?, review = ? WHERE task_id = ? AND worker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $rating, $review, $task_id, $worker_id);
    if ($stmt->execute()) {
        echo "<script>alert('Review submitted!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='dashboard.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>
