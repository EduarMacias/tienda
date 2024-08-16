<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

include 'db.php';

$sql_categorias = "SELECT * FROM categorias";
$result_categorias = $conn->query($sql_categorias);

$sql_tallas = "SELECT * FROM tallas";
$result_tallas = $conn->query($sql_tallas);

$sql_colores = "SELECT * FROM colores";
$result_colores = $conn->query($sql_colores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script>
        function showSection(section) {
            document.getElementById('sectionAgregarProducto').style.display = 'none';
            document.getElementById('sectionAgregarCategoria').style.display = 'none';
            document.getElementById('sectionAgregarTalla').style.display = 'none';
            document.getElementById('sectionAgregarColor').style.display = 'none';
            document.getElementById('sectionPedidos').style.display = 'none';
            document.getElementById('sectionVerProductos').style.display = 'none';
            document.getElementById(section).style.display = 'block';
        }

        $(document).ready(function() {
            $('#tablaPedidos').DataTable();
            $('#tablaProductos').DataTable();
        });
    </script>
</head>
<body>
    <div class="container mx-auto">
        <div class="flex flex-wrap">
            <nav class="w-full md:w-1/4 bg-gray-800 p-4">
                <div class="text-white">
                    <ul class="space-y-4">
                        <li>
                            <a href="#" onclick="showSection('sectionAgregarProducto')" class="block py-2 px-4 rounded hover:bg-gray-700">Agregar Producto</a>
                        </li>
                        <li>
                            <a href="#" onclick="showSection('sectionAgregarCategoria')" class="block py-2 px-4 rounded hover:bg-gray-700">Agregar Categoría</a>
                        </li>
                        <li>
                            <a href="#" onclick="showSection('sectionAgregarTalla')" class="block py-2 px-4 rounded hover:bg-gray-700">Agregar Talla</a>
                        </li>
                        <li>
                            <a href="#" onclick="showSection('sectionAgregarColor')" class="block py-2 px-4 rounded hover:bg-gray-700">Agregar Color</a>
                        </li>
                        <li>
                            <a href="#" onclick="showSection('sectionPedidos')" class="block py-2 px-4 rounded hover:bg-gray-700">Ver Pedidos</a>
                        </li>
                        <li>
                            <a href="#" onclick="showSection('sectionVerProductos')" class="block py-2 px-4 rounded hover:bg-gray-700">Ver Productos</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="w-full md:w-3/4 p-6">
                <div id="sectionAgregarProducto" style="display:none;">
                    <div class="bg-white shadow-md rounded-lg mb-4">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Agregar Producto</h2>
                        </div>
                        <div class="p-6">
                            <form id="formProducto" action="agregar_producto.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="codigoProducto" class="block text-gray-700 text-sm font-bold mb-2">Código del Producto:</label>
                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="codigoProducto" name="codigo" required>
                                </div>
                                <div class="mb-4">
                                    <label for="nombreProducto" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Producto:</label>
                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombreProducto" name="nombre" required>
                                </div>
                                <div class="mb-4">
                                    <label for="precioProducto" class="block text-gray-700 text-sm font-bold mb-2">Precio del Producto:</label>
                                    <input type="number" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="precioProducto" name="precio" required>
                                </div>
                                <div class="mb-4">
                                    <label for="cantidadProducto" class="block text-gray-700 text-sm font-bold mb-2">Cantidad:</label>
                                    <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cantidadProducto" name="cantidad" required>
                                </div>
                                <div class="mb-4">
                                    <label for="imagenProducto" class="block text-gray-700 text-sm font-bold mb-2">Imagen del Producto:</label>
                                    <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="imagenProducto" name="imagen" accept="image/*" required>
                                </div>
                                <div class="mb-4">
                                    <label for="categoriaProducto" class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="categoriaProducto" name="categoria_id" required>
                                        <?php while ($row = $result_categorias->fetch_assoc()): ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="tallasProducto" class="block text-gray-700 text-sm font-bold mb-2">Tallas:</label>
                                    <?php while ($row = $result_tallas->fetch_assoc()): ?>
                                        <div class="flex items-center mb-2">
                                            <input class="mr-2 leading-tight" type="checkbox" name="tallas[]" value="<?php echo $row['id']; ?>">
                                            <label class="text-gray-700"><?php echo $row['nombre']; ?></label>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <div class="mb-4">
                                    <label for="coloresProducto" class="block text-gray-700 text-sm font-bold mb-2">Colores:</label>
                                    <?php while ($row = $result_colores->fetch_assoc()): ?>
                                        <div class="flex items-center mb-2">
                                            <input class="mr-2 leading-tight" type="checkbox" name="colores[]" value="<?php echo $row['id']; ?>">
                                            <label class="text-gray-700"><?php echo $row['nombre']; ?></label>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="sectionAgregarCategoria" style="display:none;">
                    <div class="bg-white shadow-md rounded-lg mb-4">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Agregar Categoría</h2>
                        </div>
                        <div class="p-6">
                            <form id="formCategoria" action="agregar_categoria.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="nombreCategoria" class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Categoría:</label>
                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombreCategoria" name="nombre" required>
                                </div>
                                <div class="mb-4">
                                    <label for="imagenCategoria" class="block text-gray-700 text-sm font-bold mb-2">Imagen de la Categoría:</label>
                                    <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="imagenCategoria" name="imagen" accept="image/*" required>
                                </div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="sectionAgregarTalla" style="display:none;">
                    <div class="bg-white shadow-md rounded-lg mb-4">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Agregar Talla</h2>
                        </div>
                        <div class="p-6">
                            <form id="formTalla" action="agregar_talla.php" method="POST">
                                <div class="mb-4">
                                    <label for="nombreTalla" class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Talla:</label>
                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombreTalla" name="nombre" required>
                                </div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="sectionAgregarColor" style="display:none;">
                    <div class="bg-white shadow-md rounded-lg mb-4">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Agregar Color</h2>
                        </div>
                        <div class="p-6">
                            <form id="formColor" action="agregar_color.php" method="POST">
                                <div class="mb-4">
                                    <label for="nombreColor" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Color:</label>
                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombreColor" name="nombre" required>
                                </div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="sectionPedidos" style="display:none;">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Ver Pedidos</h2>
                    <table id="tablaPedidos" class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="px-6 py-3 border-b border-gray-200">ID</th>
                                <th class="px-6 py-3 border-b border-gray-200">Nombre</th>
                                <th class="px-6 py-3 border-b border-gray-200">Dirección</th>
                                <th class="px-6 py-3 border-b border-gray-200">Email</th>
                                <th class="px-6 py-3 border-b border-gray-200">Teléfono</th>
                                <th class="px-6 py-3 border-b border-gray-200">Comprobante</th>
                                <th class="px-6 py-3 border-b border-gray-200">Productos</th>
                                <th class="px-6 py-3 border-b border-gray-200">Estado</th>
                                <th class="px-6 py-3 border-b border-gray-200">Total</th>
                                <th class="px-6 py-3 border-b border-gray-200">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            <?php
                            $sql_pedidos = "SELECT p.*, (SELECT SUM(pp.cantidad * prod.precio) FROM pedido_productos pp JOIN productos prod ON pp.producto_id = prod.id WHERE pp.pedido_id = p.id) AS total FROM pedidos p";
                            $result_pedidos = $conn->query($sql_pedidos);
                            while ($row_pedido = $result_pedidos->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_pedido['id']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_pedido['nombre']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_pedido['direccion']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_pedido['email']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_pedido['telefono']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><a href="../uploads/comprobantes/<?php echo $row_pedido['comprobante']; ?>" target="_blank" class="text-blue-500 hover:text-blue-700">Ver Comprobante</a></td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        <a href="ver_productos_pedido.php?pedido_id=<?php echo $row_pedido['id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs">Ver Productos</a>
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        <form action="actualizar_estado_pedido.php" method="POST">
                                            <input type="hidden" name="pedido_id" value="<?php echo $row_pedido['id']; ?>">
                                            <select name="estado" class="shadow appearance-none border rounded w-full py-1 px-2 leading-tight focus:outline-none focus:shadow-outline" onchange="this.form.submit()">
                                                <option value="pendiente" <?php if ($row_pedido['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                                                <option value="completado" <?php if ($row_pedido['estado'] == 'completado') echo 'selected'; ?>>Completado</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-200">$<?php echo number_format($row_pedido['total'], 2); ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        <form action="eliminar_pedido.php" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este pedido?');">
                                            <input type="hidden" name="pedido_id" value="<?php echo $row_pedido['id']; ?>">
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div id="sectionVerProductos" style="display:none;">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Ver Productos</h2>
                    <table id="tablaProductos" class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="px-6 py-3 border-b border-gray-200">ID</th>
                                <th class="px-6 py-3 border-b border-gray-200">Código</th>
                                <th class="px-6 py-3 border-b border-gray-200">Nombre</th>
                                <th class="px-6 py-3 border-b border-gray-200">Precio</th>
                                <th class="px-6 py-3 border-b border-gray-200">Cantidad</th>
                                <th class="px-6 py-3 border-b border-gray-200">Categoría</th>
                                <th class="px-6 py-3 border-b border-gray-200">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            <?php
                            $sql_productos = "SELECT p.*, c.nombre AS categoria FROM productos p LEFT JOIN categorias c ON p.categoria_id = c.id";
                            $result_productos = $conn->query($sql_productos);
                            while ($row_producto = $result_productos->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_producto['id']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_producto['codigo']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_producto['nombre']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_producto['precio']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_producto['cantidad']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200"><?php echo $row_producto['categoria']; ?></td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        <a href="editar_producto.php?id=<?php echo $row_producto['id']; ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs">Editar</a>
                                        <a href="eliminar_producto.php?id=<?php echo $row_producto['id']; ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
    <script>
        // Inicializa la primera sección visible
        showSection('sectionAgregarProducto');
    </script>
</body>
</html>
