<?php
include 'db.php';

$nombre = $_POST['nombre'];
$sql = "INSERT INTO colores (nombre) VALUES ('$nombre')";
if ($conn->query($sql) === TRUE) {
    echo "Color agregado correctamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
