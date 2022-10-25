<?php
require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Rewritting url to get pagination working.
if(isset($_GET['page']) && $_GET['page'] === '1') {
    
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if(!empty($query)) {
       $uri = $uri . '?' . $query;
    }
    
    http_response_code(301);
    header('Location ' . $uri);
    exit();
}

// Instanciating Router class and configuring routes.
$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'post/index', 'home')
    ->get('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
    ->get('/blog/[*:slug]-[i:id]', 'post/show', 'post')
    ->run();
// $router->get(string $url, string $vue, string $nom)

