<?php
header('Content-Type: application/json');
require_once "../config/dbaccess.php";
//  Produkt-ID aus der URL auslesen
$product_id = isset($_GET['id']) ? $_GET['id'] : null;
//  Wenn eine Produkt-ID übergeben wurde, Produktinformationen abrufen
if ($product_id) {
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    echo json_encode($product);
    $stmt->close();
} else {
    echo json_encode(['error' => 'Produkt-ID nicht angegeben']);
}

$db->close();
?>
