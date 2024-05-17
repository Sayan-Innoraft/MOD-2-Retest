<?php

require 'creds.php';

/**
 * Query class initiates a database connection to mysql database server, adds
 * new users, resets passwords, checks if the username is already in database
 * and returns password to a specific username.
 */
class Query {

  /**
   * Database connection
   */
  private static mixed $conn = null;

  /**
   * Connects to mysql database. If the connection is successful, returns true
   * else returns false.
   *
   * @return bool
   *   If connection is successful , returns true, else returns false.
   */
  static function connect(): bool {
    global $server_host, $db_username, $db_password, $dbname ;

    // Connection.
    self::$conn = new mysqli(
      $server_host,
      $db_username,
      $db_password,
      $dbname
    );

    // For checking if connection is successful or not.
    return !self::$conn->connect_error;
  }


  /**
   * Checks if the username is already exists in the database or not.
   *
   * @param $username
   *   Username of the user.
   *
   * @return bool
   *   Returns true if user already exists in the database, returns false if
   *   username doesn't exist in database.
   */
  static  function checkUser($username):bool {
    $sql = 'select password from admin where username = ' . "'$username'";
    $res = mysqli_query(self::$conn, $sql);
    return !mysqli_fetch_assoc($res) == null;
  }

  /**
   * Gets password of the user. Returns password as a string. If username
   * doesn't exist in database, returns false.
   *
   * @param $name
   *   Username of the user.
   *
   * @return string|null
   *   Returns password as string. returns null if username doesn't exist in
   *   database.
   */
  static function getUserPassword($name): ?string {
    if(self::checkUser($name)){
      $sql = 'select password from admin where username = ' . "'$name'";
      $res = mysqli_query(self::$conn, $sql);
      return mysqli_fetch_assoc($res)['password'];
    }
    return null;
  }

  /**
   * Takes category as parameter and returns an array of snacks
   *
   * @param string $category
   *   Snack category.
   *
   * @return \mysqli_result|bool
   *   Array of snack.
   */
  static public function getSnacks(string $category):mysqli_result|bool {
    $sql = 'select * from grocery where category = ' . "'$category'";
    return mysqli_query(self::$conn, $sql);
  }

  /**
   * Takes snack id as parameter and returns snack.
   *
   * @param int $id
   *   Id of snack.
   *
   * @return false|array|null
   *   Return snack as an array.
   */
  static public function getSnackById(int $id):false|array|null {
    $sql = 'select * from grocery where id = ' . "'$id'";
    return mysqli_fetch_assoc(mysqli_query(self::$conn, $sql));
  }

  static public function addSnacks(string $name, int $price, int $quantity,
                                   string $category):bool {
    $sql = "INSERT INTO grocery(snack_name, price, quantity, category) VALUES ($name, $price, $quantity, $category)";
    return mysqli_query(self::$conn, $sql);
  }

}