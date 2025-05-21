<?php
header('Content-Type: application/json');
require_once "../config/dbaccess.php";
//  Produkt-ID aus der POST-Anfrage auslesen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['id'];
//  SQL-Abfrage zum Löschen des Produkts
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Produkt erfolgreich gelöscht']);
    } else {
        echo json_encode(['error' => 'Fehler beim Löschen des Produkts']);
    }

    $stmt->close();
    $db->close();
}
?>
