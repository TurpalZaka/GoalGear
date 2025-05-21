<?php
session_start();
require_once "../config/dbaccess.php";

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(["status" => "error", "message" => "Sie müssen angemeldet sein, um eine Bestellung aufzugeben."]);
    exit;
}

// Benutzer-ID aus der Session abrufen
$userId = $_SESSION['user_id'];

// Abrufen der Warenkorbdaten des Benutzers
$stmt = $db->prepare("SELECT ci.quantity, p.id AS product_id, p.name, p.price FROM cartitems ci INNER JOIN products p ON ci.product_id = p.id WHERE ci.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Ihr Warenkorb ist leer."]);
    exit;
}

// Berechnen des Gesamtbetrags der Bestellung
$totalAmount = 0;
$orderDetails = [];
while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $totalAmount += $subtotal;

    // Details zu den bestellten Produkten sammeln
    $orderDetails[] = [
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
    ];
}

// Überprüfen, ob ein Gutschein angewendet wurde
$couponCode = isset($_POST['voucherCode']) ? $_POST['voucherCode'] : '';
if (!empty($couponCode)) {
    // Überprüfen, ob der Gutschein gültig ist und den Gesamtbetrag aktualisieren
    $couponStmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND expiration_date >= CURDATE()");
    $couponStmt->bind_param("s", $couponCode);
    $couponStmt->execute();
    $couponResult = $couponStmt->get_result();

    if ($couponResult->num_rows > 0) {
        $coupon = $couponResult->fetch_assoc();
        $discount = $coupon['value'];
        $totalAmount -= $discount; // Rabatt vom Gesamtbetrag abziehen
        echo "Rabatt angewendet: $discount"; // Debugging-Ausgabe hinzufügen
    } else {
        echo json_encode(["status" => "error", "message" => "Der eingegebene Gutscheincode ist ungültig."]);
        exit;
    }
}

// Bestellung in die Datenbank einfügen
$orderStmt = $db->prepare("INSERT INTO orders (customer_id, total_amount) VALUES (?, ?)");
$orderStmt->bind_param("id", $userId, $totalAmount);
$orderStmt->execute();
$orderId = $orderStmt->insert_id;

// Details zu den bestellten Produkten in die Datenbank einfügen
foreach ($orderDetails as $detail) {
    $productId = $detail['product_id'];
    $quantity = $detail['quantity'];
    $price = $detail['price'];
    $orderDetailStmt = $db->prepare("INSERT INTO orderdetails (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $orderDetailStmt->bind_param("iiid", $orderId, $productId, $quantity, $price);
    $orderDetailStmt->execute();
}

// Warenkorb des Benutzers leeren
$clearCartStmt = $db->prepare("DELETE FROM cartitems WHERE user_id = ?");
$clearCartStmt->bind_param("i", $userId);
$clearCartStmt->execute();

echo json_encode(["status" => "success", "message" => "Die Bestellung wurde erfolgreich aufgegeben."]);

// Verbindung zur Datenbank schließen
$stmt->close();
$orderStmt->close();
$orderDetailStmt->close();
$clearCartStmt->close();
$db->close();
?>
