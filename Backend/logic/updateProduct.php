<?php
header('Content-Type: application/json');
require_once "../config/dbaccess.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data === null) {
        echo json_encode(['error' => 'Fehler beim Verarbeiten der JSON-Daten']);
        exit;
    }
    //  Produktinformationen aus den JSON-Daten extrahieren
    $id = $data['id'] ?? 0;
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $price = $data['price'] ?? 0;
    $categoryId = $data['category'] ?? 0;
    $imageUrl = $data['imageUrl'] ?? '';
    //  Produkt in die Datenbank akutalisieren
    if ($imageUrl) {
        $stmt = $db->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("ssdsii", $name, $description, $price, $imageUrl, $categoryId, $id);
    } else {
        $stmt = $db->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("ssdii", $name, $description, $price, $categoryId, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Produkt erfolgreich aktualisiert']);
    } else {
        echo json_encode(['error' => 'Fehler beim Aktualisieren des Produkts']);
    }
    $stmt->close();
    $db->close();
}
?>
