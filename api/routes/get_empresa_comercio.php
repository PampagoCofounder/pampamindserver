<?php

require_once __DIR__ . "/../config/database.php";

header("Content-Type: application/json");

$db = (new Database())->connect();

try {
    $stmt = $db->query("SELECT * FROM empresa_comercio");

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["error" => "Sin datos"]);
        exit;
    }

    echo json_encode([
        "empresa_id" => $row["empresa_id"],
        "cantidad_clientes" => $row["cantidad_clientes"],
        "productos_importados" => $row["productos_importados"],
        "productos_exportados" => $row["productos_exportados"],
        "costos" => $row["costos"],
        "updated_at" => $row["updated_at"],
     
        
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "error" => "Error en consulta"
    ]);
}



?>