<?php
// Создание заявки на добавление статьи
function make_request_articles($connection, $arr)
{
  $musicians_creator = $_SESSION['id'];
  $musicians_experience = $arr['musicians_experience'];
  $musicians_instrument = $arr['musicians_instrument'];
  $musicians_genre = $_arr['musicians_genre'];
  $musicians_description = $arr['musicians_description'];
  $musicians_name = $arr['musicians_name'];
  $musicians_city = $arr['musicians_city'];
  $musicians_age = $arr['musicians_age'];
  $musicians_sex = $arr['musicians_sex'];
  if(!isset($arr['musicians_experience']) || !isset($arr['musicians_instrument']) || !isset($arr['musicians_genre'])
    || !isset($arr['musicians_name']) || !isset($arr['musicians_city']) || !isset($arr['musicians_age'])){
    $message = "Пожалуйста, заполните все обязательные поля.";
  }
  else{
    if($musicians_sex == "Мужской"){
      $sex = "man";
    }
    else if($musicians_sex == "Женский"){
      $sex = "woman";
    }
    else $sex = "Любой";

    $isvip = 0;
    if(!empty($arr['vipcode'])){
      $temp = activate_code($connection, $arr['vipcode']);
      if ($temp['status'] == TRUE )
        {
          $isvip = 1;
          mysqli_query($connection, 'SET foreign_key_checks = 0');
          $query = mysqli_query($connection, "INSERT INTO groups (groups_creator, groups_experience, groups_name,
            groups_instrument, groups_genre, groups_description, groups_city, groups_age, groups_sex, groups_isvip) VALUES
            ('$musicians_creator', '$musicians_experience', '$musicians_name', '$musicians_instrument', '$musicians_genre',
            '$musicians_description', '$musicians_city', '$musicians_age', '$sex', '$isvip')");
         header("Location: musician.php");
         exit();
        }
        else{
          $message = $temp['message'];
        }
      }
      else{
        mysqli_query($connection, 'SET foreign_key_checks = 0');
        $query = mysqli_query($connection, "INSERT INTO groups (groups_creator, groups_experience, groups_name,
          groups_instrument, groups_genre, groups_description, groups_city, groups_age, groups_sex, groups_isvip) VALUES
          ('$musicians_creator', '$musicians_experience', '$musicians_name', '$musicians_instrument', '$musicians_genre',
            '$musicians_description', '$musicians_city', '$musicians_age', '$sex', '$isvip')");
        header("Location: musician.php");
        exit();
      }
    }
  return array( 
    'message' => $message,
    'name' => $musicians_name,
    'city' => $musicians_city,
    'description' => $musicians_description,
    'code' => $arr['vipcode'],
  );return array(
    'message' => $message,
    'name' => $musicians_name,
    'city' => $musicians_city,
    'description' => $musicians_description,
    'code' => $arr['vipcode'],
  );
}

// Регистрация.
function do_register($connection, $arr){
  if(isset($arr["do_submit"])){
  	if(empty($arr['name']) || empty($arr['surname'])  || empty($arr['email']) || empty($arr['date'])
		|| empty($arr['password']) || empty($arr['repassword']) || empty($arr['city']) || 
		empty($arr['socnet']) || empty($arr['login'])){
  			$message = "Пожалуйста, заполните все поля!";
  		}
  	else{
  		$fio = $arr['name'] . " " . $arr['surname'];
        $email = $arr['email'];
        $city = $arr['city'];
        $date = $arr['date'];
  		$socnet = $arr['socnet'];
  		$login = $arr['login'];
  		$password = $arr['password'];
  		$password2 = $arr['repassword'];
  		$query = mysqli_query($connection, "SELECT * FROM User WHERE User.Mail = '$email'");
  		$numrows = mysqli_num_rows($query);
  		if($numrows != 0) {
  			$message = "Пользователь с данной почтой уже существует!";
  		}
  		else{
  			if($password != $password2){
  				$message = "Введённые пароли должны совпадать!";
  			}
  			else{
  				$password = password_hash($password, PASSWORD_DEFAULT);
  				$result = mysqli_query($connection,
  				"INSERT INTO User (User.Login, User.Password, User.FIO, User.Dith_day, User.SocNet, User.ID_Country, 
  				User.Mail, User.State, User.Photo) VALUES('$login', '$password', '$fio', '$date', '$socnet', '$city',
  				 '$email', 'false', './img/ava.png')");
  				if($result == FALSE) {
  					$message = "Проблемы при создании аккаунта!";
  				}
  				else{
            $query = mysqli_query($connection, "SELECT ID_User FROM User WHERE User.Mail = '$email' LIMIT 1");
      			$array = mysqli_fetch_array($query);
      			$id = $array['ID_User'];
            $_SESSION['logged_user'] = $email;
            $_SESSION['id'] = $id;
  					header("Location: account.php");
  					exit();
  				}
  			}
  		}
  	}
  }
  return array(
    'message' => $message,
    'fio' => $fio,
    'login' => $login,
    'socnet' => $socnet,
    'email' => $email,
    'date' => $date,
    );
}
// Авторизация.
function do_auth($connection, $arr){
  if(isset($arr["do_login"])){
  	if(empty($arr['email'])|| empty($arr['password'])){
  			$message = "Пожалуйста, заполните все поля!";
  	}else{
  		$email = $arr['email'];
  		$password = $arr['password'];
  		$query = mysqli_query($connection, "SELECT * FROM User WHERE User.Mail = '$email'");
  		if(mysqli_num_rows($query) == 1){
  			$query1 = mysqli_query($connection, "SELECT Password FROM User WHERE User.Mail = '$email' LIMIT 1");
  			$array = mysqli_fetch_array($query1);
  			$hash = $array['Password'];
  			var_dump($password);
  			var_dump($hash);
  			var_dump(password_verify($password, $hash));
  			if(password_verify($password, $hash)){
  		    $message = "Неверный логин или пароль";
                $query = mysqli_query($connection, "SELECT ID_User FROM User WHERE User.Mail = '$email' LIMIT 1");
                $array = mysqli_fetch_array($query);
                $id = $array['ID_User'];
                if($id == 1){
                    $_SESSION['logged_user'] = $email;
                    $_SESSION['id'] = $id;
                    header("Location: ../admin");
  				    exit();
                } else {
                    $_SESSION['logged_user'] = $email;
                    $_SESSION['id'] = $id;
  			        header("Location: account.php");
  				    exit();
                }
  		    }
        }
      	else {
  		    $message = "Неверный логин или пароль";
  	    }
  		}
  	}
    return array(
      'message' => $message,
      'email' => $email,
  );
}

// Выовод массива статей.
function print_articles_requests($requests, $connection){
  if(!empty($requests)){
  $i = 1;
  foreach($requests as $cat)
  {
    echo "<div class=\"box\">";
    echo "<h2>{$cat['Name']}</h2>";
    echo "<h4>Автор - {$cat['FIO']}</h4>";
    echo "<img src='../img/art.png' />";
    echo "<div class=\"info\">";
    echo "<ul>
      <li>Краткое описание: {$cat['Short_story']}</li>
      <li>Рейтинг: {$cat['Rating']}</li>
      <li>Дата публикации: {$cat['Date']}</li>
    </ul>";
    echo "</div>";
    echo "<nav><a href=\"#id-$i\">Просмотреть статью!</a></nav>";
    echo "  <div class=\"remodal\" data-remodal-id=\"id-$i\">
        <button data-remodal-action=\"close\" class=\"remodal-close\"></button>
        <h2>{$cat['Name']}</h2>";
		
		$str = articles($i, $connection);
		$i += 1;
    echo "{$str}" ;
    echo "</div>";

    echo "</div>";
    $i++;
  
}
}
else{
  echo "По вашему запросу ничего не найдено";
}
}
function articles($i, $connection){
	$str = " ";
    $query = mysqli_query($connection, "SELECT * FROM Article
  LEFT OUTER JOIN User ON User.ID_User = Article.ID_User 
  LEFT OUTER JOIN Block ON Block.ID_Article = Article.ID_Article 
  where Article.ID_Article = '$i'");
  if(mysqli_num_rows($query) != 0){
    while($cat = mysqli_fetch_assoc($query)){
		$str .= "<h4>{$cat['Name']}</h4>" ."<div class=\"info\">" ."<a>{$cat['Text']}</a>";
    }
 }
 return $str;
}

// Массив всех статей.
function all_articles($connection){
  $requests = array();
  $query = mysqli_query($connection, "SELECT * FROM Article
  LEFT OUTER JOIN User ON User.ID_User = Article.ID_User 
  order by Article.Rating");
  if(mysqli_num_rows($query) != 0){
    while($request = mysqli_fetch_assoc($query)){
      array_push($requests, $request);
    }
 }

  return $requests;
}
// Фильтрация по категории.
function filter_by($arr, $cat, $value, $zero_key){
  if($value != "" && $value != $zero_key){
    $res = array();
    foreach($arr as &$item){
      if(mb_strtoupper($item["$cat"]) == mb_strtoupper($value)){
        array_push($res, $item);
      }
    }
    return $res;
  }
  return $arr;
}
// Массив статей по фильтру.
function articles_by_filter($connection, $arr){
  if(isset($arr["do_filter"])){
    if(empty($arr['subject']) && empty($arr['sort']) && empty($arr['search'])){
        return all_articles($connection);
  		}
    else{
      $requests = all_articles($connection);
      $temp = array();


      $temp = filter_by($requests, "ID_Subject", $arr["subject"], "0");
      $res = $temp;
      $temp = array();
     }
   }
   return $res;
}

function user_data_output($connection, $users_id = null)
{
  $query = mysqli_query($connection, "SELECT * FROM users WHERE users_id = $users_id");
  $cat = mysqli_fetch_assoc($query);

  if($cat["users_sex"] == "man"){
    $sex = "Мужской";
  }
  else if($cat["users_sex"] == "woman"){
    $sex = "Женский";
  }
  else $sex = "Любой";
  $tmp = explode('-',$cat['users_birth_date']);
  $date = $tmp[2] . '.' . $tmp[1] . '.' . $tmp[0];

  echo "<table>
  <tr><td>Имя</td><td>$cat[users_name]</td></tr>
  <tr><td>Фамилия</td><td>$cat[users_surname]</td></tr>
  <tr><td>Email</td><td>$cat[users_email]</td></tr>
  <tr><td>Пол</td><td>$sex</td></tr>
  <tr><td>Дата рождения</td><td>$date</td></tr>
  <tr><td>Город</td><td>$cat[users_city]</td></tr>
  <tr><td><a href = account_update.php?users_id=$cat[users_id]&update=1>Редактировать</a></td><td></td></tr>";
  echo"</table>";

}



function user_data_edit($connection, $users_id = null, $users_name = null, $users_surname = null,
 $users_email = null, $users_sex = null, $users_birth_date = null, $users_city = null)
{
  $query = mysqli_query($connection, "UPDATE users SET users_name = '$users_name',
    users_surname = '$users_surname', users_email = '$users_email', users_sex = '$users_sex',
    users_birth_date = '$users_birth_date', users_city = '$users_city'
     WHERE users_id = '$users_id'");
}

function post($connection, $users_id = null)
	{
		$query = mysqli_query($connection, "SELECT * from users WHERE users_id = $users_id");
		$cat = mysqli_fetch_array($query);
		return $cat;
	}



function groups_request_output($connection)
  {
    $query = mysqli_query($connection, "SELECT * FROM groups
    LEFT OUTER JOIN instruments ON groups.groups_instrument = instruments.instruments_id
    LEFT OUTER JOIN users ON groups.groups_creator = users.users_id
    LEFT OUTER JOIN genres ON groups.groups_genre = genres.genres_id
    WHERE groups.groups_isvip = 1");
    while ($cat = mysqli_fetch_assoc($query))
    {
      echo "<table>
      <tr><td>Имя</td><td>$cat[groups_name]</td></tr>
      <tr><td>Опыт</td><td>$cat[groups_experience]</td></tr>
      <tr><td>Инструмент</td><td>$cat[instruments_name]</td></tr>
      <tr><td>Жанр</td><td>$cat[genres_name]</td></tr>
      <tr><td>Пол</td><td>$cat[groups_sex]</td></tr>
      <tr><td>Возраст</td><td>$cat[groups_age]</td></tr>
      <tr><td>Город</td><td>$cat[groups_city]</td></tr>
      <tr><td>Описание</td><td>$cat[groups_description]</td></tr>";
      echo "</table>";
    }
    $query = mysqli_query($connection, "SELECT * FROM groups
    LEFT OUTER JOIN instruments ON groups.groups_instrument = instruments.instruments_id
    LEFT OUTER JOIN users ON groups.groups_creator = users.users_id
    LEFT OUTER JOIN genres ON groups.groups_genre = genres.genres_id
    WHERE groups.groups_isvip = 0");
    while ($cat = mysqli_fetch_assoc($query))
    {
      echo "<table>
      <tr><td>Имя</td><td>$cat[groups_name]</td></tr>
      <tr><td>Опыт</td><td>$cat[groups_experience]</td></tr>
      <tr><td>Инструмент</td><td>$cat[instruments_name]</td></tr>
      <tr><td>Жанр</td><td>$cat[genres_name]</td></tr>
      <tr><td>Пол</td><td>$cat[groups_sex]</td></tr>
      <tr><td>Возраст</td><td>$cat[groups_age]</td></tr>
      <tr><td>Город</td><td>$cat[groups_city]</td></tr>
      <tr><td>Описание</td><td>$cat[groups_description]</td></tr>";
      echo "</table>";
    }
}

function musicians_request_output($connection)
  {
    $query = mysqli_query($connection, "SELECT * FROM musicians
    LEFT OUTER JOIN instruments ON musicians.musicians_instrument = instruments.instruments_id
    LEFT OUTER JOIN users ON musicians.musicians_creator = users.users_id
    LEFT OUTER JOIN genres ON musicians.musicians_genre = genres.genres_id
    WHERE musicians.musicians_isvip = 1");
    $i = 1;
    while ($cat = mysqli_fetch_assoc($query))
    {
      echo "<div class=\"box\">";
      echo "<h2>$cat[users_surname] $cat[users_name]</h2>";
      echo "<img src='../img/".$cat['instruments_picture']."' />";
      echo "<div class=\"info\">";
      echo "<ul>
        <li>Пол: $cat[users_sex]</li>
        <li>Возраст: $cat[users_birth_date]</li>
        <li>Инструмент: $cat[instruments_name]</li>
        <li>Опыт: $cat[musicians_experience]</li>
        <li>Жанр: $cat[genres_name]</li>
        <li>Город: $cat[users_city]</li>
      </ul>";
      echo "</div>";
      echo "<div class=\"about\">";
      echo "<h4>О себе</h4><p>$cat[musicians_description]</p>";
      echo "</div>";
      echo "<nav><a href=\"#id-$i\">Откликнуться!</a></nav>";
      echo "  <div class=\"remodal\" data-remodal-id=\"id-$i\">
          <button data-remodal-action=\"close\" class=\"remodal-close\"></button>
          <h4>Понравился музыкант? Напишите ему на почту!</h4>
          <a href=\"mailto:$cat[users_email]\">$cat[users_email]</a>
            </div>";
      echo "</div>";
    }
    $query = mysqli_query($connection, "SELECT * FROM musicians
    LEFT OUTER JOIN instruments ON musicians.musicians_instrument = instruments.instruments_id
    LEFT OUTER JOIN users ON musicians.musicians_creator = users.users_id
    LEFT OUTER JOIN genres ON musicians.musicians_genre = genres.genres_id
    WHERE musicians.musicians_isvip = 0");
    //$i = 1;
    while ($cat = mysqli_fetch_assoc($query))
    {
      echo "<div class=\"box\">";
      echo "<h2>$cat[users_surname] $cat[users_name]</h2>";
      echo "<img src='../img/".$cat['instruments_picture']."' />";
      echo "<div class=\"info\">";
      echo "<ul>
        <li>Пол: $cat[users_sex]</li>
        <li>Возраст: $cat[users_birth_date]</li>
        <li>Инструмент: $cat[instruments_name]</li>
        <li>Опыт: $cat[musicians_experience]</li>
        <li>Жанр: $cat[genres_name]</li>
        <li>Город: $cat[users_city]</li>
      </ul>";
      echo "</div>";
      echo "<div class=\"about\">";
      echo "<h4>О себе</h4><p>$cat[musicians_description]</p>";
      echo "</div>";
      echo "<nav><a href=\"#id-$i\">Откликнуться!</a></nav>";
      echo "  <div class=\"remodal\" data-remodal-id=\"id-$i\">
          <button data-remodal-action=\"close\" class=\"remodal-close\"></button>
          <h4>Понравился музыкант? Напишите ему на почту!</h4>
          <a href=\"mailto:$cat[users_email]\">$cat[users_email]</a>
            </div>";
      echo "</div>";
    }
}

function user_request_output($connection, $users_id = null)
{
  $query = mysqli_query($connection, "SELECT * FROM musicians
  LEFT OUTER JOIN instruments ON musicians.musicians_instrument = instruments.instruments_id
  LEFT OUTER JOIN users ON musicians.musicians_creator = users.users_id
  LEFT OUTER JOIN genres ON musicians.musicians_genre = genres.genres_id
  WHERE musicians_creator = $users_id");
  $i = 1;
  while ($cat = mysqli_fetch_assoc($query))
  {
    echo "<div class=\"box\">";
    echo "<div class=\"text_block\">";
    echo "<h3>Заявка №$i</h3>";
    if ($cat['musicians_ismodered'] == TRUE)
      echo "<h4 class = 'apply'>Одобрена</h4>";
    else echo "<h4 class = 'checking'>На рассмотрении</h4>";
    echo "<h4>Поиск музыканта</h4>";
    echo "<input type=\"checkbox\" id=\"hd-$i\" class=\"hide\"/>";
    echo "<label for=\"hd-$i\" >Подробнее</label>
      <div>
        <ul>
          <li><b>Имя:</b> $cat[users_name]</li>
          <li><b>Опыт:</b> $cat[musicians_experience]</li>
          <li><b>Инструмент:</b> $cat[instruments_name]</li>
          <li><b>Жанр:</b> $cat[genres_name]</li>
        </ul>
        <p><h4>О себе</h4>
          $cat[musicians_description]
        </p>";
        if ($cat['musicians_isvip'] == FALSE)
        {
        echo "<form method='post' class='vipacc'>
          <input type='text' name='vipcodem' placeholder='Промокод вводить сюда!'>
          <input type = 'submit' name = 'totop-$cat[musicians_id]' value = 'В топ'>
        </form>";
        }
      echo "</div>";
    echo "<form method='post'>
            <input type = 'submit' name = 'rejectm-$cat[musicians_id]' value = 'Удалить'>
            </form>";
    echo "</div>";
    echo "</div>";
          if(isset($_POST['rejectm-'.$cat['musicians_id']]))
          {
          rejectm($connection, $cat['musicians_id']);
          echo "<meta http-equiv='refresh' content='0'>";
          }
          if(isset($_POST['totop-'.$cat['musicians_id']]))
          {
          totopm($connection, $cat['musicians_id'], $_POST['vipcodem']);
          echo "<meta http-equiv='refresh' content='0'>";
          }
    $i++;
  }

  $query = mysqli_query($connection, "SELECT * FROM groups
  LEFT OUTER JOIN instruments ON groups.groups_instrument = instruments.instruments_id
  LEFT OUTER JOIN users ON groups.groups_creator = users.users_id
  LEFT OUTER JOIN genres ON groups.groups_genre = genres.genres_id
  WHERE groups_creator = $users_id");
  while ($cat = mysqli_fetch_assoc($query))
  {
    if($cat["groups_sex"] == "man")
      $sex = "Мужской";
    else if($cat["groups_sex"] == "woman")
      $sex = "Женский";
      else $sex = "Любой";
    echo "<div class=\"box\">";
    echo "<div class=\"text_block\">";
    echo "<h3>Заявка №$i</h3>";
    if ($cat['musicians_ismodered'] == TRUE)
      echo "<h4 class = 'apply'>Одобрена</h4>";
    else echo "<h4 class = 'checking'>На рассмотрении</h4>";
    echo "<h4>Поиск группы</h4>";
    echo "<input type=\"checkbox\" id=\"hd-$i\" class=\"hide\"/>";
    echo "<label for=\"hd-$i\" >Подробнее</label>
      <div>
        <ul>
          <li><b>Название группы:</b> $cat[groups_name]</li>
          <li><b>Желаемый опыт:</b> $cat[groups_experience]</li>
          <li><b>Пол:</b> $sex</li>
          <li><b>Возраст:</b> $cat[groups_age]</li>
          <li><b>Город:</b> $cat[groups_city]</li>
          <li><b>Инструмент:</b> $cat[instruments_name]</li>
          <li><b>Жанр:</b> $cat[genres_name]</li>
        </ul>
        <p><h4>Описание группы</h4>
          $cat[groups_description]
        </p>";
        if ($cat['groups_isvip'] == FALSE)
        {
        echo "<form method='post' class='vipacc'>
          <input type='text' name='vipcodeg' placeholder='Промокод вводить сюда!'>
          <input type = 'submit' name = 'totop-$cat[groups_id]' value = 'В топ'>
          </form>";
        }
      echo "</div>";
      echo "<form method='post'>
            <input type = 'submit' name = 'rejectg-$cat[groups_id]' value = 'Удалить'>
            </form>";
    echo "</div>";
    echo "</div>";
          if(isset($_POST['rejectg-'.$cat['groups_id']]))
          {
          rejectg($connection, $cat['groups_id']);
          echo "<meta http-equiv='refresh' content='0'>";
          }
          if(isset($_POST['totop-'.$cat['groups_id']]))
          {
          totopg($connection, $cat['groups_id'], $_POST['vipcodeg']);
          echo "<meta http-equiv='refresh' content='0'>";
          }
    $i++;
  }
}

function setinstruments($connection, $instruments_id = null, $instruments_name = null)
  {
    $query = mysqli_query($connection, "SELECT * FROM instruments");
    while ($cat = mysqli_fetch_assoc($query))
    {
    echo "<option value='".$cat['instruments_id']."'>".$cat['instruments_name']."</option>";
  }
}

function setgenres($connection, $genres_id = null, $genres_name = null)
  {
    $query = mysqli_query($connection, "SELECT * FROM genres");
    while ($cat = mysqli_fetch_assoc($query))
    {
    echo "<option value='".$cat['genres_id']."'>".$cat['genres_name']."</option>";
  }
}

function getsubject($connection, $subject, $clear){
  $query = mysqli_query($connection, "SELECT * FROM Subject");
  if(isset($clear)){
    $subject = null;
  }
  echo '<option selected = "selected" value = "0">';
  echo "Предметная область";
  echo '</option>';
  while($row = mysqli_fetch_assoc($query)){
    if($row["ID_Subject"] == $subject){
      echo "<option selected =\"selected\" value='".$row['ID_Subject']."'>".$row['Name']."</option>";
    }
    else{
      echo "<option value='".$row['ID_Subject']."'>".$row['Name']."</option>";
    }
  }
}
function getsort($connection, $subject, $clear){
  $query = mysqli_query($connection, "SELECT * FROM Article");
  if(isset($clear)){
    $subject = null;
  }
  echo '<option selected = "selected" value = "0">';
  echo "Сортировка по";
  echo '</option>';
  while($row = mysqli_fetch_assoc($query)){
    if($row["ID_Subject"] == $subject){
      echo "<option selected =\"selected\" value='".$row['ID_Subject']."'>".$row['Name']."</option>";
    }
    else{
      echo "<option value='".$row['ID_Subject']."'>".$row['Name']."</option>";
    }
  }
}
function getsearch($connection, $subject, $clear){
  $query = mysqli_query($connection, "SELECT * FROM Subject");
  if(isset($clear)){
    $subject = null;
  }
  echo '<option selected = "selected" value = "0">';
  echo "Предметная область";
  echo '</option>';
  while($row = mysqli_fetch_assoc($query)){
    if($row["ID_Subject"] == $subject){
      echo "<option selected =\"selected\" value='".$row['ID_Subject']."'>".$row['Name']."</option>";
    }
    else{
      echo "<option value='".$row['ID_Subject']."'>".$row['Name']."</option>";
    }
  }
}

function getcity($connection, $subject, $clear){
  $query = mysqli_query($connection, "SELECT * FROM Country");
  if(isset($clear)){
    $subject = null;
  }
  echo '<option selected = "selected" value = "0">';
  echo "Страны";
  echo '</option>';
  while($row = mysqli_fetch_assoc($query)){
    if($row["ID_Country"] == $subject){
      echo "<option selected =\"selected\" value='".$row['ID_Country']."'>".$row['Name']."</option>";
    }
    else{
      echo "<option value='".$row['ID_Country']."'>".$row['Name']."</option>";
    }
  }
}

 // Проверка наличия значения в массиве.
 function isInArr($arr, $item){
   foreach($arr as &$value){
     if($value == $item)
       return true;
   }
   return false;
 }
 // Печать массива.
 function print_array($arr){
   foreach($arr as &$value){
   echo $value;
   echo '</br>';
   }
 }

function checkisadmin($connection)
{
  $query = mysqli_query($connection, "SELECT * FROM User");
  while($cat = mysqli_fetch_assoc($query))
  {
    if ($cat['login'] == "admin")
    {
      return true;
    }
  }
  return false;
}

function acceptm($connection, $musicians_id = null)
{
  $query = mysqli_query($connection, "UPDATE musicians SET musicians_ismodered = 1
  WHERE musicians_id = '$musicians_id'");
}

function rejectm($connection, $musicians_id = null)
{
  $query = mysqli_query($connection, "DELETE FROM musicians WHERE musicians_id = '$musicians_id'");
}

function acceptg($connection, $groups_id = null)
{
  $query = mysqli_query($connection, "UPDATE groups SET groups_ismodered = 1
  WHERE groups_id = '$groups_id'");
}

function rejectg($connection, $groups_id = null)
{
  $query = mysqli_query($connection, "DELETE FROM groups WHERE groups_id = '$groups_id'");
}

function admins_requsts_output_mus($connection)
  {
    $query = mysqli_query($connection, "SELECT * FROM musicians
    LEFT OUTER JOIN instruments ON musicians.musicians_instrument = instruments.instruments_id
    LEFT OUTER JOIN users ON musicians.musicians_creator = users.users_id
    LEFT OUTER JOIN genres ON musicians.musicians_genre = genres.genres_id
    WHERE musicians_ismodered = 0");
    while ($cat = mysqli_fetch_assoc($query))
    {
      $mail = $cat["users_email"];
      if($cat["users_sex"] == "man"){
        $sex = "Мужской";
      }
      else if($cat["users_sex"] == "woman"){
        $sex = "Женский";
      }
      else $sex = "Любой";
      $age = get_age($cat["users_birth_date"]);
      echo "<div class=\"box\">";
      echo "<h2>$cat[users_surname] $cat[users_name]</h2>";
      echo "<div class=\"info\">";
      echo "<ul>
        <li>Пол: $sex</li>
        <li>Возраст: $age</li>
        <li>Инструмент: $cat[instruments_name]</li>
        <li>Опыт: $cat[musicians_experience]</li>
        <li>Жанр: $cat[genres_name]</li>
        <li>Город: $cat[users_city]</li>
      </ul>";
      echo "</div>";
      echo "<div class=\"about\">";
      echo "<h4>О себе</h4><p>$cat[musicians_description]</p>";
      echo "</div>";
      echo "<form class = \"\" method='post'>
            <input type = 'submit' name = 'acceptm-$cat[musicians_id]' value = 'Одобрить'>
            <input type = 'submit' name = 'rejectm-$cat[musicians_id]' value = 'Отклонить'>
            <select  name=\"reason\">
              <option>Некорректные данные</option>
              <option>Нецензурные выражения</option>
              <option>Оскорбления</option>
              <option>Спам</option>
            </select>
            </form>";
            if(isset($_POST['acceptm-'.$cat['musicians_id']]))
            {
              $recepient = $mail;
              $text = "Ваша заявка на поиск группы с сайта Believe была одобрена! Спасибо, что вы с нами!";
              $pagetitle = "Ответ на заявку на сайте Believe";
              mail($recepient, $pagetitle, $text, "From: believe@gmail.com");
              acceptm($connection, $cat['musicians_id']);
              echo "<meta http-equiv='refresh' content='0'>";
            }
            if(isset($_POST['rejectg-'.$cat['musicians_id']]))
            {
              $recepient = $mail;
              $text = "Ваша заявка на поиск группы с сайта Believe была отклонена! Причина: ".$_POST["reason"]."." ;
              $pagetitle = "Ответ на заявку на сайте Believe";
              mail($recepient, $pagetitle, $text, "From: believe@gmail.com");
              rejectm($connection, $cat['musicians_id']);
              echo "<meta http-equiv='refresh' content='0'>";
            }echo "</div>";
    }
  }

  function admins_requsts_output_gr($connection)
  {
    $query = mysqli_query($connection, "SELECT * FROM groups
    LEFT OUTER JOIN instruments ON groups.groups_instrument = instruments.instruments_id
    LEFT OUTER JOIN users ON groups.groups_creator = users.users_id
    LEFT OUTER JOIN genres ON groups.groups_genre = genres.genres_id
    WHERE groups_ismodered = 0");
    while ($cat = mysqli_fetch_assoc($query))
    {
      $mail = $cat["users_email"];
      if($cat["groups_sex"] == "man"){
        $sex = "Мужской";
      }
      else if($cat["groups_sex"] == "woman"){
        $sex = "Женский";
      }
      else $sex = "Любой";
      echo "<div class=\"box\" value=\"$mail\">";
      echo "<h2>$cat[groups_name]</h2>";
      echo "<div class=\"info\">";
      echo "<ul>
        <li>Пол: $sex</li>
        <li>Возраст: $cat[groups_age]</li>
        <li>Инструмент: $cat[instruments_name]</li>
        <li>Опыт: $cat[groups_experience]</li>
        <li>Жанр: $cat[genres_name]</li>
        <li>Город: $cat[groups_city]</li>
        <li>Почта: $cat[users_email];
      </ul>";
      echo "</div>";
      echo "<div class=\"about\">";
      echo "<h4>О группе</h4><p>$cat[groups_description]</p>";
      echo "</div>";
      echo "<form class = \"\" method='post'>
            <input type = 'submit' name = 'acceptg-$cat[groups_id]' value = 'Одобрить'>
            <input type = 'submit' name = 'rejectg-$cat[groups_id]' value = 'Отклонить'>
            <select  name=\"reason\">
              <option>Некорректные данные</option>
              <option>Нецензурные выражения</option>
              <option>Оскорбления</option>
              <option>Спам</option>
            </select>
            </form>";
            if(isset($_POST['acceptg-'.$cat['groups_id']]))
            {
              $recepient = $mail;
              $text = "Ваша заявка на поиск музыканта с сайта Believe была одобрена! Спасибо, что вы с нами!";
              $pagetitle = "Ответ на заявку на сайте Believe";
              mail($recepient, $pagetitle, $text, "From: believe@gmail.com");
              acceptg($connection, $cat['groups_id']);
              echo "<meta http-equiv='refresh' content='0'>";
            }
            if(isset($_POST['rejectg-'.$cat['groups_id']]))
            {
              $recepient = $mail;
              $text = "Ваша заявка на поиск музыканта с сайта Believe была отклонена! Причина: ".$_POST["reason"]."." ;
              $pagetitle = "Ответ на заявку на сайте Believe";
              mail($recepient, $pagetitle, $text, "From: believe@gmail.com");
              rejectg($connection, $cat['groups_id']);
              echo "<meta http-equiv='refresh' content='0'>";
            }echo "</div>";
    }
  }
?>
