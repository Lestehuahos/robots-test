<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Результаты проверки</title>
	<link rel="stylesheet" href="style/css/bootstrap.min.css" />
	<script type="text/javascript" src="style/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="style/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="/style/css/style.css" />
</head>
<body>
<a href="/"><img src="style/images/logo.png" alt="Logo" class="center-block" style="width: 150px;"></a>

<?php
// Подключаем функции
include $_SERVER['DOCUMENT_ROOT']. '/functions.php';


/* Блок обработки формы */
if(isset($_POST['analyze'])) {
	
	$url = prepareURL($_POST['sitename']);
	$file = @file_get_contents($url);
	
	// Если найден файл
	if($file) {
	
		/* Инициализация значениями */
		robots_exists($url); 
		hostVerification($file);
		hostQuantity($file);
		robotsSize($url);
		sitemapVerification($file);
		responseCode($url);
		
		$data[0] = json_decode(json_encode($obj1), true);
		$data[1] = json_decode(json_encode($obj2), true);
		$data[2] = json_decode(json_encode($obj3), true);
		$data[3] = json_decode(json_encode($obj4), true);
		$data[4] = json_decode(json_encode($obj5), true);
		$data[5] = json_decode(json_encode($obj6), true);
	
	}
	else {
	
		$data[0] = json_decode(json_encode($obj1), true);
		$data[1] = json_decode(json_encode($obj2), true);
		$data[2] = json_decode(json_encode($obj3), true);
		$data[3] = json_decode(json_encode($obj4), true);
		$data[4] = json_decode(json_encode($obj5), true);
		$data[5] = json_decode(json_encode($obj6), true);
		
		foreach ($data as $key=>$column) {
			foreach($column as $innerkey=>$value) {
				// Если статус
				if($innerkey == "status") {
					$data[$key][$innerkey] = "Ошибка";
				}
				// Если состояние
				if($innerkey == "condition") {
					$data[$key][$innerkey] = "Ошибка получения Файла";
				}
			}
		}
		
	}

	createExcel($data);
	
	echo "<div class='container'><table border='0' class='table'>";
	echo "<tr>
	<td><b>№</b></td>
	<td><b>Название проверки</b></td>
	<td><b>Статус</b></td>
	<td> </td>
	<td><b>Текущее состояние</b></td>
	</tr>
	<tr><td colspan='5' height='10px'></td></tr>";

	foreach($data as $key=>$value) {

		//В зависимости от статуса - меняем цвет ячейки
		if($value['status'] == "ОК") {
			$class = 'bg-success';
		}
		else {
			$class = 'bg-error';
		}
		
		$numb = [1, 6, 8, 10, 11, 12];
		echo "<tr>
		<td rowspan='2'>".$numb[$key]."</td>
		<td rowspan='2'>".$value['name']."</td>
		<td rowspan='2' class='".$class." text-center'>".$value['status']."</td>
		<td>Состояние</td>
		<td>".$value['condition']."</td>
		</tr>
		<tr><td>Рекомендации</td><td>".$value['recommends']."</td>
		</tr>";

	}

	echo "</table></div>";

	echo '<div class="center-block" style="width:92px;"><a href="/Checklist.xls">Скачать файл</a></div>';

	//header('Location: /');
}

?>
</body>
</html>