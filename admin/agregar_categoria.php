<?php
include 'db.php';

$nombre = $_POST['nombre'];
$imagen = $_FILES['imagen']['name'];
$target_dir = "../uploads/";
$target_file = $target_dir . basename($imagen);

if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
    $sql = "INSERT INTO categorias (nombre, imagen) VALUES ('$nombre', '$imagen')";
    if ($conn->query($sql) === TRUE) {
        echo "Categoría agregada correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error al subir la imagen.";
}
?>
