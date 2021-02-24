<?php
//Подключение к базе данных
require_once 'app/pdo_ini.php';

session_start();
//session_destroy();
//session_unset();

function parseUrl($url)
{
    $data = [];
    $data = parse_url($url);
    $path = trim($data['path'], '/');
    return explode('/', $path);
}

$url = parseUrl($_SERVER['REQUEST_URI']);
//print_r($url);
$view = ($url[0]) ? $url[0] : 'main';
//print_r($view);

//$view = ($url[0]) ? $url : '/main';

if ($_SESSION['user']) {
    $user_id = $_SESSION['user']['id'];
    $user_login = $_SESSION['user']['login'];
} else {
    $login = '';
}

switch ($view) {
    case 'main':
        ob_start();
        $user_id ? include_once 'app/views/lists.php' : include_once 'app/views/main.php';
        $content = ob_get_clean();
        break;

    case 'login':
        // Запись инклуда в переменную
        ob_start();
        include_once 'app/views/login.php';
        $content = ob_get_clean();
        break;

    case 'logout':
        // Запись инклуда в переменную
        ob_start();
        include_once 'app/views/logout.php';
        $content = ob_get_clean();
        break;

    case 'lists':
        if (!empty($user_id)) {
            // Запись инклуда в переменную
            ob_start();
            include_once 'app/views/lists.php';
            $content = ob_get_clean();
        } else {
            header('Location: app/views/login');
        }
        break;

    case 'list':
        if (!empty($user_id)) {
            // Запись инклуда в переменную
            ob_start();
            include_once 'app/views/list.php';
            $content = ob_get_clean();
        } else {
            header('Location: app/views/login');
        }
        break;

    case 'register':
        ob_start();
        include_once 'app/views/regist.php';
        $content = ob_get_clean();
        break;

    default:
        http_response_code(404);
        ob_start();
        include_once 'app/views/404.php';
        $content = ob_get_clean();
}

require_once 'app/views/layout.php';
