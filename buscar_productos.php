<?php
include 'admin/db.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT id, nombre FROM productos WHERE nombre LIKE '%$query%' LIMIT 5";
    $result = $conn->query($sql);

    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    echo json_encode($productos);
}
?>
