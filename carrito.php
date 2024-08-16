<?php
include 'admin/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $talla_id = $_POST['talla_id'];
    $color_id = $_POST['color_id'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $item = [
        'product_id' => $product_id,
        'talla_id' => $talla_id,
        'color_id' => $color_id,
        'quantity' => $quantity
    ];

    $_SESSION['cart'][] = $item;
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Producto agregado al carrito',
        'cart_count' => count($_SESSION['cart'])
    ]);
    exit();
}

$cart_items = $_SESSION['cart'] ?? [];
$user_name = $_SESSION['user_name'] ?? null;

// Calculate total price
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
    <script>
        function confirmRemove(index) {
            if (confirm("¿Estás seguro de quitar el producto del carrito?")) {
                window.location.href = 'quitar_del_carrito.php?index=' + index;
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow">
        <div class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="img/logo.png" alt="Soporte al Cliente" class="h-10">
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-800"><a href="/">Mayra Store</a></h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <?php if ($user_name): ?>
                        <span class="mr-4">Bienvenido, <?php echo htmlspecialchars($user_name); ?></span>
                        <a href="logout.php" class="text-gray-800 hover:text-gray-600">
                            <i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    <?php else: ?>
                        <a href="usuarios/login.php" class="text-gray-800 hover:text-gray-600">
                            <i class="fa-solid fa-user"></i> Iniciar Sesión
                        </a>
                    <?php endif; ?>
                    <a href="carrito.php" class="ml-4 text-gray-800 hover:text-gray-600">
                        <i class="fa-solid fa-basket-shopping"></i>
                        <div class="inline-block">
                            <span class="text">Carrito</span>
                            <span class="number">(<?php echo count($cart_items); ?>)</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="container mx-auto px-6 py-3">
            <nav class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-800 hover:text-gray-600">Inicio</a>
                </div>
                <form class="flex items-center">
                    <input type="search" placeholder="Buscar..." class="border rounded-l px-4 py-2">
                    <button class="bg-gray-800 text-white px-4 py-2 rounded-r">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </nav>
        </div>
    </header>
    
    <main class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Carrito de Compras</h1>
        <table class="min-w-full bg-white rounded-lg shadow-lg">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Talla</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $index => $item): ?>
                    <?php
                    $product_id = $item['product_id'];
                    $sql = "SELECT * FROM productos WHERE id = $product_id";
                    $result = $conn->query($sql);
                    $product = $result->fetch_assoc();

                    $talla_id = $item['talla_id'];
                    $sql = "SELECT nombre FROM tallas WHERE id = $talla_id";
                    $result = $conn->query($sql);
                    $talla = $result->fetch_assoc()['nombre'];

                    $color_id = $item['color_id'];
                    $sql = "SELECT nombre FROM colores WHERE id = $color_id";
                    $result = $conn->query($sql);
                    $color = $result->fetch_assoc()['nombre'];

                    $subtotal = $product['precio'] * $item['quantity'];
                    $total_price += $subtotal;
                    ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="uploads/<?php echo $product['imagen']; ?>" alt="<?php echo $product['nombre']; ?>" class="h-10 w-10 rounded-full">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $product['nombre']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $talla; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $color; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $item['quantity']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">$<?php echo number_format($subtotal, 2); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="confirmRemove(<?php echo $index; ?>)" class="bg-red-500 text-white px-4 py-2 rounded">Quitar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-right mt-4">
            <h3 class="text-xl font-bold text-gray-800">Total: $<?php echo number_format($total_price, 2); ?></h3>
        </div>
        <a href="checkout.php" class="bg-green-500 text-white px-4 py-2 rounded mt-4 inline-block">Proceder al Pago</a>
    </main>
    
    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-6 py-4">
            <p class="text-gray-600">&copy; 2024 Tienda de Ropa. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
