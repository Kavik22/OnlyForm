<?php
namespace App\Controllers;

use App\Models\RegisterData;
use App\Utils\Helpers;
require_once __DIR__ . '/../Models/RegisterData.php';
require_once __DIR__ . '/../Utils/Helpers.php';


$_SESSION['validation'] = [];

$register_data = new RegisterData($_POST);
var_dump($_POST);
$register_data->validate();

if (!empty($_SESSION['validation'])) {
  Helpers::redirect('/src/Views/RegisterForm.php');
}


