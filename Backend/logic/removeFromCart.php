<?php
session_start();
require_once "../config/dbaccess.php";
//  Wenn der Benutzer nicht eingeloggt ist, Fehlermeldung zurückgeben
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
//  Wenn keine Artikel-ID übermittelt wurde, Fehlermeldung zurückgeben
if (!isset($data['itemId'])) {
    echo json_encode(['success' => false, 'message' => 'Keine Artikel-ID übermittelt']);
    exit;
}

$item_id = $data['itemId'];
//  Artikel aus dem Warenkorb entfernen
$sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Artikel wurde entfernt']);
} else {
    echo json_encode(['success' => false, 'message' => 'Fehler beim Entfernen des Artikels']);
}
?>
