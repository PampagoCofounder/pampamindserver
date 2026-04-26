<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function validarJWT()
{
    $headers = getallheaders();

    if (!isset($headers["Authorization"])) {
        http_response_code(401);
        echo json_encode(["error" => "Token requerido"]);
        exit();
    }

    $token = str_replace("Bearer ", "", $headers["Authorization"]);

    try {
        $decoded = JWT::decode(
            $token,
            new Key("mi_clave_super_secreta_de_32_caracteres_minimo_2026", "HS256")
        );

        return (array) $decoded;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["error" => "Token inválido"]);
        exit();
    }
}
