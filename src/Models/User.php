<?php
namespace App\Models;


require_once('../../config.php');


class User
{
  private ?\PDO $connection = null;

  public function __construct()
  {
    $this->connection = self::getPDO();
  }

  public static function getPDO(): \PDO
  {
    try {
      return new \PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8;dbname=" . DB_NAME, DB_USER, DB_PASS);
    } catch (\PDOException $e) {
      die('Database connection failed: ' . $e->getMessage());
    }
  }

  public function createTable()
  {
    $sql = "CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(11) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
    )";
    $stmt = $this->connection->prepare($sql);
    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      die('Error adding user: ' . $e->getMessage());
    }
  }

  public function addUser($username, $email, $phone_number, $password)
  {
    $sql = "INSERT INTO users (username, email, phone_number, password) VALUES (:username, :email, :phone_number, :password)";
    $params = [
      'username' => $username,
      'email' => $email,
      'phone_number' => $phone_number,
      'password' => password_hash($password, PASSWORD_DEFAULT),
    ];
    $stmt = $this->connection->prepare($sql);
    try {
      $stmt->execute($params);
      return $this->connection->lastInsertId();
    } catch (\PDOException $e) {
      die('Error adding user: ' . $e->getMessage());
    }
  }

  public function getUser($type, $value)
  {
    $sql = "SELECT * FROM users WHERE ". $type . " = :" . $type;
    try {
      $stmt = $this->connection->prepare($sql);
      $stmt->execute([$type => $value]);
      $user = $stmt->fetch(\PDO::FETCH_ASSOC);

      if ($user) {
        return $user;
      }
    } catch (\PDOException $e) {
      die('Error adding user: ' . $e->getMessage());
    }
  }

  public function updateUser($update_data, $id){
    $data_to_update = [
      'username' => $update_data->username,
      'email' => $update_data->email,
      'phone_number' => $update_data->phone_number,
      'password' => $update_data->password,
    ];
    
    $sql = "UPDATE users SET ";
    $set_parts = [];
    $params = [];
    
    foreach ($data_to_update as $column => $value) {
      if (strlen($value) == 0) {
        continue;
      }
      $set_parts[] = "$column = :$column";
      if ($column == 'password') {
        $params[":$column"] = password_hash($value, PASSWORD_DEFAULT);
        $_SESSION['password'] = 'changed';
      } else {
        $params[":$column"] = $value;
      }
    }
    
    
    $sql .= implode(", ", $set_parts);
    $sql .= " WHERE id = :id";
    $params[":id"] = $id;

    $stmt = $this->connection->prepare($sql);
    
    $stmt->execute($params);
  }

  
}