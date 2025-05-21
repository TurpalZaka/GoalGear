<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../config/dbaccess.php";
//  Kategorien abrufen
$query = "SELECT * FROM categories";
$result = $db->query($query);

if (!$result) {
    echo json_encode(['error' => 'Fehler beim Abrufen der Kategorien']);
    exit;
}
//  Ergebnisse in ein Array umwandeln
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
//  Kategorien als JSON zurückgeben
echo json_encode($categories);
?>
