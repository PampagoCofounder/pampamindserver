<?php
require_once __DIR__ . "/../config/database.php";

header("Content-Type: application/json");

$db = (new Database())->connect();

try {
    $stmt = $db->query("SELECT * FROM datos_exportacion_importacion_argentina");

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["error" => "Sin datos"]);
        exit;
    }

    echo json_encode([
        "exportaciones" => (float)$row["exportaciones"],
        "importaciones" => (float)$row["importaciones"],
        "update_data" => $row["update_data"]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "error" => "Error en consulta"
    ]);
}

?>