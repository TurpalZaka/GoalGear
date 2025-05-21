<?php
session_start();
header('Content-Type: application/json');
require_once "../config/dbaccess.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data === null) {
        echo json_encode(['error' => 'Fehler beim Verarbeiten der JSON-Daten']);
        exit;
    }

    $productId = $data['productId'] ?? null;

    if ($productId === null) {
        echo json_encode(['error' => 'Ungültige Produkt-ID']);
        exit;
    }

    // Überprüfen, ob der Benutzer eingeloggt ist
    $userId = $_SESSION['user_id'] ?? null;
    $sessionId = session_id();

    if ($userId === null) {
        // Benutzer ist nicht eingeloggt, prüfe nur anhand der Session ID
        $checkQuery = "SELECT quantity FROM cartitems WHERE session_id = ? AND product_id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bind_param('si', $sessionId, $productId);
    } else {
        // Benutzer ist eingeloggt, prüfe anhand der UserID und Session ID
        $checkQuery = "SELECT quantity FROM cartitems WHERE user_id = ? AND product_id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bind_param('ii', $userId, $productId);
    }

    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Produkt ist bereits im Warenkorb, Menge aktualisieren
        $row = $result->fetch_assoc();
        $newQuantity = $row['quantity'] + 1;

        if ($userId === null) {
            $updateQuery = "UPDATE cartitems SET quantity = ? WHERE session_id = ? AND product_id = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param('isi', $newQuantity, $sessionId, $productId);
        } else {
            $updateQuery = "UPDATE cartitems SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param('iii', $newQuantity, $userId, $productId);
        }

        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Produktmenge wurde aktualisiert']);
        } else {
            echo json_encode(['error' => 'Fehler beim Aktualisieren der Produktmenge']);
        }
    } else {
        // Produkt ist nicht im Warenkorb, neues Einfügen
        if ($userId === null) {
            $insertQuery = "INSERT INTO cartitems (session_id, product_id, quantity) VALUES (?, ?, 1)";
            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->bind_param('si', $sessionId, $productId);
        } else {
            $insertQuery = "INSERT INTO cartitems (user_id, session_id, product_id, quantity) VALUES (?, ?, ?, 1)";
            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->bind_param('isi', $userId, $sessionId, $productId);
        }

        if ($insertStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Produkt wurde zum Warenkorb hinzugefügt']);
        } else {
            echo json_encode(['error' => 'Fehler beim Hinzufügen zum Warenkorb']);
        }
    }
} else {
    echo json_encode(['error' => 'Ungültige Anfrage-Methode']);
}
?>
