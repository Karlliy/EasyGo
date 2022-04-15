<?php

    CDbShell::Add_S($_POST);
    if (!isset($_SESSION)) {
        CDbShell::Add_S($_SESSION);
    }    
    //確認登入狀態
    if (!CSession::GetVar("id")) {
        $js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
        $js .= "window.location.href=\"../index.php\";\r\n";
        $js .= "</script>\r\n";
        echo $js;
        exit;
    }
    //檢查登入帳號是否合法
    CDbShell::Connect();
    // echo CSession::GetVar("IsAdmin");exit;
    CDbShell::query("SELECT * FROM administrator WHERE AdminAccount='".CSession::GetVar("admin_Account")."'  AND AdminPW = '".CSession::GetVar("admin_Password")."' " );
    $Row = CDbShell::fetch_array();
    // while ($Row = CDbShell::fetch_array());

    // if (0 != intval($row['ResultCode'])) {
    //     session_destroy();
    //     // $js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
    //     $js .= 'alert("'.$row['ResultMessage']."\");\r\n";
    //     $js .= "window.location.href=\"../index.php\";\r\n";
    //     // $js .= "</script>\r\n";
    //     echo $js;
    //     exit;
    // }
    //取得轉跳前進入的頁面
    // $currentFile = $_SERVER['PHP_SELF'];
    $currentFile = $_SERVER['REQUEST_URI'];
    $parts = explode('/', $currentFile);
    $exe_name = $parts[count($parts) - 2]."/".$parts[count($parts) - 1];
    $admin_purview = str_replace("amp;","",CSession::GetVar("admin_purview"));

    //若登入者為不為子帳號則首頁預設為home.php 反之預設為權限列表內的第一個位置 (防止F5重整後跑入迴圈)
    $purview = CSession::GetVar("IsAdmin") ? ['Home/Dashboard'] : preg_split('/&/', $admin_purview);
    // echo CSession::GetVar("admin_purview")."<br>";
    // echo $exe_name."<br>";
    // echo $admin_purview."<br>";
    #print_r($purview)."<br>";
    // // echo html_entity_decode(html_entity_decode(html_entity_decode(CSession::GetVar("admin_purview"))))."<br>";
    //echo CSession::GetVar("IsAdmin");exit;
    //確認轉跳前頁面是否為check_login 否則將轉跳頁設為首頁(發生在重整頁面)
    // $regex = "/".$exe_name."(.*)$/";
    // echo $regex."<br>";
    // echo preg_match($regex, $admin_purview);
    // exit;
    if ('check_login.php' == $exe_name) {
        // $js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
        $js .= 'window.location.href="'.$purview[0]."\";\r\n";
        // $js .= "</script>\r\n";
        echo $js;
        exit;
    } elseif (!CSession::GetVar("IsAdmin") && !in_array($exe_name, $purview)) {
    // elseif (!CSession::GetVar("IsAdmin") && !preg_match($regex, $admin_purview)) {
        //若登入者為子帳號且欲轉跳之頁面不存在於權限表中則
        $js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
        $js .= "alert(\"抱歉!此管理者無權限進入該頁面\");\r\n";
        $js .= 'window.location.href="../'.$purview[0]."\";\r\n";
        $js .= "</script>\r\n";
        echo $js;
        exit;
    }    
        
?>