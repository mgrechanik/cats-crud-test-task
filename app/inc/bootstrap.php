<?php

declare(strict_types=1);

define("APP_DIR", __DIR__ . '/../');

set_exception_handler(function (Throwable $exception) {
    error_log($exception->getMessage());
    // Показываю страницу ошибки
    print view('layout.php', ['title' => 'Страница ошибки', 'content' => $exception->getMessage()]);
    die();
});

/**
 * Экранирование
 * @param string $input
 * @return string
 */
function html(string $input)
{
    return htmlentities($input, ENT_QUOTES, 'UTF-8');
}

/**
 * Рендер вьюхи
 * @param string $template
 * @param array $params
 * @return string
 * @throws Exception
 */
function view(string $template, array $params = []): string
{
    $viewDir = APP_DIR . 'views/';
    $file = $viewDir . $template;
    if (!file_exists($file)) {
        throw new Exception('view файл' . $template . 'не найден');
    }
    ob_start();
    extract($params);
    include $file;
    $templateContent = ob_get_contents();
    ob_end_clean();
    return $templateContent;
}

/**
 * Редирект
 * @param string $url
 * @return void
 */
function redirect(string $url): void
{
    header("Location: " . $url);
    die();
}
