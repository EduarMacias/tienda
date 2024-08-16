<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $categoria_id = $_POST['categoria_id'];
    $tallas = $_POST['tallas'];
    $colores = $_POST['colores'];
    $is_offer = isset($_POST['is_offer']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_recent = isset($_POST['is_recent']) ? 1 : 0;
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;
    $is_article_of_the_month = isset($_POST['is_article_of_the_month']) ? 1 : 0;

    // Manejo de la imagen
    if ($_FILES['imagen']['name']) {
        $imagen = $_FILES['imagen']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);
        $sql = "UPDATE productos SET codigo='$codigo', nombre='$nombre', precio=$precio, cantidad=$cantidad, imagen='$imagen', categoria_id=$categoria_id, is_offer=$is_offer, is_featured=$is_featured, is_recent=$is_recent, is_best_seller=$is_best_seller, is_article_of_the_month=$is_article_of_the_month WHERE id=$id";
    } else {
        $sql = "UPDATE productos SET codigo='$codigo', nombre='$nombre', precio=$precio, cantidad=$cantidad, categoria_id=$categoria_id, is_offer=$is_offer, is_featured=$is_featured, is_recent=$is_recent, is_best_seller=$is_best_seller, is_article_of_the_month=$is_article_of_the_month WHERE id=$id";
    }
    
    if ($conn->query($sql) === TRUE) {
        // Actualizar tallas
        $conn->query("DELETE FROM productos_tallas WHERE producto_id = $id");
        foreach ($tallas as $talla_id) {
            $sql = "INSERT INTO productos_tallas (producto_id, talla_id) VALUES ($id, $talla_id)";
            $conn->query($sql);
        }
        
        // Actualizar colores
        $conn->query("DELETE FROM productos_colores WHERE producto_id = $id");
        foreach ($colores as $color_id) {
            $sql = "INSERT INTO productos_colores (producto_id, color_id) VALUES ($id, $color_id)";
            $conn->query($sql);
        }
        
        echo "Producto actualizado correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
