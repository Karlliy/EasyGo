<?php
	ini_set('SHORT_OPEN_TAG',"On"); 				// 是否允許使用\"\<\? \?\>\"短標識。否則必須使用\"<\?php \?\>\"長標識。
	ini_set('display_errors',"On"); 				// 是否將錯誤信息作為輸出的一部分顯示。
	ini_set('error_reporting',E_ALL & ~E_NOTICE);
	ini_set('memory_limit',"-1");					// 一個腳本所能夠申請到的最大內存字節數(可以使用K和M作為單位)。如果要取消內存限制，則必須將其設為 -1 。
	header('Content-Type: text/html; charset=utf-8');
	
	date_default_timezone_set("Asia/Taipei");
	
	if (!class_exists('CommonElement'))		include_once("./CommonElement.php");
	CommonElement::Run();
	define("SiteID", "H");
	define("Base_Company", "快易購管理平台");
	define("Base_Address", " ");
	define("Base_URL", "");
	define("Base_TEL", " ");
	define("Base_FAX", " ");
	
?>