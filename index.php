<?php
/** @var \PDO $pdo */
require_once 'app/db/pdo_ini.php';

session_start();

function parseUrl($url)
{
//    $data = [];
    $data = parse_url($url);
    $path = trim($data['path'], '/');
    return explode('/', $path);
}

$url = parseUrl($_SERVER['REQUEST_URI']);

$view = ($url[0]) ? $url[0] : 'main';

if ($_SESSION['user']) {
    $user_id = $_SESSION['user']['id'];
    $user_login = $_SESSION['user']['login'];
} else {
    $login = '';
}

// Routing
switch ($view) {
    case 'main': // main page
        ob_start();
        $user_id ? include_once 'app/views/lists.php' : include_once 'app/views/main.php';
        $content = ob_get_clean();
        break;

    case 'login': // authorization page
        ob_start();
        include_once 'app/views/login.php';
        $content = ob_get_clean();
        break;

    case 'logout': // logout page
        ob_start();
        include_once 'app/views/logout.php';
        $content = ob_get_clean();
        break;

    case 'lists': // page with user's lists
        if (!empty($user_id)) {
            ob_start();
            include_once 'app/views/lists.php';
            $content = ob_get_clean();
        } else {
            header('Location: app/views/login');
        }
        break;

    case 'list': // page with the tasks of the current list
        if (!empty($user_id)) {
            ob_start();
            include_once 'app/views/list.php';
            $content = ob_get_clean();
        } else {
            header('Location: app/views/login');
        }
        break;

    case 'register': // registration page
        ob_start();
        include_once 'app/views/regist.php';
        $content = ob_get_clean();
        break;

    default: // page 404
        http_response_code(404);
        ob_start();
        include_once 'app/views/404.php';
        $content = ob_get_clean();
}

require_once 'app/views/layout.php';
