<?php 

require_once __DIR__ . "/../config/database.php";

header("Content-Type: application/json");

$db = (new Database())->connect();

try {
    $stmt = $db->query("SELECT * FROM datos_empresa");

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["error" => "Sin datos"]);
        exit;
    }

    echo json_encode([
        "nombre_empresa" => $row["nombre_empresa"],
        "CUIT" => $row["CUIT"],
        "actividad" => $row["actividad"],
        "direccion" => $row["direccion"],
        "provincia" => $row["provincia"],
        "telefono" => $row["telefono"],
        "email" => $row["email"],
        "rol" => $row["rol"],
        "fecha" => $row["fecha"]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "error" => "Error en consulta"
    ]);
}







?>