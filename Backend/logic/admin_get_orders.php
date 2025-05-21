<?php
session_start();
header('Content-Type: application/json');
require_once "../config/dbaccess.php";


$customer_id = $_GET['customer_id'];
//  Kundeninformationen aus der Datenbank abrufen
$sql = "SELECT o.*, od.product_id, od.quantity, od.price, p.name AS product_name FROM orders o JOIN orderdetails od ON o.id = od.order_id JOIN products p ON od.product_id = p.id WHERE o.customer_id = $customer_id";


$result = $db->query($sql);
//  SQL-Abfrage ausführen und Ergebnis in ein Array konvertieren
$orders = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

echo json_encode($orders);

$db->close();
?>
