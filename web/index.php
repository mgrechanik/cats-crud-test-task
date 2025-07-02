<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require __DIR__ . '/../app/inc/bootstrap.php';

$route = $_GET['r'] ?? 'main';

$routes = include APP_DIR . 'config/routes.php';

if (!isset($routes[$route])) {
    throw new Exception('Страница не найдена');
}

$handlerClass = $routes[$route][0];
$handlerMethod = $routes[$route][1];
// Выполняю экшен и получаю его переменные, для лайаута
$params = call_user_func([new $handlerClass, $handlerMethod]);

print view('layout.php', ['title' => $params['title'] ?? '', 'content' => $params['content'] ?? '']);


