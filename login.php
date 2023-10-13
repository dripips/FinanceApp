<?php

require 'config/database.php';
require 'includes/functions.php';

session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (loginUser($pdo, $username, $password)) {
            echo 'Успешный вход.';
            exit();
        } else {
            echo 'Ошибка входа.';
            exit();
        }
    } elseif (isset($_POST['register'])) {
      $username = $_POST['username'];
      $password = $_POST['password'];

      $existingUser = isUserExists($pdo, $username);

      if ($existingUser) {
          echo 'Пользователь с таким именем уже существует.';
          exit();
      }

      if (registerUser($pdo, $username, $password)) {
          echo 'Успешная регистрация.';
          exit();
      } else {
          echo 'Ошибка при регистрации.';
          exit();
      }
  }
}
?>

<?php include 'templates/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-3">FinanceApp</h5>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Вход</h2>
                </div>
                <div class="card-body">
                    <form id="login-form" method="POST">
                        <div class="form-group">
                            <label for="username">Имя пользователя:</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль:</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <input type="hidden" name="login" value="">
                        <button type="submit" name="login" class="btn btn-primary w-100">Войти</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Регистрация</h2>
                </div>
                <div class="card-body">
                    <form id="register-form" method="POST">
                        <div class="form-group">
                            <label for="new-username">Имя пользователя:</label>
                            <input type="text" name="username" id="new-username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="new-password">Пароль:</label>
                            <input type="password" name="password" id="new-password" class="form-control" required>
                        </div>
                        <input type="hidden" name="register" value="">
                        <button type="submit" name="register" class="btn btn-success w-100">Зарегистрироваться</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  $('#login-form, #register-form').submit(function(e) {
      e.preventDefault();
      var form = $(this);
      var url = form.attr('action');
      $.ajax({
          type: 'POST',
          url: url,
          data: form.serialize(),
          success: function(data) {
              Toastify({
                  text: data,
                  duration: 3000,
                  gravity: 'top',
                  position: 'right',
              }).showToast();
              if (data == "Успешный вход." || data == "Успешная регистрация.") {
                window.location.replace("/");
                form.trigger('reset');
              }
          }
      });
  });
});
</script>
<?php include 'templates/footer.php'; ?>
