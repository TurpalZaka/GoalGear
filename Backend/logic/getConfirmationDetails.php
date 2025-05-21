<?php
session_start();
require_once "../config/dbaccess.php";

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(["message" => "Sie müssen angemeldet sein, um eine Bestellbestätigung zu erhalten."]);
    exit;
}

// Benutzer-ID aus der Session abrufen
$userId = $_SESSION['user_id'];

// Die zuletzt eingefügte Bestellnummer abrufen
$orderNumberStmt = $db->prepare("SELECT id FROM orders WHERE customer_id = ? ORDER BY id DESC LIMIT 1");
$orderNumberStmt->bind_param("i", $userId);
$orderNumberStmt->execute();
$orderNumberResult = $orderNumberStmt->get_result();

if ($orderNumberResult->num_rows === 0) {
    echo json_encode(["message" => "Es gab ein Problem beim Abrufen der Bestellnummer."]);
    exit;
}

$orderNumber = $orderNumberResult->fetch_assoc()['id'];

echo json_encode(["message" => "Vielen Dank! Ihre Bestellung wurde erfolgreich aufgegeben.", "orderNumber" => $orderNumber]);

$orderNumberStmt->close();
$db->close();
?>
