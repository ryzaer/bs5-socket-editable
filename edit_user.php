<?php
include 'db.php';

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

if ($id && $name && $email) {
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();
    echo 'success';
} else {
    http_response_code(400);
    echo 'Missing data';
}
?>