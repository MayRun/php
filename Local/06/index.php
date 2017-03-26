<?php
/*«адание 6

–еализовать функцию, осуществл¤ющую разбор url-адреса
ƒл¤ решени¤ задачи использовать “ќЋ№ ќ функции дл¤ работы со строками (нельз¤ использовать регул¤рные выражени¤)

–азместить код в одном файле 06/index.php

Ќеобходимые услови¤ сдачи:
- написать функцию my_url_parse(), получающую на вход url-адрес и возвращающую массив следующего вида:
(на примере: protocol://subdomain.domain3.domain2.zone:port/folder/subfolder/../././//../myfolder/script.php?var1=val1&var2=val2)
array(
'protocol'	=> 'protocol',
'domain'	=> 'subdomain.domain3.domain2.zone',
'zone'		=> 'zone',
'2_level_domain' => 'domain2.zone',
'port'		=> 'port',
'raw_folder'	=> 'folder/subfolder/../././//../myfolder/',
'folder'	=> 'myfolder/',
'script_path'	=> 'myfolder/script.php',
'script_name'	=> 'script.php',
'is_php'	=> true,
'parameters' => array(
'var1' => 'val1',
'var1' => 'val1',
),
'is_error'	=>false
)

- люба¤ часть выражени¤ может отсутствовать.
- если отсутствует протокол, то url определ¤етс¤ как относительный путь
(дл¤ subdomain.domain3.domain2.zone:port/folder/subfolder/../../../myfolder/script.php?var1=val1&var2=val2))
'domain'	=> false,
'raw_folder'	=> 'subdomain.domain3.domain2.zone:port/folder/subfolder/../../../myfolder/',
'folder'	=> 'myfolder/'

- если количество поддоменов > 5, устанавливать флаг ошибки (is_error)
- дл¤ пути к файлу на сервере вычислить его действительное (folder) и введенное (raw_folder) значени¤:
учесть следующие конструкции:
./ - остаемс¤ в той же папке
../ - поднимаемс¤ на уровень вверх (но нельз¤ выйти за доменное им¤!)
много // - эквивалентно /
вычисление пути оформить отдельной функцией
- если не указан сценарий, но есть строка параметров, то значит указать сценарий index.php:
(дл¤ myfolder/?var1=val1&var2=val2)
'script_name'	=> 'index.php',

- строка параметров может содержать вопросы:
?var1=is_it_ok?&or=not?
- если параметры в строке параметров повтор¤ютс¤, то правильное значение - в последнем!*/


function my_url_parse($url) //Функция для распарсивания url-адреса
{
	print($url . " : ");
	$my_url_parse_arr = array(
		'protocol' => protocol($url),
		'domain' => domain($url),
		'zone' => zone_parse($url),
		'2_level_domain' => domain2_parse($url),
		'port' => port($url),
		'raw_folder' => raw_folder($url),
		'folder' => folder($url),
		'script_path' => folder($url) . script_name($url),
		'script_name' => script_name($url),
		'is_php' => is_php($url),
		'parameters' => variable($url),
		'is_error' => is_error($url)
	);
	var_dump($my_url_parse_arr);
	
}
function is_error($url) //Функция проверяюшая если ошибки в url-адресe
{
	if (substr_count(domain($url), ".") >= 5) {
		return TRUE;
	} else {
		return FALSE;
	}
}
function domain($url) //Функция для парсинга domain
{
	$dot_position         = strpos($url, ".");
	$slash_position       = strpos($url, "/");
	$url_whitout_protocol = "";
	$portocol_index       = strpos($url, "://");
	if (protocol($url) === FALSE) {
		$url_whitout_protocol = $url;
	}
	
	elseif ($portocol_index < $dot_position) {
		$url_whitout_protocol = substr($url, $portocol_index + 3);
	}
	$doubledot = strpos($url_whitout_protocol, ":");
	$slash_position = strpos($url_whitout_protocol, "/");
	if($doubledot <= $slash_position)
	{
		return substr($url_whitout_protocol, 0, $doubledot);
	}
	else{
	return substr($url_whitout_protocol, 0, strpos($url_whitout_protocol, "/"));
	}
}

function port($url)//Распарсивание порта url-адреса
{
	if (strpos($url, "url") !== FALSE) {
		$url = substr($url, strpos($url, "url"));
	}
	$protocol            = protocol($url);
	$wo_ptotocol         = substr($url, strlen($protocol) + 3);
	$position_double_dot = strpos($wo_ptotocol, ":");
	if ($position_double_dot === FALSE) {
		return FALSE;
		
	}
	$position_slash = strpos($wo_ptotocol, "/");
	if (($position_slash > strpos($url, "?")) && ($position_double_dot > strpos($url, "?"))) {
		return FALSE;
	}
	$port = substr($wo_ptotocol, $position_double_dot + 1, $position_slash - $position_double_dot - 1);
	return $port;
	
}


function protocol($url) // Распарсивание протокола
{
	$portocol_index = strpos($url, "://");
	$dot_position   = strpos($url, ".");
	if ($portocol_index < $dot_position) {
		return substr($url, 0, $portocol_index);
	} else {
		return FALSE;
	}
}
function zone_parse($url) //Распарсивание зоны
{
	$index   = strripos(domain($url), '.') + 1;
	$zone    = substr(domain($url), $index);
	$zonelen = strlen($zone);
	if ($zonelen != 0) {
		return $zone;
	} else {
		return FALSE;
	}
}
function domain2_parse($url) //Распарсивание домена 2 уровня
{
	
	$domains_str    = substr(domain($url), 0, strripos(domain($url), '.'));
	$domain_2_index = strripos($domains_str, '.');
	if (strlen($domains_str) == 0) {
		return FALSE;
	}
	if ($domain_2_index === FALSE) {
		return $domains_str . '.' . zone_parse($url);
	} else {
		return substr($domains_str, $domain_2_index + 1) . '.' . zone_parse($url);
	}
}
function raw_abs_folder($url)
{
	$raw_folder = raw_folder($url);
	if (protocol($url) === FALSE) {
		$url = "http://" . $url;
		return domain($url) . $raw_folder;
	} else {
		return $raw_folder;
	}
	
}
function raw_folder($url) //Распарсивание пути
{
	$url_whitout_protocol = "";
	$folder_wo_script     = "";
	$portocol_index       = strpos($url, "://");
	$dot_position         = strpos($url, "?");
	
	if ($portocol_index < $dot_position) {
		$url_whitout_protocol = substr($url, $portocol_index + 3);
	}
	if (strpos($url_whitout_protocol, "/?") == strpos($url_whitout_protocol, "/")) {
		return "/";
	}
	$begin_raw_folder = strpos($url_whitout_protocol, "/");
	if ($begin_raw_folder === FALSE) {
		return "/";
	}
	$end_raw_folder = strpos($url_whitout_protocol, "?");
	
	
	$raw_folder  = substr($url_whitout_protocol, $begin_raw_folder, $end_raw_folder - $begin_raw_folder);
	$dot_index   = strrpos($raw_folder, ".");
	$slash_index = strrpos($raw_folder, "/");
	if ($dot_index > $slash_index) {
		$folder_wo_script = substr($raw_folder, 1, $slash_index);
	} else {
		$folder_wo_script = $raw_folder;
	}
	return $folder_wo_script;
	
}
function folder($url) // Распарсивание пути
{
	$folder     = array();
	$temp       = "";
	$raw_folder = raw_folder($url);
	$count_dot  = 0;
	for ($i = 0; $i < strlen($raw_folder) - 1; ++$i) {
		if (($raw_folder[$i] == ".") && ($raw_folder[$i] == ".")) {
			$count_dot = $count_dot + 2;
		}
		if ($raw_folder[$i] == "/") {
			while ($raw_folder[$i] == "/") {
				++$i;
			}
			if (($count_dot == 0) && (strlen($temp))) {
				array_push($folder, $temp);
				$temp = "";
			}
			if ($count_dot == 2) {
				array_pop($folder);
				$count_dot = 0;
				
			}
			
		}
		if (($raw_folder[$i] != ".") && ($raw_folder[$i] != "/")) {
			$temp .= $raw_folder[$i];
		}
		
	}
	return implode("/", $folder) . "/";
}

function script_name($url) // Название скрипта
{
	$folder = raw_folder($url);
	$begin  = strpos($url, $folder);
	if ($begin === FALSE) {
		return "index.php";
	}
	$index = strpos($url, "/?");
	if ($index !== FALSE) {
		return "index.php";
	}
	$end = strpos($url, "?");
	if ($end < $begin) {
		return "index.php";
	}
	$script = substr($url, $begin + strlen($folder), $end - $begin - strlen($folder));
	if (strlen($script) != 0) {
		return $script;
	} else {
		return "index.php";
	}
	
	
}
function is_php($url) //Проверка файла скипта на расширение
{
	$script       = script_name($url);
	$dot_position = strpos($script, ".");
	$extension    = substr($script, $dot_position + 1);
	if ($extension == "php") {
		return TRUE;
	} else {
		return FALSE;
	}
}
function variable($url) //Функция для получения переменных
{
	if (strpos($url, "url") !== FALSE) {
		my_url_parse(substr($url, strpos($url, "url") + 4));
		$url = substr($url, 0, strpos($url, "url") - 1);
	}
	
	$variables = array();
	$var       = array();
	$pos_var   = strpos($url, "?");
	$vars_str  = substr($url, $pos_var + 1);
	$vars      = explode("&", $vars_str);
	$count_var = count($vars);
	for ($i = 0; $i < $count_var; ++$i) {
		array_push($variables, explode("=", $vars[$i]));
		$temp       = $variables[$i][0];
		$var[$temp] = $variables[$i][1];
		if ($temp == "url") {
			my_url_parse($var[$temp]);
		}
	}
	
	return $var;
}
$url1 = 'http://http.ru/folder/subfolder/../././script.php?var1=val1&var2=val2';
$url2 = 'https://http.google.com/folder//././?var1=val1&var2=val2';
$url3 = 'ftp://mail.ru/?hello=world&url=https://http.google.com/folder//././?var1=val1&var2=val2';
$url4 = 'mail.ru/?hello=world&url=https://http.google.com/folder//././?var1=val1&var2=val2';
$url5 = '?mail=ru';
$url6 = 'http://dom.dom.domain2.com:8080/folder/subfolder/./myfolder/script.php?var1=val1&var2=val2?var1=val1&var2=val2';

my_url_parse($url1);
my_url_parse($url2);
my_url_parse($url3);
my_url_parse($url4);
my_url_parse($url5);
my_url_parse($url6);