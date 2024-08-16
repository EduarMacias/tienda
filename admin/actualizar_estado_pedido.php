<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['pedido_id'];
    $estado = $_POST['estado'];

    $sql = "UPDATE pedidos SET estado='$estado' WHERE id=$pedido_id";
    if ($conn->query($sql) === TRUE) {
        header('Location: pedidos.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
