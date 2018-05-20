<?php

// Подключаем класс
include $_SERVER['DOCUMENT_ROOT']. '/Classes/Parametr.php';

// Подключаем библиотеку PHPExcel
require_once $_SERVER['DOCUMENT_ROOT']. '/Classes/PHPExcel.php'; // подключаем библиотеку PHPExcel

// Объекты
$obj1 = new Parametr;
$obj2 = new Parametr;
$obj3 = new Parametr;
$obj4 = new Parametr;
$obj5 = new Parametr;
$obj6 = new Parametr;

/* Инициализация названий проверок */
$obj1->name = "Проверка наличия файла robots.txt";
$obj2->name = "Проверка указания директивы Host";
$obj3->name = "Проверка количества директив Host, прописанных в файле";
$obj4->name = "Проверка размера файла robots.txt";
$obj5->name = "Проверка указания директивы Sitemap";
$obj6->name = "Проверка кода ответа сервера для файла robots.txt";

//$obj2->name = "Проверка";

/**
* Проверка наличия файла robots.txt
*/
function robots_exists($address) {
	
	global $obj1;

	// открываем файл для чтения
	if (@fopen($address, "r")) {
		$obj1->status = "ОК";
		$obj1->condition = "Файл robots.txt присутствует";
		$obj1->recommends = "Доработки не требуются";
		
		return true;
	} else {
		$obj1->status = "Ошибка";
		$obj1->condition = "Файл robots.txt отсутствует";
		$obj1->recommends = "Программист: Создать файл robots.txt и разместить его на сайте.";
		
		return false;
	}
}

/**
* Проверка на наличие директивы Host
* @param string $str
*/
function hostVerification($str) {
	
	global $obj2;
	
	//Находим вхождения директивы Host
	preg_match("/Host/", $str, $values);
	
	if($values) {		
		$obj2->status = "ОК";
		$obj2->condition = "Директива Host указана";
		$obj2->recommends = "Доработки не требуются";
	}
	else {
		$obj2->status = "Ошибка";
		$obj2->condition = "В файле robots.txt не указана директива Host";
		$obj2->recommends = "Программист: Для того, чтобы поисковые системы знали, какая версия сайта является основным зеркалом, необходимо прописать адрес основного зеркала в директиве Host. В данный момент это не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host задаётся в файле 1 раз, после всех правил.";
	}
}


/**
* Проверка количества директив Host, прописанных в файле
*
*/
function hostQuantity($str) {
	
	global $obj3;
	
	//Находим вхождения директивы Sitemap
	preg_match("/Host/", $str, $values);
	
	// Если количество директив Host - больше нуля
	if(count($values) > 0) {
		// Если количество директив Host равно единице
		if(count($values) == 1) {
			$obj3->status = "ОК";
			$obj3->condition = "В файле прописана 1 директива Host";
			$obj3->recommends = "Доработки не требуются";
		}
		else {
			$obj3->status = "Ошибка";
			$obj3->condition = "В файле прописано несколько директив Host";
			$obj3->recommends = "Программист: Директива Host должна быть указана в файле только 1 раз. Необходимо удалить все дополнительные директивы Host и оставить только 1, корректную и соответсвующую основному зеркалу сайта.";
		}
	}
	else {
			$obj3->status = "Ошибка";
			$obj3->condition = "Директива Host не указана";
			$obj3->recommends = "Проверка невозможна, т.к. директива Host отсутствует";
	}
}

/**
* Проверка размера файла robots.txt
*/
function robotsSize($file) {

	global $obj4;
	
	$headers = get_headers($file, true);

	// Если размер файла существует
	if (isset($headers['Content-Length'])) {		
		// Размер файла
		$size = $headers['Content-Length'];
		
		// Если файл меньше/равно 32 килобайт
		if($headers['Content-Length'] <= 32000) {	
			$obj4->status = "ОК";
			$obj4->condition = "Размер файла robots.txt составляет ".$size.", что находится в пределах допустимой нормы";
			$obj4->recommends = "Доработки не требуются";
		}
		else {
			$obj4->status = "Ошибка";
			$obj4->condition = "Размер файла robots.txt составляет ".$size.", что превышает допустимую норму";
			$obj4->recommends = "Программист: Максимально допустимый размер файле robots.txt составляет 32кб. Необходимо отредактировать файл robots.txt таким образом, чтобы его размер не превышал 32 Кб.";
		}
	}
	else {
	   $size = 'Размер файла не найден';
	}
}

/**
* Проверка на наличие директивы Sitemap
* @param string $str
*/
function sitemapVerification($str) {
	
	global $obj5;
	
	//Находим вхождения директивы Sitemap
	preg_match("/Sitemap/", $str, $values);

	if($values) {
		$obj5->status = "ОК";
		$obj5->condition = "Директива Sitemap указана";
		$obj5->recommends = "Доработки не требуются";
	}
	else {
		$obj5->status = "Ошибка";
		$obj5->condition = "В файле robots.txt не указана директива Sitemap";
		$obj5->recommends = "Программист: Добавить в файл robots.txt директиву Sitemap";
	}
}

/**
* Проверка кода ответа сервера для файла robots.txt
*/
function responseCode($address) {

	global $obj6;

	$Headers = @get_headers($address);
	
	// проверяем ответ от сервера с кодом 200 - ОК
	if(strpos($Headers[0], '200')) {
		$obj6->status = "ОК";
		$obj6->condition = "Файл robots.txt отдаёт код ответа сервера 200";
		$obj6->recommends = "Доработки не требуются";
	} else {
		
		// Получаем код ответа сервера
		$code = explode( " ", $Headers[0] );
		$code = $code[1];
	
		$obj6->status = "Ошибка";
		$obj6->condition = "При обращении к файлу robots.txt сервер возвращает код ответа ".$code;
		$obj6->recommends = "Программист: Файл robots.txt должен отдавать код ответа 200, иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом, чтобы при обращении к файлу robots.txt сервер возвращал код ответа 200.";
	}
	
}


function prepareURL($userInput) {
	if (substr_count(strtolower($userInput), "http://" ) > 0 ) {
		$result = trim($userInput) . "/robots.txt";
	} else {
		$result = "http://" . trim($userInput) . "/robots.txt";
	}
	return $result;
}


function createExcel($array) {

$document = new PHPExcel();
 
$sheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе
 
$columnPosition = 0; // Начальная координата x
$startLine = 0; // Начальная координата y
 
// Выравниваем по центру
//$sheet->getStyleByColumnAndRow($columnPosition, $startLine)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Перекидываем указатель на следующую строку
$startLine++;
 
// Массив с названиями столбцов
$columns = ['№', 'Название проверки', 'Статус', ' ', 'Текущее состояние'];
 
// Указатель на первый столбец
$currentColumn = $columnPosition;
  
// Формируем шапку
foreach ($columns as $key=>$column) {
    // Красим ячейку
    $sheet->getStyleByColumnAndRow($currentColumn, $startLine)
        ->getFill()
        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('d1cccc');
 
    $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $column);
	
	// Номер и статус выравниваем по центру (заголовки)
	if($key == 0 || $key == 2) {
		// Выравниваем по центру по горизонтали
		$sheet->getStyleByColumnAndRow($currentColumn, $startLine)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// Выравниваем по центру по вертикали
		$sheet->getStyleByColumnAndRow($currentColumn, $startLine)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	}
 
    // Смещаемся вправо
    $currentColumn++;
}

// Устанавливаем автоматическую ширину колонок
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);

$columnNamePosition = 1; // Указатель на второй столбец
$columnStatusPosition = 2; // Указатель на третий столбец

// Формируем список
foreach ($array as $key=>$checkItem) {
	// Перекидываем указатель на следующую строку
    $startLine++;
    // Указатель на первый столбец
    $currentColumn = $columnPosition;
	
	// Нумерация
	$numb = [1, 6, 8, 10, 11, 12];
	
    // Вставляем порядковый номер
    $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $numb[$key]);
	$document->getActiveSheet()->mergeCellsByColumnAndRow($currentColumn, $startLine, $currentColumn, $startLine+1);
	
	// Выравниваем по центру по горизонтали
	$sheet->getStyleByColumnAndRow($currentColumn, $startLine)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	// Выравниваем по центру по вертикали
	$sheet->getStyleByColumnAndRow($currentColumn, $startLine)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	// Статус/Рекомендации
	$sheet->setCellValueByColumnAndRow(3, $startLine, 'Состояние');
	$sheet->setCellValueByColumnAndRow(3, $startLine+1, 'Рекомендации');
	
	$startLine++;
	 
	foreach ($checkItem as $key=>$value) {
		
		$currentColumn++;
				
		if($key == "status") {
			if($value == "ОК") {
			
			// Красим ячейку
			$sheet->getStyleByColumnAndRow($currentColumn, $startLine-1)
				->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()
				->setRGB('4dbf62');
			
			/*
			$sheet->getStyleByColumnAndRow($currentColumn, $startLine+1)
				->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()
				->setRGB('4dbf62');
			*/
			
			}
			else {
				// Красим ячейку
				$sheet->getStyleByColumnAndRow($currentColumn, $startLine-1)
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('ff0000');
				/*	
				$sheet->getStyleByColumnAndRow($currentColumn, $startLine+1)
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('ff0000');
				*/
			}
		}
		 
		//Если ключ равняется состоянию
		if($key == "condition") {
			$sheet->setCellValueByColumnAndRow($currentColumn+1, $startLine-1, $value);
		}
		elseif($key == "recommends") {
			$sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $value);
		}
		else {
			$sheet->setCellValueByColumnAndRow($currentColumn, $startLine-1, $value);
			$document->getActiveSheet()->mergeCellsByColumnAndRow($currentColumn, $startLine-1, $currentColumn, $startLine);
			
			// Если ключ равен названию проверки
			if($key == "name") {
				// Выравниваем по вертикали
				$sheet->getStyleByColumnAndRow($currentColumn, $startLine-1)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			}
			
			// Если ключ равняется статусу
			if($key == "status") {
				// Выравниваем по центру по горизонтали
				$sheet->getStyleByColumnAndRow($currentColumn, $startLine-1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				// Выравниваем по центру по вертикали
				$sheet->getStyleByColumnAndRow($currentColumn, $startLine-1)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			}
		}
		

	
	}
}
  
 
$objWriter = PHPExcel_IOFactory::createWriter($document, 'Excel5');
$objWriter->save("Checklist.xls");

}


?>