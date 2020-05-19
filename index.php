<?php

	$conf = parse_ini_file(".cfg", true); // Подключение настроек проекта

	require_once ("lib/function.php"); // Общие функции

	$mysql = mysqli_connect(
		$conf['mysql']['dbhost'],
		$conf['mysql']['dbuser'],
		$conf['mysql']['dbpass'],
		$conf['mysql']['dbname']
	);

	if (!$mysql) {
		exit("Ошибка подключения к базе данных");
    } else {
		mysqli_query($mysql, 'SET NAMES ' . $conf['mysql']['dbcharset']);
    }

	// Проверка таблицы
	function checkTable($check)
	{
		global $mysql;
		$sql = "SHOW TABLES";
		$result = [];
		$result = mysqli_fetch_assoc(mysqli_query($mysql, $sql));
		if ($result) {
			foreach ($result as $table) {
				if ($table == $check) return true;
			}
		}
		return false;
	}

	if (!checkTable('gust_book')) {
		$query ="CREATE Table gust_book (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			userName VARCHAR(100) NOT NULL,
			email VARCHAR(200) NOT NULL,
			homepage VARCHAR(100) NULL,
			text TEXT NOT NULL,
			time INT(11) NOT NULL,
			ip VARCHAR(100) NOT NULL,
			browser TEXT NOT NULL
		)";
		$result = mysqli_query($mysql, $query) or die("Ошибка " . mysqli_error($mysql));
	}

	// Маршруты
	require_once ("route.php");

	// Вывод страницы
	include __DIR__ . "/html/_head.html";
	include __DIR__ . "/html/" . $body;
	include __DIR__ . "/html/_feet.html";
?>
