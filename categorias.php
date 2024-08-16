<?php
include 'admin/db.php';

$sql_categorias = "SELECT * FROM categorias";
$result_categorias = $conn->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrap.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Tienda de Ropa</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="categorias.php">Categoría</a></li>
                </ul>
            </nav>
            <div class="search-bar">
                <input type="text" placeholder="Buscar...">
                <button type="submit">Buscar</button>
            </div>
            <div class="user-cart">
                <a href="login.php">Iniciar sesión</a>
                <a href="carrito.php">Carrito (0)</a>
            </div>
        </div>
    </header>
    
    <main>
        <div class="container">
            <h2>Categorías</h2>
            <div class="category-grid">
                <?php while ($row = $result_categorias->fetch_assoc()): ?>
                    <div class="category-item">
                        <img src="uploads/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>">
                        <h3><?php echo $row['nombre']; ?></h3>
                        <a href="productos.php?categoria_id=<?php echo $row['id']; ?>" class="btn btn-primary">Ver más</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2024 Tienda de Ropa. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
