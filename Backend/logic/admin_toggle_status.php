<?php
session_start();
header('Content-Type: application/json');
require_once "../config/dbaccess.php";

// Daten aus der POST-Anfrage lesen
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['status'])) {
    $customerId = $data['id'];
    $newStatus = $data['status'];
//  SQL-Abfrage zum Aktualisieren des Status des Benutzers
    $sql = "UPDATE users SET active = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $newStatus, $customerId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Datenbankfehler']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage']);
}

$db->close();
?>
