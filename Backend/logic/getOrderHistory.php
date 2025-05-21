<?php
session_start();
require_once "../config/dbaccess.php";

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(["message" => "Sie müssen angemeldet sein, um die Bestellhistorie abzurufen."]);
    exit;
}

// Benutzer-ID aus der Session abrufen
$userId = $_SESSION['user_id'];

// Abrufen der Bestellhistorie des Benutzers
$stmt = $db->prepare("SELECT o.*, od.product_id, od.quantity, od.price, p.name AS product_name FROM orders o JOIN orderdetails od ON o.id = od.order_id JOIN products p ON od.product_id = p.id WHERE o.customer_id = ?");
if (!$stmt) {
    echo json_encode(["message" => "Fehler bei der Vorbereitung der SQL-Abfrage: " . $db->error]);
    exit;
}

$stmt->bind_param("i", $userId);
$result = $stmt->execute();

if (!$result) {
    echo json_encode(["message" => "Fehler beim Ausführen der SQL-Abfrage: " . $stmt->error]);
    exit;
}

// Bestellhistorie abrufen und als JSON ausgeben
$orderHistory = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
echo json_encode(["order_history" => $orderHistory]);

$stmt->close();
$db->close();
?>
