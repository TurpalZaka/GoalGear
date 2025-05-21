<?php
$host="localhost";
$user="root";
$password="";
$database="goalgear";
$db= new mysqli($host,$user,$password,$database);

// Überprüfe die Verbindung
if ($db->connect_errno) {
    echo "Connection Error: ". $db->connect_error;
    exit();
}

