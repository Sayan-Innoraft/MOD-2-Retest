<?php

require_once 'Query.php';

session_start();
if(!isset($_SESSION['username'])){
  header('Location: /login');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Title</title>
</head>
<body>

</body>
</html>

