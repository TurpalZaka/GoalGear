<?php
session_start();
session_destroy(); // Zerstöre die Session und logge den Nutzer aus

// Lösche den PHPSESSID-Cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

setcookie("user", "", time() - 3600, "/"); // Lösche das Cookie, indem du ein abgelaufenes Datum setzt

$response = array('status' => 'success', 'message' => 'Logout erfolgreich!');
echo json_encode($response);
?>
