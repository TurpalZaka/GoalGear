<?php
session_start();
header('Content-Type: application/json');
require_once "../config/dbaccess.php"; 

$userId = $_SESSION['user_id'] ?? null;//  Benutzer-ID aus der Session abrufen
$sessionId = session_id();

if ($userId === null && $sessionId === null) {
    echo json_encode(['error' => 'Benutzer nicht eingeloggt und keine Session vorhanden']);
    exit;
}
//  SQL-Abfrage zum Abrufen der Artikel im Warenkorb
if ($userId !== null) {
    $query = "SELECT p.id as product_id, p.name, p.image, p.price, ci.quantity 
              FROM cartitems ci 
              JOIN products p ON ci.product_id = p.id 
              WHERE ci.user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $userId);
} else {
    $query = "SELECT p.id as product_id, p.name, p.image, p.price, ci.quantity 
              FROM cartitems ci 
              JOIN products p ON ci.product_id = p.id 
              WHERE ci.session_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $sessionId);
}

$stmt->execute();
$result = $stmt->get_result();
$cartItems = [];
//  Ergebnis der SQL-Abfrage in ein Array konvertieren
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode($cartItems);

$stmt->close();
$db->close();
?>
