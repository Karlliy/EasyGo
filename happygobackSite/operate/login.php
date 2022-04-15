<?php
	session_start(); 
	ini_set('SHORT_OPEN_TAG',"On"); 				// 是否允许使用\"\<\? \?\>\"短标识。否则必须使用\"<\?php \?\>\"长标识。
	ini_set('display_errors',"On"); 				// 是否将错误信息作为输出的一部分显示。
	ini_set('error_reporting',E_ALL & ~E_NOTICE);

	header('Content-Type: text/html; charset=utf-8');

	if (!class_exists('CDbShell'))			include_once("../BaseClass/CDbShell.php");
	if (!class_exists('CSession'))			include_once("../BaseClass/CSession.php");  
	if (!class_exists('CUrlQuery'))			include_once("../BaseClass/CUrlQuery.php");
	if (!class_exists('JSModule'))			include_once("../BaseClass/JSModule.php");
    if (!class_exists('Operate'))			include_once("../Operate/Operate.php");
	if (!class_exists('CommonElement'))		include_once("../BaseClass/CommonElement.php");
	if (!class_exists('Setting'))			include_once("../BaseClass/Setting.php");
    // include_once("./check_login.php");

    switch ($_SERVER["func"]) {
        case "Login":
            Login();
            break;
        // case "Checklogin" ;
        //     Checklogin();
        //     break;
        // case "Logout":
        //     Logout();
        //     break;
    }

    function Login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {//echo($_POST["txtUser"]);echo($_POST["txtPassword"]);exit;
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                
                //throw new exception("遊戲維護中，本日維護時間至12:30!");
                // if ($_POST['mode'] == "test") {
                // 	CSession::setVar("mode", "test");
                // 	echo "window.location.href=' ./Game'"; 
                // 	exit;
                // }
                if (strlen($_POST["txtUser"]) < 1) {
                    throw new exception("請輸入帳號!");
                }
                
                if (strlen($_POST["txtPassword"]) < 1) {
                    throw new exception("請輸入密碼!");
                }
                
                CDbShell::Connect();
                CDbShell::query("SELECT * FROM administrator WHERE AdminAccount='".$_POST["txtUser"]."'  AND AdminPW = '".MD5($_POST["txtPassword"])."' " );
                // echo "SELECT * FROM administrator WHERE AdminAccount='".$_POST["txtUser"]."'  AND AdminPW = '".MD5($_POST["txtPassword"])."'";
                // exit;
                if (CDbShell::num_rows() == 1) {
                    $Row = CDbShell::fetch_array();
                    // $myip = get_client_ip(0, true);
                    // ini_set('session.gc_maxlifetime', 2*60*60*24); //2 days
                    CommonElement::Add_S($_SESSION);
                    CSession::setVar("admin_Account", $_POST["txtUser"]);
                    CSession::setVar("admin_Password", MD5($_POST["txtPassword"]));
                    CSession::setVar("admin_NickName", $Row["NickName"]);
                    CSession::setVar("id", $Row["RowId"]);
                    CSession::setVar("admin_purview", $Row["Privilege"]);
                    CSession::setVar("IsAdmin", $Row["IsAdmin"]);
                    // echo $Row["Privilege"];echo preg_split('/&/', CSession::GetVar("admin_purview"))[0];exit;
                    CDbShell::query("UPDATE administrator SET IsOnline = 1, LoginCount=LoginCount+1, LastLoginTime = '".Date('Y-m-d H:i:s')."' WHERE AdminAccount='".$_POST["txtUser"]."' AND AdminPW = '".MD5($_POST["txtPassword"])."' ");
                    if ('1' != CSession::GetVar("IsAdmin") && '' == CSession::GetVar("admin_purview")) {
                        session_destroy();
                        throw new exception("抱歉!您無權限進入管理後台");
                    } else {
                        $page = CSession::GetVar("admin_purview") ? preg_split('/&/', CSession::GetVar("admin_purview"))[0] : 'Home/Dashboard';
                        // $js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
                        $js .= "window.location.href=\"$page\";\r\n";
                        // $js .= "</script>\r\n";
                        echo $js;
                        exit;
                    }
                    // echo "window.location.href='Member/Members'";
                    // exit;
                }else {
                    throw new exception("帳號密碼錯誤!");
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
    }

    // function Checklogin() {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         CDbShell::Add_S($_POST);
    //         if (!isset($_SESSION)) {
    //             CDbShell::Add_S($_SESSION);
    //         }    
    //         //確認登入狀態
    //         if (!CSession::GetVar("id")) {
    //             // $js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
    //             $js .= "window.location.href=\"../index.php\";\r\n";
    //             // $js .= "</script>\r\n";
    //             echo $js;
    //             exit;
    //         }
    //         //檢查登入帳號是否合法
    //         CDbShell::Connect();
    //         // echo CSession::GetVar("IsAdmin");exit;
    //         CDbShell::query("SELECT * FROM administrator WHERE AdminAccount='".CSession::GetVar("Account")."'  AND AdminPW = '".CSession::GetVar("Password")."' " );
    //         $Row = CDbShell::fetch_array();
    //         // while ($Row = CDbShell::fetch_array());
    //         $purview = preg_split('/&/', CSession::GetVar("admin_purview"));
    //         $data=[];
    //         for($i=0;$i<=count($purview);$i++){
    //             array_push($data,$purview[$i]);
    //         }
    //         // var_dump($data);exit;
    //         // $data= array($Row["ProductTitle"],$Row["ProductNumber"],$Row["ProductName"],$Row["GamePlatform"],$Row["GameCoinQuantity"]
    //         //             ,$Row["CreateDate"],$Row["TypeName"],$Row["GameServer"],$Row["Price"],$Row["LevelName"],$Row["PointCardKind"],$Row["Currency"],$Row["KuTsuenQuantity"]
    //         //             ,$Row["Sellmember"],$Row["Buymember"],$Row["GameName"],$Row["CurrencyValue"]);
    //         echo json_encode($purview);exit;
    //         // if (0 != intval($row['ResultCode'])) {
    //         //     session_destroy();
    //         //     // $js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
    //         //     $js .= 'alert("'.$row['ResultMessage']."\");\r\n";
    //         //     $js .= "window.location.href=\"../index.php\";\r\n";
    //         //     // $js .= "</script>\r\n";
    //         //     echo $js;
    //         //     exit;
    //         // }
    //         //取得轉跳前進入的頁面
    //         // $currentFile = $_SERVER['PHP_SELF'];
    //         // $parts = explode('/', $currentFile);
    //         // $exe_name = $parts[count($parts) - 1];
    //         // echo $currentFile;exit;
        
    //         //若登入者為不為子帳號則首頁預設為home.php 反之預設為權限列表內的第一個位置 (防止F5重整後跑入迴圈)
    //         // $purview = CSession::GetVar("IsAdmin") ? ['Home/Dashboard'] : preg_split('/&/', CSession::GetVar("admin_purview"));
        
    //         //確認轉跳前頁面是否為check_login 否則將轉跳頁設為首頁(發生在重整頁面)
    //         // if ('Login/Checklogin.php' == $exe_name) {
    //         //     // $js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
    //         //     $js .= 'window.location.href="'.$purview[0]."\";\r\n";
    //         //     // $js .= "</script>\r\n";
    //         //     echo $js;
    //         //     exit;
    //         // } elseif (!CSession::GetVar("IsAdmin") && !in_array($exe_name, $purview)) {
    //         //     //若登入者為子帳號且欲轉跳之頁面不存在於權限表中則
    //         //     // $js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
    //         //     $js .= "alert(\"抱歉!此管理者无权限进入该页面\");\r\n";
    //         //     // $js .= 'window.location.href="'.$purview[0]."\";\r\n";
    //         //     // // $js .= "</script>\r\n";
    //         //     echo $js;
    //         //     exit;
    //         // }    
    //     }
    // }
 
    


?>