<?php
require "db.php";
require "functions.php";
if(isset($_SESSION["id"])){
  header("Location: account.php");
    exit();
}
$arr = do_auth($connection, $_POST);
?>
<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="../css/register.css">
  <link rel="stylesheet" href="../css/fonts.css">
  <link rel="stylesheet" href="../libs/bootstrap/bootstrap-grid-3.3.1.min.css" />
</head>

<title>Авторизация</title>

<body>
<script type="text/javascript" src="../js/main.js">

</script>

  <div class="container">
    <div class="col-md-12">
      <div class="row">
        <div class="log_form">
          <div class="logo">
            <a href="../"><img src="../img/logo.png" alt=""></a>
          </div>
          <?php
          if(!empty($arr['message'])){
          echo  '<div class="error_message">'.$arr['message'].'</div>';
          }
        ?>
          <form class="reg" method="post">
              <input type="email" class="form-input" name="email" placeholder="Email" required value = "<?php echo $arr['email']?>">
              <input type="password" class="form-input" name="password" placeholder="Пароль" required>
              <p class="qa">Ещё нет аккаунта? <a href="register.php">Регистрируйся</a> прямо сейчас!</p>
              <input type="submit" name="do_login" value="Войти">
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
