<?php

require_once __DIR__ . "/../config/cors.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];



//limpiar ruta correctamente
$path = trim($uri,"/");
$segments = explode("/",$path);

//validar que empiece con /api
if(isset($segments[0]) && $segments[0] === "api"){
    $route = $segments[1] ?? "";
}else{
    $route = "";
}

if ($route === "login" && $method === "GET") {
    http_response_code(405);
    echo json_encode([
        "error" => "Método no permitido",
        "hint" => "Usar POST"
    ]);
    exit;
}



$routes = [

    "POST" => [
        "login" => "routes/login.php"
    ],
    
];

if (isset($routes[$method][$route])) {
    require_once __DIR__ . "/../" . $routes[$method][$route];
    exit();
}

http_response_code(404);
echo json_encode([
    "error" => "Ruta no encontrada",
    "route" => $route,
    "method"=>$method,
    "uri" => $uri
]);

?>