<?php
include 'db.php';

$id = $_GET['id'];

// Eliminar filas dependientes en pedido_productos
$conn->query("DELETE FROM pedido_productos WHERE producto_id = $id");

// Eliminar filas dependientes en productos_tallas
$conn->query("DELETE FROM productos_tallas WHERE producto_id = $id");

// Eliminar filas dependientes en productos_colores
$conn->query("DELETE FROM productos_colores WHERE producto_id = $id");

// Ahora eliminar el producto en productos
$sql = "DELETE FROM productos WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo "Producto eliminado correctamente";
} else {
    echo "Error al eliminar el producto: " . $conn->error;
}
?>
