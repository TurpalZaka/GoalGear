<?php
session_start();
//  Überprüfen, ob der Benutzer angemeldet ist
$response = array('loggedin' => false);
//  Wenn der Benutzer angemeldet ist, Benutzername und Rolle zurückgeben
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $response['loggedin'] = true;
    $response['username'] = $_SESSION['username']; 
    if ($_SESSION['role'] === 'admin') {
        $response['admin'] = true;
    }
}
//  JSON-Antwort zurückgeben
echo json_encode($response);
?>
