<?php

namespace App\Models;
use App\Models\User;
require_once __DIR__ . '/../Models/User.php';

class UpdateData
{
  public $username;
  public $email;
  public $phone_number;
  public $password;
  public $confirm_password;

  public function __construct($data)
  {
    $this->username = htmlspecialchars($data['username'], ENT_QUOTES, 'UTF-8');
    $this->email = htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8');
    $this->phone_number = htmlspecialchars($data['phone_number'], ENT_QUOTES, 'UTF-8');
    $this->password = htmlspecialchars($data['password'], ENT_QUOTES, 'UTF-8');
    $this->confirm_password = htmlspecialchars($data['confirm_password'], ENT_QUOTES, 'UTF-8');
  }

  public function saveOldValues()
  {
    $_SESSION['old']['username'] = $this->username;
    $_SESSION['old']['email'] = $this->email;
    $_SESSION['old']['phone_number'] = $this->phone_number;
  }

  public function validate($id)
  {
    $this->validate_email();
    $this->validate_phone_number();
    $this->validate_password();
    $this->validate_uniqueness($id);
  }

  public function validate_email()
  {
    if (!empty($this->email) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['validation']['email'] = 'Неверный формат почты';
    }
  }
  public function validate_phone_number()
  {
    if (!empty($this->phone_number) && (!is_numeric($this->phone_number) || strlen($this->phone_number) != 11)) {
      $_SESSION['validation']['phone_number'] = 'Номер должен состоять из 11 цифр';
    }
  }

  public function validate_uniqueness($id)
  {
    $user = new User();
    $current_user = $user->getUser('email', $this->email);
    if ($current_user && $current_user['id'] != $id) {
      $_SESSION['validation']['email'] = 'Пользователь с такой почтой уже существует';
    }
    $current_user = $user->getUser('phone_number', $this->phone_number);
    if ($current_user && $current_user['id'] != $id) {
      $_SESSION['validation']['phone_number'] = 'Пользователь с этим номером уже существует';
    }
  }
  public function validate_password()
  {
    if (!empty($this->password) && ($this->password !== $this->confirm_password)) {
      $_SESSION['validation']['password'] = 'Пароли не совпадают';
    }
  }

  public function getValues()
  {
    return [
      $this->username,
      $this->email,
      $this->phone_number,
      $this->password,
    ];
  }
}