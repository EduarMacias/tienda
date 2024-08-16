<?php
include 'db.php';

$pedido_id = $_GET['pedido_id'];
$sql_productos_pedido = "SELECT pp.*, p.nombre, p.codigo, t.nombre AS talla, c.nombre AS color, p.imagen
                         FROM pedido_productos pp
                         LEFT JOIN productos p ON pp.producto_id = p.id
                         LEFT JOIN tallas t ON pp.talla_id = t.id
                         LEFT JOIN colores c ON pp.color_id = c.id
                         WHERE pp.pedido_id = $pedido_id";
$result_productos_pedido = $conn->query($sql_productos_pedido);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos del Pedido</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Productos del Pedido #<?php echo $pedido_id; ?></h1>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Talla</th>
                    <th>Color</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_producto_pedido = $result_productos_pedido->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row_producto_pedido['producto_id']; ?></td>
                        <td><?php echo $row_producto_pedido['nombre']; ?></td>
                        <td><?php echo $row_producto_pedido['codigo']; ?></td>
                        <td><?php echo $row_producto_pedido['talla']; ?></td>
                        <td><?php echo $row_producto_pedido['color']; ?></td>
                        <td><?php echo $row_producto_pedido['cantidad']; ?></td>
                        <td><img src="../uploads/<?php echo $row_producto_pedido['imagen']; ?>" alt="<?php echo $row_producto_pedido['nombre']; ?>" width="100"></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary">Volver</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
