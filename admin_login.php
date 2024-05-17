<?php

require 'Query.php';

if(Query::connect()){
  if(isset($_POST['login'])){
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    if( strlen($username) > 100){
      echo 'Name should be less than 100 characters';
    }
    if( strlen($password) > 100){
      echo 'Password should be less than 100 characters';
    }
    if (Query::checkUser($username)){
      if(Query::getUserPassword($username) === $password){
        session_start();
        $_SESSION['username'] = $username;
        header('Location: /');
      }else{
        echo 'Wrong password';
      }
    }else{
      echo '';
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body class="bg-success-subtle">
<div class="container col-5 mt-5">
  <h1>Login as Admin</h1>
  <form action="" method="post">
      <label>
        <input class="form-control" type="text" name="username" placeholder="Username">
      </label>
      <label>
        <input class="form-control" type="password" name="password"
               placeholder="Password">
      </label>
      <input value="Login" class="btn btn-primary" type="submit" name="login">
  </form>
</div>
</body>
</html>
