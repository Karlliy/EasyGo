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
    if (!class_exists('Operate'))			include_once("./Operate/Operate.php");
	if (!class_exists('CommonElement'))		include_once("./BaseClass/CommonElement.php");
	if (!class_exists('Setting'))			include_once("./BaseClass/Setting.php");


    switch ($_SERVER["func"]) {
        case "Logout":
            Logout();
            break;
    }

    function Logout() {
        CDbShell::Connect();
        CDbShell::query("UPDATE administrator SET IsOnline = 0 WHERE AdminAccount='".CSession::GetVar("admin_Account")."' AND AdminPW = '".CSession::GetVar("admin_Password")."'" );
        CDbShell::DB_close();
        CSession::ClearVar("admin_Account");
		CSession::ClearVar("admin_Password");
        CSession::ClearVar("admin_NickName");
        CSession::ClearVar("id");
        CSession::ClearVar("admin_purview");
        CSession::ClearVar("IsAdmin");
		$LogOut =
            <<<EOF
            <script>
            window.location.href='../index.php';
            </script>
EOF;
        echo $LogOut;
        exit;
    }



    include("index.html");


?>