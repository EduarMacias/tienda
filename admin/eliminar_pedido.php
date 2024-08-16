<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pedido_id = $_POST['pedido_id'];

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Eliminar los productos asociados al pedido
        $sql_delete_productos = "DELETE FROM pedido_productos WHERE pedido_id = ?";
        $stmt_productos = $conn->prepare($sql_delete_productos);
        $stmt_productos->bind_param('i', $pedido_id);
        $stmt_productos->execute();
        $stmt_productos->close();

        // Eliminar el pedido
        $sql_delete_pedido = "DELETE FROM pedidos WHERE id = ?";
        $stmt_pedido = $conn->prepare($sql_delete_pedido);
        $stmt_pedido->bind_param('i', $pedido_id);
        $stmt_pedido->execute();
        $stmt_pedido->close();

        // Confirmar la transacción
        $conn->commit();

        // Redirigir a la página de pedidos después de eliminar
        header('Location: pedidos.php');
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();

        echo "Error al eliminar el pedido: " . $exception->getMessage();
    }

    $conn->close();
}
?>
