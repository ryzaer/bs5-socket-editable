<?php
$host = 'localhost';
$user = 'root';
$pass = '123';
$db   = 'test_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>