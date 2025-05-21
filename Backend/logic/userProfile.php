<?php
session_start();
require_once "../config/dbaccess.php";

// Sicherstellen, dass der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// SQL-Abfrage zum Abrufen der Benutzerdaten
$sql = "SELECT salutation, first_name, last_name, address, city, postal_code, email, username, payment_info FROM users WHERE id = ?";

$stmt = $db->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Database error: ' . $db->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Überprüfen, ob Daten gefunden wurden
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    echo json_encode($userData);
} else {
    echo json_encode(['error' => 'No user found']);
}

$stmt->close();
$db->close();
?>
