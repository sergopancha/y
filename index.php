<?php

require "settings.php";
require "class/pdo.php";
require "class/render.php";
require "class/user.php";
require "class/route.php";

$route = new Route;
$route->addItems(['theme', 'message', 'add']);
$connect = new Database;
$user = new User($connect);
$render = new Render($connect, $user);
$path = htmlentities($_GET['mode']);

if ( !$route->check($path)) {
    $render->showThemesList();
}
else {
    if (!class_exists($path)){
        include "class/".$path.'.php';
    }
    else {
        $modeClass = new $path;
    }

    get_class($modeClass);
}
