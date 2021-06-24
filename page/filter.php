<style>
.sidenav {
    flex-wrap: left;
    justify-content: left;
    margin: 0px 0;
    font-family: 'Proxima Nova Regular';
}

.sidenav a {
    display: block;
	padding: 0 10px;
	color:black;
	text-decoration: none;
}

.sidenav a:after {
  background-color: #d14351;
  display: block;
  content: "";
  height: 2px;
  width: 0;
  -webkit-transition: width .5s ease-in-out;
  -moz--transition: width .5s ease-in-out;
  transition: width .5s ease-in-out;
}

.sidenav a:hover:after,a:focus:after {
  width: 100%;
}

.main {

}

@media screen and (max-height: 990px) {

}
</style>



<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="../css/filter.css">
  <link rel="stylesheet" href="../libs/bootstrap/bootstrap-grid-3.3.1.min.css" />
  <link rel="stylesheet" href="../libs/font-awesome-4.2.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="../libs/remodal/remodal.css">
  <link rel="stylesheet" href="../libs/remodal/remodal-default-theme.css">
</head>
<body>

  <div class="container">
    <div class="col-md-12">
      <div class="row">
            <form class="filter" method = "post">
                
            <div class="all_filter"><div class="sidenav">
                    <a>Выберите предметную область</a>
                    <select class="select" id="subject" name="subject" >
            	        <?php getsubject($connection, $_POST["subject"], $_POST["clear"]);?>
                    </select>
                    <a>Выберите сортировку</a>
                    <select class="select" id="sort" name="subject" >
                        <?php getsort($connection, $_POST["sort"], $_POST["clear"]);?>
                    </select>
                </div>
                <div class="filter">
				    <input type="text" name="se" placeholder="Найти статью по ..." value="<?php if(!isset($_POST["clear"])) echo $_POST['se'];?>">
				    <select class="select" id="search" name="search" >
					    <?php getsearch($connection, $_POST["search"], $_POST["clear"]);?>
				    </select>
				    <input type="submit" name="clear" value = "Очистить">
				    <input type="submit" name="do_filter" value="Отфильтровать">
				    
                </div>
			</form>
			
        </div>
      </div>
    </div>
  </div>
<!-- Подключение JS -->
    <script type="text/javascript" src="../js/main.js"></script>
    <script type="text/javascript" src="../libs/jquery/jquery-1.11.1.min.js"></script>
    <script src="../libs/remodal/remodal.min.js"></script>
</body>