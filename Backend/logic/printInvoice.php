<?php
session_start();
require_once "../config/dbaccess.php";

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(["error" => "Sie müssen angemeldet sein, um eine Rechnung zu drucken."]);
    exit;
}

// Abrufen der Bestelldaten basierend auf der Bestell-ID
$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT o.id, o.order_date, o.total_amount, o.status, 
                             od.product_id, od.quantity, od.price, p.name AS product_name,
                             u.username AS customer_name, u.address AS customer_address
                      FROM orders o 
                      JOIN orderdetails od ON o.id = od.order_id 
                      JOIN products p ON od.product_id = p.id
                      JOIN users u ON o.customer_id = u.id
                      WHERE o.id = ? AND o.customer_id = ?");
if (!$stmt) {
    echo json_encode(["error" => "Fehler bei der Vorbereitung der SQL-Abfrage: " . $db->error]);
    exit;
}

$stmt->bind_param("ii", $order_id, $user_id);
$result = $stmt->execute();

if (!$result) {
    echo json_encode(["error" => "Fehler beim Ausführen der SQL-Abfrage: " . $stmt->error]);
    exit;
}

// Bestelldaten abrufen
$order = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
if (empty($order)) {
    echo json_encode(["error" => "Keine Bestelldaten gefunden."]);
    exit;
}

$order_details = $order[0];
$invoice_number = 'INV-' . str_pad($order_id, 8, '0', STR_PAD_LEFT);

$stmt->close();
$db->close();

echo json_encode([
    "invoice_number" => $invoice_number,
    "order_date" => $order_details['order_date'],
    "customer_name" => $order_details['customer_name'],
    "customer_address" => $order_details['customer_address'],
    "total_amount" => $order_details['total_amount'],
    "products" => $order
]);
?>
