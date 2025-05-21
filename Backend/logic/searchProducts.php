<?php
header('Content-Type: application/json');
require_once "../config/dbaccess.php";
//  Suchbegriff aus der URL auslesen
$query = isset($_GET['query']) ? $_GET['query'] : '';
//  Wenn kein Suchbegriff übergeben wurde, leeres Array zurückgeben
if ($query === '') {
    echo json_encode([]);
    exit;
}

//  Suche nach Produkten, deren Name oder Beschreibung den Suchbegriff enthalten
$searchQuery = '%' . $query . '%';
$stmt = $db->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
$stmt->bind_param('ss', $searchQuery, $searchQuery);
$stmt->execute();
$result = $stmt->get_result();
//  Ergebnisse in ein Array umwandeln
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);//  Ergebnisse als JSON zurückgeben
?>
