<?php
session_start();
header('Content-Type: application/json');
require_once "../config/dbaccess.php";
//  Kundeninformationen aus der Datenbank abrufen
$sql = "SELECT id, salutation, first_name, last_name, address, city, postal_code, email, username, payment_info, active, role FROM users";
//  SQL-Abfrage ausführen und Ergebnis in ein Array konvertieren
$result = $db->query($sql);
$customers = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}
//  Kundeninformationen als JSON ausgeben
echo json_encode($customers);

$db->close();
?>

