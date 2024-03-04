<?php

require "settings.php";
require "class/pdo.php";
require "class/render.php";

$connect = new Database;
$render = new Render($connect);
$render->showThemesList();