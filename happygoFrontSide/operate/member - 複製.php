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
	if (!class_exists('CommonElement'))		include_once("../BaseClass/CommonElement.php");
	if (!class_exists('Setting'))			include_once("../BaseClass/Setting.php");


    switch ($_SERVER["func"]) {
        case "Login":
            Login();
            break;
        case "Register":
            Register();
            break;   
        case "Center" ;
            // if (Checklogin(true) == 1) Center();
            if(Checklogin(true) == 1){ Center();}else{
                header("location:http://127.0.0.1/%E5%BF%AB%E6%98%93%E8%B3%BCFrontSide(%E5%89%8D%E5%8F%B020211025)/index.php");
            }
            break;
        case "Checklogin" ;
            Checklogin();
            break;
        case "Order_buy":
            if (Checklogin(true) == 1){ Order_buy();}else{
                header("location:http://127.0.0.1/%E5%BF%AB%E6%98%93%E8%B3%BCFrontSide(%E5%89%8D%E5%8F%B020211025)/index.php");
            }
            break;
        case "Order_sell":
            if (Checklogin(true) == 1){ Order_sell();}else{
                header("location:http://127.0.0.1/%E5%BF%AB%E6%98%93%E8%B3%BCFrontSide(%E5%89%8D%E5%8F%B020211025)/index.php");
            }
            break;
        case "Members_edit":
            if (Checklogin(true) == 1){ members_edit();}else{
                header("location:http://127.0.0.1/%E5%BF%AB%E6%98%93%E8%B3%BCFrontSide(%E5%89%8D%E5%8F%B020211025)/index.php");
            }
            break;
        case "Password_edit":
            if (Checklogin(true) == 1){ Password_edit();}else{
                header("location:http://127.0.0.1/%E5%BF%AB%E6%98%93%E8%B3%BCFrontSide(%E5%89%8D%E5%8F%B020211025)/index.php");
            }
            break;
        case "Paynumber_edit":
            if (Checklogin(true) == 1){ Paynumber_edit();}else{
                header("location:http://127.0.0.1/%E5%BF%AB%E6%98%93%E8%B3%BCFrontSide(%E5%89%8D%E5%8F%B020211025)/index.php");
            }
            break;
        case "Wallet":
            if (Checklogin(true) == 1){ Wallet();}else{
                header("location:http://127.0.0.1/%E5%BF%AB%E6%98%93%E8%B3%BCFrontSide(%E5%89%8D%E5%8F%B020211025)/index.php");
            }
            break;
        case "Logout":
            Logout();
            break;
    }

    function Login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                if (preg_match("/^[A-Za-z0-9|\-|\_]{4,20}$/",Trim($_POST["Account"])) == false) {
                    throw new exception("請輸入帳號!");
                }
                
                if (preg_match("/^[A-Za-z0-9]{6,20}$/",$_POST["Password"]) == false) {
                    throw new exception("請輸入密碼!");
                }
                
                /*if (0 != strcmp($_POST['Inspect'], $_SESSION['auth_num'])) {
                    throw new exception("驗證碼不正確!");
                }*/
        
                
                    CDbShell::Connect();
                    CDbShell::query("SELECT * FROM member WHERE MemberAccount='".$_POST["Account"]."'  AND MemberPassword = '".MD5($_POST["Password"])."'" );
                    //echo "SELECT * FROM member WHERE MemberAccount='".$_POST["Account"]."'  AND MemberPassword = '".MD5($_POST["Password"])."'";
                    //	exit;
                    if (CDbShell::num_rows() == 1) {
                        
                        $Row = CDbShell::fetch_array();
                        CDbShell::query("SELECT * FROM membersession WHERE MemberId = '".$Row["MemberId"]."' AND LastOnlineTime >= '".date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). " -1 minute"))."'" );
                        $IsOnline = CDbShell::num_rows();
                        
                        //echo "SELECT * FROM membersession WHERE MemberId = '".$Row["MemberId"]."' AND LastOnlineTime >= '".date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). " -1 minute"))."'";
                        //exit;
                        // if ($Row["MailVerified"] == 1 ) {
                            if ($Row["PauseAccount"] == "0") {
                                // if ($Row["LockStatus"] == 0) {
                                    /*if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   
                                        $myip = $_SERVER['REMOTE_ADDR'];   
                                    } else {   
                                        $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);   
                                        $myip = $myip[2];   
                                    }*/

                                    $myip = get_client_ip(0, true);
                                    ini_set('session.gc_maxlifetime', 2*60*60*24); //2 days
        
                                    CSession::setVar("Account", $_POST["Account"]);
                                    CSession::setVar("Password", MD5($_POST["Password"]));
                                    CommonElement::Add_S($_SESSION);
                                    CDbShell::query("UPDATE member SET LockStatus = 1, LastLoginIp = '".$myip."', LastLoginDate = '".Date('Y-m-d H:i:s')."' WHERE MemberAccount='".$_POST["Account"]."' AND MemberPassword = '".MD5($_POST["Password"])."'" );
                                    
                                    CDbShell::query("INSERT INTO MemberIpHistory(MemberId, Ip, CreateDate) VALUES (".$Row["MemberId"].", '".$myip."', NOW())");
                                    //$ValidTime = date("Y-m-d H:i:s",mktime (date("H"),date("i") + 5,date("s"),date("m") ,date("d") ,date("Y")));
                                    //CSession::setVar("LoginKey", Cryptographic::encrypt($_POST["Account"]."|".$Random."|".$ValidTime));

                                    // echo "window.location.href='Member/Center'";
                                    echo "window.location.reload()";
                                    //echo "window.open('member/center','_self')";
                                    exit;
                                // }else {
                                // 	throw new exception("帳號登入中!");
                                // }
                            }else {
                                throw new exception("帳號禁止登入!");
                            }
                        // }else {
                        // 	throw new exception("帳號尚未通過驗證，請進行驗證再登入!");
                        // }
                        
                    }else {
                        throw new exception("帳號密碼錯誤!");
                    }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
    }

    function Register() {
        // var_dump($_POST);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {//echo 123;exit;
            try {
                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['Account']) == 0) {
                    throw new exception("帳號只能是英文數字組合長度6-15字!");
                }
                CDbShell::Connect();
                CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount = '".$_POST['Account']."'");
                if (CDbShell::num_rows() > 0) {
                    CDbShell::DB_close();
                    throw new exception("此帳號己經註冊成會員了！");
                }

                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['Password']) == 0) {
                    throw new exception("密碼只能是英文數字組合長度6-15字!");
                }

                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['MoneyPassword']) == 0) {
                    throw new exception("支付密碼只能是英文數字組合長度6-15字!");
                }

                if ($_POST['Terms'] != 1) {
                    throw new exception("未勾選使用條款!");
                }
                CDbShell::query("SELECT * FROM member WHERE MemberAccount = '".$_POST['Account']."'");				
                    
                if (CDbShell::num_rows() == 0) {
                    Again:
                    $SpreadCode = ""; 

                    $SpreadCode = str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);

                    CDbShell::query("SELECT * FROM member WHERE SpreadCode = '".$SpreadCode."'");
                    if (CDbShell::num_rows() > 0) {
                        goto Again;
                    }

                    if (!empty($_SERVER["HTTP_CLIENT_IP"])){
                        $_IP = $_SERVER["HTTP_CLIENT_IP"];
                    }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
                        $_IP = $_SERVER["HTTP_X_FORWARDED_FOR"];
                    }else{
                        $_IP = $_SERVER["REMOTE_ADDR"];
                    }

                        $sql = "INSERT INTO member (MemberAccount, MemberPassword, CellPhone, PauseAccount, MemberLevel, MemberKind, CreateDate, RegisterIp, LastLoginIp, SpreadCode)
                                                VALUES ('".$_POST['Account']."', '".MD5($_POST['Password'])."', '".$_POST['Mobile']."', '0', 7, 1, '".Date('Y-m-d H:i:s')."', '".$_IP."', '".$_IP."', '".$SpreadCode."')";
                        CDbShell::query($sql);
                        $NewMemverId = CDbShell::insert_id();
                        $_Points = 0;
                        CSession::setVar("Account", $_POST["Account"]);
                        CSession::setVar("Password", MD5($_POST["Password"]));
                        CDbShell::query("INSERT INTO memberfinance (MemberId, GamePoints) VALUES('".$NewMemverId."', ".$_Points.")");
                                
                        // CDbShell::query("CALL nsp_membertree_insert(".$NewMemverId.")");

                        // CDbShell::DB_close();
                        // $js .= "alert(\"己傳送驗證碼至這個".trim($_POST["EMail"])."，驗證後就可以進行會員登入.\");";
                        // $js .= "window.open('member.html','_self');";
                        echo "window.location.href='Member/Center'";
                        exit;
                        // echo $js;
                    // }
                }else {
                    throw new exception("帳號己註冊!");
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
    }

    function Center() {
        // var_dump($_POST);
        // if(CSession::GetVar("Account") != "" && CSession::GetVar("Password") != ""){
        //     CDbShell::Connect();
        //     CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
        //     $Row = CDbShell::fetch_array();
        //     $MemberId=$Row["MemberId"];
        // }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if ('MemberTop' == $_POST['fun']) {
                    CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                    $Row = CDbShell::fetch_array();
                    CDbShell::query("SELECT GamePoints FROM memberfinance WHERE MemberId = ".$Row["MemberId"] );
                    $FRow = CDbShell::fetch_array();
                    CDbShell::query("SELECT count(o.OrderNumber) as OrderNumber, count(o2.state) as Daifu, count(o3.state) as Daishowho FROM ordertobuy o
                    LEFT JOIN (SELECT MemberId,OrderNumber,state FROM ordertobuy where state = 1) o2 on o2.MemberId = o.MemberId and o2.OrderNumber = o.OrderNumber
                    LEFT JOIN (SELECT MemberId,OrderNumber,state FROM ordertobuy where state = 2) o3 on o3.MemberId = o.MemberId and o3.OrderNumber = o.OrderNumber
                    WHERE o.MemberId = ".$Row["MemberId"]);
                    $OBRow = CDbShell::fetch_array();
                    CDbShell::query("SELECT count(o.ProductNumber) as ProductNumber, count(o2.OrderState) as Daishow, count(o3.OrderState) as Daichu, count(ob.State) as ChiuHsiao FROM `order` o
                    LEFT JOIN (SELECT MemberId,ProductNumber,OrderState FROM `order` where OrderState = 1) o2 on o2.MemberId = o.MemberId and o2.ProductNumber = o.ProductNumber
                    LEFT JOIN (SELECT MemberId,ProductNumber,OrderState FROM `order` where OrderState = 2) o3 on o3.MemberId = o.MemberId and o3.ProductNumber = o.ProductNumber
                    LEFT JOIN (SELECT MemberId,ProductNumber,State FROM ordertobuy where State = 4) ob on ob.MemberId = o.MemberId and ob.ProductNumber = o.ProductNumber
                    WHERE o.MemberId = ".$Row["MemberId"]);
                    $ORow = CDbShell::fetch_array();
                    $data= array($FRow["GamePoints"],$OBRow["OrderNumber"],$OBRow["Daifu"],$OBRow["Daishowho"],0,$ORow["ProductNumber"],$ORow["Daishow"],$ORow["Daichu"],$ORow["ChiuHsiao"]);
                    echo json_encode($data);
                    exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }

        include("../member.html");
    }

    function Checklogin($login = false) {
        if(CSession::GetVar("Account") != "" && CSession::GetVar("Password") != ""){
            CDbShell::Connect();
            CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
            //echo "SELECT * FROM member WHERE MemberAccount='".$_POST["Account"]."'  AND MemberPassword = '".MD5($_POST["Password"])."'";
            //	exit;
            $Row = CDbShell::fetch_array();
            if (CDbShell::num_rows() == 1) {
                $_IsLonin = 1;
                $data=array("1", $Row["MemberAccount"]);
            }else {
                $_IsLonin = 0;
                $data=array("0");
            }
        }else{
            $_IsLonin = 0;
            $data=array("0");
        }
        if ($login == false) {
            echo json_encode($data);
            exit;
        }else {
            return $_IsLonin;
        }
    }

    function Order_buy() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                $Rowm = CDbShell::fetch_array();

                if($_POST["val"] == 1) {
                    $_Condition .= "AND ob.State = '1'";
                }else if($_POST["val"] == 2) {
                    $_Condition .= "AND ob.State = '2'";
                }else if($_POST["val"] == 3) {
                    $_Condition .= "AND ob.State = '3'";
                }else if($_POST["val"] == 4) {
                    $_Condition .= "AND ob.State = '4'";
                }elseif($_POST["val"] == 9) {
                    $_Condition .= " ";
                }

                CDbShell::query("SELECT
                o.ProductTitle,
                ob.ProductNumber,
                ob.OrderNumber,
                o.MemberId,
                ob.CreateDate,
                case when o.GamePlatform = 1 then 'Android' when o.GamePlatform = 2 then 'iOS' when o.GamePlatform = 3 then '電腦' when o.GamePlatform = 4 then 'Steam' end as GamePlatform,
                pt.TypeName,
                o.PointCardKind,
                ob.PaymentMethod,
                ob.SumPrice,
                ob.SumPricePlusHand,
                ob.Quantity,
                case when ob.State = 1 then '待付款' when ob.State = 2 then '待收貨' when ob.State = 3 then '已完成' when ob.State = 4 then '已取消' end as `State`,
                EvaluState
                FROM `ordertobuy` ob
                LEFT JOIN member m ON m.MemberId = ob.MemberId
                LEFT JOIN `order` o ON o.ProductNumber = ob.ProductNumber
                LEFT JOIN evaluate e ON e.OrderNumber = ob.OrderNumber and EvaluState = '1'
                LEFT JOIN producttype pt on pt.TypeId = o.TypeId WHERE m.MemberId = '".$Rowm['MemberId']."' " .$_Condition. " order by ob.CreateDate desc ");    

                if (CDbShell::num_rows() > 0) {
                    while ($Row = CDbShell::fetch_array()) {
                        switch ($Row['State']) {
                            case "待付款":
                                $text_color = "text_red";
                                $display = '';
                            break;
                            case "待收貨":
                                $text_color = "text_orange";
                                $display = 'style="display: none;"';
                            break;
                            case "已完成":
                                $text_color = "text_blue";
                                $display = 'style="display: none;"';
                            break;
                            case "已取消":
                                $text_color = "text_gray";
                                $display = 'style="display: none;"';
                            break;
                        }
                        $PointCardKind = empty($Row['PointCardKind']) ? 'style="display: none;"' : '';
                        $EvaluState = ($Row['EvaluState'] == '1') ? 'style="display: none;"' : '';
                        $SumPrice = isset($Row['SumPricePlusHand']) ? $Row['SumPricePlusHand'] : $Row['SumPrice'];
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td class="{$text_color}">{$Row['State']}</td>
                            <td class="text_left">
                                <ul>
                                    <li class="title text_deepblue">{$Row['ProductTitle']}</li>
                                    <li class="text_green">商品編號：<span>{$Row['ProductNumber']}</span></li>
                                    <li class="text_green">賣家編號：<span>{$Row['MemberId']}</span></li>
                                    <li>下單時間：<span>{$Row['CreateDate']}</span></li>
                                    <li>遊戲伺服：<span>{$Row['GamePlatform']}</span></li>
                                    <li>商品類型：<span>{$Row['TypeName']}</span></li>
                                    <li {$PointCardKind}>點卡類型：<span>{$Row['PointCardKind']}</span></li>
                                </ul>
                            </td>
                            <td>{$Row['Quantity']}</td>
                            <td class="text_red text_big">{$SumPrice}</td>
                            <td>{$Row['PaymentMethod']}</td>
                            <td class="sktb_btn">
                                <input type="button" id="OrderBuyInfo" value="詳細訂單" class="btn_small btn_yellow" data-value="{$Row['OrderNumber']}">
                                <input type="button" id="Paynow" value="立即付款" class="btn_small btn_pink" {$display} data-value="{$Row['OrderNumber']}">
                                <input type="button" value="取消交易" id="OrderBuyDel" class="jsCanclePay btn_small btn_gray" data-value="{$Row['OrderNumber']}">
                                <input type="button" value="給予評價" id="OrderBuyEvalu" class="js_BuyComment btn_small btn_blue" {$EvaluState} data-value="{$Row['OrderNumber']}">
                                <input type="button" value="聯絡賣家" onclick="window.open('Order/QABuy');" class="btn_small btn_green" data-value="{$Row['OrderNumber']}">
                            </td>
                        </tr>
EOF;
                    }
                    CDbShell::DB_close();
        
                    echo $Layout;
                    exit;
                }else{
                    $Layout=
                        <<<EOF
                            <td class="text_center" align='center' valign="middle">
                                <img src="images/member/job-search.png" alt="">
                                <span>查無訂單</span>
                            </td>
EOF;
                    echo $Layout;exit;
                }
                
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../order_list_buy.html");
    }

    function Order_sell() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                $Rowm = CDbShell::fetch_array();

                if($_POST["val"] == 1) {
                    $_Condition .= "AND ob.State = '1'";
                }else if($_POST["val"] == 2) {
                    $_Condition .= "AND ob.State = '2'";
                }else if($_POST["val"] == 3) {
                    $_Condition .= "AND ob.State = '3'";
                }else if($_POST["val"] == 4) {
                    $_Condition .= "AND ob.State = '4'";
                }elseif($_POST["val"] == 9) {
                    $_Condition .= " ";
                }

                CDbShell::query("SELECT 
                o.MemberId,
                o.ProductNumber,
                ob.OrderNumber,
                o.ProductId,
                pt.TypeName,
                -- TypeId,
                case when o.GamePlatform = 1 then 'Android' when o.GamePlatform = 2 then 'iOS' when o.GamePlatform = 3 then '電腦' when o.GamePlatform = 4 then 'Steam' end as GamePlatform,
                o.GameName,
                o.GameServer,
                o.ProductTitle,
                o.PointCardKind,
                o.ShelfState,
                o.Price,
                o.OrderQuantity,
                o.GameCoinQuantity,
                o.CurrencyValue,
                o.Currency,
                o.KuTsuenQuantity,
                o.ProductInfo,
                o.GameAccount,
                o.CharacterName,
                o.CharacterLevel,
                o.CharacterProfession,
                o.CharacterSex,
                o.ChangePassword,
                o.FileInfo1,
                o.FileInfo2,
                o.ChiuHsiaoQuantity,
                o.HsiaoShouQuantity,
                o.HandlingFee,
                case when ob.State = 1 then '待收款' when ob.State = 2 then '待出貨' when ob.State = 3 then '已完成' when ob.State = 4 then '已取消' else '未送出的訂單' end as OrderState,
                ob.PaymentMethod,
                ob.SumPrice,
                ob.SumPricePlusHand,
                ob.Quantity,
                o.CreateDate,
                o.ModifyDate,
                EvaluState
                FROM `order` o
                LEFT JOIN member m ON m.MemberId = o.MemberId
                LEFT JOIN `ordertobuy` ob ON ob.ProductNumber = o.ProductNumber
                LEFT JOIN evaluate e ON e.OrderNumber = ob.OrderNumber and EvaluState = '2'
                LEFT JOIN producttype pt on pt.TypeId = o.TypeId WHERE m.MemberId = '".$Rowm['MemberId']."' " .$_Condition. " order by CreateDate desc ");    

                if (CDbShell::num_rows() > 0) {
                    while ($Row = CDbShell::fetch_array()) {
                        switch ($Row['OrderState']) {
                            case "待收款":
                                $text_color = "text_red";
                            break;
                            case "待出貨":
                                $text_color = "text_orange";
                            break;
                            case "已完成":
                                $text_color = "text_blue";
                            break;
                            case "已取消":
                                $text_color = "text_gray";
                            break;
                            case "未送出的訂單":
                                $text_color = "text_black";
                            break;
                        }
                        // empty($Row['SumPrice']) ? $Row['Price'] : $Row['SumPrice']
                        $EvaluState = ($Row['EvaluState'] == '2') ? 'style="display: none;"' : '';
                        $Price = empty($Row['SumPricePlusHand']) ? (empty($Row['SumPrice']) ? $Row['Price'] : $Row['SumPrice']) : $Row['SumPricePlusHand'];
                        $Quantity = empty($Row['Quantity']) ? $Row['OrderQuantity'] : $Row['Quantity'];
                        
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td class="{$text_color}">{$Row['OrderState']}</td>
                            <td class="text_left">
                                <ul>
                                    <li class="title text_deepblue">{$Row['ProductTitle']}</li>
                                    <li class="text_green">商品編號：<span>{$Row['ProductNumber']}</span></li>
                                    <li class="text_green">賣家編號：<span>{$Row['MemberId']}</span></li>
                                    <li>下單時間：<span>{$Row['CreateDate']}</span></li>
                                    <li>遊戲伺服：<span>{$Row['GamePlatform']}</span></li>
                                    <li>商品類型：<span>{$Row['TypeName']}</span></li>
                                </ul>
                            </td>
                            <td>{$Quantity}</td>
                            <td class="text_red text_big">{$Price}</td>
                            <td>{$Row['PaymentMethod']}</td>
                            <td class="sktb_btn">
                                <input type="button" id="OrderSellInfo" value="詳細訂單" class="btn_small btn_yellow" data-value="{$Row['OrderNumber']}">
                                <input type="button" value="取消交易" id="OrderSellDel" class="jsSellCanclePay btn_small btn_gray" data-value="{$Row['OrderNumber']}">
                                <input type="button" value="等待評價" id="OrderSellEvalu" class="js_BuyComment btn_small btn_blue" {$EvaluState} data-value="{$Row['OrderNumber']}">
                                <input type="button" value="聯絡買家" onclick="window.open('Order/QASell');" class="btn_small btn_green">
                            </td>
                        </tr>
EOF;
                    }
                    CDbShell::DB_close();
        
                    echo $Layout;
                    exit;
                }else{
                    $Layout=
                        <<<EOF
                        <tr>
                            <td colspan="6">
                                <img src="images/member/job-search.png" alt="">
                                <span>查無訂單</span>
                            </td>
                        </tr>
EOF;
                    echo $Layout;exit;
                }
                
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../order_list_sell.html");
    }

    function Members_edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if('Members_editList' == $_POST['fun']){
                    CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                    $Row = CDbShell::fetch_array();
                    $MemberAccount = substr_replace($Row["MemberAccount"], '*****', 3);
                    // cut_str($str, 1, 0).’**’.cut_str($str, 1, -1);
                    $data= array($MemberAccount,$Row["Sex"],$Row["IdNumber"],$Row["RealName"],$Row["CellPhone"],$Row["Email"],$Row["Address"]);
                    echo json_encode($data);exit;
                }
                if('Members_edit' == $_POST['fun']){
                    CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".CSession::GetVar("Account")."' AND MemberPassword = '".CSession::GetVar("Password")."'" );
                    $Rowm = CDbShell::fetch_array();

                    $field = array("Sex","IdNumber","RealName","CellPhone","Email","Address");
                    $value = array($_POST["Sex"],$_POST["IdNumber"],$_POST["RealName"],$_POST["CellPhone"],$_POST["Email"],$_POST["Address"]);
                    CDbShell::update("member", $field, $value, "MemberId = ".$Rowm["MemberId"]); 
                    echo "window.location.href='Member/Members_edit'";exit;
                }                
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../members_edit.html");
    }

    function Password_edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                
                CDbShell::query("SELECT MemberId,MemberPassword FROM member WHERE MemberAccount='".CSession::GetVar("Account")."' AND MemberPassword = '".CSession::GetVar("Password")."'" );
                // echo "SELECT MemberId,MemberPassword FROM member WHERE MemberAccount='".CSession::GetVar("Account")."' AND MemberPassword = '".CSession::GetVar("Password")."'"; exit;
                $Rowp = CDbShell::fetch_array();

                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['Newpsw']) == 0) {
                    throw new exception("密碼只能是英文數字組合長度6-15字!");
                }
                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['Newpsw2']) == 0) {
                    throw new exception("確認密碼只能是英文數字組合長度6-15字!");
                }
                if ($_POST['Newpsw'] != $_POST['Newpsw2']) {
                    throw new exception("新密碼與確認密碼不符");
                }
                if (CDbShell::num_rows() == 1) {
                    $field = array("MemberPassword");
                    $value = array(MD5($_POST['Newpsw']));
                    CDbShell::update("member", $field, $value, "MemberId = ".$Rowp["MemberId"]); 
                    echo "window.location.href='Member/Members_edit'";
                    exit;
                }else{
                    throw new exception("error");
                }
            
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
    }

    function Paynumber_edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                
                CDbShell::query("SELECT MemberId,MoneyPassword FROM member WHERE MemberAccount='".CSession::GetVar("Account")."' AND MemberPassword = '".CSession::GetVar("Password")."'" );
                // echo "SELECT MemberId,MemberPassword FROM member WHERE MemberAccount='".CSession::GetVar("Account")."' AND MemberPassword = '".CSession::GetVar("Password")."'"; exit;
                $Rowp = CDbShell::fetch_array();

                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['Newpaynumber']) == 0) {
                    throw new exception("密碼只能是英文數字組合長度6-15字!");
                }
                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['Newpaynumber2']) == 0) {
                    throw new exception("確認密碼只能是英文數字組合長度6-15字!");
                }
                if ($_POST['Newpaynumber'] != $_POST['Newpaynumber2']) {
                    throw new exception("新密碼與確認密碼不符");
                }
                if (CDbShell::num_rows() == 1) {
                    $field = array("MoneyPassword");
                    $value = array($_POST['Newpaynumber']);
                    CDbShell::update("member", $field, $value, "MemberId = ".$Rowp["MemberId"]); 
                    echo "window.location.href='Member/Members_edit'";
                    exit;
                }else{
                    throw new exception("error");
                }
            
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
    }

    function Wallet() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                $Rowm = CDbShell::fetch_array();
                if('Wallet_top' == $_POST['fun']){
                    CDbShell::query("SELECT
                        m.MemberId,
                        mf.GamePoints,
                        sum(pl.AfterPoints) as Finaltake, -- 已撥款
                        sum(pl2.AfterPoints) as Goingtake, -- 即將撥款
                        m.BankName,
                        m.BankAccount
                        FROM member m
                        LEFT JOIN (
                            SELECT MemberId, AfterPoints FROM pointchanglog where ChangeEvent = 4 and PointChangState = 1
                        ) pl ON pl.MemberId = m.MemberId
                        LEFT JOIN 
                        (
                            SELECT MemberId, AfterPoints FROM pointchanglog where ChangeEvent = 4 and PointChangState = 0
                        ) pl2 ON pl2.MemberId = m.MemberId
                        LEFT JOIN memberfinance mf ON mf.MemberId = m.MemberId
                        WHERE m.MemberId = '".$Rowm['MemberId']."' ");
                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();

                        $Finaltake = isset($Row['Finaltake']) ? $Row['Finaltake'] : 0;
                        $Goingtake = isset($Row['Goingtake']) ? $Row['Goingtake'] : 0;
                        $AddBankbtn = ($Row['BankAccount'] =="") ? '' : 'display: none;';
                        $DelBankbtn = ($Row['BankAccount'] =="") ? 'display: none;' : '';

                        $data= array($Row["MemberId"],$Row["GamePoints"],$Finaltake,$Goingtake,$Row["BankName"],$Row["BankAccount"],$AddBankbtn,$DelBankbtn);
                        echo json_encode($data);exit;
                    }
                }
                if('AddBank' == $_POST['fun']){
                    $field = array("BankName","BankAccount","BankArea","BranchName","BankId");
                    $value = array($_POST["BankName"],$_POST["BankAccount"],$_POST["BankArea"],$_POST["BranchName"],$_POST["BankId"]);
                    CDbShell::update("`member`", $field, $value, "`MemberId` = ".$Rowm['MemberId']);
                    echo "window.location.href='Member/Wallet'";exit;
                }
                if('DelBank' == $_POST['fun']){
                    $field = array("BankName","BankAccount");
                    $value = array('','');
                    CDbShell::update("`member`", $field, $value, "`MemberId` = ".$Rowm['MemberId']);
                    echo "window.location.href='Member/Wallet'";exit;
                }
                if($_POST["val"] == 1) {
                    $_Condition .= "AND pc.ChangeEvent in (1,2,4) ";
                }else if($_POST["val"] == 2) {
                    $_Condition .= "AND pc.ChangeEvent in (4,5,6) and pc.PointChangState = 0 ";
                }else if($_POST["val"] == 3) {
                    $_Condition .= "AND pc.ChangeEvent = 4 and pc.PointChangState = 1 ";
                }else if($_POST["val"] == 4) {
                    $_Condition .= "AND pc.ChangeEvent = 5 and pc.PointChangState = 1 ";
                }else if($_POST["val"] == 5) {
                    $_Condition .= "AND pc.ChangeEvent = 6 and pc.PointChangState = 1 ";
                }else if($_POST["val"] == 6) {
                    $_Condition .= "AND pc.ChangeEvent = 7 ";
                }elseif($_POST["val"] == 9) {
                    $_Condition .= " ";
                }
                CDbShell::query("SELECT 
                case when (pc.ChangeEvent = 4 and pc.PointChangState = 0) then '撥款中'
                     when (pc.ChangeEvent = 5 and pc.PointChangState = 0) then '提款中'
                     when (pc.ChangeEvent = 6 and pc.PointChangState = 0) then '退款中' else '完成' end as PointChangState,
                case when ob.OrderNumber <> '' then ob.OrderNumber 
                     when pc.ChangeEvent = 4 then '撥款至帳號' 
                     when pc.ChangeEvent = 5 then '提款至帳號' 
                     when pc.ChangeEvent = 6 then '退款至帳號' else '處理中' end as OrderNumberState,
                ob.OrderNumber,
                o.ProductTitle,
                pe.EventName,
                case when pc.ChangeEvent = 4 then '' else pc.ChangePoints end as PayinPayout,
                case when pc.ChangeEvent = 4 then pc.ChangePoints else '' end as WithdrawMoney,
                LEFT(pc.CreateDate,19) as CreateDate
                
                FROM pointchanglog pc
                LEFT JOIN member m on m.MemberId = pc.MemberId
                LEFT JOIN pointevent pe on pe.RowId = pc.ChangeEvent
                LEFT JOIN ordertobuy ob on ob.OrderNumber = pc.OrderNumber
                LEFT JOIN `order` o on o.ProductNumber = ob.ProductNumber WHERE m.MemberId = '".$Rowm['MemberId']."' ".$_Condition."   
                ORDER BY pc.CreateDate desc ");
                if (CDbShell::num_rows() > 0) {
                    while ($Row = CDbShell::fetch_array()) {
                        switch ($Row['PointChangState']) {
                            case "完成":
                                $text_color = "";
                            break;
                            case "撥款中":
                                $text_color = "text_green";
                            break;
                            case "提款中":
                                $text_color = "text_blue";
                            break;
                            case "退款中":
                                $text_color = "text_red";
                            break;
                        }
                        $OrderNumber = ($Row['OrderNumber'] =='') ? "" : "text_big text_deepblue";
                        $OrderStr = ($Row['OrderNumber'] =='') ? "" : "訂單編號：";
                        $PayinPayout = ($Row['PayinPayout'] > 0) ? "text_green" : "text_red";
                        $WithdrawMoney = ($Row['WithdrawMoney'] > 0) ? "text_green" : "text_red";
                        
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td>
                                <div class={$text_color}>{$Row['PointChangState']}</div>
                            </td>
                            <td class="text_left">
                                <ul>
                                    <li class="{$OrderNumber}">{$OrderStr}<span>{$Row['OrderNumberState']}</span></li>
                                    <li class="title">{$Row['ProductTitle']}</li>
                                </ul>
                            </td>
                            <td>{$Row['EventName']}</td>
                            <td class="text_right"><span class="{$PayinPayout}">{$Row['PayinPayout']}</span></td>
                            <td class="text_right"><span class="{$WithdrawMoney}">{$Row['WithdrawMoney']}</span></td>
                            <td>{$Row['CreateDate']}</td>
                            <td class="sktb_btn">
                                <input type="button" value="明細" class="jsWalletIncome btn_small btn_yellow">
                            </td>
                        </tr>
EOF;
                    }
                    CDbShell::DB_close();
        
                    echo $Layout;
                    exit;
                }else{
                    $Layout=
                        <<<EOF
                        <tr>
                            <td colspan="7">
                                <img src="images/member/job-search.png" alt="">
                                <span>查無交易紀錄</span>
                            </td>
                        </tr>
EOF;
                    echo $Layout;exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../my_wallet.html");
    }

    function Logout() {
        CDbShell::Connect();
        CDbShell::query("UPDATE member SET LockStatus = 0 WHERE MemberAccount='".CSession::GetVar("Account")."' AND MemberPassword = '".CSession::GetVar("Password")."'" );
        CDbShell::DB_close();
        CSession::ClearVar("Account");
		CSession::ClearVar("Password");
		$LogOut =
            <<<EOF
            <script>
            window.location.href='../Index/index';
            </script>
EOF;
        echo $LogOut;
        exit;
        // include("../order_list_buy.html");
    }



    function SockPost($URL, $Query){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $Query);
		$SSL = (substr($URL, 0, 8) == "https://" ? true : false); 
		if ($SSL) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		$strReturn = curl_exec($ch);
		
		curl_close ($ch);
		
		return $strReturn;
		
	}
	
	function pkcs5_pad ($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text. str_repeat(chr($pad), $pad);
	}
	
	function get_client_ip($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法驗證
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
	}
?>