<?php
require_once "../config/dbaccess.php";

// Die Daten kommen als JSON an
$data = json_decode(file_get_contents('php://input'), true);

// Daten aus dem JSON-Objekt abrufen
$salutation = htmlspecialchars(trim($data["salutation"]));
$first_name = htmlspecialchars(trim($data["first_name"]));
$last_name = htmlspecialchars(trim($data["last_name"]));
$address = htmlspecialchars(trim($data["address"]));
$city = htmlspecialchars(trim($data["city"]));
$postal_code = htmlspecialchars(trim($data["postal_code"]));
$email = htmlspecialchars(trim($data["email"]));
$username = htmlspecialchars(trim($data["username"]));
$password = htmlspecialchars(trim($data["password"]));
$password_repeat = htmlspecialchars(trim($data["password_repeat"]));
$payment_info = htmlspecialchars(trim($data["payment_info"]));

//Überprüfen, dass Vorname nicht leer ist
if(empty($first_name)) {
    echo json_encode(array("error" => "Vorname darf nicht leer sein"));
    exit();
}

//Überprüfen, dass Nachname nicht leer ist
if(empty($last_name)) {
    echo json_encode(array("error" => "Nachname darf nicht leer sein"));
    exit();
}

//Überprüfen, dass Adresse nicht leer ist
if(empty($address)) {
    echo json_encode(array("error" => "Adresse darf nicht leer sein"));
    exit();
}

//Überprüfen, dass PLZ nicht leer ist
if(empty($postal_code)) {
    echo json_encode(array("error" => "PLZ darf nicht leer sein"));
    exit();
}

//Überprüfen, dass PLZ nur Zahlen beinhaltet
if (!ctype_digit($postal_code)) {
    echo json_encode(array("error" => "PLZ darf nur Zahlen enthalten"));
    exit();
}

//Überprüfen, dass Ort nicht leer ist
if(empty($city)) {
    echo json_encode(array("error" => "Ort darf nicht leer sein"));
    exit();
}

// Überprüfen, dass die E-Mail-Adresse nicht leer ist und ein gültiges Format hat
if (empty($email)) {
    echo json_encode(array("error" => "E-Mail darf nicht leer sein"));
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array("error" => "Ungueltiges E-Mail-Format"));
    exit();
}

//Überprüfen, dass Username nicht leer ist
if(empty($username)) {
    echo json_encode(array("error" => "Username darf nicht leer sein"));
    exit();
}

// Überprüfen, ob Passwörter übereinstimmen
if ($password !== $password_repeat) {
    echo json_encode(array("error" => "Die Passwörter stimmen nicht überein"));
    exit();
}

// Passwortlänge prüfen (mindestens 8 Zeichen)
if (strlen($password) < 8) {
    echo json_encode(array("error" => "Das Passwort muss mindestens 8 Zeichen lang sein"));
    exit();
}

// Passwort muss mindestens einen Großbuchstaben, einen Kleinbuchstaben und eine Zahl enthalten
if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/", $password)) {
    echo json_encode(array("error" => "Das Passwort muss mindestens einen Großbuchstaben, einen Kleinbuchstaben und eine Zahl enthalten"));
    exit();
}

// Passwort verschlüsseln
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL-Befehl zum Einfügen des Benutzers in die Datenbank 
$sql = "INSERT INTO users (salutation, first_name, last_name, address, city, postal_code, email, username, password, payment_info) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $db->prepare($sql);
$stmt->bind_param("ssssssssss", $salutation, $first_name, $last_name, $address, $city, $postal_code, $email, $username, $hashed_password, $payment_info);

$response = array();

if ($stmt->execute()) {
    $response['success'] = "Neuer Benutzer wurde erfolgreich registriert.";
} else {
    $response['error'] = "Fehler beim Hinzufügen des Benutzers: " . $stmt->error;
}

echo json_encode($response);

// Verbindung zur Datenbank schließen
$stmt->close();
$db->close();
?>
