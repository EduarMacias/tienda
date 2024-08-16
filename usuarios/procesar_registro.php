<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $token = bin2hex(random_bytes(50));

    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists) {
        echo "El correo electrónico ya está registrado. Por favor, utiliza otro correo electrónico.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, token) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $token]);

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'macieduksc1@gmail.com';  // Reemplaza con tu dirección de correo electrónico de Gmail
            $mail->Password = 'n q d i t o s c m r v k f h f y'; // Reemplaza con tu contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Habilitar la depuración detallada
            $mail->SMTPDebug = 2;

            // Configuración de los destinatarios
            $mail->setFrom('tu_email@gmail.com', 'Tienda de Ropa');
            $mail->addAddress($email, $name);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu email';
            $mail->Body    = "Haz clic en el siguiente enlace para confirmar tu email: <a href='http://yourdomain.com/usuarios/confirmar_email.php?token=$token'>Confirmar Email</a>";

            $mail->send();
            echo 'El mensaje ha sido enviado';
        } catch (Exception $e) {
            echo "No se pudo enviar el mensaje. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
