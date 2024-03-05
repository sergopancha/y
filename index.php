<?php

require "settings.php";
require "class/pdo.php";
require "class/template.php";
require "class/user.php";
require "class/route.php";

$route = new Route;

// перечисляем все допустимые роуты
$route->addItems(['themeslist', 'theme', 'message', 'add']);

$connect = new Database;
$user = new User($connect);

// целевой класс получаем в GET запросе через параметр mode
// по умолчанию грузим список тематик
$path = empty($_GET['mode'])? 'themeslist': trim(htmlentities($_GET['mode']));

if ( !$route->check($path)) {
    $path = 'tememslist';
}
else {
    if (!class_exists($path)){
        include "class/".$path.'.php';
    }

    $modeClass = new $path($connect, $user);
}
