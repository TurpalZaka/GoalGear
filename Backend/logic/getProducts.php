<?php
header('Content-Type: application/json');
require_once "../config/dbaccess.php";
//  Kategorie-ID aus der URL auslesen
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
//  Wenn eine Kategorie-ID übergeben wurde, Produkte dieser Kategorie abrufen
if ($category_id) {
    $query = "SELECT * FROM products WHERE category_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $category_id);
} else {
    $query = "SELECT * FROM products";//  Wenn keine Kategorie-ID übergeben wurde, alle Produkte abrufen
    $stmt = $db->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();
//  Ergebnisse in ein Array umwandeln
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
//  Produkte als JSON zurückgeben
echo json_encode($products);
$stmt->close();
$db->close();
?>
