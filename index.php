<?php
require_once "altorouter.php";
$router = new AltoRouter;
header("Content-Type: application/json");
//routes
$router->map('GET|POST', '/samurai/ws/[*:st]/telops', 'pages/telops.php');
$router->map('GET|POST', '/samurai/ws/[*:st]/directories', 'pages/directories.php');
$router->map('GET|POST', '/samurai/ws/[*:st]/languages', 'pages/lang.php');
$router->map('GET|POST', '/samurai/ws/[*:st]/news', 'pages/news.php');
$router->map('GET|POST', '/samurai/ws/[*:st]/eshop_message/about', 'pages/about.php');
$match = $router->match(urldecode($_SERVER['REQUEST_URI']));
if ($match) {
    foreach ($match['params'] as &$param) {
        ${key($match['params'])} = $param;
    }
    require_once $match['target'];
} else {
    http_response_code(404);
    exit("not found");
}