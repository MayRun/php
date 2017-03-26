<?php 
 

$table_x_size = 9; // ���������� �������� 
$table_y_size = 13; //���������� ����� 

//���������� ��� ������������ ������ �� ����� 
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

//������� ����� ������� 
for ($row_index = 1; $row_index < $table_y_size + 1; ++$row_index) { 

//��������� ����������� ��� ������ ������� 
$result .= '<tr>'; 

//������� ����� ������ 
for ($col_index = 1; $col_index < $table_x_size + 1; ++$col_index) { 

//��������� �������� ����� ������ � ������ ������� �������/������ 
$result .= ($col_index == 1 || $row_index == 1) 
? '<td id = "red">' 
: '<td>'; 

//��������� ����� �����, ���� ��� �� � ������ ������/������� � �������� ��������� ������ ����� 
$result .= (((sqrt($row_index * $col_index)) * 1000 % 1000 == 0) && ($col_index != 1 && $row_index != 1)) 
? '<span>' . $row_index * $col_index . '</span>' 
: $row_index * $col_index; 

//��������� ����������� ��� ������ 
$result .= '</td>'; 
} 

//��������� ����������� ��� ������ ������� 
$result .= '</tr>'; 
} 

//����� ���������� 
echo $result. '</body></html>'; 

?>