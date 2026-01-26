<?php

include("conexion.php");

$sql = "SELECT IFNULL(MAX(item3),0) AS ultimo_item FROM vigas";
$res = $conexion->query($sql);

if ($res && $row = $res->fetch_assoc()) {
    echo json_encode([
        'ultimo_item' => intval($row['ultimo_item'])
    ]);
} else {
    echo json_encode([
        'ultimo_item' => 0
    ]);
}
