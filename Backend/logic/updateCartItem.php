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
    //  Produkt-ID und Änderung der Produktmenge aus den JSON-Daten extrahieren
    $productId = $data['productId'] ?? null;
    $change = $data['change'] ?? null;

    if ($productId === null || $change === null) {
        echo json_encode(['error' => 'Ungültige Daten']);
        exit;
    }

    $userId = $_SESSION['user_id'] ?? null;
    $sessionId = session_id();
    //  SQL-Abfrage zum Aktualisieren der Produktmenge im Warenkorb
    if ($userId === null) {
        $query = "UPDATE cartitems SET quantity = quantity + ? WHERE session_id = ? AND product_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('isi', $change, $sessionId, $productId);
    } else {
        $query = "UPDATE cartitems SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('iii', $change, $userId, $productId);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Fehler beim Aktualisieren der Produktmenge']);
    }
} else {
    echo json_encode(['error' => 'Ungültige Anfrage-Methode']);
}
?>
