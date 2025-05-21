<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        //
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $targetFile = '../productpictures/' . time() . '.' . $imageFileType;
        //  Überprüfen, ob die Datei ein Bild ist
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $targetFile = '../../Backend/productpictures/' . basename($targetFile);//
            echo json_encode(['imageUrl' => $targetFile]);
        } else {
            echo json_encode(['error' => 'Fehler beim Hochladen des Bildes']);
        }
    } else {
        echo json_encode(['error' => 'Keine Datei hochgeladen oder Fehler beim Hochladen']);
    }
}
?>
