<?php

namespace App\Models;
use App\Models\User;
require_once __DIR__ . '/../Models/User.php';

class RegisterData
{
  public $username;
  public $email;
  public $phone_number;
  public $password;
  public $confirm_password;
  public $smart_token;

  public function __construct($data)
  {
    $this->username = htmlspecialchars($data['username'], ENT_QUOTES, 'UTF-8');
    $this->email = htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8');
    $this->phone_number = htmlspecialchars($data['phone_number'], ENT_QUOTES, 'UTF-8');
    $this->password = htmlspecialchars($data['password'], ENT_QUOTES, 'UTF-8');
    $this->confirm_password = htmlspecialchars($data['confirm_password'], ENT_QUOTES, 'UTF-8');
    $this->smart_token = $data['smart-token'];
  }

  public function saveOldValues()
  {
    $_SESSION['old']['username'] = $this->username;
    $_SESSION['old']['email'] = $this->email;
    $_SESSION['old']['phone_number'] = $this->phone_number;
  }

  public function validate()
  {
    $this->validate_empty();
    $this->validate_email();
    $this->validate_phone_number();
    $this->validate_password();
    $this->validate_uniqueness();
    $this->check_captcha();
  }

  public function validate_empty()
  {
    foreach (['username', 'email', 'password', 'confirm_password'] as $field) {
      if (empty($this->{$field})) {
        $_SESSION['validation'][$field] = 'Это обязательное поле';
      }
    }
  }
  public function validate_email()
  {
    if (empty($_SESSION['validation']['email']) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['validation']['email'] = 'Неверный формат почты';
    }
  }
  public function validate_phone_number()
  {
    if (!is_numeric($this->phone_number) || strlen($this->phone_number) != 11) {
      $_SESSION['validation']['phone_number'] = 'Номер должен состоять из 11 цифр';
    }
  }

  public function validate_uniqueness()
  {
    $user = new User();
    $current_user = $user->getUser('email', $this->email);
    if ($current_user) {
      $_SESSION['validation']['email'] = 'Пользователь с такой почтой уже существует';
    }
    $current_user = $user->getUser('phone_number', $this->phone_number);
    if ($current_user) {
      $_SESSION['validation']['phone_number'] = 'Пользователь с этим номером уже существует';
    }
  }
  public function validate_password()
  {
    if (empty($_SESSION['validation']['password']) && ($this->password !== $this->confirm_password)) {
      $_SESSION['validation']['password'] = 'Пароли не совпадают';
    }
  }
  function check_captcha()
  {
    $ch = curl_init("https://smartcaptcha.yandexcloud.net/validate");
    $args = [
      "secret" => SMARTCAPTCHA_SERVER_KEY,
      "token" => $this->smart_token,
      "ip" => $_SERVER['REMOTE_ADDR'],
    ];
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
      $_SESSION['validation']['smart-token'] = "Allow access due to an error: code=$httpcode; message=$server_output\n";
    }


    $resp = json_decode($server_output);
    if ($resp->status !== "ok") {
      $_SESSION['validation']['smart-token'] = "Произошла ошибка при проверке капчи";
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