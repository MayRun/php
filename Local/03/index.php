<?php 
 

$table_x_size = 9; // Количество столбцов 
$table_y_size = 13; //Количество строк 

//Накопитель для последующего вывода на экран 
$result = '<table> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<style type="text/css"> 
td 
{ 
padding: 5px; 
border: 1px solid black; 
} 
#red 
{ 
background-color: green; 
} 
#blue 
{ 
background-color: blue; 
} 
span 
{ 
color: red; 
font-weight: bold; 
} 
</style> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>Task3</title> 
</head> 

<body>'; 

//Перебор строк таблицы 
for ($row_index = 1; $row_index < $table_y_size + 1; ++$row_index) { 

//Добавляем открывающий тег строки таблицы 
$result .= '<tr>'; 

//Перебор ячеек строки 
for ($col_index = 1; $col_index < $table_x_size + 1; ++$col_index) { 

//Установка фонового цвета ячейки в случае первого столбца/строки 
$result .= ($col_index == 1 || $row_index == 1) 
? '<td id = "red">' 
: '<td>'; 

//Установка цвета числа, если оно не в первой строке/столбце и является квадратом целого числа 
$result .= (((sqrt($row_index * $col_index)) * 1000 % 1000 == 0) && ($col_index != 1 && $row_index != 1)) 
? '<span>' . $row_index * $col_index . '</span>' 
: $row_index * $col_index; 

//Добавляем закрывающий тег ячейки 
$result .= '</td>'; 
} 

//Добавляем закрывающий тег строки таблицы 
$result .= '</tr>'; 
} 

//Вывод результата 
echo $result. '</body></html>'; 

?>