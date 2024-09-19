<?php
namespace App\Utils;

session_start();

class Helpers
{
  public static function redirect(string $path)
  {
    header('Location: ' . $path);
    die();
  }

  public static function mayBeHasError(string $field_name){
    $array = [
      'Пользователь с такой почтой уже существует',
      'Пользователь с этим номером уже существует'
    ];
    $out = 'value=' . Helpers::getOldValue($field_name) . '>';
    if (isset($_SESSION['validation'][$field_name]) && in_array($_SESSION['validation'][$field_name], $array)){
      $out = '';
    }
    echo $out;
    self::displayError($field_name);
  }

  public static function mayBeHasErrorForSecret(string $field_name, $value){

    $out = '';
    if (!isset($_SESSION['validate'][$field_name])) {
      $out = $value;
    } else {
      $out = Helpers::getOldValue($_SESSION['validate']['phone_number']);
    }
    echo $out . '">';
    self::displayError($field_name);
  }

  public static function displayError(string $field_name){
    if (isset($_SESSION['validation'][$field_name])){
      $value = $_SESSION['validation'][$field_name];
      unset($_SESSION['validation'][$field_name]);
      echo '<small>' . $value . '</small>';
    }
  }


  public static function getOldValue($key){
    $value = $_SESSION['old'][$key] ?? '';
    unset($_SESSION['old'][$key]); 
    return $value;
  }
}