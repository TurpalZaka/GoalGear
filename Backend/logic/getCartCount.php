<?php
session_start();
header('Content-Type: application/json');
require_once "../config/dbaccess.php"; 

$userId = $_SESSION['user_id'] ?? null;//  Benutzer-ID aus der Session abrufen
$sessionId = session_id();
//  Wenn Benutzer nicht eingeloggt und keine Session vorhanden, Fehlermeldung ausgeben
if ($userId === null && $sessionId === null) {
    echo json_encode(['success' => false, 'message' => 'Benutzer nicht eingeloggt und keine Session vorhanden']);
    exit;
}
//  SQL-Abfrage zum Abrufen der Gesamtanzahl der Artikel im Warenkorb
if ($userId !== null) {
    $query = "SELECT SUM(ci.quantity) as totalItems 
              FROM cartitems ci 
              WHERE ci.user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $userId);
} else {
    $query = "SELECT SUM(ci.quantity) as totalItems 
              FROM cartitems ci 
              WHERE ci.session_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $sessionId);
}

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalItems = $row['totalItems'] ?? 0;

echo json_encode(['success' => true, 'count' => $totalItems]);

$stmt->close();
$db->close();
?>
