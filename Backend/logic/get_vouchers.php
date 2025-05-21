<?php
session_start();
header('Content-Type: application/json');
require_once "../config/dbaccess.php";
//  Gutscheine aus der Datenbank abrufen
$sql = "SELECT code, value, expiry_date, 
        CASE 
            WHEN expiry_date < CURDATE() THEN 'Abgelaufen' 
            ELSE 'Aktiv' 
        END AS status 
        FROM vouchers";

$result = $db->query($sql);
// Checkt ob Gutschein abgelaufen oder Verbraucht ist
$vouchers = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['value'] == 0 && $row['expiry_date'] < date('Y-m-d')) {
            $row['status'] = 'Abgelaufen';
        } elseif ($row['value'] == 0) {
            $row['status'] = 'Verbraucht';
        }
        $vouchers[] = $row;
    }
}

echo json_encode($vouchers);

$db->close();
?>
