<?php

$p = "main"; // Идентификтор страницы

// Проверка переменной страницы
if (isset($_GET['p']))
	$p = strip_tags(htmlspecialchars($_GET['p']));


// Обработка входящего сообщения
if ($p == "newmsg") {
	$json['success'] = "error"; // Идентификатор ошибки

	// Проверка метода отправки
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		$json['error'] = "No data";
		exit(json_encode($json));
	}
	else {
		// Проверка от стороннего выполнения
		$ip = $_SERVER['REMOTE_ADDR'];
		$browser = $_SERVER['HTTP_USER_AGENT'];
		if (isset($_POST['check'])) {
			if ($_POST['check'] != md5($ip.$browser)) {
				$json['error'] = "Несанкционированный доступ";
				exit(json_encode($json));
			}
		}
		else {
			$json['error'] = "Ошибка данных";
			exit(json_encode($json));
		}


		// Првоерка имени
		if (isset($_POST['name'])) {
			if (preg_match("/[^a-zA-Z0-9\s+]/i", $_POST['name'])) {
				$json['error'] = "Имя должно содержать только латинские буквы и цифры";
				exit(json_encode($json));
			}
			else if (empty($_POST['name'])) {
				$json['error'] = "Укажите своё имя";
				exit(json_encode($json));
			}
			else {
				$json['name'] = htmlspecialchars(addslashes($_POST['name']));
			}
		}
		else {
			$json['error'] = "Ошибка данных";
			exit(json_encode($json));
		}

		// Првоерка адреса почты
		if (isset($_POST['email'])) {
			if (!preg_match("/^([a-zA-Z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$/i", $_POST['email'])) {
				$json['error'] = "Указан неправильный адрес электронной почты";
				exit(json_encode($json));
			}
			else if (empty($_POST['email'])) {
				$json['error'] = "Укажите адрес своей электронной почты";
				exit(json_encode($json));
			}
			else {
				$json['email'] = htmlspecialchars(addslashes($_POST['email']));
			}
		}
		else {
			$json['error'] = "Ошибка данных";
			exit(json_encode($json));
		}

		// Проверка адреса сайта
		$json['site'] = "";
		if (isset($_POST['site'])) {
			if (!empty($_POST['site'])) {
				$site = postGet($_POST['site']);
				if (strripos($site, 'https://') === false) {
				    if (strripos($site, 'http://') === false) {
				    	$site = "http://" . $site;
				    }
				}
				if (preg_match("/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?\/?/Diu", $site)) {
					$json['site'] = $site;
				}				
			}
		}
		else {
			$json['error'] = "Ошибка данных";
			exit(json_encode($json));
		}

		// Првоерка сообщения
		if (isset($_POST['msg'])) {
			$text = htmlspecialchars(addslashes($_POST['msg']));
			if (empty($text)) {
				$json['error'] = "Укажите текст сообщения";
				exit(json_encode($json));
			}
			else {
				$json['msg'] = $text;
			}
		}
		else {
			$json['error'] = "Ошибка данных";
			exit(json_encode($json));
		}
	}

	$json['success'] = "ok";
	$json['time'] = getDateRus(time(), true);

	$data = [
		'userName' => $json['name'],
		'email' => $json['email'],
		'homepage' => $json['site'],
		'text' => $json['msg'],
		'time' => time(),
		'ip' => $ip,
		'browser' => $browser
	];

	$query = "INSERT INTO `gust_book` SET 
		`userName` = '{$data['userName']}',
		`email`= '{$data['email']}',
		`homepage` = '{$data['homepage']}',
		`text` = '{$data['text']}',
		`time` = '{$data['time']}',
		`ip` = '{$data['ip']}',
		`browser` = '{$data['browser']}'
	";
	mysqli_query($mysql, $query);
	$json['id'] = mysqli_insert_id($mysql);

	exit(json_encode($json));
}
// Главная страница
// Вывод сообщений из гостевой книги
else {
	// Количество записей на страницу
	$lim = 25;
	$offset = 0; // Смещение в БД

	// Количество сообщений в БД
	$query = "SELECT `id` FROM `gust_book`";
	$count = mysqli_num_rows(mysqli_query($mysql, $query));

	// Количество страниц
	$pages = ceil($count/$lim);
	if ($pages == 0) $pages = 1;

	$thisPage = 1;
	// Проверка открытой страницы
	if (isset($_GET['p'])) {
		$thisPage = postGet($_GET['p'],1);

		if ($thisPage == 0) $thisPage = 1;
		else if ($thisPage >= $pages) $thisPage = $pages;

		// Смещение вывода сообщений из БД
		$offset = ($thisPage*$lim)-$lim;
	}

	$sort = "time";
	$sc = "DESC";
	// Сортировка
	if (isset($_COOKIE['sort'])) {
		$cook = explode("-", htmlspecialchars(postGet($_COOKIE['sort'])));
		if ($cook[0] == "date") $sort = "time";
		elseif ($cook[0] == "name") $sort = "userName";
		elseif ($cook[0] == "mail") $sort = "email";
		if ($cook[1] == "up") $sc = "ASC";
		elseif ($cook[1] == "down") $sc = "DESC";
	}

	// Получение сообщений
	$msgs = [];
	$sql = "SELECT * FROM `gust_book` ORDER BY `$sort` $sc LIMIT $offset, $lim";
	$query = mysqli_query($mysql, $sql);
	while ($row = mysqli_fetch_assoc($query)) {
		$row['time'] = getDateRus($row['time'], true);
		$msgs[] = $row;
	}

	// Идентификатор страницы вывода
	$body = "gusetBook.html";
}
