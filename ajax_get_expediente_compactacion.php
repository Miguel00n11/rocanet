<?php

include("conexion.php");

$expediente = $_GET['expediente'] ?? '';

if ($expediente === '') {
    echo json_encode(['error' => 'Expediente vacío']);
    exit;
}

$sql = "
SELECT 
    o.expediente,
    o.obra,
    o.localizacion,
    c.idcliente,
    c.cliente,
    COUNT(r.reporte) + 1 AS siguiente_reporte
FROM obras o
JOIN clientes c ON o.cliente = c.idcliente
LEFT JOIN reportes r ON r.expediente = o.expediente
WHERE o.expediente = '$expediente'
GROUP BY o.expediente
LIMIT 1
";

$res = $conexion->query($sql);

if ($res && $res->num_rows > 0) {
    echo json_encode($res->fetch_assoc());
} else {
    echo json_encode(['error' => 'No encontrado']);
}
