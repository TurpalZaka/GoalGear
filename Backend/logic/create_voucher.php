<?php
session_start();
header('Content-Type: application/json');
require_once "../config/dbaccess.php";

$data = json_decode(file_get_contents('php://input'), true);

$code = $data['code'];
$value = $data['value'];
$expiryDate = $data['expiryDate'];
//  SQL-Abfrage zum Einfügen des Gutscheins in die Datenbank
$sql = "INSERT INTO vouchers (code, value, expiry_date) VALUES ('$code', '$value', '$expiryDate')";

if ($db->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $db->error]);
}

$db->close();
?>
