<?php
$host = 'localhost';
$db = 'tienda18';
$user = 'root';
$pass = '';
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
