<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../config/database.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

try {

    if (!isset($_FILES['file'])) {
        throw new Exception("Archivo no enviado");
    }

    $file = $_FILES['file'];

    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception("Archivo demasiado grande");
    }

    $spreadsheet = IOFactory::load($file['tmp_name']);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    $headers = array_map(fn($h) => strtolower(trim($h)), array_values($rows[1]));

    $map = array_flip($headers);

    $db = (new Database())->connect();
    $db->beginTransaction();

    $stmt = $db->prepare("
        INSERT INTO clientes_mora (nombre, email, telefono, deuda, fecha_mora)
        VALUES (?, ?, ?, ?, ?)
    ");

    $inserted = 0;

    foreach ($rows as $i => $row) {
        if ($i === 1) continue;

        $values = array_values($row);

        $nombre = trim($values[$map['nombre']] ?? '');
        $email = trim($values[$map['email']] ?? '');
        $telefono = trim($values[$map['telefono']] ?? '');
        $deuda = floatval($values[$map['deuda']] ?? 0);
        $fechaRaw = $values[$map['fecha_mora']] ?? null;

        if (!$nombre || !filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

        $fecha = is_numeric($fechaRaw)
            ? Date::excelToDateTimeObject($fechaRaw)->format('Y-m-d')
            : date('Y-m-d', strtotime($fechaRaw));

        $stmt->execute([$nombre, $email, $telefono, $deuda, $fecha]);
        $inserted++;
    }

    $db->commit();

    echo json_encode([
        "success" => true,
        "inserted" => $inserted
    ]);

} catch (Exception $e) {

    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }

    http_response_code(400);
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}