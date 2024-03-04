<?php

require "settings.php";
require "class/pdo.php";
require "class/render.php";
require "class/user.php";

$connect = new Database;
$user = new User($connect);
$render = new Render($connect, $user);
$render->showThemesList();