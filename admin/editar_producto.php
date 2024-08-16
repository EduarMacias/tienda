<?php
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM productos WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

$is_offer = $product['is_offer'] ?? 0;
$is_featured = $product['is_featured'] ?? 0;
$is_recent = $product['is_recent'] ?? 0;
$is_best_seller = $product['is_best_seller'] ?? 0;
$is_article_of_the_month = $product['is_article_of_the_month'] ?? 0;

$sql_categorias = "SELECT * FROM categorias";
$result_categorias = $conn->query($sql_categorias);

$sql_tallas = "SELECT * FROM tallas";
$result_tallas = $conn->query($sql_tallas);

$sql_colores = "SELECT * FROM colores";
$result_colores = $conn->query($sql_colores);

$sql_tallas_producto = "SELECT talla_id FROM productos_tallas WHERE producto_id = $id";
$result_tallas_producto = $conn->query($sql_tallas_producto);
$tallas_producto = [];
while ($row = $result_tallas_producto->fetch_assoc()) {
    $tallas_producto[] = $row['talla_id'];
}

$sql_colores_producto = "SELECT color_id FROM productos_colores WHERE producto_id = $id";
$result_colores_producto = $conn->query($sql_colores_producto);
$colores_producto = [];
while ($row = $result_colores_producto->fetch_assoc()) {
    $colores_producto[] = $row['color_id'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Editar Producto</h1>
        <form action="actualizar_producto.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <div class="mb-4">
                <label for="codigo" class="block text-gray-700 text-sm font-bold mb-2">Código del Producto:</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="codigo" name="codigo" value="<?php echo $product['codigo']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Producto:</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" name="nombre" value="<?php echo $product['nombre']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="precio" class="block text-gray-700 text-sm font-bold mb-2">Precio del Producto:</label>
                <input type="number" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="precio" name="precio" value="<?php echo $product['precio']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="cantidad" class="block text-gray-700 text-sm font-bold mb-2">Cantidad:</label>
                <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cantidad" name="cantidad" value="<?php echo $product['cantidad']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="imagen" class="block text-gray-700 text-sm font-bold mb-2">Imagen del Producto:</label>
                <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="imagen" name="imagen" accept="image/*">
                <img src="../uploads/<?php echo $product['imagen']; ?>" alt="<?php echo $product['nombre']; ?>" class="mt-4 w-32 h-32 object-cover">
            </div>
            <div class="mb-4">
                <label for="categoria" class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="categoria" name="categoria_id" required>
                    <?php while ($row = $result_categorias->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $product['categoria_id']) echo 'selected'; ?>>
                            <?php echo $row['nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="tallas" class="block text-gray-700 text-sm font-bold mb-2">Tallas:</label>
                <?php while ($row = $result_tallas->fetch_assoc()): ?>
                    <div class="flex items-center mb-2">
                        <input class="mr-2 leading-tight" type="checkbox" name="tallas[]" value="<?php echo $row['id']; ?>" <?php if (in_array($row['id'], $tallas_producto)) echo 'checked'; ?>>
                        <label class="text-gray-700"><?php echo $row['nombre']; ?></label>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="mb-4">
                <label for="colores" class="block text-gray-700 text-sm font-bold mb-2">Colores:</label>
                <?php while ($row = $result_colores->fetch_assoc()): ?>
                    <div class="flex items-center mb-2">
                        <input class="mr-2 leading-tight" type="checkbox" name="colores[]" value="<?php echo $row['id']; ?>" <?php if (in_array($row['id'], $colores_producto)) echo 'checked'; ?>>
                        <label class="text-gray-700"><?php echo $row['nombre']; ?></label>
                    </div>
                <?php endwhile; ?>
            </div>
            <!-- Nueva sección para marcar el producto como oferta, destacado, reciente, mejor vendido y artículo del mes -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Producto en oferta:</label>
                <input type="checkbox" name="is_offer" <?php if ($is_offer) echo 'checked'; ?>>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Producto destacado:</label>
                <input type="checkbox" name="is_featured" <?php if ($is_featured) echo 'checked'; ?>>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Producto más reciente:</label>
                <input type="checkbox" name="is_recent" <?php if ($is_recent) echo 'checked'; ?>>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Mejor vendido:</label>
                <input type="checkbox" name="is_best_seller" <?php if ($is_best_seller) echo 'checked'; ?>>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Artículo del Mes:</label>
                <input type="checkbox" name="is_article_of_the_month" <?php if ($is_article_of_the_month) echo 'checked'; ?>>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
        </form>
    </div>
</body>
</html>
