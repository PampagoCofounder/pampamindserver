<?php

require_once __DIR__ . "/config/cors.php";

// preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . "/router/router.php";


?>