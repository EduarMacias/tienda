<?php
include 'db.php';

$sql_pedidos = "SELECT * FROM pedidos";
$result_pedidos = $conn->query($sql_pedidos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Pedidos</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Ver Pedidos</h1>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Comprobante</th>
                    <th>Productos</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_pedido = $result_pedidos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row_pedido['id']; ?></td>
                        <td><?php echo $row_pedido['nombre']; ?></td>
                        <td><?php echo $row_pedido['direccion']; ?></td>
                        <td><?php echo $row_pedido['email']; ?></td>
                        <td><?php echo $row_pedido['telefono']; ?></td>
                        <td><a href="../uploads/comprobantes/<?php echo $row_pedido['comprobante']; ?>" target="_blank">Ver Comprobante</a></td>
                        <td>
                            <a href="ver_productos_pedido.php?pedido_id=<?php echo $row_pedido['id']; ?>" class="btn btn-info btn-sm">Ver Productos</a>
                        </td>
                        <td>
                            <form action="actualizar_estado_pedido.php" method="POST" class="d-inline">
                                <input type="hidden" name="pedido_id" value="<?php echo $row_pedido['id']; ?>">
                                <input type="hidden" name="estado" value="completado">
                                <button type="submit" class="btn btn-success btn-sm">Marcar como Completado</button>
                            </form>
                            <span><?php echo ucfirst($row_pedido['estado']); ?></span>
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
