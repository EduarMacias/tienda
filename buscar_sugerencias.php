<?php
include 'admin/db.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $query = $conn->real_escape_string($query);

    $sql = "SELECT id, nombre FROM productos WHERE nombre LIKE '%$query%' LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<a href="detalles.php?id=' . $row['id'] . '" class="suggestion-item cursor-pointer px-4 py-2 hover:bg-gray-200 block">' . htmlspecialchars($row['nombre']) . '</a>';
        }
    } else {
        echo '<div class="suggestion-item cursor-pointer px-4 py-2 text-gray-500">No disponible</div>';
    }
}
?>
