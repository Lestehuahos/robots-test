<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Анализ сайта</title>
	<link rel="stylesheet" href="style/css/bootstrap.min.css" />
	<script type="text/javascript" src="style/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="style/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="/style/css/style.css" />
</head>
<body>

<?php

// Подключаем функции
include $_SERVER['DOCUMENT_ROOT']. '/functions.php';

/*
foreach ($array as $key => $value) {
    //вместо strpos можно и регуляркой
    if (strpos($key,'extra') ===  0 && isset($value['menu0']['title'])) {
        echo $value['menu0']['title'].'<br/>';
    }   
}
*/
?>

<?php 

/*echo '<div class="wrapper">
	<form method="post" action="/get.php" class="form-center">
	<input type="text" name="sitename" class="form-control">
	<input type="submit" name="send" value="Анализ">
	</form>
</div>';*/

echo '<div class="wrapper"><form role="form" class="form-inline search-form" method="post" action="/get.php">
	<div class="form-group">
		<input type="text" name="sitename" class="form-control" placeholder="http://example.com">
	</div>
	<button type="submit" class="btn btn-success" name="analyze">Анализ</button>
</form></div>';

?>
</body>
</html>