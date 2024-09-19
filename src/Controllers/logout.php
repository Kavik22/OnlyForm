<?php
namespace App\Controllers;

use App\Models\LoginData;
use App\Models\User;
use App\Utils\Helpers;

require_once __DIR__ . '/../Models/LoginData.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Utils/Helpers.php';


$_SESSION['user'] = [];

Helpers::redirect('/index.php');





