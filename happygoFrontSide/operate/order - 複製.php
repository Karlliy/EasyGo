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
        case "AddOrder":
            AddOrder();
            break;
        case "SellList":
            SellList();
            break;   
        case "GameInfo";
            // if("Member/Checklogin" == 1){ GameInfo();}else{
            //     header("location:http://127.0.0.1/%E5%BF%AB%E6%98%93%E8%B3%BCFrontSide(%E5%89%8D%E5%8F%B020211025)/index.php");
            // }
            GameInfo();
            break;
        case "Shopping_cart" ;
            // if("Member/Checklogin" == 1){ Shopping_cart();}else{
            //     header("location:http://127.0.0.1/%E5%BF%AB%E6%98%93%E8%B3%BCFrontSide(%E5%89%8D%E5%8F%B020211025)/index.php");
            // }
            Shopping_cart();
            break;
        case "SellListSelect":
            SellListSelect();
            break;
        case "strReturn":
            strReturn();
            break;    
        case "OrderBuyInfo":
            OrderBuyInfo();
            break;
        case "OrderSellInfo":
            OrderSellInfo();
            break;
        case "DealNotify":
            DealNotify();
            break;
        case "CommentSell":
            CommentSell();
            break;
        case "CommentBuy":
            CommentBuy();
            break;
        case "QASell":
            QASell();
            break;
        case "QABuy":
            QABuy();
            break;
        case "MailMembers":
            MailMembers();
            break;
        case "Complain":
            Complain();
            break;
    }

    function AddOrder() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                $OrderQuantity = (isset($_GET['OrderQuantity'])) ? $_GET['OrderQuantity'] : ((isset($_POST['OrderQuantity'])) ? $_POST['OrderQuantity'] : 0);
                $GameCoinQuantity = (isset($_GET['GameCoinQuantity'])) ? $_GET['GameCoinQuantity'] : ((isset($_POST['GameCoinQuantity'])) ? $_POST['GameCoinQuantity'] : 0);
                $KuTsuenQuantity = (isset($_GET['KuTsuenQuantity'])) ? $_GET['KuTsuenQuantity'] : ((isset($_POST['KuTsuenQuantity'])) ? $_POST['KuTsuenQuantity'] : 0);
                $ChiuHsiaoQuantity = (isset($_GET['ChiuHsiaoQuantity'])) ? $_GET['ChiuHsiaoQuantity'] : ((isset($_POST['ChiuHsiaoQuantity'])) ? $_POST['ChiuHsiaoQuantity'] : 0);
                $HsiaoShouQuantity = (isset($_GET['HsiaoShouQuantity'])) ? $_GET['HsiaoShouQuantity'] : ((isset($_POST['HsiaoShouQuantity'])) ? $_POST['HsiaoShouQuantity'] : 0);
                $HandlingFee = (isset($_GET['HandlingFee'])) ? $_GET['HandlingFee'] : ((isset($_POST['HandlingFee'])) ? $_POST['HandlingFee'] : 0);
                if ('ProductId' == $_POST['fun']) {
                    CDbShell::Connect();
                    $Row = CDbShell::fetch_row_field("SELECT ProductId, ProductName FROM product where Productid <> 99");
                    echo json_encode($Row);
                    exit;
                }
                if ('GameName' == $_POST['fun']) {
                    CDbShell::Connect();
                    $Row = CDbShell::fetch_row_field("SELECT GameId, GameName, ProductId FROM game ");
                    echo json_encode($Row);
                    exit;
                }
                if ('OrderId' == $_POST['fun']) {//A01-123456-1234567890
                    CDbShell::Connect();
                    CDbShell::query("SELECT TypeId FROM producttype");
                    $Row = CDbShell::fetch_array();
                    $data[0] = "A"; // 類別可以撈資料庫
                    $data[1] = "0".$_POST['val'];  //.$Row["TypeId"];
                    $data[2] = "_";
                    $data[3] = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
                    $data[4] = "_";
                    $data[5] = strtotime(date('Y/m/d H:i:s'));
                    echo json_encode($data);
                    exit;
                }
                // echo 123;echo $_POST["ProductNumber"];exit;
                CDbShell::Connect();
                CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount = '".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                // if (CDbShell::num_rows() == 1) {
                    $Row = CDbShell::fetch_array();
                    $MemberId=$Row["MemberId"];
                    $field = array("MemberId","ProductNumber","ProductId","TypeId","GamePlatform","GameId","GameServer","ProductTitle","PointCardKind","ShelfState","Price","OrderQuantity","GameCoinQuantity","CurrencyValue","Currency","KuTsuenQuantity","ProductInfo","GameAccount","CharacterName","CharacterLevel","CharacterProfession","CharacterSex","ChangePassword","FileInfo1","FileInfo2","ChiuHsiaoQuantity","HsiaoShouQuantity","HandlingFee","OrderState","CreateDate","ModifyDate");
                    $value = array($Row["MemberId"],$_POST["ProductNumber"],$_POST["ProductId"],$_POST["TypeId"],$_POST["GamePlatform"],$_POST["GameName"],$_POST["GameServer"],$_POST["ProductTitle"],$_POST["PointCardKind"],"1",$_POST["Price"],$OrderQuantity,$GameCoinQuantity,$_POST["CurrencyValue"],$_POST["Currency"],$KuTsuenQuantity,$_POST["ProductInfo"],$_POST["GameAccount"],$_POST["CharacterName"],$_POST["CharacterLevel"],$_POST["CharacterProfession"],$_POST["CharacterSex"],$_POST["ChangePassword"],$_POST["FileInfo1"],$_POST["FileInfo2"],$ChiuHsiaoQuantity,$HsiaoShouQuantity,$HandlingFee,'0',date('Y/m/d H:i:s'),date('Y/m/d H:i:s'));
                    CDbShell::insert("`order`", $field, $value);
                    $Id = CDbShell::insert_id();

                    if (!empty($_FILES['FileName']) && $_FILES['FileName']['tmp_name'] != "") {

                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileName']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileName']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileName']['name']) == "png") {
                                $FileName = CommonElement::CopyImg($Id, $_FILES['FileName'], "../picturedata/");
                                // echo $Id;exit;
                                $field = array("FileName");
                                $value = array($FileName);
                                CDbShell::update("`order`", $field, $value, "`Row` = ".$Id); 
                        }else {
                            throw new exception("照片不符合!");
                        }
                    // }else {
                    //     throw new exception("照片不符合!");
                    }
                    if (!empty($_FILES['FileInfo1']) && $_FILES['FileInfo1']['tmp_name'] != "") {

                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo1']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo1']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo1']['name']) == "png") {
                                $FileInfo1 = CommonElement::CopyImg($Id, $_FILES['FileInfo1'], "../Infoimg1/");
                                // echo $Id;exit;
                                $field = array("FileInfo1");
                                $value = array($FileInfo1);
                                CDbShell::update("`order`", $field, $value, "`Row` = ".$Id); 
                        }else {
                            throw new exception("照片不符合!");
                        }
                    // }else {
                    //     throw new exception("照片不符合!");
                    }
                    if (!empty($_FILES['FileInfo2']) && $_FILES['FileInfo2']['tmp_name'] != "") {

                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo2']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo2']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo2']['name']) == "png") {
                                $FileInfo2 = CommonElement::CopyImg($Id, $_FILES['FileInfo2'], "../Infoimg2/");
                                // echo $Id;exit;
                                $field = array("FileInfo2");
                                $value = array($FileInfo2);
                                CDbShell::update("`order`", $field, $value, "`Row` = ".$Id); 
                        }else {
                            throw new exception("照片不符合!");
                        }
                    // }else {
                    //     throw new exception("照片不符合!");
                    }

                    echo "window.location.href='Member/Center'";exit;
                // }else{
                //     echo "window.history.go(-1)";exit;
                // }
                
            //    echo 123; exit;
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../publish.html");
    }

    function SellList() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST["func"] == 'GetData') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();

                $_Condition ="";
                // echo $_POST["GameId"];
                $_Condition = " and o.GameId = '".$_POST["GameId"]."' ";
                CDbShell::query("SELECT 
                MemberId,
                ProductNumber,
                o.ProductId,
                pt.TypeName,
                -- TypeId,
                case when GamePlatform = 1 then 'Android' when GamePlatform = 2 then 'iOS' when GamePlatform = 3 then '電腦' when GamePlatform = 4 then 'Steam' end as GamePlatform,
                g.GameName,
                GameServer,
                ProductTitle,
                PointCardKind,
                ShelfState,
                Price,
                OrderQuantity,
                GameCoinQuantity,
                CurrencyValue,
                Currency,
                KuTsuenQuantity,
                ProductInfo,
                GameAccount,
                CharacterName,
                CharacterLevel,
                CharacterProfession,
                CharacterSex,
                ChangePassword,
                FileInfo1,
                FileInfo2,
                ChiuHsiaoQuantity,
                HsiaoShouQuantity,
                HandlingFee,
                case when OrderState = 1 then '待收款' when OrderState = 2 then '待出貨' when OrderState = 3 then '已完成' when OrderState = 4 then '已取消' end as OrderState,
                o.CreateDate,
                ModifyDate
                FROM `order` o
                LEFT JOIN game g on g.GameId = o.GameId 
                LEFT JOIN producttype pt on pt.TypeId = o.TypeId where ShelfState = '1' " .$_Condition. " order by CreateDate desc ");    

                // if('SellListForm' == $_POST['fun']){
                    if (CDbShell::num_rows() > 0) {
                        while ($Row = CDbShell::fetch_array()) {
                            switch ($Row['TypeName']) {
                                case "道具":
                                    $text_color = "border_orange";
                                break;
                                case "遊戲幣":
                                    $text_color = "border_blue";
                                break;
                                case "帳號":
                                    $text_color = "border_green";
                                break;
                                case "代儲":
                                    $text_color = "border_pink";
                                break;
                                case "代練":
                                    $text_color = "border_purple";
                                break;
                                case "點數卡":
                                    $text_color = "border_red";
                                break;
                                case "商城":
                                    $text_color = "border_lightgreen";
                                break;
                                case "其他":
                                    $text_color = "border_black";
                                break;
                                case "送禮":
                                    $text_color = "border_lightblue";
                                break;
                            }
                            $Layout .=
                            <<<EOF
                            <tr>
                                <td data-th="">{$Row['GameName']}</td>
                                <td data-th="" class="text_left">
                                    {$Row['ProductTitle']}
                                </td>
                                <td data-th="">
                                    <span class={$text_color}>{$Row['TypeName']}</span>
                                </td>
                                <td data-th="">{$Row['GamePlatform']}</td>
                                <td data-th="">台服</td>
                                <td data-th="">台幣</td>
                                <td data-th="" class="text_right text_red text_big">{$Row['Price']}</td>
                                <td data-th="">{$Row['KuTsuenQuantity']}</td>
                                <td data-th="" class="text_right text_blue">瀏覽量</td>
                                <td data-th="">
                                    <div class="heart_red"></div>
                                    <div class="heart_white" style="display: none;"></div>
                                    <input type="button" id="detailbutton" value="購買" class="btn_big btn_yellow" data-value="{$Row['ProductNumber']}">
                                </td>
                            </tr>
EOF;
                        }
                        CDbShell::DB_close();
            
                        echo $Layout;
                        exit;
                    }
                // }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }

        $JS =
        <<<EOF
        <script>
        var JSGameId = '{$_POST["GameId"]}'
        </script>
EOF;
        echo $JS;
        include("../game_list.html");
    }

    function SellListSelect() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();

                if ('TypeId' == $_POST['fun']) {
                    $Row = CDbShell::fetch_row_field("SELECT TypeId, TypeName FROM producttype ");
                    echo json_encode($Row);
                    exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
    }

    function GameInfo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {//echo $_POST["ProductNumber"];exit;
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CSession::SetVar("ProductNumber", $_POST["ProductNumber"]);
                CommonElement::Add_S($_SESSION);
                
                CDbShell::query("SELECT 
                MemberId,
                ProductNumber,
                o.ProductId,
                pt.TypeName,
                -- TypeId,
                case when GamePlatform = 1 then 'Android' when GamePlatform = 2 then 'iOS' when GamePlatform = 3 then '電腦' when GamePlatform = 4 then 'Steam' end as GamePlatform,
                g.GameName,
                GameServer,
                ProductTitle,
                PointCardKind,
                ShelfState,
                Price,
                OrderQuantity,
                GameCoinQuantity,
                CurrencyValue,
                Currency,
                KuTsuenQuantity,
                ProductInfo,
                GameAccount,
                CharacterName,
                CharacterLevel,
                CharacterProfession,
                CharacterSex,
                ChangePassword,
                FileInfo1,
                FileInfo2,
                ChiuHsiaoQuantity,
                HsiaoShouQuantity,
                HandlingFee,
                case when OrderState = 1 then '待收款' when OrderState = 2 then '待出貨' when OrderState = 3 then '已完成' when OrderState = 4 then '已取消' end as OrderState,
                LEFT(o.CreateDate, 10) as CreateDate,
                ModifyDate,
                o.`FileName`
                FROM `order` o
                LEFT JOIN game g on g.GameId = o.GameId 
                LEFT JOIN producttype pt on pt.TypeId = o.TypeId where ShelfState = '1' AND ProductNumber = '".$_POST["ProductNumber"]."' order by CreateDate desc "); 

                if (CDbShell::num_rows() > 0) {
                    $Row = CDbShell::fetch_array();
                    $CurrencyValue = empty($Row['CurrencyValue']) ? "無" : $Row['CurrencyValue'];
                    $PointCardKind = empty($Row['PointCardKind']) ? "無" : $Row['PointCardKind'];
                }
                if('SellMember' == $_POST['fun']){
                    CDbShell::Connect();
                    CDbShell::query("SELECT m.MemberAccount, LEFT(m.LastLoginDate, 10) as LastLoginDate, 
                    case when LockStatus = 1 then '在線' when LockStatus = 0 then '離線' end as LockStatusStr, LockStatus 
                    from `order` o LEFT JOIN member m on m.MemberId = o.MemberId where o.ProductNumber = '".$_POST["val"]."' ");
                    $Row = CDbShell::fetch_array();
                    $data= array($Row["MemberAccount"],$Row["LastLoginDate"],$Row["LockStatusStr"],$Row["LockStatus"]);
                    echo json_encode($data);
                    exit;
                }
                if('MemberOnline' == $_POST['fun']){
                    CDbShell::Connect();
                    CDbShell::query("SELECT m.MemberAccount, LEFT(m.LastLoginDate, 10) as LastLoginDate, 
                    case when LockStatus = 1 then '在線' when LockStatus = 0 then '離線' end as LockStatusStr, LockStatus 
                    from `order` o LEFT JOIN member m on m.MemberId = o.MemberId where m.MemberAccount = '".$_POST["val"]."' group by m.MemberAccount");
                    $Row = CDbShell::fetch_array();
                    $data= array($Row["MemberAccount"],$Row["LastLoginDate"],$Row["LockStatusStr"],$Row["LockStatus"]);
                    echo json_encode($data);
                    exit;
                }
                if('CheckBuy' == $_POST['fun']){
                    CDbShell::Connect();
                    CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                    // echo "SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" ;exit;
                    if (CDbShell::num_rows() == 1) {
                        CSession::setVar("ProductNumber", $_POST["ProductNumber"]);
                        $PaymentMethod = (isset($_GET['PaymentMethod'])) ? $_GET['PaymentMethod'] : ((isset($_POST['PaymentMethod'])) ? $_POST['PaymentMethod'] : '');
                        $PaymentType = (isset($_GET['PaymentType'])) ? $_GET['PaymentType'] : ((isset($_POST['PaymentType'])) ? $_POST['PaymentType'] : 0);
                        CDbShell::Connect();
                        CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                        $Row = CDbShell::fetch_array();
                        $MemberId=$Row["MemberId"];
                        CDbShell::query("SELECT GameId, `FileName` FROM game where GameName = '".$_POST[GameName]."'" );
                        $Rowg = CDbShell::fetch_array();
                        CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".$_POST["SellMemberId"]."'");
                        $Rowm = CDbShell::fetch_array();
                        Again:
                        $OrderNumber = "";
                        $OrderNumber = date('ymdH').str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
                        CDbShell::query("SELECT * FROM ordertobuy WHERE OrderNumber = '".$OrderNumber."'");
                        if (CDbShell::num_rows() > 0) {
                            goto Again;
                        }
                        // $OrderNumber = str_pad(rand(100000000000000, 999999999999999), 15, '0', STR_PAD_LEFT);
                        $field = array("MemberId","ProductNumber","OrderNumber","GameId","GameServer","Quantity","SumPrice","SellMemberId","PaymentMethod","PaymentType","State","CreateDate");
                        $value = array($MemberId,$_POST["ProductNumber"],$OrderNumber,$Rowg["GameId"],$_POST["GameServer"],$_POST["Quantity"],$_POST["SumPrice"],$Rowm["MemberId"],$PaymentMethod,$PaymentType,"1",date('Y/m/d H:i:s'));
                        CDbShell::insert("`ordertobuy`", $field, $value);
                        $Id = CDbShell::insert_id();
                        $field = array("OrderNumber");
                        $value = array($OrderNumber);
                        CDbShell::insert("`review`", $field, $value);
                        echo "window.location.href='Order/Shopping_cart'";exit;

                    }else{
                        $data=array(" ");
                        echo json_encode($data);
                        exit;
                    }
                }

            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        // echo $_POST["ProductNumber"];
        include("../game_info.html");
    }

    function Shopping_cart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                // echo "SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" ;exit;
                if (CDbShell::num_rows() == 1) {
                    if('Shopping_cart' == $_POST['fun']){
                        $ProductNumber = isset($_POST["ProductNumber"]) ? $_POST["ProductNumber"] : $_POST["OrderNumber"];
                        CDbShell::query("SELECT ob.`Row`,
                        o.ProductTitle,
                        pt.TypeName,
                        o.FileName,
                        g.GameName,
                        ob.MemberId,
                        ob.ProductNumber,
                        ob.OrderNumber,
                        ob.GameId,
                        ob.GameServer,
                        ob.Quantity,
                        ob.SumPrice,
                        ob.SellMemberId,
                        ob.PaymentMethod,
                        ob.PaymentType,
                        ob.State,
                        case when o.GamePlatform = 1 then 'Android' when o.GamePlatform = 2 then 'iOS' when o.GamePlatform = 3 then '電腦' when o.GamePlatform = 4 then 'Steam' end as GamePlatform,
                        o.PointCardKind,
                        5 as HandlingFee
                        FROM ordertobuy  ob
                        LEFT JOIN game g on g.GameId = ob.GameId 
                        LEFT JOIN `order` o on o.ProductNumber = ob.ProductNumber  
                        LEFT JOIN producttype pt on pt.TypeId = o.TypeId
                        where (ob.ProductNumber = '".$ProductNumber."' or ob.OrderNumber = '".$ProductNumber."') and ob.PaymentType = '0' and ob.State = '1' ORDER BY ob.CreateDate desc LIMIT 1 "); 

                        $randoma = "";
                        for ($i=1; $i<=20; $i=$i+1)
                        {
                            $c=mt_rand(1,3);
                            if($c==1){$a=mt_rand(97,122);$b=chr($a);}
                            if($c==2){$a=mt_rand(65,90);$b=chr($a);}
                            if($c==3){$b=mt_rand(0,9);}
                            $randoma = $randoma.$b;
                        }
                        if (CDbShell::num_rows() > 0) {
                            $Row = CDbShell::fetch_array();
                            switch ($Row['TypeName']) {
                                case "道具":
                                    $text_color = "border_orange";
                                break;
                                case "遊戲幣":
                                    $text_color = "border_blue";
                                break;
                                case "帳號":
                                    $text_color = "border_green";
                                break;
                                case "代儲":
                                    $text_color = "border_pink";
                                break;
                                case "代練":
                                    $text_color = "border_purple";
                                break;
                                case "點數卡":
                                    $text_color = "border_red";
                                break;
                                case "商城":
                                    $text_color = "border_lightgreen";
                                break;
                                case "其他":
                                    $text_color = "border_black";
                                break;
                                case "送禮":
                                    $text_color = "border_lightblue";
                                break;
                            }
                            $PointCardKind = ($Row['PointCardKind'] == "") ? 'display: none;' : '';
                            CSession::setVar("OrdertobuyRow", $Row["Row"]);
                            
                            $data[0] = $Row["FileName"];
                            $data[1] = $Row["GameName"];
                            $data[2] = $Row["TypeName"];
                            $data[3] = $Row["ProductTitle"];
                            $data[4] = $Row["ProductNumber"];
                            $data[5] = $Row["GamePlatform"];
                            $data[6] = $Row["GameServer"];
                            $data[7] = $Row['PointCardKind'];
                            $data[8] = $PointCardKind;
                            $data[9] = $text_color;
                            $data[10] = $Row["Quantity"];
                            $data[11] = $Row["SumPrice"];
                            $data[12] = strval($Row["SumPrice"]+$Row["HandlingFee"]);
                            $data[13] = $Row["HandlingFee"];
                            $data[14] = str_pad(rand(1000000000000, 9999999999999), 13, '0', STR_PAD_LEFT); // 虛擬帳號暫時
                            $data[15] = $randoma;
                            $data[16] = date("Y-m-d H:i:s", strtotime("+2 day", strtotime(date("Y-m-d H:i:s"))));
                            echo json_encode($data);
                            exit;
                        }else{
                            echo "window.location.href='Member/Order_buy'";exit;
                        }
                    }

                    if('ATMConfirmBuy' == $_POST['fun']){
                        $ProductTitle    =$_POST["ProductTitle"];
                        $OrderNumber     =$_POST["OrderNumber"];
                        $ProductNumber   =$_POST["ProductNumber"];
                        $PaymentMethod   =$_POST["PaymentMethod"];
                        $SumPricePlusHand=$_POST["SumPricePlusHand"];
                        $PaymentType     =$_POST["PaymentType"];

                        // $Id = CDbShell::insert_id();
                        // echo $Id;exit;
                        // $field = array("PaymentMethod","SumPricePlusHand","VirtualAccount","PaymentType","PayDate","State");
                        // $value = array($PaymentMethod."-".$BankName,$SumPricePlusHand,$VirtualAccount,$PaymentType,date("Y-m-d H:i:s"),'2');
                        // CDbShell::update("`ordertobuy`", $field, $value, "`Row` = ".CSession::GetVar("OrdertobuyRow"));
                        // $field = array("OrderState");
                        // $value = array(2);
                        // CDbShell::update("`order`", $field, $value, "`ProductNumber` = '".$ProductNumber."'");
                        CDbShell::query("SELECT KuTsuenQuantity, HsiaoShouQuantity FROM `order` WHERE `ProductNumber` = '".$ProductNumber."' ");
                        $Row = CDbShell::fetch_array();
                        $field = array("KuTsuenQuantity","HsiaoShouQuantity");
                        $value = array($Row["KuTsuenQuantity"]-1,$Row["HsiaoShouQuantity"]+1);
                        CDbShell::update("`order`", $field, $value, "`ProductNumber` = '".$ProductNumber."'");

                        $PayCanal = 'ATM虛擬帳號';
                                
                        CDbShell::Connect();
                        CDbShell::query("SELECT MemberId, SpreadCode FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
                        $MRow = CDbShell::fetch_array();
            
                        mt_srand();
                        $CashFlowID = "EG".substr(date("Y"), -1).Date("mdHis").str_pad(floor(microtime() * 100),4,'0',STR_PAD_LEFT).str_pad(mt_rand(0,9999),4,'0',STR_PAD_LEFT);
                
                        $field = array("SumPricePlusHand","PaymentMethod","PaymentType");
                        $value = array($SumPricePlusHand,$PayCanal,$PaymentType);
                        CDbShell::update("`ordertobuy`", $field, $value, "`MemberId` = '".$MRow["MemberId"]."' and `ProductNumber` = '".$ProductNumber."' and `OrderNumber` = '".$OrderNumber."'");
            
                        $parameter = array(
                            "HashKey"				=> "JNYYYWP8WXS95HP85F9HU9BJ5",
                            "HashIV"				=> "3NWU7VKNRTHNG8FJ6ERRVYWF8X",
                            "MerTradeID"			=> $CashFlowID,
                            "MerProductID"			=> $OrderNumber,
                            "MerUserID"				=> $MRow["SpreadCode"],
                            "Amount"				=> intval($SumPricePlusHand),
                            "TradeDesc"				=> "Pay".$SumPricePlusHand."元",
                            "ItemName"				=> $ProductTitle
                        );
            
                        // $strReturn = SockPost($PayURL, $parameter, $curlerror);
                        $log_obj = json_encode($parameter);
                        echo $log_obj;
                        exit;
                        // echo "window.location.href='Member/Center'";exit;
                    }
                    if('ShopConfirmBuy' == $_POST['fun']){
                        $ProductTitle    =$_POST["ProductTitle"];
                        $OrderNumber     =$_POST["OrderNumber"];
                        $ProductNumber   =$_POST["ProductNumber"];
                        // $PaymentMethod   =$_POST["PaymentMethod"];
                        $SumPricePlusHand=$_POST["SumPricePlusHand"];
                        // $PaymentCode     =$_POST["PaymentCode"];
                        $PaymentType     =$_POST["PaymentType"];
                        // $PaymentEndDate  =$_POST["PaymentEndDate"];
                        // $Id = CDbShell::insert_id();
                        // // echo $Id;exit;
                        // $field = array("PaymentMethod","SumPricePlusHand","PaymentType","PaymentCode","PaymentEndDate","PayDate","State");
                        // $value = array($PaymentMethod,$SumPricePlusHand,$PaymentType,$PaymentCode,$PaymentEndDate,date("Y-m-d H:i:s"),'2');
                        // CDbShell::update("`ordertobuy`", $field, $value, "`Row` = ".CSession::GetVar("OrdertobuyRow"));
                        // $field = array("OrderState");
                        // $value = array(2);
                        // CDbShell::update("`order`", $field, $value, "`ProductNumber` = '".$ProductNumber."'");

                        CDbShell::query("SELECT KuTsuenQuantity, HsiaoShouQuantity FROM `order` WHERE `ProductNumber` = '".$ProductNumber."' ");
                        $Row = CDbShell::fetch_array();
                        $field = array("KuTsuenQuantity","HsiaoShouQuantity");
                        $value = array($Row["KuTsuenQuantity"]-1,$Row["HsiaoShouQuantity"]+1);
                        CDbShell::update("`order`", $field, $value, "`ProductNumber` = '".$ProductNumber."'");

                        $PayCanal = '超商付款';
                                
                        CDbShell::Connect();
                        CDbShell::query("SELECT MemberId, SpreadCode FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
                        $MRow = CDbShell::fetch_array();
            
                        mt_srand();
                        $CashFlowID = "EG".substr(date("Y"), -1).Date("mdHis").str_pad(floor(microtime() * 100),4,'0',STR_PAD_LEFT).str_pad(mt_rand(0,9999),4,'0',STR_PAD_LEFT);
                    
                        // $_Points = 0;
            
                        // $field = array("MemberId", "OrderID", "PayCanal", "Amount", "Points", "Status");
                        // $value = array($MRow["MemberId"], $OrderNumber, $PayCanal, intval($SumPricePlusHand), $_Points, "0");
                        // CDbShell::insert("storeddata", $field, $value);

                        $field = array("SumPricePlusHand","PaymentMethod","PaymentType");
                        $value = array($SumPricePlusHand,$PayCanal,$PaymentType);
                        CDbShell::update("`ordertobuy`", $field, $value, "`MemberId` = '".$MRow["MemberId"]."' and `ProductNumber` = '".$ProductNumber."' and `OrderNumber` = '".$OrderNumber."'");
            
                        $parameter = array(
                            "HashKey"				=> "JNYYYWP8WXS95HP85F9HU9BJ5",
                            "HashIV"				=> "3NWU7VKNRTHNG8FJ6ERRVYWF8X",
                            "MerTradeID"			=> $CashFlowID,
                            "MerProductID"			=> $OrderNumber,
                            "MerUserID"				=> $MRow["SpreadCode"],
                            "Amount"				=> intval($SumPricePlusHand),
                            "TradeDesc"				=> "Pay".$SumPricePlusHand."元",
                            "ItemName"				=> $ProductTitle
                        );
            
                        // $strReturn = SockPost($PayURL, $parameter, $curlerror);
                        $log_obj = json_encode($parameter);
                        echo $log_obj;
                        exit;
            
                        // $sHtml = "<form id='rongpaysubmit' name='rongpaysubmit' action='".$PayURL."' method='POST'>";
                        // while (list ($key, $val) = each ($parameter)) 
                        // {
                        //     if ($key != 'returnUrl') $sHtml.= "<input type='hidden' id='".$key."' name='".$key."' value='".$val."'/>";
                        // }
                        // //$sHtml = $sHtml."<input type='hidden' name='Validate' value='".$Validate."'/>";
                        // //$sHtml = $sHtml."<input type='hidden' id='returnUrl' name='returnUrl' value='".$returnUrl."'/>";
                        
                        // //submit按钮控件请不要含有name属性
                        // $sHtml = $sHtml."<input type='submit' value='付款' style='display:none'></form>";
            
                        // $sHtml = $sHtml."<script>document.forms['rongpaysubmit'].submit();</script>";
                        
                        // echo $sHtml;
                        // exit;
                        
                        // echo "window.location.href='Order/strReturn'";exit;
                    }
                }else{
                    echo "window.location.href='Index/index'";exit;
                }
                // echo "window.location.href='Member/Center'";exit;
                
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../Shopping_cart.html");
    }

    function OrderBuyInfo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                $Rowm = CDbShell::fetch_array();
                // echo $_POST["OrderNumber"];
                if('OrderBuyInfo' == $_POST['fun']){
                    CDbShell::query("SELECT
                    o.ProductTitle,
                    ob.ProductNumber,
                    ob.OrderNumber,
                    o.MemberId,
                    o.CreateDate,
                    case when o.GamePlatform = 1 then 'Android' when o.GamePlatform = 2 then 'iOS' when o.GamePlatform = 3 then '電腦' when o.GamePlatform = 4 then 'Steam' end as GamePlatform,
                    pt.TypeName,
                    o.PointCardKind,
                    ob.SumPrice,
                    ob.SumPricePlusHand,
					PaymentMethod,
                    case when ob.State = 1 then '待付款' when ob.State = 2 then '待收貨' when ob.State = 3 then '已完成' when ob.State = 4 then '已取消' end as `State`
                    FROM `ordertobuy` ob
                    LEFT JOIN member m ON m.MemberId = ob.MemberId
                    LEFT JOIN `order` o ON o.ProductNumber = ob.ProductNumber
                    LEFT JOIN producttype pt on pt.TypeId = o.TypeId WHERE m.MemberId = '".$Rowm['MemberId']."' and ob.OrderNumber='".$_POST["OrderNumber"]."' order by ob.CreateDate desc ");   
                    $Row = CDbShell::fetch_array();
                    if (CDbShell::num_rows() > 0) {
                        $data= array($Rowm["MemberId"],$Row["ProductTitle"],$Row["ProductNumber"],$Row["OrderNumber"],$Row["MemberId"],$Row["CreateDate"],$Row["GamePlatform"],
                        $Row["TypeName"],$Row["PointCardKind"],$Row["SumPrice"],$Row["SumPricePlusHand"],$Row["PaymentMethod"],$Row["State"]);
                        echo json_encode($data);
                        exit;
                    }
                }
                if('OrderBuyQA' == $_POST['fun']){
                    CDbShell::query("SELECT RowId, BuyMemberId, SellMemberId, OrderNumber, QAInfo, CreateDate, LEFT(CreateDate,10) as `Date`, QAStatus FROM quesandans 
                    where BuyMemberId = '".$Rowm['MemberId']."' and OrderNumber = '".$_POST["OrderNumber"]."' order by `Date` desc ");
                    // echo "SELECT RowId, BuyMemberId, BuyMemberId, OrderNumber, QAInfo, CreateDate, LEFT(CreateDate,10) as `Date`, QAStatus FROM quesandans 
                    // where BuyMemberId = '".$Rowm['MemberId']."' and OrderNumber = '".$_POST["OrderNumber"]."' order by `Date` desc ";
                    if (CDbShell::num_rows() > 0) {
                        while ($Rowq = CDbShell::fetch_array()) {
                            if($Rowq["QAStatus"] == '1'){ //問
                                $Layout .=
                                <<<EOF
                                <div class="ask_main">
                                    <div class="qa_box">
                                        <img src="images/sell_info/fa-user-circlei-r.png" alt="" class="qa_head_l">
                                        <div class="txt">
                                            <div class="name text_deepred">問：</div>
                                            <div class="date text_deepred">{$Rowq['CreateDate']}</div>
                                            <div class="info">{$Rowq['QAInfo']}</div>
                                        </div>
                                    </div>
                                </div>
EOF;
                            }else if($Rowq["QAStatus"] == '0'){ //答
                                $Layout .=
                                <<<EOF
                                <div class="answer_main">
                                    <div class="qa_box">
                                        <img src="images/sell_info/fa-user-circlei-g.png" alt="" class="qa_head_r">
                                        <div class="txt">
                                            <div class="name text_blue">賣家：</div>
                                            <div class="date text_blue">{$Rowq['CreateDate']}</div>
                                            <div class="info">{$Rowq['QAInfo']}</div>
                                            <!--清除float浮動跑版(此行不可刪)-->
                                            <div class="clear"></div>
                                        </div>
                                        <!--清除float浮動跑版(此行不可刪)-->
                                        <div class="clear"></div>
                                    </div>
                                    <!--清除float浮動跑版(此行不可刪)-->
                                    <div class="clear"></div>
                                </div>
EOF;
                            }
                        }
                        echo $Layout;
                        exit;
                    }else{
                        $Layout .=
                            <<<EOF
                            <div class="no-orderlist_box">
                                <img src="images/member/job-search.png" alt="暫無相關訊息">
                                <span>暫無相關訊息</span>
                            </div>
EOF;
                    }
                    CDbShell::DB_close();
                    echo $Layout;
                    exit;
                }
                if('OrderBuyQAInsert' == $_POST['fun']){
                    CDbShell::query("SELECT MemberId, SellMemberId, OrderNumber FROM ordertobuy where OrderNumber = '".$_POST["OrderNumber"]."' ");
                    $Rowo = CDbShell::fetch_array();
                    $field = array("BuyMemberId","SellMemberId","OrderNumber","QAInfo","CreateDate","QAStatus");
                    $value = array($Rowo["MemberId"],$Rowo["SellMemberId"],$_POST["OrderNumber"],$_POST["QAtext"],date('Y/m/d H:i:s'),'1');
                    CDbShell::insert("quesandans", $field, $value);

                    echo "window.location.reload()";
                    exit;
                }
                if('OrderBuyEvaluInsert' == $_POST['fun']){
                    CDbShell::query("SELECT MemberId, SellMemberId, OrderNumber FROM ordertobuy where OrderNumber = '".$_POST["OrderNumber"]."' ");
                    $Rowo = CDbShell::fetch_array();
                    $field = array("MemberId","OrderNumber","Evalu","EvaluInfo","EvaluDate","EvaluState","SellMemberId");
                    $value = array($Rowo["MemberId"],$_POST["OrderNumber"],$_POST["Evalu"],$_POST["EvaluInfo"],date('Y/m/d H:i:s'),'1',$Rowo["SellMemberId"]);
                    CDbShell::insert("evaluate", $field, $value);

                    echo "window.location.reload()";
                    exit;
                }
                if('CommentSell_top' == $_POST['fun']){ //來自賣家評價
                    CDbShell::query("SELECT e1.good as nowgood, e2.good as beforegood, e3.soso as nowsoso, e4.soso as beforesoso, e5.bad as nowbad, e6.bad as beforebad
                    FROM evaluate e
					LEFT JOIN
                    (
                        SELECT MemberId, count(Evalu) as good, EvaluDate FROM evaluate 
                        WHERE Evalu = '1' and 
                        (substring(EvaluDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        or substring(EvaluDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '2'
                    ) e1 ON e1.MemberId = e.MemberId
                    LEFT JOIN
                    (
                        SELECT MemberId, count(Evalu) as good, EvaluDate FROM evaluate 
                        WHERE Evalu = '1' and 
                        (substring(EvaluDate,6,2) != case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        or substring(EvaluDate,1,4) != case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '2'
                    ) e2 ON e2.MemberId = e.MemberId
                    LEFT JOIN
                    (
                        SELECT MemberId, count(Evalu) as soso, EvaluDate FROM evaluate 
                        WHERE Evalu = '2' and 
                        (substring(EvaluDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        and substring(EvaluDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '2'
                    ) e3 ON e3.MemberId = e.MemberId
                    LEFT JOIN
                    (
                        SELECT MemberId, count(Evalu) as soso, EvaluDate FROM evaluate 
                        WHERE Evalu = '2' and 
                        (substring(EvaluDate,6,2) != case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        or substring(EvaluDate,1,4) != case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '2'
                    ) e4 ON e4.MemberId = e.MemberId
                    LEFT JOIN
                    (
                        SELECT MemberId, count(Evalu) as bad, EvaluDate FROM evaluate 
                        WHERE Evalu = '3' and 
                        (substring(EvaluDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        and substring(EvaluDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '2'
                    ) e5 ON e5.MemberId = e.MemberId
                    LEFT JOIN
                    (
                        SELECT MemberId, count(Evalu) as bad, EvaluDate FROM evaluate 
                        WHERE Evalu = '3' and 
                        (substring(EvaluDate,6,2) != case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        or substring(EvaluDate,1,4) != case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '2'
                    ) e6 ON e6.MemberId = e.MemberId
                    WHERE e.MemberId = '".$Rowm['MemberId']."' LIMIT 1 ");

                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();
                        $nowgood = $Row["nowgood"] == null ? '0' : $Row["nowgood"];
                        $beforegood = $Row["beforegood"] == null ? '0' : $Row["beforegood"];
                        $nowsoso = $Row["nowsoso"] == null ? '0' : $Row["nowsoso"];
                        $beforesoso = $Row["beforesoso"] == null ? '0' : $Row["beforesoso"];
                        $nowbad = $Row["nowbad"] == null ? '0' : $Row["nowbad"];
                        $beforebad = $Row["beforebad"] == null ? '0' : $Row["beforebad"];
                        $good= $Row["nowgood"] + $Row["beforegood"];
                        $bad = $Row["nowbad"]+$Row["beforebad"];
                        $avgEvalu = number_format(($good / ($good + $bad) * 100));
                        $data= array($nowgood,$beforegood,$nowsoso,$beforesoso,$nowbad,$beforebad,$good,$bad,($good-$bad),$avgEvalu);
                        echo json_encode($data);exit;
                    }
                }
                if('CommentSell' == $_POST['fun']){
                    CDbShell::query("SELECT m.MemberAccount, e.OrderNumber, Evalu, EvaluInfo, EvaluDate, ReplyInfo, ReplyDate, o.ProductTitle
                    FROM evaluate e
                    LEFT JOIN member m ON m.MemberId = e.MemberId
                    LEFT JOIN ordertobuy ob ON ob.OrderNumber = e.OrderNumber
                    LEFT JOIN `order` o ON o.ProductNumber = ob.ProductNumber where e.MemberId = '".$Rowm["MemberId"]."' AND EvaluState = '2' ORDER BY EvaluDate DESC");
                    if (CDbShell::num_rows() > 0) {
                        while ($Row = CDbShell::fetch_array()) {
                            switch ($Row['Evalu']) {
                                case "1":
                                    $Evalu = '<td data-th="評價"><img src="images/sell_info/like.png" alt="好評"><span>好評</span></td>';
                                break;
                                case "2":
                                    $Evalu = '<td data-th="評價"><img src="images/sell_info/neutral.png" alt=""><span>普評</span></td>';
                                break;
                                case "3":
                                    $Evalu = '<td data-th="評價"><img src="images/sell_info/dislike.png" alt=""><span>差評</span></td>';
                                break;
                            }
                            $reply_box = ($Row['ReplyInfo'] == "") ? 'display: none;' : '';
                            $reply = ($Row['ReplyInfo'] == "") ? 'display: none;' : '';
                            $ReplyComment = ($Row['ReplyInfo'] == "") ? '' : 'display: none;';
                            $MemberAccount = substr_replace($Row["MemberAccount"], '*****', 3, -2);
                            $Layout .=
                            <<<EOF
                            <tr>
                                {$Evalu}
                                <td data-th="買家帳號">{$MemberAccount}</td>
                                <td data-th="評價內容">
                                    <!-- 買家評價 -->
                                    <div class="talk_box">
                                        <div class="ms_txt">{$Row["EvaluInfo"]}</div>
                                        <div class="ms_date text_gray float_r">{$Row["EvaluDate"]}</div>
                                        <!--清除float浮動跑版(此行不可刪)-->
                                        <div class="clear"></div>
                                    </div>
                                    <!-- 回覆才會秀出下方內容，沒回則關閉 -->
                                    <div class="reply_box text_deepred" style="{$reply_box}">
                                        <div class="ms_txt float_r">{$Row['ReplyInfo']}</div>
                                        <div class="ms_date float_r">{$Row['ReplyDate']}</div>
                                        <!--清除float浮動跑版(此行不可刪)-->
                                        <div class="clear"></div>
                                    </div>
                                </td>
                                <td data-th="商品標題">{$Row['ProductTitle']}</td>
                                <!-- 回覆按鈕 -->
                                <td data-th="回評">
                                    <input type="button" value="回覆" id="ReplyComment" class="ReplyComment btn_big btn_green" style="{$ReplyComment}" data-value="{$Row['OrderNumber']}">
                                    <input type="button" value="已回" class="btn_big btn_disabled" style="{$reply}" disabled>
                                </td>
                            </tr>
EOF;
                        }
                        echo $Layout;
                        exit;
                    }else{
                        $Layout .=
                            <<<EOF
                            <div class="no-orderlist_box" style="display: none;">
                                <img src="images/member/job-search.png" alt="">
                                <span>尚無評價</span>
                            </div>
EOF;
                    }
                    CDbShell::DB_close();
                    echo $Layout;
                    exit;
                }
                if('CommentReplySell' == $_POST['fun']){
                    $field = array("ReplyInfo","ReplyDate");
                    $value = array($_POST["ReplyInfo"],date('Y/m/d H:i:s'));
                    CDbShell::update("evaluate", $field, $value, "OrderNumber = ".$_POST["OrderNumber"]);

                    echo "window.location.reload()";
                    exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../order_info_buy.html");
    }

    function OrderSellInfo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::GetVar("Account")."'  AND MemberPassword = '".CSession::GetVar("Password")."'" );
                $Rowm = CDbShell::fetch_array();
                // echo $_POST["OrderNumber"];
                if('OrderSellInfo' == $_POST['fun']){
                    CDbShell::query("SELECT 
                    ob.MemberId,
                    o.ProductNumber,
					ob.OrderNumber,
                    -- case when o.ProductId = 1 then '點數卡'when o.ProductId = 2 then '手機遊戲' when o.ProductId = 3 then '線上遊戲' end as ProductId,
                    pt.TypeName,
                    -- TypeId,
                    case when o.GamePlatform = 1 then 'Android' when o.GamePlatform = 2 then 'iOS' when o.GamePlatform = 3 then '電腦' when o.GamePlatform = 4 then 'Steam' end as GamePlatform,
                    o.ProductTitle,
                    o.PointCardKind,
                    case when ob.State = 1 then '待收款' when ob.State = 2 then '待出貨' when ob.State = 3 then '已完成' when ob.State = 4 then '已取消' else '未送出的訂單' end as OrderState,
                    ob.PaymentMethod,
                    ob.SumPrice,
                    ob.SumPricePlusHand,
                    ob.CreateDate
                    FROM `order` o
                    LEFT JOIN member m ON m.MemberId = o.MemberId
                    LEFT JOIN `ordertobuy` ob ON ob.ProductNumber = o.ProductNumber
                    LEFT JOIN producttype pt on pt.TypeId = o.TypeId WHERE m.MemberId = '".$Rowm['MemberId']."' and ob.OrderNumber='".$_POST["OrderNumber"]."' order by CreateDate desc ");   
                    $Row = CDbShell::fetch_array();
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
                    if (CDbShell::num_rows() > 0) {
                        $data= array($Row["MemberId"],$Row["ProductNumber"],$Row["OrderNumber"],$Row["TypeName"],$Row["GamePlatform"],$Row["ProductTitle"],$Row["PointCardKind"],$Row["OrderState"],
                        $Row["PaymentMethod"],$Row["SumPrice"],$Row["SumPricePlusHand"],$Row["CreateDate"],$text_color);
                        echo json_encode($data);
                        exit;
                    }
                }
                if('OrderSellQA' == $_POST['fun']){
                    CDbShell::query("SELECT RowId, MemberAccount, SellMemberId, OrderNumber, QAInfo, q.CreateDate, LEFT(q.CreateDate,10) as `Date`, QAStatus 
                    FROM quesandans q
                    LEFT JOIN member m on m.MemberId = q.BuyMemberId 
                    where BuyMemberId = '".$Rowm['MemberId']."' and OrderNumber = '".$_POST["OrderNumber"]."' order by `Date` desc ");
                    // echo "SELECT RowId, BuyMemberId, BuyMemberId, OrderNumber, QAInfo, CreateDate, LEFT(CreateDate,10) as `Date`, QAStatus FROM quesandans 
                    // where BuyMemberId = '".$Rowm['MemberId']."' and OrderNumber = '".$_POST["OrderNumber"]."' order by `Date` desc ";
                    if (CDbShell::num_rows() > 0) {
                        while ($Rowq = CDbShell::fetch_array()) {
                            $MemberAccount = substr_replace($Rowq["MemberAccount"], '*****', 3, -2);
                            if($Rowq["QAStatus"] == '1'){ //問
                                $Layout .=
                                <<<EOF
                                <div class="ask_main">
                                    <div class="qa_box">
                                        <img src="images/sell_info/fa-user-circlei-r.png" alt="" class="qa_head_l">
                                        <div class="txt">
                                            <div class="name text_deepred">會員 {$MemberAccount} 問：</div>
                                            <div class="date text_deepred">{$Rowq['CreateDate']}</div>
                                            <div class="info">{$Rowq['QAInfo']}</div>
                                        </div>
                                    </div>
                                </div>
EOF;
                            }else if($Rowq["QAStatus"] == '0'){ //答
                                $Layout .=
                                <<<EOF
                                <div class="answer_main">
                                    <div class="qa_box">
                                        <img src="images/sell_info/fa-user-circlei-g.png" alt="" class="qa_head_r">
                                        <div class="txt">
                                            <div class="name text_blue">賣家：</div>
                                            <div class="date text_blue">{$Rowq['CreateDate']}</div>
                                            <div class="info">{$Rowq['QAInfo']}</div>
                                            <!--清除float浮動跑版(此行不可刪)-->
                                            <div class="clear"></div>
                                        </div>
                                        <!--清除float浮動跑版(此行不可刪)-->
                                        <div class="clear"></div>
                                    </div>
                                    <!--清除float浮動跑版(此行不可刪)-->
                                    <div class="clear"></div>
                                </div>
EOF;
                            }
                        }
                        echo $Layout;
                        exit;
                    }else{
                        $Layout .=
                            <<<EOF
                            <div class="no-orderlist_box" >
                                <img src="images/member/job-search.png" alt="暫無相關訊息">
                                <span>暫無相關訊息</span>
                            </div>
EOF;
                    }
                    CDbShell::DB_close();
                    echo $Layout;
                    exit;
                }
                if('OrderSellQAInsert' == $_POST['fun']){
                    CDbShell::query("SELECT MemberId, SellMemberId, OrderNumber FROM ordertobuy where OrderNumber = '".$_POST["OrderNumber"]."' ");
                    $Rowo = CDbShell::fetch_array();
                    $field = array("BuyMemberId","SellMemberId","OrderNumber","QAInfo","CreateDate","QAStatus");
                    $value = array($Rowo["MemberId"],$Rowo["SellMemberId"],$_POST["OrderNumber"],$_POST["QAtext"],date('Y/m/d H:i:s'),'0');
                    CDbShell::insert("quesandans", $field, $value);

                    echo "window.location.reload()";
                    exit;
                }
                if('OrderSellEvaluInsert' == $_POST['fun']){
                    CDbShell::query("SELECT MemberId, SellMemberId, OrderNumber FROM ordertobuy where OrderNumber = '".$_POST["OrderNumber"]."' ");
                    $Rowo = CDbShell::fetch_array();
                    $field = array("MemberId","OrderNumber","Evalu","EvaluInfo","EvaluDate","EvaluState","SellMemberId");
                    $value = array($Rowo["MemberId"],$_POST["OrderNumber"],$_POST["Evalu"],$_POST["EvaluInfo"],date('Y/m/d H:i:s'),'2',$Rowo["SellMemberId"]);
                    CDbShell::insert("evaluate", $field, $value);

                    echo "window.location.reload()";
                    exit;
                }
                if('CommentBuy_top' == $_POST['fun']){ //來自買家評價
                    CDbShell::query("SELECT e1.good as nowgood, e2.good as beforegood, e3.soso as nowsoso, e4.soso as beforesoso, e5.bad as nowbad, e6.bad as beforebad
                    FROM evaluate e
										LEFT JOIN
                    (
                        SELECT SellMemberId, count(Evalu) as good, EvaluDate FROM evaluate 
                        WHERE Evalu = '1' and 
                        (substring(EvaluDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        or substring(EvaluDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '1'
                    ) e1 ON e1.SellMemberId = e.SellMemberId
                    LEFT JOIN
                    (
                        SELECT SellMemberId, count(Evalu) as good, EvaluDate FROM evaluate 
                        WHERE Evalu = '1' and 
                        (substring(EvaluDate,6,2) != case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        or substring(EvaluDate,1,4) != case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '1'
                    ) e2 ON e2.SellMemberId = e.SellMemberId
                    LEFT JOIN
                    (
                        SELECT SellMemberId, count(Evalu) as soso, EvaluDate FROM evaluate 
                        WHERE Evalu = '2' and 
                        (substring(EvaluDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        and substring(EvaluDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '1'
                    ) e3 ON e3.SellMemberId = e.SellMemberId
                    LEFT JOIN
                    (
                        SELECT SellMemberId, count(Evalu) as soso, EvaluDate FROM evaluate 
                        WHERE Evalu = '2' and 
                        (substring(EvaluDate,6,2) != case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        or substring(EvaluDate,1,4) != case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '1'
                    ) e4 ON e4.SellMemberId = e.SellMemberId
                    LEFT JOIN
                    (
                        SELECT SellMemberId, count(Evalu) as bad, EvaluDate FROM evaluate 
                        WHERE Evalu = '3' and 
                        (substring(EvaluDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        and substring(EvaluDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '1'
                    ) e5 ON e5.SellMemberId = e.SellMemberId
                    LEFT JOIN
                    (
                        SELECT SellMemberId, count(Evalu) as bad, EvaluDate FROM evaluate 
                        WHERE Evalu = '3' and 
                        (substring(EvaluDate,6,2) != case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                        or substring(EvaluDate,1,4) != case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end) AND EvaluState = '1'
                    ) e6 ON e6.SellMemberId = e.SellMemberId
                    WHERE e.SellMemberId = '".$Rowm['MemberId']."' LIMIT 1 ");

                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();
                        $nowgood = $Row["nowgood"] == null ? '0' : $Row["nowgood"];
                        $beforegood = $Row["beforegood"] == null ? '0' : $Row["beforegood"];
                        $nowsoso = $Row["nowsoso"] == null ? '0' : $Row["nowsoso"];
                        $beforesoso = $Row["beforesoso"] == null ? '0' : $Row["beforesoso"];
                        $nowbad = $Row["nowbad"] == null ? '0' : $Row["nowbad"];
                        $beforebad = $Row["beforebad"] == null ? '0' : $Row["beforebad"];
                        $good= $Row["nowgood"] + $Row["beforegood"];
                        $bad = $Row["nowbad"]+$Row["beforebad"];
                        $avgEvalu = number_format(($good / ($good + $bad) * 100));
                        $data= array($nowgood,$beforegood,$nowsoso,$beforesoso,$nowbad,$beforebad,$good,$bad,($good-$bad),$avgEvalu);
                        echo json_encode($data);exit;
                    }
                }
                if('CommentBuy' == $_POST['fun']){
                    CDbShell::query("SELECT m.MemberAccount, e.OrderNumber, Evalu, EvaluInfo, EvaluDate, ReplyInfo, ReplyDate, o.ProductTitle
                    FROM evaluate e
                    LEFT JOIN member m ON m.MemberId = e.SellMemberId
                    LEFT JOIN ordertobuy ob ON ob.OrderNumber = e.OrderNumber
                    LEFT JOIN `order` o ON o.ProductNumber = ob.ProductNumber where e.SellMemberId = '".$Rowm["MemberId"]."' AND EvaluState = '1' ORDER BY EvaluDate DESC");
                    if (CDbShell::num_rows() > 0) {
                        while ($Row = CDbShell::fetch_array()) {
                            switch ($Row['Evalu']) {
                                case "1":
                                    $Evalu = '<td data-th="評價"><img src="images/sell_info/like.png" alt="好評"><span>好評</span></td>';
                                break;
                                case "2":
                                    $Evalu = '<td data-th="評價"><img src="images/sell_info/neutral.png" alt=""><span>普評</span></td>';
                                break;
                                case "3":
                                    $Evalu = '<td data-th="評價"><img src="images/sell_info/dislike.png" alt=""><span>差評</span></td>';
                                break;
                            }
                            $reply_box = ($Row['ReplyInfo'] == "") ? 'display: none;' : '';
                            $reply = ($Row['ReplyInfo'] == "") ? 'display: none;' : '';
                            $ReplyComment = ($Row['ReplyInfo'] == "") ? '' : 'display: none;';
                            $MemberAccount = substr_replace($Row["MemberAccount"], '*****', 3, -2);
                            $Layout .=
                            <<<EOF
                            <tr>
                                {$Evalu}
                                <td data-th="買家帳號">{$MemberAccount}</td>
                                <td data-th="評價內容">
                                    <!-- 買家評價 -->
                                    <div class="talk_box">
                                        <div class="ms_txt">{$Row["EvaluInfo"]}</div>
                                        <div class="ms_date text_gray float_r">{$Row["EvaluDate"]}</div>
                                        <!--清除float浮動跑版(此行不可刪)-->
                                        <div class="clear"></div>
                                    </div>
                                    <!-- 回覆才會秀出下方內容，沒回則關閉 -->
                                    <div class="reply_box text_deepred" style="{$reply_box}">
                                        <div class="ms_txt float_r">{$Row['ReplyInfo']}</div>
                                        <div class="ms_date float_r">{$Row['ReplyDate']}</div>
                                        <!--清除float浮動跑版(此行不可刪)-->
                                        <div class="clear"></div>
                                    </div>
                                </td>
                                <td data-th="商品標題">{$Row['ProductTitle']}</td>
                                <!-- 回覆按鈕 -->
                                <td data-th="回評">
                                    <input type="button" value="回覆" id="ReplyComment" class="ReplyComment btn_big btn_green" style="{$ReplyComment}" data-value="{$Row['OrderNumber']}">
                                    <input type="button" value="已回" class="btn_big btn_disabled" style="{$reply}" disabled>
                                </td>
                            </tr>
EOF;
                        }
                        echo $Layout;
                        exit;
                    }else{
                        $Layout .=
                            <<<EOF
                            <div class="no-orderlist_box" style="display: none;">
                                <img src="images/member/job-search.png" alt="">
                                <span>尚無評價</span>
                            </div>
EOF;
                    }
                    CDbShell::DB_close();
                    echo $Layout;
                    exit;
                }
                if('CommentReplyBuy' == $_POST['fun']){
                    $field = array("ReplyInfo","ReplyDate");
                    $value = array($_POST["ReplyInfo"],date('Y/m/d H:i:s'));
                    CDbShell::update("evaluate", $field, $value, "OrderNumber = ".$_POST["OrderNumber"]);

                    echo "window.location.reload()";
                    exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../order_info_sell.html");
    }
    
    function CommentSell() {
        include("../comment_sell.html");
    }

    function CommentBuy() {
        include("../comment_buy.html");
    }

    function QASell() {
        include("../qa_sell.html");
    }

    function QABuy() {
        include("../qa_buy.html");
    }

    function MailMembers() {
        include("../mail_members.html");
    }

    function Complain() {
        include("../complain.html");
    }

    function DealNotify() {
        header('Access-Control-Allow-Origin: *');

        if (!class_exists('CDbShell')) {
            include_once '../BaseClass/CDbShell.php';
        }
        
        //var_dump($_POST);
        $_IPV4 = Operate::get_client_ip(1, true);
        $_IP = Operate::get_client_ip(0, true);

        $fp = fopen('../operate/Log/DealNotify_LOG_'.date("YmdHis").'.txt', 'a');
        fwrite($fp, " ---------------- 開始 ---------------- ".PHP_EOL);
        fwrite($fp, " IPV4  >> ". $_IPV4 .PHP_EOL);
        fwrite($fp, " IP    >> ". $_IP .PHP_EOL);
        while (list ($key, $val) = each ($_POST)) 
        {
            fwrite($fp, "key =>".$key."  val=>".$val.PHP_EOL);
        };
        fclose($fp);

        // if (strcmp($_IPV4, "2813554502") == 0) {

        $Validate = MD5("ValidateKey=63G6JSX475Q&HashKey=JNYYYWP8WXS95HP85F9HU9BJ5&RtnCode=".$_POST["RtnCode"]."&TradeID=".$_POST["MerTradeID"]."&UserID=".$_POST["MerUserID"]."&Money=".$_POST['Amount']);
        
        // $Validate = MD5("ValidateKey=63G6JSX475Q&RtnCode=".$_POST["RtnCode"]."&MerTradeID=".$_POST["MerTradeID"]."&MerUserID=".$_POST["MerUserID"]);
        
        $fp = fopen('../operate/Log/Notify_LOG_'.date("YmdHis").'.txt', 'a');
        fwrite($fp, " ---------------- 開始 ---------------- ".PHP_EOL);
        fwrite($fp, " Validate  >> ". $Validate .PHP_EOL);
        
        fclose($fp);
        if (0 == strcmp($_POST['Validate'], $Validate)) {
            
            CDbShell::Connect();
            if ($_POST['RtnCode'] == "1") {
                CDbShell::query("SELECT * FROM ordertobuy WHERE OrderNumber = '".$_POST['MerProductID']."' AND `State` = 1");	
                /*$_ErrorStr = CDbShell::GetErrorStr();
                $fp = fopen('../operate/Log/_LOG_'.date("YmdHis").'.txt', 'a');
                fwrite($fp, " ---------------- 開始 ---------------- ".PHP_EOL);
                fwrite($fp, " _ErrorStr  >> ". $_ErrorStr .PHP_EOL);
                fclose($fp);*/
                if (CDbShell::num_rows() == 1) {

                    $SRow = CDbShell::fetch_array();

                    CDbShell::query("SELECT GamePoints FROM memberfinance WHERE MemberId = '".$SRow['SellMemberId']."'");
                    $MRow = CDbShell::fetch_array();

                    try {
                        CDbShell::begin();
                        $PayBankCode = isset($_POST['PayBankCode']) ? ($_POST['PayBankCode']."-".$_POST['PayBankAccount']) : "";
                        $StoreName = isset($_POST['StoreName']) ? ($_POST['StoreName'].$_POST['StoreID']) : "" ;
                        CDbShell::query("UPDATE ordertobuy SET PayNumber = $PayBankCode, PayAddress = $StoreName, `State` = 2, PayDate ='".$_POST['PaymentDate']."', RtnCode = '".$_POST['RtnCode']."', RtnMessage = '".$_POST['RtnMessage']."' WHERE OrderNumber = '".$_POST['MerProductID']."'");
                        
                        // $field = array("PayDate","State","RtnCode","RtnMessage");
                        // $value = array($_POST['PaymentDate'],'2',$_POST['RtnCode'],$_POST['RtnMessage']);
                        // CDbShell::update("`ordertobuy`", $field, $value, "OrderNumber = ".$_POST['MerProductID']);

                        if (CDbShell::GetErrorNo() != 0) {
                            $_ErrorStr = CDbShell::GetErrorStr();
                            CDbShell::rollback();
                            CDbShell::DB_close();
                            
                            throw new exception("-97 系統錯誤 > ".$_ErrorStr);
                        }
                        
                        CDbShell::query("INSERT INTO pointchanglog (MemberId, BeforePoints, ChangePoints, AfterPoints, ChangeEvent, PointChangState, Note, CreateDate) 
                        VALUES (".$SRow["SellMemberId"].", ".$MRow["GamePoints"].", ".floatval($SRow['SumPrice']).", ".bcadd(floatval($MRow["GamePoints"]), floatval($SRow['SumPrice'])).", '2', '0', '訂購付款', CURRENT_TIMESTAMP(3) )");

                        // $field = array("MemberId","BeforePoints","ChangePoints","AfterPoints","ChangeEvent","PointChangState","Note","CreateDate");
                        // $value = array($SRow["MemberId"], $MRow["GamePoints"], floatval($SRow['Points']), bcadd(floatval($MRow["GamePoints"]),floatval($SRow['Points'])), 2, '0', '訂購付款', CURRENT_TIMESTAMP(3));
                        // CDbShell::insert("`pointchanglog`", $field, $value);
                        if (CDbShell::GetErrorNo() != 0) {
                            $_ErrorStr = CDbShell::GetErrorStr();
                            CDbShell::rollback();
                            CDbShell::DB_close();
                            
                            throw new exception("-98 系統錯誤 > ".$_ErrorStr);
                        }

                        CDbShell::query("UPDATE memberfinance SET GamePoints = GamePoints + ".floatval($SRow['SumPrice'])." WHERE MemberId = '".$SRow['SellMemberId']."'");
                        if (CDbShell::GetErrorNo() != 0) {
                            $_ErrorStr = CDbShell::GetErrorStr();
                            CDbShell::rollback();
                            CDbShell::DB_close();
                            
                            throw new exception("-99 系統錯誤 > ".$_ErrorStr);
                        }

                        CDbShell::commit();
                        CDbShell::DB_close();

                        echo "success";
                        exit;
                    }catch(Exception $e) {
                        $fp = fopen('../operate/Log/Fail_LOG_'.date("YmdHis").'.txt', 'a');
                        fwrite($fp, " ---------------- 開始 ---------------- ".PHP_EOL);
                        fwrite($fp,"Fail >> ".$e->getMessage() .PHP_EOL);
                        fclose($fp);
                        echo "fail";
                        exit;
                    }
                }else {
            
                    $fp = fopen('../operate/Log/Repeat_LOG_'.date("YmdHis").'.txt', 'a');
                    fwrite($fp, " ---------------- 開始 ---------------- ".PHP_EOL);
                    fwrite($fp,"SELECT * FROM ordertobuy WHERE OrderNumber = '".$_POST['MerProductID']."' AND Status = 0".PHP_EOL);
                    fclose($fp);
                }
            }else if(CDbShell::num_rows() == 5){
                $PaymentCode = isset($_POST['CodeNo']) ? $_POST['CodeNo'] : ($_POST['VatmBankCode']."-".$_POST['VatmAccount']);
                CDbShell::query("UPDATE ordertobuy SET PaymentCode = '".$PaymentCode."', ExpireTime = '".$_POST['ExpireTime']."', RtnCode = '".$_POST['RtnCode']."', RtnMessage = '".$_POST['RtnMessage']."' WHERE OrderNumber = '".$_POST['MerProductID']."'");
            }else{
                CDbShell::query("UPDATE ordertobuy SET RtnCode = '".$_POST['RtnCode']."', RtnMessage = '".$_POST['RtnMessage']."' WHERE OrderNumber = '".$_POST['MerProductID']."'");
                    
                echo "success";
                exit;
            }
        }else {
            $fp = fopen('../operate/Log/Imitated_LOG_'.date("YmdHis").'.txt', 'a');
            fwrite($fp, " ---------------- 開始 ---------------- ".PHP_EOL);
            fwrite($fp, " Validate              >> ".$Validate .PHP_EOL);
            fwrite($fp, " \$_POST['Validate'] >> ". $_POST['Validate'] .PHP_EOL);
            fclose($fp);
        }
    }

    // function strReturn() {
        
    //     include("../adpay.html");
    // }

    // function Order_sell() {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         try {
    //             CDbShell::Add_S($_POST);
    //             CDbShell::Add_S($_GET);
    //             CDbShell::Add_S($_SESSION);
    //             CDbShell::Add_S($_COOKIE);
    //             CDbShell::Connect();
                

    //             echo "window.location.href='Member/Center'";exit;
                
    //         }catch(Exception $e) {
    //             JSModule::ErrorJSMessage($e->getMessage());
    //         } 
    //     }
    //     include("../order_list_sell.html");
    // }

    


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

    // function SockPost($URL, $Query, &$curlerror){
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $URL);
    //     //curl_setopt($ch, CURLOPT_HEADER, false);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $Query);
    //     $SSL = (substr($URL, 0, 8) == "https://" ? true : false);
    //     if ($SSL) {
    //         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //     }
    //     $strReturn = curl_exec($ch);
    
    //     if(curl_errno($ch)){
    //         $curlerror = "Request Error(".curl_errno($ch)."):" . curl_error($ch) ;
    //     }else {
    //         $curlerror = "Request OK(".curl_errno($ch)."):" . curl_error($ch);
    //     }
    
    //     curl_close ($ch);
    
    //     return $strReturn;
    
    // }
	
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