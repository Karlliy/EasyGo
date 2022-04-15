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
        case "Members":
            Members();
            break;
        case "Member_Add":
            Member_Add();
            break;
        case "Members_ip":
            Members_ip();
            break;   
        case "Payment_record" ;
            Payment_record();
            break;
        case "Verification_code" ;
            Verification_code();
            break;
        case "Members_edit":
            Members_edit();
            break;
    }

    function Members() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                $_Condition = "";
                if($_POST["MemberId"] != "" && $_Condition == ""){
                    $_Condition = " WHERE m.MemberId = ".$_POST["MemberId"] ;
                }else if($_POST["MemberId"] != ""){
                    $_Condition .= " AND m.MemberId = ".$_POST["MemberId"] ;
                }
                if($_POST["MemberAccount"] != "" && $_Condition == ""){//echo $_POST["MemberAccount"];exit;
                    $_Condition = " WHERE m.MemberAccount like '%".$_POST["MemberAccount"]."%'" ;
                }else if($_POST["MemberAccount"] != ""){
                    $_Condition .= " AND m.MemberAccount like '%".$_POST["MemberAccount"]."%'" ;
                }
                if($_POST["Isonline"] != "" && $_Condition == ""){
                    $_Condition = " WHERE m.LockStatus = '".$_POST["Isonline"]."'" ;
                }else if($_POST["Isonline"] != ""){
                    $_Condition .= " AND m.LockStatus = '".$_POST["Isonline"]."'" ;
                }
                if($_POST["MemberState"] != "" && $_Condition == ""){
                    $_Condition = " WHERE m.State = '".$_POST["MemberState"]."'" ;
                }else if($_POST["MemberState"] != ""){
                    $_Condition .= " AND m.State = '".$_POST["MemberState"]."'" ;
                }
                if($_POST["MemberName"] != "" && $_Condition == ""){
                    $_Condition = " WHERE m.RealName like '%".$_POST["MemberName"]."%'" ;
                }else if($_POST["MemberName"] != ""){
                    $_Condition .= " AND m.RealName like '%".$_POST["MemberName"]."%'" ;
                }
                if($_POST["Cellphone"] != "" && $_Condition == ""){
                    $_Condition = " WHERE m.CellPhone = '".$_POST["Cellphone"]."'" ;
                }else if($_POST["Cellphone"] != ""){
                    $_Condition .= " AND m.CellPhone = '".$_POST["Cellphone"]."'";
                }
                if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" && $_Condition == ""){
                    $_Condition = " WHERE m.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'" ;
                }else if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" ){
                    $_Condition .= " AND m.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                }
                CDbShell::query("SELECT 
                    case when m.LockStatus = 1 then '上線' when m.LockStatus = 0 then '離線' else '離線' end as LockStatus,
                    case when m.State = 1 then '正常' when m.State = 2 then '黑名單' when m.State = 3 then '審核中' when m.State = 4 then '停權' else '正常' end as `State`,
                    m.MemberId,
                    m.MemberAccount,
                    case when m.IDVerify = 1 then '已通過' when m.IDVerify = 2 then '審核中' when m.IDVerify = 3 then '未通過' else '離線' end as IDVerify,
                    m.RealName,
                    mf.GamePoints,  -- 錢包餘額
                    pl1.ChangeEvent as Transfer,
                    pl1.ChangePoints as TransferMoney,
                    pl2.ChangeEvent as Tiquan,
                    pl2.ChangePoints as TiquanMoney,
                    CellPhone,
                    CreateDate
                    FROM member m
                    LEFT JOIN memberfinance mf ON mf.MemberId = m.MemberId
                    LEFT JOIN ( 
                        SELECT MemberId, count(ChangeEvent) as ChangeEvent, sum(ChangePoints) as ChangePoints 
                        FROM pointchanglog WHERE ChangeEvent = 1 GROUP BY MemberId
                        ) pl1 ON pl1.MemberId = m.MemberId
                    LEFT JOIN ( 
                        SELECT MemberId, count(ChangeEvent) as ChangeEvent, sum(ChangePoints) as ChangePoints 
                        FROM pointchanglog WHERE ChangeEvent = 5 GROUP BY MemberId
                        ) pl2 ON pl2.MemberId = m.MemberId ".$_Condition );
                if (CDbShell::num_rows() > 0) {
                    while ($Row = CDbShell::fetch_array()) {
                        switch ($Row['IDVerify']) {
                            case "未通過":
                                $text_color = "text_black";
                            break;
                            case "審核中":
                                $text_color = "text_orange";
                            break;
                            case "已通過":
                                $text_color = "text_blue";
                            break;
                        }
                        switch ($Row['State']) {
                            case "正常":
                                $text_color1 = "";
                            break;
                            case "黑名單":
                                $text_color1 = "text_red";
                            break;
                            case "審核中":
                                $text_color1 = "text_blue";
                            break;
                            case "停權":
                                $text_color1 = "text_pink";
                            break;
                        }
                        switch ($Row['LockStatus']) {
                            case "上線":
                                $text_color2 = "text_blue";
                            break;
                            case "離線":
                                $text_color2 = "";
                            break;
                        }
                        $TransferMoney = $Row['TransferMoney'] > 0 ? $Row['TransferMoney'] : number_format($Row['TransferMoney'] * -1, 2) ;  
                        $Layout .=
                            <<<EOF
                            <tr>
                                <td data-th="操作">
                                    <!--<input type="button" onclick="javascript:location.href='Member/Payment_record';" value="帳務" class="btn_small btn_yellow" data-value="{$Row["MemberId"]}">-->
                                    <input type="button" id="Member_edit" value="編輯" class="btn_small" data-value="{$Row["MemberId"]}">
                                    <input type="button" id="Member_del" value="刪除" class="btn_small btn_red" data-value="{$Row["MemberId"]}">
                                </td>
                                <td data-th="在線" class="{$text_color2}">{$Row["LockStatus"]}</td>
                                <td data-th="會員狀態" class="{$text_color1}">{$Row["State"]}</td>
                                <td data-th="會員編號">{$Row["MemberId"]}</td>
                                <td data-th="會員帳號">{$Row["MemberAccount"]}</td>
                                <td data-th="姓名(驗證)">
                                    <div>{$Row["RealName"]}</div>
                                    <div class="{$text_color}">({$Row['IDVerify']})</div>
                                </td>
                                <td data-th="錢包餘額" class="text_bold text_green">{$Row['GamePoints']}</td>
                                <td data-th="匯款次數">{$Row['Transfer']}</td>
                                <td data-th="總匯款" class="text_bold text_blue">{$TransferMoney}</td>
                                <td data-th="提款次數">{$Row['Tiquan']}</td>
                                <td data-th="總提款" class="text_bold text_red">{$Row['TiquanMoney']}</td>
                                <td data-th="手機">{$Row['CellPhone']}</td>
                                <td data-th="註冊日期">{$Row['CreateDate']}</td>
                            </tr>
EOF;
                    }
                }
                CDbShell::DB_close();
                echo $Layout;exit;
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }

        include("../members.html");
    }

    function Member_Add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT * FROM member WHERE MemberAccount = '".$_POST['MemberAccount']."'");				
                    
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
                    
                    $Id = CDbShell::insert_id();
                    
                    if (!empty($_FILES['IDPicUrl']) && $_FILES['IDPicUrl']['tmp_name'] != "") {

                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['IDPicUrl']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['IDPicUrl']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['IDPicUrl']['name']) == "png") {
                                $IDPicUrl = CommonElement::CopyImg($Id, $_FILES['IDPicUrl'], "../../快易購FrontSide(前台20211025)/IDimage/");
                                // echo $Id;exit;
                                $field = array("IDPicUrl");
                                $value = array($IDPicUrl);
                                CDbShell::update("member", $field, $value, " MemberId = ".$Id); 
                        }else {
                            throw new exception("照片格式不符合!");
                        }
                    }
                    $field = array("MemberAccount","RealName","Sex","CellPhone","IdNumber","IDIssuanceDateY","IDIssuanceDateM","IDIssuanceDateD","IDIssuanceplace","IDIssuance","Email","Address","State","PhoneVerify","IDVerify","CreateDate","RegisterIp","LastLoginIp","MemberLevel","MemberKind","SpreadCode");
                    $value = array($_POST["MemberAccount"],$_POST["RealName"],$_POST["Sex"],$_POST["CellPhone"],$_POST["IdNumber"],$_POST["IDIssuanceDateY"],$_POST["IDIssuanceDateM"],$_POST["IDIssuanceDateD"],$_POST["IDIssuanceplace"],$_POST["IDIssuance"],$_POST["Email"],$_POST["Address"],$_POST["State"],$_POST["PhoneVerify"],$_POST["IDVerify"],date('Y/m/d H:i:s'),$_IP,$_IP,"7","1",$SpreadCode);
                    CDbShell::insert("member", $field, $value, " MemberId = ".$Id);

                    $MemberPassword = substr($_POST["IdNumber"],5,9);
                    CDbShell::query("UPDATE member SET MemberPassword = MD5('".$MemberPassword."') where MemberId = ".$Id);

                    CDbShell::query("SELECT * FROM member WHERE MemberAccount = '".$_POST['MemberAccount']."'");	
                    $Row = CDbShell::fetch_array();
                    if (CDbShell::num_rows() == 1) {
                        $field = array("MemberId","GamePoints");
                        $value = array($Row["MemberId"],0);
                        CDbShell::insert("memberfinance", $field, $value);
                    }

                    echo "window.location.href='Member/Members'";exit;
                }else {
                    throw new exception("帳號己註冊!");
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            } 
        }
        include("../members_add.html");
    }

    function Members_ip() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                $_Condition = "";
                if($_POST["MemberId"] != "" && $_Condition == ""){
                    $_Condition = " WHERE MemberId = ".$_POST["MemberId"] ;
                }else if($_POST["MemberId"] != ""){
                    $_Condition .= " AND MemberId = ".$_POST["MemberId"] ;
                }
                if($_POST["MemberIP"] != "" && $_Condition == ""){
                    $_Condition = " WHERE IP like '%".$_POST["MemberIP"]."%'" ;
                }else if($_POST["MemberIP"] != ""){
                    $_Condition .= " AND IP like '%".$_POST["MemberIP"]."%'" ;
                }
                if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" && $_Condition == ""){
                    $_Condition = " WHERE CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'" ;
                }else if($_POST["Cellphone"] != ""){
                    $_Condition .= " AND CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                }
                CDbShell::query("SELECT MemberId, Ip, CreateDate FROM memberiphistory ".$_Condition." ORDER BY CreateDate desc LIMIT 0,10 ");
                if (CDbShell::num_rows() > 0) {
                    $r=1;
                    while ($Row = CDbShell::fetch_array()) {
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="項次">{$r}</td>
                            <td data-th="登入時間">{$Row["CreateDate"]}</td>
                            <td data-th="會員編號">{$Row["MemberId"]}</td>
                            <td data-th="IP位址">{$Row["Ip"]}</td>
                            
                        </tr>
EOF;
                        $r++;
                    }
                    CDbShell::DB_close();
                    echo $Layout;exit;
                }else{
                    $Layout=
                    <<<EOF
                    <tr>
                        <td colspan="5" data-th="" class="text_center">無資料內容</td>
                    </tr>
EOF;
                    echo $Layout;exit;
                }

            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            } 
        }

        include("../members_ip.html");
    }

    function Payment_record() {

        include("../payment_record.html");
    }

    function Verification_code() {

        include("../verification_code.html");
    }

    function Members_edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if("MembersEditList" == $_POST["fun"]){
                    CDbShell::query("SELECT 
                    m.MemberId,
                    MemberAccount,
                    RealName,
                    Sex,
                    Cellphone,
                    IdNumber,
                    IDPicUrl,
                    IDIssuanceDateY,
                    IDIssuanceDateM,
                    IDIssuanceDateD,
                    IDIssuanceplace,
                    IDIssuance,
                    Email,
                    Address,
                    State,
                    PhoneVerify,
                    IDVerify,
                    m.CreateDate,
                    RegisterIp,
                    LastLoginDate,
                    LastLoginIp,
                    Remark,
                    mf.GamePoints,
                    pl1.ChangeEvent as Transfer,
                    pl1.ChangePoints as TransferMoney,
                    pl2.ChangeEvent as Tiquan,
                    pl2.ChangePoints as TiquanMoney
                    FROM member m
                    LEFT JOIN memberfinance mf ON mf.MemberId = m.MemberId
                    LEFT JOIN ( SELECT MemberId, count(ChangeEvent) as ChangeEvent, sum(ChangePoints) as ChangePoints FROM pointchanglog WHERE ChangeEvent = 1 GROUP BY MemberId) pl1 ON pl1.MemberId = m.MemberId
                    LEFT JOIN ( SELECT MemberId, count(ChangeEvent) as ChangeEvent, sum(ChangePoints) as ChangePoints FROM pointchanglog WHERE ChangeEvent = 5 GROUP BY MemberId) pl2 ON pl2.MemberId = m.MemberId
                    WHERE m.MemberId = ".$_POST["MemberId"] );
                    $Row = CDbShell::fetch_array();
                    $data= array($Row["MemberId"],$Row["MemberAccount"],$Row["RealName"],$Row["Sex"],$Row["Cellphone"],$Row["IdNumber"],$Row["IDIssuanceDateY"]
                    ,$Row["IDIssuanceDateM"],$Row["IDIssuanceDateD"],$Row["IDIssuanceplace"],$Row["IDIssuance"],$Row["Email"],$Row["Address"],$Row["State"]
                    ,$Row["PhoneVerify"],$Row["IDVerify"],$Row["CreateDate"],$Row["RegisterIp"],$Row["LastLoginDate"],$Row["LastLoginIp"],$Row["Remark"],$Row["GamePoints"]
                    ,$Row["Transfer"],$Row["TransferMoney"],$Row["Tiquan"],$Row["TiquanMoney"],$Row["IDPicUrl"]);
                    echo json_encode($data);
                    exit;
                }
                if("MembersEdit" == $_POST["fun"]){
                    // echo $_POST["MemberId"];echo 999;echo $_POST["MemberAccount"];exit;
                    CDbShell::query("SELECT MemberId FROM member WHERE MemberId = ".$_POST["MemberId"]." AND MemberAccount = '".$_POST["MemberAccount"]."'");
                    $Row = CDbShell::fetch_array();
                    if (CDbShell::num_rows() == 1) {
                        $MemberId=$Row["MemberId"];
                        $field = array("RealName","Sex","CellPhone","IdNumber","IDIssuanceDateY","IDIssuanceDateM","IDIssuanceDateD","IDIssuanceplace","IDIssuance","Email","Address","State","PhoneVerify","IDVerify","Remark");
                        $value = array($_POST["RealName"],$_POST["Sex"],$_POST["CellPhone"],$_POST["IdNumber"],$_POST["IDIssuanceDateY"],$_POST["IDIssuanceDateM"],$_POST["IDIssuanceDateD"],$_POST["IDIssuanceplace"],$_POST["IDIssuance"],$_POST["Email"],$_POST["Address"],$_POST["State"],$_POST["PhoneVerify"],$_POST["IDVerify"],$_POST["Remark"]);
                        CDbShell::update("member", $field, $value, "MemberId = ".$MemberId );

                        if (!empty($_FILES['IDPicUrl']) && $_FILES['IDPicUrl']['tmp_name'] != "") {

                            if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['IDPicUrl']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['IDPicUrl']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['IDPicUrl']['name']) == "png") {
                                    $IDPicUrl = CommonElement::CopyImg($MemberId, $_FILES['IDPicUrl'], "../../快易購FrontSide(前台20211025)/IDimage/");
                                    // echo $IDPicUrl;exit;
                                    $field = array("IDPicUrl");
                                    $value = array($IDPicUrl);
                                    CDbShell::update("member", $field, $value, "MemberId = ".$MemberId); 
                            }else {
                                throw new exception("照片不符合!!!!");
                            }
                        }
                    }
                    echo "window.location.href='Member/Members'";exit;
                }
                if("MembersDel" == $_POST["fun"]){
                    // echo $_POST["MemberId"];echo $_POST["MemberAccount"];exit;
                    CDbShell::query("DELETE FROM member WHERE MemberId = ".$_POST["MemberId"] );
                    // $Row = CDbShell::fetch_array();
                    // if (CDbShell::num_rows() == 1) {
                    //     $MemberId=$Row["MemberId"];
                    //     $field = array("RealName","Sex","CellPhone","IdNumber","IDIssuanceDateY","IDIssuanceDateM","IDIssuanceDateD","IDIssuanceplace","IDIssuance","Email","Address","State","PhoneVerify","IDVerify","Remark");
                    //     $value = array($_POST["RealName"],$_POST["Sex"],$_POST["CellPhone"],$_POST["IdNumber"],$_POST["IDIssuanceDateY"],$_POST["IDIssuanceDateM"],$_POST["IDIssuanceDateD"],$_POST["IDIssuanceplace"],$_POST["IDIssuance"],$_POST["Email"],$_POST["Address"],$_POST["State"],$_POST["PhoneVerify"],$_POST["IDVerify"],$_POST["Remark"]);
                    //     CDbShell::update("member", $field, $value, "MemberId = ".$MemberId );
                    // }
                    echo "window.location.reload()";exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }    

        include("../members_edit.html");
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