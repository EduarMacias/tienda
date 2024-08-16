<?php
include 'admin/db.php';
session_start();

// Consultas para obtener los datos necesarios
$sql_categorias = "SELECT * FROM categorias";
$result_categorias = $conn->query($sql_categorias);

$sql_ofertas = "SELECT * FROM productos WHERE is_offer = 1 ORDER BY id DESC";
$result_ofertas = $conn->query($sql_ofertas);

$sql_recientes = "SELECT * FROM productos WHERE is_recent = 1 ORDER BY id DESC";
$result_recientes = $conn->query($sql_recientes);

$sql_destacados = "SELECT * FROM productos WHERE is_featured = 1 ORDER BY id DESC";
$result_destacados = $conn->query($sql_destacados);

$sql_best_sellers = "SELECT * FROM productos WHERE is_best_seller = 1 ORDER BY id DESC";
$result_best_sellers = $conn->query($sql_best_sellers);

$sql_articulo_mes = "SELECT * FROM productos WHERE is_article_of_the_month = 1 ORDER BY id DESC LIMIT 1";
$result_articulo_mes = $conn->query($sql_articulo_mes);
$articulo_mes = $result_articulo_mes->fetch_assoc();

$cart_items = $_SESSION['cart'] ?? [];
$user_name = $_SESSION['user_name'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ropa</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick-theme.css"/>
    <link rel="stylesheet" href="css/estilosdeindexprincipal.css">
    <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
    <style>
        .chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .chat-widget a {
            display: block;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .chat-widget a img {
            width: 50px;
            height: 50px;
        }

        .slick-slide img, .articulo-del-mes img, .productos-grid img {
            border-radius: 15px; /* Bordes redondeados */
        }
        .slick-slide img {
            border-radius: 15px; /* Bordes redondeados */
            width: 100%;
            height: 350px; /* Incrementa la altura para hacerla más grande */
            object-fit: cover;
        }

        .slick-caption {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
            border-radius: 5px;
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: white;
        }

        .articulo-del-mes {
            text-align: center;
            padding: 20px;
        }

        .articulo-del-mes img {
            width: 300px;
            height: 500px;
            object-fit: cover;
            margin: 0 auto;
            border-radius: 15px;
        }

        .mejores-productos-buttons span {
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .mejores-productos-buttons span:hover {
            background-color: #4f46e5; /* Cambia de color al hacer hover */
            color: #ffffff;
        }

        .productos-grid img:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }

        .productos-grid {
            display: none;
        }

        .productos-grid.active {
            display: grid;
        }

        /* Estilo para la caja de sugerencias de búsqueda */
        .suggestions-box {
            position: absolute;
            top: 100%; /* Asegura que la caja se muestre debajo del input */
            left: 0;
            right: 0;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.25rem;
            background: #fff;
        }

        .suggestion-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

        .no-results {
            padding: 10px;
            color: #999;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow">
        <div class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="img/logo.png" alt="Logo" class="h-10">
                    <h1 class="text-2xl font-bold text-gray-800 ml-4"><a href="/">Mayra Store</a></h1>
                </div>
                <div class="flex items-center relative">
                    <form class="flex items-center mr-4" autocomplete="off">
                        <input type="search" id="search-input" placeholder="Buscar..." class="border rounded-l px-4 py-2 relative">
                        <button type="button" class="bg-gray-800 text-white px-4 py-2 rounded-r">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <div class="suggestions-box absolute bg-white border border-gray-300 rounded mt-1 w-full z-10" style="display: none;"></div>
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
                    <a href="#" class="text-gray-800 hover:text-gray-600">Inicio</a>
                    <a href="#" class="text-gray-800 hover:text-gray-600">Categoría</a>
                </div>
            </nav>
        </div>
    </header>
    
    <main>
        <!-- Sección del Artículo del Mes -->
        <section class="articulo-del-mes">
            <div class="container mx-auto text-center">
                <p class="text-lg">ARTÍCULO DEL MES</p>
                <?php if ($articulo_mes): ?>
                    <div>
                        <a href="detalles.php?id=<?php echo $articulo_mes['id']; ?>">
                            <img src="uploads/<?php echo $articulo_mes['imagen']; ?>" alt="<?php echo $articulo_mes['nombre']; ?>" class="mx-auto">
                            <h2 class="text-4xl font-bold my-2"><?php echo $articulo_mes['nombre']; ?></h2>
                        </a>
                        <a href="detalles.php?id=<?php echo $articulo_mes['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block">Comprar ahora</a>
                    </div>
                <?php else: ?>
                    <h2 class="text-4xl font-bold my-2">No hay artículo del mes disponible</h2>
                <?php endif; ?>
            </div>
        </section>

        <section class="container mx-auto py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Categorías</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php while ($row = $result_categorias->fetch_assoc()): ?>
                    <div class="relative h-64 bg-cover bg-center rounded-lg shadow-lg" style="background-image: url('uploads/<?php echo $row['imagen']; ?>');">
                        <div class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
                            <div class="text-center text-white">
                                <p class="text-xl font-bold"><?php echo $row['nombre']; ?></p>
                                <a href="productos.php?categoria_id=<?php echo $row['id']; ?>" class="underline">Ver más</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Sección de Ofertas -->
        <section class="py-16">
            <div class="container mx-auto text-center">
                <p class="text-lg">OFERTAS ESPECIALES</p>
                <h2 class="text-4xl font-bold my-2">PRODUCTOS EN OFERTA</h2>
                <div class="slick-carousel">
                    <?php while ($row = $result_ofertas->fetch_assoc()): ?>
                        <div class="relative">
                            <a href="detalles.php?id=<?php echo $row['id']; ?>">
                                <img src="uploads/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="w-full h-64 object-cover">
                                <div class="slick-caption">
                                    <h5><?php echo $row['nombre']; ?></h5>
                                    <p>$<?php echo $row['precio']; ?></p>
                                </div>
                            </a>
                            <div class="absolute bottom-5 left-5">
                                <a href="detalles.php?id=<?php echo $row['id']; ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Comprar Ahora</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <!-- Sección de Mejores Productos -->
        <section class="container mx-auto py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Mejores Productos</h1>
            <div class="flex space-x-4 mb-4 mejores-productos-buttons">
                <span class="px-4 py-2 bg-blue-500 text-white rounded cursor-pointer" data-target="#destacados">Destacados</span>
                <span class="px-4 py-2 bg-gray-200 text-gray-800 rounded cursor-pointer" data-target="#recientes">Más recientes</span>
                <span class="px-4 py-2 bg-gray-200 text-gray-800 rounded cursor-pointer" data-target="#mejores">Mejores Vendidos</span>
            </div>

            <div id="destacados" class="productos-grid active">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php while ($row = $result_destacados->fetch_assoc()): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <div class="relative h-64">
                                <img src="uploads/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="h-full w-full object-contain rounded-lg">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div id="recientes" class="productos-grid hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php while ($row = $result_recientes->fetch_assoc()): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <div class="relative h-64">
                                <img src="uploads/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="h-full w-full object-contain rounded-lg">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div id="mejores" class="productos-grid hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php while ($row = $result_best_sellers->fetch_assoc()): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <div class="relative h-64">
                                <img src="uploads/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="h-full w-full object-contain rounded-lg">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    </main>
    
    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-6 py-4">
            <p class="text-gray-600">&copy; 2024 Tienda de Ropa. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Chat Widget -->
    <div class="chat-widget">
        <a href="https://wa.me/+593997718633" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
        </a>
        <a href="mailto:macieduksc1@gmail.com" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/Gmail_Icon.png" alt="Email">
        </a>
    </div>

    <!-- JavaScript para Slick Carousel -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.slick-carousel').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                arrows: true,
                dots: true
            });

            // Funcionalidad para cambiar entre secciones de productos
            $('.mejores-productos-buttons span').click(function() {
                var target = $(this).data('target');
                $('.productos-grid').hide();
                $(target).show();
                $('.mejores-productos-buttons span').removeClass('bg-blue-500 text-white').addClass('bg-gray-200 text-gray-800');
                $(this).removeClass('bg-gray-200 text-gray-800').addClass('bg-blue-500 text-white');
            });

            // Funcionalidad de búsqueda con sugerencias
            $('#search-input').on('input', function() {
                var query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: 'buscar_sugerencias.php',
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            $('.suggestions-box').html(data).show();
                        }
                    });
                } else {
                    $('.suggestions-box').hide();
                }
            });

            // Redirigir a la página del producto cuando se haga clic en una sugerencia
            $(document).on('click', '.suggestion-item', function() {
                var productId = $(this).data('id');
                window.location.href = 'detalles.php?id=' + productId;
            });
        });
    </script>
</body>
</html>
