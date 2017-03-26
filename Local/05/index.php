<?php
	
	/* 
	Задание 5
	
	Реализовать генерацию ключей в формате xxxxx-xxxxx-xxxxx-yyyyy
	Разместить код в одном файле 05/index.php
	
	Необходимые условия сдачи:
	- задать алфавит ключа переменной:
	$allowed_symbols = "0123456789abcdef";
	- ключ состоит из случайной части (позиции, обозначенные "х") и проверочной части (позиции, обозначенные "y")
	- проверочная часть должна зависеть по некоторому алгоритму от остальной части ключа (каждый реализует свой алгоритм, кто первый закоммитил, тому он идет в зачет)
	- количество фрагментов ключа (разделенных "-"), и размер фрагмента должны легко меняться. При этом размер проверочной части всегда совпадает с размером фрагмента.
	- реализовать функцию generate_keys($__keys_number), генерирующую список из n различных ключей (ключи не повторяются!)
	- проверить, что запрошенное количество ключей для генерации не превосходит множество различных ключей.
	- Реализовать функцию is_valid_key($__key), проверяющую формат ключа? Продемонстрировать ее работу на 2х ключах (плохом и хорошем).
	- программа должна соответствовать "требованиям к оформлению php.docx"
	
	*/
	/*Инициализация таблицы Кэли для GF(2^4)*/
	$additional_table_arr = array(
		array(0,	1,	2,	3,	4,	5,	6,	7,	8,	9,	10,	11,	12,	13,	14,	15), 
		array(1,	0,	3,	2,	5,	4,	7,	6,	9,	8,	11,	10,	13,	12,	15,	14),
		array(2,	3,	0,	1,	6,	7,	4,	5,	10,	11,	8,	9,	14,	15,	12,	13),
		array(3,	2,	1,	0,	7,	6,	5,	4,	11,	10,	9,	8,	15,	14,	13,	12),
		array(4,	5,	6,	7,	0,	1,	2,	3,	12,	13,	14,	15,	8,	9,	10,	11),
		array(5,	4,	7,	6,	1,	0,	3,	2,	13,	12,	15,	14,	9,	8,	11,	10),
		array(6,	7,	4,	5,	2,	3,	0,	1,	14,	15,	12,	13,	10,	11,	8,	9),
		array(7,	6,	5,	4,	3,	2,	1,	0,	15,	14,	13,	12,	11,	10,	9,	8),
		array(8,	9,	10,	11,	12,	13,	14,	15,	0,	1,	2,	3,	4,	5,	6,	7),
		array(9,	8,	11,	10,	13,	12,	15,	14,	1,	0,	3,	2,	5,	4,	7,	6),
		array(10,	11,	8,	9,	14,	15,	12,	13,	2,	3,	0,	1,	6,	7,	4,	5),
		array(11,	10,	9,	8,	15,	14,	13,	12,	3,	2,	1,	0,	7,	6,	5,	4),
		array(12,	13,	14,	15,	8,	9,	10,	11,	4,	5,	6,	7,	0,	1,	2,	3),
		array(13,	12,	15,	14,	9,	8,	11,	10,	5,	4,	7,	6,	1,	0,	3,	2),
		array(14,	15,	12,	13,	10,	11,	8,	9,	6,	7,	4,	5,	2,	3,	0,	1),
		array(15,	14,	13,	12,	11,	10,	9,	8,	7,	6,	5,	4,	3,	2,	1,	0)
		 );
	/* Строка с возмлжными символами*/
	$allowed_symbols      = "0123456789abcdef";
	$fragmentsize         = 4; // Размер фрагмента
	$countfragment        = 3; // Количество фрагментов
	
	function generate_keys($__keys_number) // Генерирует $__keys_number ключей
	{
		global $countfragment;
		global $fragmentsize;
		global $allowed_symbols;
		
		/*Проверка возможно ли  получить $__keys_number ключей*/
		if ($__keys_number > strlen($allowed_symbols) * $countfragment * $fragmentsize) {
			print("Количество ключей больше чем возможно \n");
			return FALSE;
		}
		/* Если возможно, то генерируем и заносим в массив*/
		$keys_arr = array();
		
		for ($i = 0; $i < $__keys_number; ++$i) {
			
			$temp = generate_one_key();
			if (!in_array($temp, $keys_arr)) {
				array_push($keys_arr, $temp);
			} else {
				--$i;
			}
		}
		print_r($keys_arr);
		return $keys_arr;
	}
	function generate_one_key() // Генерирует один ключ
	{
		global $countfragment;
		global $fragmentsize;
		global $allowed_symbols;
		$key = "";
		//Генерация рандомной части
		for ($i = 0; $i < $countfragment; ++$i) {
			for ($j = 0; $j < $fragmentsize; ++$j) {
				$key .= $allowed_symbols[rand(0, strlen($allowed_symbols) - 1)];
			}
			$key .= "-";
		}
		//Генерация проверочной части
		$key .= verification_fragment($countfragment, $fragmentsize, $key);
		return $key;
	}
	
	
	function verification_fragment($countfragment, $fragmentsize, $random_fragment) //Генерация проверочной части
	{
		$verification_fragment = "";
		global $additional_table_arr;
		global $allowed_symbols;
		/*Складываем из каждого фрагмента элементы под номером $s в аддитивной группе поля GF(16)*/
		for ($s = 0; $s < $fragmentsize; ++$s) {
			$sum = 0;
			$n   = 0;
			
			while ($n < $countfragment) {
				$temp = $random_fragment[$s + ($fragmentsize + 1) * $n];
				//Для сложения двух чисел обращаемся в таблицу Кели
				$sum  = $additional_table_arr[$sum][base_convert($temp % 16 - 1, 16, 10)];
				++$n;
			}
			//Добавляем в проверочный фрагмент сумму
			$verification_fragment .= $allowed_symbols[$sum];
		}
		return $verification_fragment;
	}
	function is_valid_key($__key) //Проверка на валидность ключа
	{
		global $allowed_symbols;
		$symbols = $allowed_symbols . "-";
		/*Проверка на то, что все сиволы ключа находятся в разрешенных символах*/
		for ($j = 0; $j < strlen($__key); ++$j) {
			if (strpos($symbols, $__key[$j]) === FALSE) {
				print($__key . " false \n");
				return FALSE;
			}
		}
		/*Делим ключ на фрагменты*/
		$fragments        = explode("-", $__key);
		$__fragmentscount = count($fragments); // Количество фрагментов
		$__size           = strlen($fragments[0]); //Размер первого фрагмента
		/*Проверка на размер и проверочного фрагмента*/
		for ($i = 1; $i < $__fragmentscount - 1; ++$i) {
			if ($__size != strlen($fragments[$i])) {
				print($__key . " false \n");
				return FALSE;
			}
			$rest                         = substr($__key, 0, -$__size);
			$_check_verification_fragment = verification_fragment($__fragmentscount - 1, $__size, $rest);
			if ($_check_verification_fragment != $fragments[$__fragmentscount - 1]) {
				print($__key . " false \n");
				return FALSE;
				
			}
		}
		print($__key . " true \n");
		return TRUE; // Если все условия выполнены, то ключ валидный
	}
	print('<pre/>');
	generate_keys(10);
	is_valid_key(generate_one_key());
	is_valid_key("3983-34292-5a4f1-4a3e3");
	print('</pre>');