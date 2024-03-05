<?php

require "settings.php";
require "class/pdo.php";
require "class/template.php";
require "class/user.php";
require "class/route.php";

$route = new Route;
$route->addItems(['themeslist', 'theme', 'message', 'add']);
$connect = new Database;
$user = new User($connect);

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
