<?php
	//-----------------------------------------------------------------------
	//	Функция обработки переменных форм
	//		и переменной из адресной строки
	//		0 - обработка от спецсимволов (по умолчанию)
	//		1 - вернет только цифры
	//
	function postGet($text, $type = 0) {
		$vowels = ["'", '"', ">", "<"];
		$new = str_replace($vowels, "", $text);
		if ($type == 1) {
			$new = preg_replace("/[^0-9\s]/", "", $new);
			$new = str_replace(" ", "", $new);
		}
		elseif ($type == 2) {
			$new = trim(preg_replace("/[^a-zA-Z0-9\s]/", "", $new));
		}
		return $new;
	}
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// Функция вывода массива в аккуратном виде
	//
	function echo_r($arr) {
		echo '<pre class="echor">';
		print_r($arr);
		echo '</pre>';
	}
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
    // Функция вывода красивого времени
    //
    function getDateRus($date, $time = false) {
        if (!$date) return null;
        
        $now = date('z');
        $before = date('z', $date);

        if ($now-$before == 0) $string = "сегодня";
        elseif ($now-$before == 1) $string = "вчера";
        else {
            $m = date('m', $date);
            if ($m == 1) $month = 'янв.';
            elseif ($m == 2) $month = 'фев.';
            elseif ($m == 3) $month = 'мар.';
            elseif ($m == 4) $month = 'апр.';
            elseif ($m == 5) $month = 'мая';
            elseif ($m == 6) $month = 'июня';
            elseif ($m == 7) $month = 'июля';
            elseif ($m == 8) $month = 'авг.';
            elseif ($m == 9) $month = 'сен.';
            elseif ($m == 10) $month = 'окт.';
            elseif ($m == 11) $month = 'нояб.';
            elseif ($m == 12) $month = 'дек.';
            if (date('Y', $date) != date('Y'))
                $string = date('d.m.Y', $date);
            else $string = date('d', $date)." ".$month;
        }

        if ($time) $string .= " в " . date("H:i", $date);

        return $string;
    }
    //-----------------------------------------------------------------------
