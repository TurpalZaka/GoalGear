<?php
session_start();
header('Content-Type: application/json');
require_once "../config/dbaccess.php";
    //  Daten aus der POST-Anfrage lesen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data === null) {
        echo json_encode(['error' => 'Fehler beim Verarbeiten der JSON-Daten']);
        exit;
    }
    //  Produkt-ID aus den JSON-Daten extrahieren
    $productId = $data['productId'] ?? null;

    if ($productId === null) {
        echo json_encode(['error' => 'Ungültige Produkt-ID']);
        exit;
    }

    $userId = $_SESSION['user_id'] ?? null;
    $sessionId = session_id();
    //  SQL-Abfrage zum Entfernen des Produkts aus dem Warenkorb
    if ($userId === null) {
        $query = "DELETE FROM cartitems WHERE session_id = ? AND product_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('si', $sessionId, $productId);
    } else {
        $query = "DELETE FROM cartitems WHERE user_id = ? AND product_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ii', $userId, $productId);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Fehler beim Entfernen des Produkts']);
    }
} else {
    echo json_encode(['error' => 'Ungültige Anfrage-Methode']);
}
?>
