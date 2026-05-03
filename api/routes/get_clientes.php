<?php

require __DIR__ . '/../config/database.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

try {

    $db = (new Database())->connect();

    $stmt = $db->query("
        SELECT id, nombre, email, telefono, deuda, fecha_mora, created_at
        FROM clientes_mora
        ORDER BY id DESC
        LIMIT 100
    ");

    $data = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "data" => $data
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}