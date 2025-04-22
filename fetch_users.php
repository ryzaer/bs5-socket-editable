<?php
include 'db.php';

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// Count total rows
$countQuery = "SELECT COUNT(*) as total FROM users";
$searchClause = "";
$params = [];
$types = "";

if ($search) {
    $searchClause = " WHERE name LIKE ? OR email LIKE ?";
    $params[] = "%" . $search . "%";
    $params[] = "%" . $search . "%";
    $types = "ss";
}

$countStmt = $conn->prepare($countQuery . $searchClause);
if ($params) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$totalResult = $countStmt->get_result()->fetch_assoc();
$totalRows = $totalResult['total'];
$totalPages = ceil($totalRows / $perPage);

// Fetch paginated users
$dataQuery = "SELECT id, name, email FROM users" . $searchClause . " ORDER BY id DESC LIMIT ? OFFSET ?";
$params[] = $perPage;
$params[] = $offset;
$types .= "ii";

$dataStmt = $conn->prepare($dataQuery);
$dataStmt->bind_param($types, ...$params);
$dataStmt->execute();
$result = $dataStmt->get_result();

// Output user rows
if ($result->num_rows > 0) {
    $index = $offset + 1;
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $index++. "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>
                <button class='btn btn-warning btn-sm me-2' onclick='openEditModal({$row["id"]}, \"{$row["name"]}\", \"{$row["email"]}\")'>Edit</button>
                <button class='btn btn-danger btn-sm' onclick='deleteUser({$row["id"]})'>Delete</button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No users found</td></tr>";
}

// Pagination links
echo "<tr><td colspan='4'>";
echo "<nav><ul class='pagination justify-content-center'>";

for ($i = 1; $i <= $totalPages; $i++) {
    $active = $i == $page ? "active" : "";
    echo "<li class='page-item $active'><a class='page-link' href='#' onclick='goToPage($i)'>$i</a></li>";
}

echo "</ul></nav>";
echo "</td></tr>";