<?php
include 'db.php';

$id = $_POST['id'] ?? '';

if ($id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo 'success';
} else {
    http_response_code(400);
    echo 'Invalid ID';
}
?>