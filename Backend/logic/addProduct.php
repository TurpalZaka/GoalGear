<?php
header('Content-Type: application/json');
require_once "../config/dbaccess.php";
//  Überprüfen, ob die Anfrage eine POST-Anfrage ist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data === null) {
        echo json_encode(['error' => 'Fehler beim Verarbeiten der JSON-Daten']);
        exit;
    }
    //  Produktinformationen aus den JSON-Daten extrahieren
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $price = $data['price'] ?? 0;
    $categoryId = $data['category'] ?? 0;
    $imageUrl = $data['imageUrl'] ?? '';
    //  Produkt in die Datenbank einfügen
    $stmt = $db->prepare("INSERT INTO products (name, description, price, image, category_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $name, $description, $price, $imageUrl, $categoryId);
//  Überprüfen, ob das Einfügen erfolgreich war und eine entsprechende Antwort senden
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Produkt erfolgreich hinzugefügt']);
    } else {
        echo json_encode(['error' => 'Fehler beim Einfügen des Produkts']);
    }
    $stmt->close();
    $db->close();
}
?>
