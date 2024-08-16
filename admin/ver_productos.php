<?php
include 'db.php';

$sql_productos = "SELECT p.*, c.nombre AS categoria FROM productos p LEFT JOIN categorias c ON p.categoria_id = c.id";
$result_productos = $conn->query($sql_productos);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Productos</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrap.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Ver Productos</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_producto = $result_productos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row_producto['id']; ?></td>
                        <td><?php echo $row_producto['codigo']; ?></td>
                        <td><?php echo $row_producto['nombre']; ?></td>
                        <td><?php echo $row_producto['precio']; ?></td>
                        <td><?php echo $row_producto['cantidad']; ?></td>
                        <td><?php echo $row_producto['categoria']; ?></td>
                        <td>
                            <a href="editar_producto.php?id=<?php echo $row_producto['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar_producto.php?id=<?php echo $row_producto['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
