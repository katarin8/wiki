<?php require "../page/db.php";
require "../page/functions.php";
if(checkisadmin($connection)){
  header("Location: ../admin");
  exit();
}
$data = $_POST;
$user = post($connection, $_GET['users_id']);
$users_idt = $_GET['users_id'];
if($_SESSION["id"] != $users_idt){
  header("Location: account.php");
  exit();
}
if ($_GET['update'] == 1)
{
$data['users_name'] = $user['users_name'];
$data['users_surname'] = $user['users_surname'];
$data['users_email'] = $user['users_email'];
$data['users_sex'] = $user['users_sex'];
$data['users_birth_date'] = $user['users_birth_date'];
$data['users_city'] = $user['users_city'];
$update = 2;
}

if (isset($data['save']))
{
  $errors = array();
  if (trim($data['users_name']) == '')
  {
    $errors[] = 'Введите имя!';
  }

  if (trim($data['users_surname']) == '')
  {
    $errors[] = 'Введите фамилию!';
  }

  if (trim($data['users_email']) == '')
  {
    $errors[] = 'Введите Email!';
  }

  // if (trim($data['users_sex']) == '')
  // {
  // 	$errors[] = 'Введите пол!';
  // }

  if (trim($data['users_birth_date']) == '')
  {
    $errors[] = 'Введите дату рождения!';
  }

  if (trim($data['users_city']) == '')
  {
    $errors[] = 'Введите город!';
  }

  if (empty($errors))
  {
    user_data_edit($connection, $_GET['users_id'], $data['users_name'], $data['users_surname'],
        $data['users_email'],  $data['users_sex'],  $data['users_birth_date'],  $data['users_city']);
    header("Location: account.php?users_id=$users_idt");
  }
  else
  {
    echo '<div class="">'.array_shift($errors).'</div>';
  }
}
?>
<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="../css/account.css">
  <link rel="stylesheet" href="../libs/bootstrap/bootstrap-grid-3.3.1.min.css" />
</head>

<body>
  <?php require("header.php"); ?>

<div class="container">
  <div class="col-md-12">
    <div class="row">
      <div class="edit-data">
        <h2>Редактирование данных личного кабинета</h2>
        <form action = "account_update.php?users_id=<?php echo $_GET['users_id']; ?>" method = "POST">
        <p><span>Имя</span><br><input type = "text" name = "users_name" value = "<?php echo @$data['users_name']; ?>" required></p>
        <p><span>Фамилия</span><br><input type = "text" name = "users_surname" value = "<?php echo @$data['users_surname']; ?>" required></p>
        <p><span>Email</span><br><input type = "email" name = "users_email" value = "<?php echo @$data['users_email']; ?>" required></p>
        <div class="sex">
          <p>Пол:</p>
          <p><input type="radio" name="users_sex" value="man" required>Мужчина</p>
          <p><input type="radio" name="users_sex" value="woman">Женщина</p>
        </div>
        <p><span>Дата рождения</span><br><input type = "date" name = "users_birth_date" value = "<?php echo @$data['users_birth_date']; ?>" required></p>
        <p><span>Город</span><br><input type = "text" name = "users_city" value = "<?php echo @$data['users_city']; ?>" required></p>
        <button type = "submit" name="save">Сохранить</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="../js/main.js"></script>
</body>
