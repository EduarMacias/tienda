<?php
include 'db.php';

$nombre = $_POST['nombre'];
$sql = "INSERT INTO tallas (nombre) VALUES ('$nombre')";
if ($conn->query($sql) === TRUE) {
    echo "Talla agregada correctamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
