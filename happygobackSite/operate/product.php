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
    include_once("./check_login.php");

    switch ($_SERVER["func"]) {
        case "Games_type":
            Games_type();
            break;
        case "Product_type":
            Product_type();
            break;   
        case "Currency" ;
            Currency();
            break;
        case "Product" ;
            Product();
            break;
        case "Games_info":
            Games_info();
            break;
        case "Product_add":
            Product_add();
            break;
        case "Product_edit":
            Product_edit();
            break;
    }

    function Games_type() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if("GamesTypeInsert" == $_POST['fun']){
                    // CDbShell::query("SELECT ProductId, ProductName FROM product ");
                    // $Rowo = CDbShell::fetch_array();
                    $field = array("ProductName");
                    $value = array($_POST["ProductName"]);
                    CDbShell::insert("product", $field, $value);

                    echo "window.location.reload()";
                    exit;
                }
                if("GamesTypeEdit" == $_POST['fun']){
                    CDbShell::query("SELECT ProductId, ProductName FROM product WHERE ProductId = '".$_POST["ProductId"]."' ");
                    $Row = CDbShell::fetch_array();
                    $field = array("ProductName");
                    $value = array($_POST["ProductName"]);
                    CDbShell::update("product", $field, $value, "ProductId = ".$_POST["ProductId"] );

                    echo "window.location.reload()";
                    exit;
                }
                if("GamesTypeDel" == $_POST['fun']){
                    CDbShell::query("DELETE FROM product WHERE ProductId = '".$_POST["ProductId"]."' ");

                    echo "window.location.reload()";
                    exit;
                }
                if("GamesTypeList" == $_POST['fun']){
                    CDbShell::query("SELECT ProductId, ProductName FROM product ");
                    if (CDbShell::num_rows() > 0) {
                        $r=1;
                        while ($Row = CDbShell::fetch_array()) {
                            $Layout .=
                            <<<EOF
                            <tr>
                                <td data-th="操作">
                                    <input type="button" id="item_edit" value="編輯" class="btn_small" data-value="{$Row["ProductId"]}">
                                    <input type="button" id="item_del" value="刪除" class="btn_small btn_red" data-value="{$Row["ProductId"]}">
                                </td>
                                <td data-th="項次">{$r}</td>
                                <td data-th="商品主分類">{$Row["ProductName"]}</td>
                            </tr>
EOF;
                            $r++;
                        }
                    }else{
                        $Layout=
                        <<<EOF
                        <tr>
                            <td colspan="3" data-th="" class="text_center">無資料內容</td>
                        </tr>
EOF;
                        
                    }
                    CDbShell::DB_close();
                    echo $Layout;
                    exit;
                }
                CDbShell::query("SELECT ProductId, ProductName FROM product WHERE ProductId = '".$_POST["val"]."' ORDER BY ProductId Asc");
                if (CDbShell::num_rows() > 0) {
                    $Row = CDbShell::fetch_array();
                    $data=array($Row['ProductName']);
                    echo json_encode($data);exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }

        include("../games_type.html");
    }

    function Product_type() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if("ProductTypeInsert" == $_POST['fun']){
                    $field = array("TypeName");
                    $value = array($_POST["TypeName"]);
                    CDbShell::insert("producttype", $field, $value);

                    echo "window.location.reload()";
                    exit;
                }
                if("ProductTypeEdit" == $_POST['fun']){
                    CDbShell::query("SELECT TypeId, TypeName FROM producttype WHERE TypeId = '".$_POST["TypeId"]."' ");
                    $Row = CDbShell::fetch_array();
                    $field = array("TypeName");
                    $value = array($_POST["TypeName"]);
                    CDbShell::update("producttype", $field, $value, "TypeId = ".$_POST["TypeId"] );

                    echo "window.location.reload()";
                    exit;
                }
                if("ProductTypeDel" == $_POST['fun']){
                    CDbShell::query("DELETE FROM producttype WHERE TypeId = '".$_POST["TypeId"]."' ");

                    echo "window.location.reload()";
                    exit;
                }
                if("ProductTypeList" == $_POST['fun']){
                    CDbShell::query("SELECT TypeId, TypeName FROM producttype ");
                    if (CDbShell::num_rows() > 0) {
                        $r=1;
                        while ($Row = CDbShell::fetch_array()) {
                            $Layout .=
                            <<<EOF
                            <tr>
                                <td data-th="操作">
                                    <input type="button" id="item_edit" value="編輯" class="btn_small" data-value="{$Row["TypeId"]}">
                                    <input type="button" id="item_del" value="刪除" class="btn_small btn_red" data-value="{$Row["TypeId"]}">
                                </td>
                                <td data-th="項次">{$r}</td>
                                <td data-th="商品主分類">{$Row["TypeName"]}</td>
                            </tr>
EOF;
                            $r++;
                        }
                    }else{
                        $Layout=
                        <<<EOF
                        <tr>
                            <td colspan="3" data-th="" class="text_center">無資料內容</td>
                        </tr>
EOF;
                    }
                    CDbShell::DB_close();
                    echo $Layout;
                    exit;
                }
                CDbShell::query("SELECT TypeId, TypeName FROM producttype WHERE TypeId = '".$_POST["val"]."' ORDER BY TypeId Asc");
                if (CDbShell::num_rows() > 0) {
                    $Row = CDbShell::fetch_array();
                    $data=array($Row['TypeName']);
                    echo json_encode($data);exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }

        include("../product_type.html");
    }

    function Currency() {

        include("../currency.html");
    }

    function Product() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                $_Condition = "";
                if($_POST["ProductNumber"] != "" && $_Condition == ""){
                    $_Condition = " WHERE o.ProductNumber like '%".$_POST["ProductNumber"]."%'" ;
                }else if($_POST["ProductNumber"] != ""){
                    $_Condition .= " AND o.ProductNumber like '%".$_POST["ProductNumber"]."%'" ;
                }
                if($_POST["ProductId"] != "" && $_Condition == ""){
                    $_Condition = " WHERE o.ProductId = ".$_POST["ProductId"] ;
                }else if($_POST["ProductId"] != ""){
                    $_Condition .= " AND o.ProductId = ".$_POST["ProductId"] ;
                }
                if($_POST["TypeId"] != "" && $_Condition == ""){
                    $_Condition = " WHERE o.TypeId = ".$_POST["TypeId"] ;
                }else if($_POST["TypeId"] != ""){
                    $_Condition .= " AND o.TypeId = ".$_POST["TypeId"] ;
                }
                if($_POST["MemberAccount"] != "" && $_Condition == ""){
                    $_Condition = " WHERE m.MemberAccount like '%".$_POST["MemberAccount"]."%'" ;
                }else if($_POST["MemberAccount"] != ""){
                    $_Condition .= " AND m.MemberAccount like '%".$_POST["MemberAccount"]."%'" ;
                }
                if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" && $_Condition == ""){
                    $_Condition = " WHERE o.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'" ;
                }else if($_POST["Cellphone"] != ""){
                    $_Condition .= " AND o.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                }
                CDbShell::query("SELECT p.ProductName, o.ProductNumber, pt.TypeName, o.ProductTitle, o.Price, o.KuTsuenQuantity,
                ml.LevelName, m.MemberAccount, o.CreateDate, ShelfState
                FROM `order` o
                LEFT JOIN member m ON m.MemberId = o.MemberId
                LEFT JOIN memberlevel ml ON ml.RowId = m.MemberLevel
                LEFT JOIN product p ON p.ProductId = o.ProductId
                LEFT JOIN producttype pt ON pt.TypeId = o.TypeId ".$_Condition);
                if (CDbShell::num_rows() > 0) {
                    $r = 1;
                    while ($Row = CDbShell::fetch_array()) {
                        $ShelfState = $Row['ShelfState'] == 1 ? 'checked' : '' ;
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="操作">
                                <!-- <input type="checkbox" name="" value="" class="check_box"> onclick="javascript:location.href='Product/Product_edit'"-->
                                <input type="button" id="item_edit" value="編輯" class="btn_small" data-value="{$Row["ProductNumber"]}" >
                                <input type="button" id="item_del" value="刪除" class="btn_small btn_red" data-value="{$Row["ProductNumber"]}">
                            </td>
                            <td data-th="項次">{$r}</td>
                            <!--滑動Switch開關-->
                            <td data-th="上架狀態">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="onoffswitch1" id="myonoffswitch1" class="onoffswitch-checkbox" {$ShelfState} disabled>
                                    <label for="myonoffswitch1" class="onoffswitch-label">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </td>
                            <td data-th="商品主分類">{$Row["ProductName"]}</td>
                            <td data-th="商品編號">{$Row["ProductNumber"]}</td>
                            <td data-th="商品類型">{$Row["TypeName"]}</td>
                            <td data-th="商品標題">
                                <div class="text_omit">{$Row["ProductTitle"]}</div>
                            </td>
                            <td data-th="單價" class="text_red">{$Row["Price"]}</td>
                            <td data-th="庫存">{$Row["KuTsuenQuantity"]}</td>
                            <td data-th="上架者階級/帳號">
                                <span>{$Row["LevelName"]}</span>
                                <a href="Member/Members_edit">{$Row["MemberAccount"]}</a>
                            </td>
                            <td data-th="上架日期">{$Row["CreateDate"]}</td>
                        </tr>
EOF;
                    $r++;
                    }
                }
                CDbShell::DB_close();
                echo $Layout;
                exit;
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../product.html");
    }

    function Games_info() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                
                if ('ProductId' == $_POST['fun']) {
                    $Row = CDbShell::fetch_row_field("SELECT ProductId, ProductName FROM product ");
                    echo json_encode($Row);
                    exit;
                }

                if ('TypeId' == $_POST['fun']) {
                    $Row = CDbShell::fetch_row_field("SELECT TypeId, TypeName FROM producttype ");
                    echo json_encode($Row);
                    exit;
                }

                if ('MemberLevel' == $_POST['fun']) {
                    $Row = CDbShell::fetch_row_field("SELECT RowId, LevelName FROM MemberLevel ");
                    echo json_encode($Row);
                    exit;
                }
                
                if ('CreatGame' == $_POST['data']) {
                // echo "alert('456".$_POST['GameType']."'');";
                // exit;
                    $UpGameId = (isset($_GET['UpGameId'])) ? $_GET['UpGameId'] : ((isset($_POST['UpGameId'])) ? $_POST['UpGameId'] : 0);
                    $IsOnline = (isset($_GET['IsOnline'])) ? $_GET['IsOnline'] : ((isset($_POST['IsOnline'])) ? $_POST['IsOnline'] : 1);
                    $field = array("UpGameId","GameName","ProductId","FileName","GameInfo","CreateDate","IsOnline");
                    $value = array($UpGameId,$_POST['GameName'],$_POST['ProductId'],$_POST['FileName'],$_POST['GameInfo'],date('Y/m/d H:i:s'),$IsOnline);
                    CDbShell::insert("`game`", $field, $value);
                    $Id = CDbShell::insert_id();

                    if (!empty($_FILES['GameImg']) && $_FILES['GameImg']['tmp_name'] != "") {

                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['GameImg']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['GameImg']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['GameImg']['name']) == "png") {
                                $GameImg = CommonElement::CopyImg($Id, $_FILES['GameImg'], "../picturedata/");
                                // echo $Id;exit;
                                $field = array("FileName");
                                $value = array($GameImg);
                                CDbShell::update("`game`", $field, $value, "`GameId` = ".$Id); 
                        }else {
                            throw new exception("照片不符合!");
                        }
                    // }else {
                    //     throw new exception("照片不符合!");
                    }
                    echo "window.location.href='Product/Games_info'";exit;
                }

                if ('ProductIdEdut' == $_POST['fun']) {
                    CDbShell::query("SELECT ProductId, GameId FROM game WHERE GameId = ".$_POST["GameId"]);
                    $Row = CDbShell::fetch_array();
                    $data= array($Row["ProductId"],$Row["GameId"]);
                    echo json_encode($data);
                    exit;
                }

                if ('GamesEdut' == $_POST['fun']) {
                    $field = array("ProductId");
                    $value = array($_POST["ProductId"]);
                    CDbShell::update("game", $field, $value, "GameId = ".$_POST["GameId"] );
                    echo "window.location.reload()";exit;
                    exit;
                }

                if ('GamesDel' == $_POST['fun']) {
                    CDbShell::query("DELETE FROM game WHERE GameId = '".$_POST["GameId"]."'");
                    echo "window.location.reload()";exit;
                    exit;
                }

                CDbShell::query("SELECT g.GameId, p.ProductName, g.GameName, g.`FileName`, g.GameInfo 
                FROM game g
                LEFT JOIN product p ON p.ProductId = g.ProductId 
                ORDER BY g.CreateDate DESC");
                
                if (CDbShell::num_rows() > 0) {
                    $r=1;
                    while ($Row = CDbShell::fetch_array()) {
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="操作">
                                <input type="button" id="EditGame" value="編輯" class="btn_small" data-value="{$Row['GameId']}">
                                <input type="button" id="DelGame" value="刪除" class="btn_small btn_red" data-value="{$Row['GameId']}">
                            </td>
                            <td data-th="項次">{$r}</td>
                            <td data-th="遊戲名稱">{$Row['GameName']}</td>
                            <td data-th="遊戲分類">{$Row['ProductName']}</td>
                            <td data-th="遊戲圖片"><img src="picturedata/{$Row['FileName']}" alt="遊戲" class="ga_pic"></td>
                            <td data-th="遊戲說明">{$Row['GameInfo']}</td>
                        </tr>
EOF;
                        $r++;
                    }
                    CDbShell::DB_close();
                    echo $Layout;
                    exit;
                }else{
                    $Layout=
                    <<<EOF
                    <tr>
                        <td colspan="5" data-th="" class="text_center">無資料內容</td>
                    </tr>
EOF;
                }
                echo $Layout;exit;
                
                
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            } 
        }
        include("../games_info.html");
    }

    function Product_add() {

        include("../product_add.html");
    }

    function Product_edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if("ProductList" == $_POST['fun']){
                    CDbShell::query("SELECT 
                    o.ShelfState,
                    o.ProductNumber,
                    o.CreateDate,
                    m.MemberLevel,
                    m.MemberAccount,
                    o.FileName,
                    o.ProductId,
                    o.TypeId,
                    o.PointCardKind,
                    o.ProductTitle,
                    o.GameName,
                    o.GamePlatform,
                    o.GameServer,
                    o.OrderQuantity,
                    o.CurrencyValue,
                    o.Currency,
                    o.Price,
                    o.KuTsuenQuantity,
                    o.ProductInfo,
                    o.FileInfo1,
                    o.FileInfo2,
                    o.Remark,
                    m.CreateDate as MemberCreate,
                    m.RegisterIp,
                    mh.LoginNum,
                    m.LastLoginDate,
                    m.LastLoginIp
                    FROM `order` o
                    LEFT JOIN member m ON m.MemberId = o.MemberId
                    LEFT JOIN (SELECT MemberId, count(MemberId) as LoginNum FROM memberiphistory GROUP BY MemberId) mh ON mh.MemberId = m.MemberId
                    WHERE o.ProductNumber = '".$_POST["ProductNumber"]."'" );
                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();
                        $data= array($Row["ShelfState"],$Row["ProductNumber"],$Row["CreateDate"],$Row["MemberLevel"],$Row["MemberAccount"],$Row["FileName"],$Row["ProductId"]
                        ,$Row["TypeId"],$Row["PointCardKind"],$Row["ProductTitle"],$Row["GameName"],$Row["GamePlatform"],$Row["GameServer"],$Row["OrderQuantity"],$Row["CurrencyValue"]
                        ,$Row["Currency"],$Row["Price"],$Row["KuTsuenQuantity"],$Row["ProductInfo"],$Row["FileInfo1"],$Row["FileInfo2"],$Row["Remark"],$Row["MemberCreate"],$Row["RegisterIp"],$Row["LoginNum"]
                        ,$Row["LastLoginDate"],$Row["LastLoginIp"]);
                        echo json_encode($data);
                        exit;
                    }
                }
                if("ProductFormEdit" == $_POST['data']){
                    CDbShell::query("SELECT m.MemberId, o.ProductNumber FROM `order` o LEFT JOIN member m ON m.MemberId = o.MemberId
                    WHERE o.ProductNumber = '".$_POST["ProductNumber"]."' AND m.Memberaccount = '".$_POST["MemberAccount"]."' ");
                    $Row = CDbShell::fetch_array();
// echo $_POST["ShelfState"];exit;
                    if (CDbShell::num_rows() > 0) {
                        $field = array("ShelfState","ProductId","TypeId","PointCardKind","ProductTitle","GameName","GamePlatform","GameServer","OrderQuantity","CurrencyValue","Currency","Price","KuTsuenQuantity","ProductInfo","Remark");
                        $value = array($_POST["ShelfState"],$_POST["ProductId"],$_POST["TypeId"],$_POST["PointCardKind"],$_POST["ProductTitle"],$_POST["GameName"],$_POST["GamePlatform"],$_POST["GameServer"],$_POST["OrderQuantity"],$_POST["CurrencyValue"],$_POST["Currency"],$_POST["Price"],$_POST["KuTsuenQuantity"],$_POST["ProductInfo"],$_POST["Remark"]);
                        CDbShell::update("`order`", $field, $value, "ProductNumber = '".$Row["ProductNumber"]."'");

                        $field = array("MemberLevel");
                        $value = array($_POST["MemberLevel"]);
                        CDbShell::update("member", $field, $value, "MemberId = '".$Row["MemberId"]."'");
                    }
                    
                    if (!empty($_FILES['FileName']) && $_FILES['FileName']['tmp_name'] != "") {
                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileName']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileName']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileName']['name']) == "png") {
                                $FileName = CommonElement::CopyImg($Id, $_FILES['FileName'], "../../快易購FrontSide(前台20211025)/picturedata/");
                                // echo $Id;exit;
                                $field = array("FileName");
                                $value = array($FileName);
                                CDbShell::update("`order`", $field, $value, "ProductNumber = '".$Row["ProductNumber"]."'"); 
                        }else {
                            throw new exception("照片不符合!");
                        }
                    }
                    if (!empty($_FILES['FileInfo1']) && $_FILES['FileInfo1']['tmp_name'] != "") {
                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo1']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo1']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo1']['name']) == "png") {
                                $FileInfo1 = CommonElement::CopyImg($Id, $_FILES['FileInfo1'], "../../快易購FrontSide(前台20211025)/Infoimg1/");
                                // echo $Id;exit;
                                $field = array("FileInfo1");
                                $value = array($FileInfo1);
                                CDbShell::update("`order`", $field, $value, "ProductNumber = '".$Row["ProductNumber"]."'"); 
                        }else {
                            throw new exception("照片不符合!");
                        }
                    }
                    if (!empty($_FILES['FileInfo2']) && $_FILES['FileInfo2']['tmp_name'] != "") {
                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo2']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo2']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['FileInfo2']['name']) == "png") {
                                $FileInfo2 = CommonElement::CopyImg($Id, $_FILES['FileInfo2'], "../../快易購FrontSide(前台20211025)/Infoimg2/");
                                // echo $Id;exit;
                                $field = array("FileInfo2");
                                $value = array($FileInfo2);
                                CDbShell::update("`order`", $field, $value, "ProductNumber = '".$Row["ProductNumber"]."'"); 
                        }else {
                            throw new exception("照片不符合!");
                        }
                    }
                    echo "window.location.reload()";exit;
                }

            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            } 
        }
        include("../product_edit.html");
    }

//     function Logout() {
//         CDbShell::Connect();
//         CDbShell::query("UPDATE member SET LockStatus = 0 WHERE MemberAccount='".CSession::GetVar("Account")."' AND MemberPassword = '".CSession::GetVar("Password")."'" );
//         CDbShell::DB_close();
//         CSession::ClearVar("Account");
// 		CSession::ClearVar("Password");
// 		$LogOut =
//             <<<EOF
//             <script>
//             window.location.href='../index.php';
//             </script>
// EOF;
//         echo $LogOut;
//         exit;
//         // include("../order_list_buy.html");
//     }



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