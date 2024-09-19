<?php

namespace App\Models;

class LoginData
{
  public $value;
  public $password;
  public $smart_token;

  public function __construct($data)
  {
    $this->value = htmlspecialchars($data['value'], ENT_QUOTES, 'UTF-8');
    $this->password = htmlspecialchars($data['password'], ENT_QUOTES, 'UTF-8');
    $this->smart_token = $data['smart-token'];
  }

  public function saveOldValues()
  {
    $_SESSION['old']['value'] = $this->value;
  }

  public function validate()
  {
    $this->validate_value();
    $this->validate_password();
    $this->check_captcha();
  }

  public function validate_value()
  {
    if (empty($this->value) && (!filter_var($this->value, FILTER_VALIDATE_EMAIL) || strlen($this->value) != 11)) {
      $_SESSION['validation']['value'] = 'Недопустимое значение для поля';
    }
  }

  public function validate_password()
  {
    if (empty($this->password)) {
      $_SESSION['validation']['password'] = 'Это обязательное поле';
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
      $this->value,
      $this->password,
    ];
  }
}