<?php

use PHPMailer\PHPMailer\PHPMailer;
require_once 'Query.php';
require_once 'creds.php';
require 'vendor/autoload.php';

// Connects to database.
if(Query::connect()){

  // Gets the healthy snacks.
  $healthySnacks = Query::getSnacks('healthy');
  $healthySnacks_arr = null;

  // Creates an array of snacks.
  while($healthySnack = mysqli_fetch_assoc($healthySnacks)) {
    $healthySnacks_arr[] = $healthySnack;
  }

  // Gets the healthy snacks.
  $unhealthySnacks = Query::getSnacks('unhealthy');
  $unhealthySnacks_arr = null;

  // Creates an array of snacks.
  while($unhealthySnack = mysqli_fetch_assoc($unhealthySnacks)) {
    $unhealthySnacks_arr[] = $unhealthySnack;
  }
  $totalPrice = 0;
  if(isset($_POST['add'])){
    if(isset( $_POST['selected_id']) && sizeof($_POST['selected_id']) >= 1){
      $selected_ids = $_POST['selected_id'];
      $quantities = $_POST['selected_quantity'];
      $selected_snacks = null;

      foreach ($selected_ids as $selected_id) {
        if(!ctype_digit($selected_id)){
          echo 'ID should be an integer';
        }
        if($snack = Query::getSnackById($selected_id)){
          $selected_snacks[] = $snack;

          // Checks if the selected id is valid or not.
          if($quantities[$selected_id] === '' && !ctype_digit($quantities[$selected_id])){
            header('location: /');
          }
          if($quantities[$selected_id] > 0 && $quantities[$selected_id] <=5){
            if($quantities[$selected_id] < $snack['quantity']){
              $totalPrice += $quantities[$selected_id] * $snack['price'];
            }else{
              echo 'Available quantity should be less than available quantity' . '<br>';
            }
          }else{
            echo 'Selected quantity should be between 1 and 5' . '<br>';
          }
        }
      }
    }else{
      echo 'Please select at-least an item';
    }
  }
}else{
  echo 'Connection Error';
}
if(isset($_POST['send'])){
  $name = htmlentities($_POST['name']);
  $email = $_POST['email'];
  $contact = htmlentities($_POST['number']);
  if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/",
    $email) && strlen($email) > 100){
    echo 'Please enter a valid email address';
  }
  if( strlen($name) > 100){
    echo 'Name should be less than 100 characters';
  }
  if(!preg_match("/^[6-9]\d{9}$/",$contact) && strlen($contact) !== 10){
    echo 'Please enter a valid contact number';
  }
  $mail = new PHPMailer(true);

//Configure an SMTP
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = $username;
  $mail->Password = $password;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port = 465;
  $mail->SMTPAuth = true;
// Sender information
  $mail->setFrom('sayan.manna@innoraft.com', 'Sayan');

// Multiple recipient email addresses and names
// Primary recipients
  $mail->addAddress('sayanmanna7631@gmail.com', 'Sayan Manna');

  $mail->isHTML(false);

  $mail->Subject = 'PHPMailer SMTP test';

  $mail->Body    = "Customer Name: {$_POST['name']}\nEmail address: {$_POST['email']}\nContact Number:{$_POST['number']}\nTotal Cost: $totalPrice";

// Attempt to send the email
  if (!$mail->send()) {
    echo 'Email not sent. An error was encountered: ' . $mail->ErrorInfo;
  } else {
    header('Location: /');
  }

  $mail->smtpClose();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <style>
    h2{
      cursor: pointer;
    }
  </style>
</head>
<body class="bg-success-subtle">
<div class="container">
<a class="btn btn-primary mt-3" href="/login">Login as Admin</a>
  <?php
  if(isset($_SESSION['username'])){
    ?>
    <a href="/add">Add snacks</a>
    <a href="/logout">Logout</a>
  <?php
  }
  ?>
  <form  action="" method="post">
    <div class="container mt-5 d-flex justify-content-between text-center">
      <div class="container col-5">
        <h2 id="healthy">Healthy Snacks</h2>
        <table id="tbl-h" class="table table-success table-bordered table-hover
       visually-hidden mt-3">
          <thead>
          <tr>
            <th>Select</th>
            <th>Name</th>
            <th>Price</th>
            <th>Available Quantity</th>
            <th>Choose Quantity</th>
          </tr>
          </thead>
          <tbody>
          <?php
          foreach ($healthySnacks_arr as $healthySnack) {
            ?>
            <tr>
              <td><label>
                  <input class="form-check-input" type="checkbox"
                         name="selected_id[]"
                         value="<?=$healthySnack['id'] ?>">
                </label></td>
              <td><?=$healthySnack['snack_name'] ?></td>
              <td><?=$healthySnack['price'] ?></td>
              <td><?=$healthySnack['quantity'] ?></td>
              <td><label>
                  <input class="input-group" type="number"
                         name="selected_quantity[<?=$healthySnack['id'] ?>]">
                </label></td>
            </tr>
            <?php
          }
          ?>
          </tbody>
        </table>
      </div>
      <div class="container col-5">
        <h2 id="unhealthy">Unhealthy Snacks</h2>
        <table id="tbl-u" class="table table-success table-bordered table-hover
       visually-hidden mt-3">
          <thead>
          <tr>
            <th>Select</th>
            <th>Name</th>
            <th>Price</th>
            <th>Available Quantity</th>
            <th>Choose Quantity</th>
          </tr>
          </thead>
          <tbody>
          <?php
          foreach ($unhealthySnacks_arr as $unhealthySnack) {
            ?>
            <tr>
              <td><label>
                  <input class="form-check-input" type="checkbox"
                         name="selected_id[]"
                         value="<?=$unhealthySnack['id'] ?>">
                </label></td>
              <td><?=$unhealthySnack['snack_name'] ?></td>
              <td><?=$unhealthySnack['price'] ?></td>
              <td><?=$unhealthySnack['quantity'] ?></td>
              <td><label>
                  <input class="input-group" type="number"
                         name="selected_quantity[<?=$unhealthySnack['id'] ?>]">
                </label></td>
            </tr>
            <?php
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
    <button class="btn btn-primary" name="add" type="submit">Add</button>
  </form>
  <?php
  if(isset($totalPrice) && $totalPrice > 0){
    ?>
    <div class="container">
      <form action="" method="post" class="form-control">
        <label>
          <input type="text" name="name" placeholder="Name">
        </label>
        <label>
          <input type="email" name="email" placeholder="Email address">
        </label>
        <label>
          <input type="number" name="number" placeholder="Contact Number">
        </label>
        <input class="btn btn-primary" type="submit" name="send">
      </form>
    </div>
    <?php
  }
  ?>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="scripts/toggle.js"></script>
</body>
</html>
