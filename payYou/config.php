<?php
ini_set('date.timezone', 'Europe/Moscow');
$bd_host = "localhost"; //Адрес БД
$bd_user = "ka66_07";  //Пользователь БД
$bd_password = "123456";  //Пароль от базы
$bd_base = "ka66_07";   //База данных
$con = mysql_connect($bd_host, $bd_user, $bd_password); mysql_select_db($bd_base, $con); 
mysql_query("set names 'utf8'"); 
mysql_query ("set character_set_client='utf8'"); 
mysql_query ("set character_set_results='utf8'"); 
mysql_query ("set collation_connection='utf8_general_ci'");  
?>