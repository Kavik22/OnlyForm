<?php
namespace App\Controllers;

use App\Models\UpdateData;
use App\Models\User;
use App\Utils\Helpers;

require_once __DIR__ . '/../Models/UpdateData.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Utils/Helpers.php';


$_SESSION['validation'] = [];

$update_data = new UpdateData($_POST);
$update_data->validate($_SESSION['user']['id']);

if (!empty($_SESSION['validation'])) {
  $update_data->saveOldValues();
  Helpers::redirect('/src/Views/Secret.php');
}


$user = new User();
$user->updateUser($update_data, $_SESSION['user']['id']);



Helpers::redirect('/src/Views/Secret.php');


