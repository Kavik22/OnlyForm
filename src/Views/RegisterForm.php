<?php
namespace App\Views;

require_once '../Utils/Helpers.php';
require_once '../../config.php';

use App\Utils\Helpers;
if (isset($_SESSION['user']['id'])) {
  Helpers::redirect('/src/Views/Secret.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="../../assets/app.css">
  <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>

</head>

<body>
  <div class="card">
    <h2>Регистрация</h2>
    <form action="/src/Controllers/register.php" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Имя пользователя</label>
        <input type="text" class="form-control" name="username" placeholder="Введите имя пользователя"
        <?php Helpers::mayBeHasError('username'); ?>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Электронная почта</label>
        <input type="email" class="form-control" name="email" placeholder="Введите электронную почту"
        <?php Helpers::mayBeHasError('email'); ?>
      </div>
      <div class="mb-3">
        <label for="phone_number" class="form-label">Номер телефона</label>
        <input type="test" class="form-control" name="phone_number" placeholder="Введите номер телефона"
        maxlength="11" <?php Helpers::mayBeHasError('phone_number'); ?>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input type="password" class="form-control" name="password" placeholder="Введите пароль"
        <?php Helpers::mayBeHasError('password'); ?>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Подтверждение пароля</label>
        <input type="password" class="form-control" name="confirm_password" placeholder="Подтвердите пароль"
        <?php Helpers::mayBeHasError('confirm_password'); ?>
      </div>
      <div class="mb-3">
        <div
            id="captcha-container"
            class="smart-captcha"
            data-sitekey="<?php echo CLIENT_ID?>"
        ></div>
        <?php Helpers::displayError('smart-token')?> 
      </div>
      <div class="d-flex justify-content-between">
        <a href="/src/Controllers/logout.php" class="btn btn-danger">Назад</a>
        <button type="submit" class="btn btn-primary">Регистрация</button>
      </div>
    </form>
  </div>
</body>
</html>