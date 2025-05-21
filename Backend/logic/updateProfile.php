<?php
session_start();
require_once '../config/dbaccess.php'; 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Nicht angemeldet']);
    exit;
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];

// Abfrage des aktuellen Passworts aus der Datenbank
$stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!password_verify($current_password, $user['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Aktuelles Passwort ist ungültig']);
    exit;
}

// Profil aktualisieren
$stmt = $db->prepare("UPDATE users SET salutation = ?, first_name = ?, last_name = ?, email = ?, address = ?, city = ?, postal_code = ? WHERE id = ?");
$stmt->bind_param("sssssssi", $_POST['salutation'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['address'], $_POST['city'], $_POST['postal_code'], $user_id);

if ($stmt->execute()) {
    $updateStatus = ['status' => 'success', 'message' => 'Profil aktualisiert'];
} else {
    $updateStatus = ['status' => 'error', 'message' => 'Fehler beim Aktualisieren des Profils: ' . $stmt->error];
}

$stmt->close();

// Passwort aktualisieren, falls neues Passwort gesetzt ist
if (!empty($_POST['new_password'])) {
    $new_password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password_hash, $user_id);

    if ($stmt->execute()) {
        $updateStatus['password_status'] = 'success';
        $updateStatus['password_message'] = 'Passwort aktualisiert';
    } else {
        $updateStatus['password_status'] = 'error';
        $updateStatus['password_message'] = 'Fehler beim Aktualisieren des Passworts: ' . $stmt->error;
    }

    $stmt->close();
}

$db->close();
echo json_encode($updateStatus);
?>
