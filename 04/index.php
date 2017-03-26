<?php
	/*Задание 4
	
	Реализовать выравнивание массивов слов с помощью пробелов и переносов строк
	Разместить код в одном файле 04/index.php
	
	Необходимые условия сдачи:
	- задать массив следующей структуры:
	$word_arrays_arr = array(
	array("word1.1", "bigword1.2", "moreword1.3", ...),
	array("word2.1", "bigword2.2", "moreword2.3", ...),
	array("word3.1", "bigword3.2", "moreword3.3", ...),
	...
	)
	- подмассивов может быть произвольное количество.
	- слов в каждом массиве может быть произвольное количество.
	- длина слова может быть произвольной.
	- вывести подмассивы вертикально, выравнивая их поочередно (по левому краю - правому - левому - и.т.д.):
	
	word1.1          word2.1  word3.1      ...
	bigword1.2    bigword2.2  bigword3.2    
	moreword1.3  moreword2.3  moreword3.3  
	...
	- выравнивать внутри тега <pre> с использованием ТОЛЬКО " " и "\n"
	- использовать foreach() (а не for())
	- можно объявить не более 4 своих функций
	- исходный массив можно изменять
	- вывод должен быть реализован единожды в конце файла
	- программа должна соответствовать "требованиям к оформлению php.docx"*/
	
	//Инициализация массива 
$word_arrays_arr = array( 
	array("word1.1", "bigword1.2", "moreword1.3"),
    array("word2.1", "bigword2.2", "moreword2.3"),
    array("word3.1", "bigword3.2", "moreword3.3", "word3.4", "bigword3.5", "moreword3.6"),
    array("word4.1", "bigword4.2", "moreword4.3"),
    array("word5.1", "bigword5.2", "moreword5.3"));

$large_arr_size = 0; // Длина самого большого столбца 
	$max_strlen_arr = array(); // Массив длин самых больших строк для каждого столбца 
	$result_str     = '<pre>'; // Результат для вывода
	
	// Перебираем все столбцы 
	foreach ($word_arrays_arr as $column) {
		// Определяем максимальную длину столбца 
		if (count($column) > $large_arr_size)
			$large_arr_size = count($column);
		
		// Временная переменная 
		$temp = 0;
		
		// Определяем максимальную длину слова в текущем столбце
		foreach ($column as $str) {
			if (strlen($str) > $temp)
				$temp = strlen($str);
		}
		
		// Сохраняем максимальную длину слова в текущем столбце 
		$max_strlen_arr[] = $temp;
	}
	
	// Перебираем строки во всех столбцах 
	for ($str_count = 0; $str_count < $large_arr_size; ++$str_count) {
		$index = 0;
		// Проходим все столбцы по строке с номером str_count 
		foreach ($word_arrays_arr as $column) {
			
			// Осуществляем расстановку пробелов и запись строки в результирующую переменную 
			if (isset($word_arrays_arr[$index][$str_count])) {
				// В нечетном столбце пробелы дополняются справа 
				if ($index % 2 != 0) {
					$result_str .= str_repeat(' ', $max_strlen_arr[$index] - strlen($word_arrays_arr[$index][$str_count]) + 1);
					$result_str .= $word_arrays_arr[$index][$str_count] . ' ';
				}
				// В четном столбце пробелы дополняются слева 
				else if ($index % 2 == 0) {
					$result_str .= ' ' . $word_arrays_arr[$index][$str_count];
					$result_str .= str_repeat(' ', $max_strlen_arr[$index] - strlen($word_arrays_arr[$index][$str_count]) + 1);
				}
			}
			// Осуществляем расстановку пробелов для отсутствующей строки в столбце 
			else {
				$result_str .= str_repeat(' ', $max_strlen_arr[$index] + 2);
			}
			$index = $index + 1;
		}
		
		// Завершаем очередную строку переходом на новую 
		$result_str .= "\n";
		
	}
	
	// Завершаем результирующую строку звкрывающимся тегом 
	$result_str .= '</pre>';
	
	// Выводим результа
	print($result_str);
?>
	