<?php
use App\Utils\Helpers;
require_once __DIR__ . '/../Utils/Helpers.php';


if (!isset($_SESSION['user']['id'])) {
  Helpers::redirect('/index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="../../assets/app.css">
  <title>Secret</title>
</head>

<body>
  <div class="card">
    <h1 class="mb-3">Вы вошли в систему</h1>
    <div>
      <?php
      use App\Models\User;
      require_once __DIR__ . '/../Models/User.php';
      $user = new User();
      $current_user = $user->getUser('id', $_SESSION['user']['id']);
      ?>
      <form action="/src/Controllers/update.php" method="POST" class="mb-3">
        <div class="mb-3">
          <label for="username" class="form-label">Имя пользователя</label>
          <input type="text" class="form-control" name="username" placeholder="Введите новое имя пользователя" value="<?php Helpers::mayBeHasErrorForSecret('username', $current_user['username'])?>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Электронная почта</label>
          <input type="email" class="form-control" name="email" placeholder="Введите новую электронную почту" value="<?php Helpers::mayBeHasErrorForSecret('email', $current_user['email'])?>
        </div>
        <div class="mb-3">
          <label for="phone_number" class="form-label">Номер телефона</label>
          <input type="test" class="form-control" name="phone_number" placeholder="Введите новый номер телефона"
            maxlength="11" value="<?php Helpers::mayBeHasErrorForSecret('phone_number', $current_user['phone_number'])?>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Пароль</label>
          <input type="password" class="form-control" name="password" placeholder="Введите новый пароль">
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Подтверждение пароля</label>
          <input type="password" class="form-control" name="confirm_password" placeholder="Подтвердите новый пароль">
        </div>
        <?php if (isset($_SESSION['password'])){
          echo '<div class="alert alert-success" role="alert">Пароль успешно изменен!</div>';
          unset($_SESSION['password']);
        }?>
        <div class="d-flex justify-content-between">
          <a href="/src/Controllers/logout.php" class="btn btn-danger">Выйти</a>
          <button type="submit" class="btn btn-primary">Обновить</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>