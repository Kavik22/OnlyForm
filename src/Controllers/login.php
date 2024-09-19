<?php
namespace App\Controllers;

use App\Models\LoginData;
use App\Models\User;
use App\Utils\Helpers;

require_once __DIR__ . '/../Models/LoginData.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Utils/Helpers.php';


$_SESSION['validation'] = [];


$login_data = new LoginData($_POST);
$login_data->saveOldValues();
$login_data->validate();

if (!empty($_SESSION['validation'])) {
  Helpers::redirect('/src/Views/LoginForm.php');
}

$user = new User();

$current_user = $user->getUser('email', $login_data->value);
if (!$current_user) {
  $current_user = $user->getUser('phone_number', $login_data->value);
} 
if (!$current_user) {
  $_SESSION['validation']['value'] = 'Такого пользователя не существует';
  Helpers::redirect('/src/Views/LoginForm.php');
}

if ($current_user && password_verify($login_data->password, $current_user['password'])) {
  $_SESSION['user']['id'] = $current_user['id'];
  Helpers::redirect('/src/Views/Secret.php');
} else {
  $_SESSION['validation']['password'] = 'Неверный пароль';
  Helpers::redirect('/src/Views/LoginForm.php');
}



