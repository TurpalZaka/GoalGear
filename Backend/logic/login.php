<?php
session_start(); // Session starten
// Verbindung zur Datenbank herstellen
require_once "../config/dbaccess.php";

$request_body = file_get_contents('php://input');

if (!$request_body) {
    echo json_encode(["status" => "error", "message" => "Ungültige Anfrage"]);
    exit;
}
$data = json_decode($request_body, true);

// Überprüfe, ob erforderliche Felder vorhanden sind
if (!isset($data["username_email"]) || !isset($data["password"])) {
    echo json_encode(["status" => "error", "message" => "Benutzername und Passwort dürfen nicht leer sein."]);
    exit;
}

// Bereite die Anmeldeinformationen vor
$username_email = htmlspecialchars(trim($data["username_email"]));
$password = htmlspecialchars(trim($data["password"]));
$remember_me = isset($data["remember_me"]) ? true : false;

// Vorbereitung der Datenbankabfrage
$stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => 'Fehler beim Vorbereiten der Abfrage: ' . $db->error]);
    exit;
}

$stmt->bind_param("ss", $username_email, $username_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Überprüfe die Anmeldeinformationen
if ($user && password_verify($password, $user['password'])) {
    // Überprüfe, ob der Benutzer aktiv ist
    if ($user['active'] == 1) {
        // Benutzer ist aktiv, Anmeldung erfolgreich
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['active'] = $user['active'];
        
        $response['status'] = "success";
        $response['message'] = "Login erfolgreich!";
    
        // Cookie setzen, wenn "Login merken" aktiviert ist
        if ($remember_me) {
            setcookie("user", $user['username'], time() + 86400 * 30, "/");
        }
    
        // Aktualisiere die Bestellungen mit der Benutzer-ID, wenn die Session-ID übereinstimmt
        $sessionId = session_id();
        $updateStmt = $db->prepare("UPDATE cartitems SET user_id = ? WHERE session_id = ?");
        $updateStmt->bind_param('is', $user['id'], $sessionId);
        
        if ($updateStmt->execute()) {
            $response['message'] .= " Warenkorb wurde aktualisiert.";
        } else {
            $response['message'] .= " Fehler beim Aktualisieren des Warenkorbs.";
        }

        $updateStmt->close();
    } else {
        // Benutzer ist nicht aktiv
        $response['status'] = "error";
        $response['message'] = "Ihr Konto ist nicht aktiv. Bitte kontaktieren Sie den Support.";
    }
} else {
    // Ungültige Anmeldeinformationen
    $response['status'] = "error";
    $response['message'] = "Ungültige Anmeldeinformationen. Bitte versuchen Sie es erneut.";
}

echo json_encode($response);
$stmt->close();
$db->close();
?>
