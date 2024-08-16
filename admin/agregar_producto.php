<?php
include 'db.php';

$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$cantidad = $_POST['cantidad'];
$categoria_id = $_POST['categoria_id'];
$tallas = $_POST['tallas'];
$colores = $_POST['colores'];

$imagen = $_FILES['imagen']['name'];
$target_dir = "../uploads/";
$target_file = $target_dir . basename($imagen);

if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
    $sql = "INSERT INTO productos (codigo, nombre, precio, cantidad, imagen, categoria_id) VALUES ('$codigo', '$nombre', $precio, $cantidad, '$imagen', $categoria_id)";
    if ($conn->query($sql) === TRUE) {
        $producto_id = $conn->insert_id;
        foreach ($tallas as $talla_id) {
            $sql = "INSERT INTO productos_tallas (producto_id, talla_id) VALUES ($producto_id, $talla_id)";
            $conn->query($sql);
        }
        foreach ($colores as $color_id) {
            $sql = "INSERT INTO productos_colores (producto_id, color_id) VALUES ($producto_id, $color_id)";
            $conn->query($sql);
        }
        echo "Producto agregado correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error al subir la imagen.";
}
?>
