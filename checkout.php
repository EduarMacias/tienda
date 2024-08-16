<?php
include 'admin/db.php';

session_start();

$cart_items = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $comprobante = $_FILES['comprobante']['name'];
    $target_dir = "uploads/comprobantes/";
    $target_file = $target_dir . basename($comprobante);

    // Crear el directorio si no existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $target_file)) {
        $estado = 'pendiente'; // Estado inicial del pedido
        $sql = "INSERT INTO pedidos (nombre, direccion, email, telefono, comprobante, estado) VALUES ('$nombre', '$direccion', '$email', '$telefono', '$comprobante', '$estado')";
        if ($conn->query($sql) === TRUE) {
            $pedido_id = $conn->insert_id;
            foreach ($cart_items as $item) {
                $producto_id = $item['product_id'];
                $talla_id = $item['talla_id'];
                $color_id = $item['color_id'];
                $cantidad = $item['quantity'];

                $sql = "INSERT INTO pedido_productos (pedido_id, producto_id, talla_id, color_id, cantidad) VALUES ($pedido_id, $producto_id, $talla_id, $color_id, $cantidad)";
                $conn->query($sql);

                // Restar la cantidad del inventario
                $sql = "UPDATE productos SET cantidad = cantidad - $cantidad WHERE id = $producto_id";
                $conn->query($sql);
            }
            unset($_SESSION['cart']);
            echo "<script>
                    alert('Pedido realizado correctamente');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error al subir el comprobante.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <img src="img/logo.png" alt="Logo" class="h-10">
                <h1 class="text-2xl font-bold text-gray-800 ml-4"><a href="/">Mayra Store</a></h1>
            </div>
            <div class="flex items-center">
                <form class="flex items-center mr-4">
                    <input type="search" placeholder="Buscar..." class="border rounded-l px-4 py-2">
                    <button class="bg-gray-800 text-white px-4 py-2 rounded-r">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
                <a href="carrito.php" class="text-gray-800 hover:text-gray-600 ml-4">
                    <i class="fa-solid fa-basket-shopping text-xl"></i>
                    <span class="ml-1">(<?php echo count($cart_items); ?>)</span>
                </a>
            </div>
        </div>
    </header>
    
    <main class="container mx-auto my-10 px-6">
        <h2 class="text-3xl font-bold mb-6">PAGO</h2>
        <form action="checkout.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="direccion" class="block text-gray-700 font-bold mb-2">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="telefono" class="block text-gray-700 font-bold mb-2">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-6">
                <label for="comprobante" class="block text-gray-700 font-bold mb-2">Comprobante de Pago:</label>
                <input type="file" id="comprobante" name="comprobante" accept="image/*" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Realizar Pedido</button>
        </form>
    </main>
    
    <footer class="bg-white shadow mt-8 py-4">
        <div class="container mx-auto text-center">
            <p class="text-gray-600">&copy; 2024 Tienda de Ropa. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</body>
</html>
