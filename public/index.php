<?php
require '../vendor/autoload.php';

$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/blog', 'post/index', 'blog')
    ->get('/blog/category', 'category/show', 'category')
    ->run();
// $router->get(string $url, string $vue, string $nom)
