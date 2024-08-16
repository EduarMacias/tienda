<?php
include 'admin/db.php';
session_start();

$categoria_id = $_GET['categoria_id'];
$sql_productos = "SELECT * FROM productos WHERE categoria_id = $categoria_id";
$result_productos = $conn->query($sql_productos);

$cart_items = $_SESSION['cart'] ?? [];
$user_name = $_SESSION['user_name'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
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
                    location.reload(); // Recargar la página después de agregar al carrito
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
                        <i class="fa-solid fa-basket-shopping text-xl"></i>
                        <div class="inline-block ml-1">
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
            </nav>
        </div>
    </header>
    
    <main class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Productos</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($row = $result_productos->fetch_assoc()): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="relative h-64">
                        <img src="uploads/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="h-full w-full object-contain">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <div class="flex space-x-1">
                                <i class="fa-solid fa-star text-yellow-500"></i>
                                <i class="fa-solid fa-star text-yellow-500"></i>
                                <i class="fa-solid fa-star text-yellow-500"></i>
                                <i class="fa-solid fa-star text-yellow-500"></i>
                                <i class="fa-regular fa-star text-gray-400"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo $row['nombre']; ?></h3>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-gray-600">$<?php echo $row['precio']; ?></p>
                            <div class="flex space-x-2">
                                <a href="detalles.php?id=<?php echo $row['id']; ?>" class="text-gray-600 hover:text-gray-800">
                                    <i class="fa-regular fa-eye text-2xl"></i>
                                </a>
                                <form onsubmit="return agregarAlCarrito(this);">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="talla_id" value="1">
                                    <input type="hidden" name="color_id" value="1">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="text-gray-600 hover:text-gray-800">
                                        <i class="fa-solid fa-basket-shopping text-2xl"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
    
    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-6 py-4">
            <p class="text-gray-600">&copy; 2024 Tienda de Ropa. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
