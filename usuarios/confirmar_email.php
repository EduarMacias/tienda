<?php
require '../config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->execute([$token]);

    if ($stmt->rowCount() == 1) {
        $stmt = $pdo->prepare("UPDATE users SET token = NULL, verified = 1 WHERE token = ?");
        $stmt->execute([$token]);
        echo "Email confirmado con éxito";
    } else {
        echo "Token inválido";
    }
} else {
    echo "No se proporcionó ningún token";
}
?>
