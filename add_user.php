<?php
include 'db.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

if ($name && $email) {
    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    echo 'success';
} else {
    http_response_code(400);
    echo 'Missing fields';
}
?>
