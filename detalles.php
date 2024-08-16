<?php
include 'admin/db.php';
session_start();

$id = $_GET['id'];
$sql = "SELECT * FROM productos WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

$sql_tallas = "SELECT t.id, t.nombre FROM tallas t 
               JOIN productos_tallas pt ON t.id = pt.talla_id 
               WHERE pt.producto_id = $id";
$result_tallas = $conn->query($sql_tallas);

$sql_colores = "SELECT c.id, c.nombre FROM colores c 
                JOIN productos_colores pc ON c.id = pc.color_id 
                WHERE pc.producto_id = $id";
$result_colores = $conn->query($sql_colores);

$cart_items = $_SESSION['cart'] ?? [];
$user_name = $_SESSION['user_name'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function agregarAlCarrito(form) {
            $.ajax({
                type: 'POST',
                url: 'carrito.php',
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    $('#carrito-count').text('('+response.cart_count+')');
                },
                error: function() {
                    alert('Error al agregar el producto al carrito');
                }
            });
            return false; // prevent default form submission
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
                    <form class="flex items-center mr-4">
                        <input type="search" placeholder="Buscar..." class="border rounded-l px-4 py-2">
                        <button class="bg-gray-800 text-white px-4 py-2 rounded-r">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
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
                        <div class="inline-block ml-1">
                            <span class="text">Carrito</span>
                            <span id="carrito-count" class="number">(<?php echo count($cart_items); ?>)</span>
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
            </nav>
        </div>
    </header>
    
    <main class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Detalles del Producto</h1>
        <div class="flex flex-wrap -mx-3">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <img src="uploads/<?php echo $product['imagen']; ?>" alt="<?php echo $product['nombre']; ?>" class="object-contain h-96 w-full">
            </div>
            <div class="w-full md:w-1/2 px-3">
                <h3 class="text-2xl font-semibold text-gray-800"><?php echo $product['nombre']; ?></h3>
                <p class="text-gray-600 mt-2">Código: <?php echo $product['codigo']; ?></p>
                <p class="text-gray-600 mt-2">Precio: $<?php echo $product['precio']; ?></p>
                <p class="text-gray-600 mt-2">Cantidad disponible: <?php echo $product['cantidad']; ?></p>
                <form onsubmit="return agregarAlCarrito(this);" class="mt-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="mb-4">
                        <label for="talla" class="block text-gray-700">Talla:</label>
                        <select class="form-control" id="talla" name="talla_id" required>
                            <?php while ($row = $result_tallas->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="color" class="block text-gray-700">Color:</label>
                        <select class="form-control" id="color" name="color_id" required>
                            <?php while ($row = $result_colores->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700">Cantidad:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['cantidad']; ?>" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Añadir al Carrito</button>
                </form>
            </div>
        </div>
    </main>
    
    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-6 py-4">
            <p class="text-gray-600">&copy; 2024 Tienda de Ropa. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
