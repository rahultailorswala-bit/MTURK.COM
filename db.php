<?php
$host = 'localhost';
$db = 'dbuxjm3ebanyah';
$user = 'uzrprp3rmtdfr';
$pass = '#[qI(M3@k1bz';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
