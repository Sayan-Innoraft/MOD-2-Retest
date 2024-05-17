<?php

$request = $_SERVER['REQUEST_URI'];
// Routes to the files.
switch ($request) {
  case '':
  case '/':
    require '../dashboard.php';
    break;
  case '/add':
    require '../add.php';
    break;
  case '/login':
    require '../admin_login.php';
    break;
  case '/logout':
    require '../logout.php';
    break;
  default:
    echo '<h1>404 Not Found path ' . $request . '</h1>';
}
