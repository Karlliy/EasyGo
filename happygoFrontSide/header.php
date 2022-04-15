<?php
session_start(); 
ini_set('SHORT_OPEN_TAG',"On"); 				// 是否允许使用\"\<\? \?\>\"短标识。否则必须使用\"<\?php \?\>\"长标识。
ini_set('display_errors',"On"); 				// 是否将错误信息作为输出的一部分显示。
ini_set('error_reporting',E_ALL & ~E_NOTICE);

header('Content-Type: text/html; charset=utf-8');

if (!class_exists('CDbShell'))			include_once("./BaseClass/CDbShell.php");
if (!class_exists('CSession'))			include_once("./BaseClass/CSession.php");  
if (!class_exists('CUrlQuery'))			include_once("./BaseClass/CUrlQuery.php");
if (!class_exists('JSModule'))			include_once("./BaseClass/JSModule.php");
if (!class_exists('Operate'))			include_once("./operate/Operate.php");
if (!class_exists('CommonElement'))		include_once("./BaseClass/CommonElement.php");
if (!class_exists('Setting'))			include_once("./BaseClass/Setting.php");

if(isset(CSession::GetVar($name))){
    echo CSession::GetVar($name);
    include("headerlogin.html");
}else{
    include("headerlogout.html");
}

?>
