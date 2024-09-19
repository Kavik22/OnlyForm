<?php
namespace App\Controllers;

use App\Models\RegisterData;
use App\Models\User;
use App\Utils\Helpers;

require_once __DIR__ . '/../Models/RegisterData.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Utils/Helpers.php';

$_SESSION['validation'] = [];


$update_data = new RegisterData($_POST);
$update_data->validate();
$update_data->saveOldValues();



if (!empty($_SESSION['validation'])) {
  Helpers::redirect('/src/Views/RegisterForm.php');
}


$user = new User();
$id = $user->addUser(...$update_data->getValues());

$_SESSION['user']['id'] = $id;
Helpers::redirect('/src/Views/Secret.php');


