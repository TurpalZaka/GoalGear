<?php
session_start();
header('Content-Type: application/json');

require_once "../config/dbaccess.php";

$data = json_decode(file_get_contents("php://input"), true);

$order_id = $data['order_id'];
$product_id = $data['product_id'];

// Abrufen des Preises des zu löschenden Produkts
$sql = "SELECT price, quantity FROM orderdetails WHERE order_id = ? AND product_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("ii", $order_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    $product_price = $product['price'] * $product['quantity'];

    // Löschen des Produkts aus der orderdetails-Tabelle
    $sql = "DELETE FROM orderdetails WHERE order_id = ? AND product_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $order_id, $product_id);

    $response = [];

    if ($stmt->execute()) {
        // Aktualisieren des Gesamtbetrags in der orders-Tabelle
        $sql = "UPDATE orders SET total_amount = total_amount - ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("di", $product_price, $order_id);
        $stmt->execute();

        // Überprüfen, ob noch weitere Produkte zu dieser Bestellung gehören
        $sql = "SELECT COUNT(*) as product_count FROM orderdetails WHERE order_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['product_count'];

        if ($count == 0) {
            // Löschen der Bestellung, wenn keine Produkte mehr vorhanden sind
            $sql = "DELETE FROM orders WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $order_id);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Bestellung und Produkte erfolgreich gelöscht";
            } else {
                $response['success'] = false;
                $response['error'] = "Fehler beim Löschen der Bestellung: " . $stmt->error;
            }
        } else {
            $response['success'] = true;
        }
    } else {
        $response['success'] = false;
        $response['error'] = "Fehler beim Löschen des Produkts: " . $stmt->error;
    }
} else {
    $response['success'] = false;
    $response['error'] = "Produkt nicht gefunden";
}

echo json_encode($response);

$stmt->close();
$db->close();
?>
