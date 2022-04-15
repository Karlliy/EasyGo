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
    include_once("./check_login.php");

    switch ($_SERVER["func"]) {
        case "Dashboard":
            Dashboard();
            break;
        case "Banner" ;
            Banner();
            break;
        case "Permission":
            Permission();
            break;
        case "Change_pw":
            Change_pw();
            break;
        case "Bank":
            Bank();
            break;
        case "Permission_edit":
            Permission_edit();
            break;
        case "Permission_add":
            Permission_add();
            break;
    }

    function Dashboard() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                //本日新增會員數
                CDbShell::query("SELECT count(MemberId) as NewDayMember FROM member 
                where 
                (
                substring(CreateDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end
                and
                substring(CreateDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                and
                substring(CreateDate,9,2) = case when DAY(NOW()) < 10 then concat('0',DAY(NOW())) else DAY(NOW()) end
                )");
                $RowDM = CDbShell::fetch_array();
                
                //本周新增會員數
                CDbShell::query("SELECT count(MemberId) as NewWeekMember from member 
                where CreateDate between DATE_SUB(NOW(),INTERVAL DAYOFWEEK(NOW())+1 day) and DATE_ADD(NOW(),INTERVAL 7-DAYOFWEEK(NOW()) day)");
                $RowWM = CDbShell::fetch_array();

                //本月新增會員數
                CDbShell::query("SELECT count(MemberId) as NewMounthMember FROM member 
                where 
                (
                substring(CreateDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end
                and
                substring(CreateDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                )");
                $RowMM = CDbShell::fetch_array();


                //本日訂單數
                CDbShell::query("SELECT count(OrderNumber) as NewDayOrder, CASE WHEN SUM(SumPrice) is null THEN 0 else SUM(SumPrice) end as SumPrice
                FROM ordertobuy
                where 
                (
                substring(CreateDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end
                and
                substring(CreateDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                and
                substring(CreateDate,9,2) = case when DAY(NOW()) < 10 then concat('0',DAY(NOW())) else DAY(NOW()) end
                )");
                $RowDO = CDbShell::fetch_array();
                    
                //本周訂單數
                CDbShell::query("SELECT count(OrderNumber) as NewWeekOrder from ordertobuy 
                where CreateDate between DATE_SUB(NOW(),INTERVAL DAYOFWEEK(NOW())+1 day) and DATE_ADD(NOW(),INTERVAL 7-DAYOFWEEK(NOW()) day)");
                $RowWO = CDbShell::fetch_array();

                //本月訂單數
                CDbShell::query("SELECT count(OrderNumber) as NewMounthOrder FROM ordertobuy 
                where 
                (
                substring(CreateDate,1,4) = case when YEAR(NOW()) < 10 then concat('0',YEAR(NOW())) else YEAR(NOW()) end
                and
                substring(CreateDate,6,2) = case when MONTH(NOW()) < 10 then concat('0',MONTH(NOW())) else MONTH(NOW()) end
                )");
                $RowMO = CDbShell::fetch_array();

                $data=array($RowDM["NewDayMember"],$RowWM["NewWeekMember"],$RowMM["NewMounthMember"],$RowDO["NewDayOrder"],$RowWO["NewWeekOrder"],$RowMO["NewMounthOrder"],$RowDO["SumPrice"]);
                CDbShell::DB_close();
                echo json_encode($data);exit;
                
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../dashboard.html");
    }

    function Banner() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();

                if ('DelBanner' == $_POST['fun']) {
                    CDbShell::query("DELETE FROM banner WHERE RowId = ".$_POST['val']);
                    
                    echo "window.location.reload()";exit;
                }

                if ('EditBanner' == $_POST['data']) {
                    $field = array("Id","BannerInfo","BannerDate");
                    $value = array($_POST['EditId'],$_POST['EditBannerInfo'],date('Y/m/d H:i:s'));
                    CDbShell::update("banner", $field, $value, "RowId = ".$_POST["RowId"]);
                    // echo $_POST["RowId"];exit;
                    // $RowId = CDbShell::insert_id();
                    if (!empty($_FILES['EditBannerUrl']) && $_FILES['EditBannerUrl']['tmp_name'] != "") {

                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['EditBannerUrl']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['EditBannerUrl']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['EditBannerUrl']['name']) == "png") {
                                $EditBannerUrl = CommonElement::CopyImg($_POST['RowId'], $_FILES['EditBannerUrl'], "../../快易購FrontSide(前台20211025)/images/cm/");
                                // echo $EditBannerUrl;exit;
                                $field = array("BannerUrl");
                                $value = array($EditBannerUrl);
                                CDbShell::update("banner", $field, $value, "RowId = ".$_POST["RowId"]); 
                        }else {
                            throw new exception("照片不符合!!!!");
                        }
                    }
                    echo "window.location.reload()";exit;
                }
                
                if ('CreateBanner' == $_POST['data']) {
                    $field = array("Id","BannerInfo","BannerDate");
                    $value = array($_POST['Id'],$_POST['BannerInfo'],date('Y/m/d H:i:s'));
                    CDbShell::insert("banner", $field, $value);
                    $RowId = CDbShell::insert_id();
                    if (!empty($_FILES['BannerUrl']) && $_FILES['BannerUrl']['tmp_name'] != "") {

                        if (preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['BannerUrl']['name']) == "jpg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['BannerUrl']['name']) == "jpeg" || preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['BannerUrl']['name']) == "png") {
                                $BannerUrl = CommonElement::CopyImg($_POST['RowId'], $_FILES['BannerUrl'], "../../快易購FrontSide(前台20211025)/images/cm/");
                                // echo $BannerUrl;exit;
                                $field = array("BannerUrl");
                                $value = array($BannerUrl);
                                CDbShell::update("banner", $field, $value, "RowId = ".$RowId); 
                        }else {
                            throw new exception("照片不符合!!!!");
                        }
                    }
                    echo "window.location.reload()";exit;
                }

                if ('EditBannerList' == $_POST['fun']) {
                    CDbShell::query("SELECT RowId, Id, BannerInfo, BannerUrl, BannerDate FROM banner WHERE RowId = ".$_POST['val']);
                    // echo "SELECT RowId, Id, BannerInfo, BannerUrl, BannerDate FROM banner  WHERE RowId = ".$val;exit;
                    $Row = CDbShell::fetch_array();
                    $data=array($Row['RowId'],$Row['Id'],$Row['BannerInfo'],$Row['BannerUrl']);
                    echo json_encode($data);exit;
                }

                CDbShell::query("SELECT RowId, Id, BannerInfo, BannerUrl, BannerDate FROM banner ".$Where." ORDER BY RowId ASC");
                
                if (CDbShell::num_rows() > 0) {
                    while ($Row = CDbShell::fetch_array()) {
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="操作">
                                <input type="button" id="item_edit" value="編輯" class="btn_small" data-value="{$Row['RowId']}">
                                <input type="button" id="item_del" value="刪除" class="btn_small btn_red" data-value="{$Row['RowId']}">
                            </td>
                            <td data-th="排序">{$Row["Id"]}</td>
                            <td data-th="說明">{$Row["BannerInfo"]}</td>
                            <td data-th="輪播圖檔(限800*400px，jpg格式)"><img src="../../快易購FrontSide(前台20211025)/images/cm/{$Row["BannerUrl"]}" alt="圖片" style="width:150px" class="ga_pic"></td>
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
                        <td colspan="4" data-th="" class="text_center">無資料內容</td>
                    </tr>
EOF;
                    echo $Layout;exit;
                }
                
                
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../banner.html");
    }

    function Permission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT RowId, IsOnline, AdminAccount, NickName, Privilege, LastLoginTime FROM administrator WHERE IsAdmin != '1' ");
                if (CDbShell::num_rows() > 0) {
                    while ($Row = CDbShell::fetch_array()) {
                        switch ($Row['IsOnline']) {
                            case "1":
                                $text_color = "text_green";
                                $IsOnline="上線";
                            break;
                            case "0":
                                $text_color = "text_red";
                                $IsOnline="離線";
                            break;
                        }
                        $Comma = "，";
                        $Privilege = explode("&",$Row['Privilege']);
                        
                        $PrivilegeBanner    = in_array("Home/Banner", $Privilege) == true ? "輪播圖管理" : "";
                        $PrivilegeProduct   = in_array("Product/Games_type", $Privilege) == true ? "商品主分類" : "";
                        $PrivilegeProduct   .= in_array("Product/Product", $Privilege) == true ? ($PrivilegeProduct != "" ? $Comma."上架商品管理" : "上架商品管理") : "";
                        $PrivilegeProduct   .= in_array("Product/Games_info", $Privilege) == true ? ($PrivilegeProduct != "" ? $Comma."遊戲管理" : "遊戲管理") : "";
                        $PrivilegeMember    = in_array("Member/Members", $Privilege) == true ? "會員列表" : "";
                        $PrivilegeMember    .= in_array("Member/Members_ip", $Privilege) == true ? ($PrivilegeMember != "" ? $Comma."登入IP紀錄" : "登入IP紀錄") : "";
                        $PrivilegeMember    .= in_array("Member/Payment_record", $Privilege) == true ? ($PrivilegeMember != "" ? $Comma."存提匯款紀錄" : "存提匯款紀錄") : "";
                        $PrivilegeMember    .= in_array("Member/Verification_code", $Privilege) == true ? ($PrivilegeMember != "" ? $Comma."手機認證碼" : "手機認證碼") : "";
                        $PrivilegeOrder     = in_array("Order/Order", $Privilege) == true ? "訂單總報表" : "";
                        $PrivilegeOrder     .= in_array("Order/Deposit", $Privilege) == true ? ($PrivilegeOrder != "" ? $Comma."存款管理" : "存款管理") : "";
                        $PrivilegeOrder     .= in_array("Order/Withdrawal", $Privilege) == true ? ($PrivilegeOrder != "" ? $Comma."提款管理" : "提款管理") : "";
                        $PrivilegeBank      = in_array("Home/Bank", $Privilege) == true ? "銀行存款審核" : "";
                        $PrivilegeMessage   = in_array("Message/News", $Privilege) == true ? "最新消息管理" : "";
                        $PrivilegeMessage   .= in_array("Message/MailSystem", $Privilege) == true ? ($PrivilegeMessage != "" ? $Comma."站內信管理" : "站內信管理") : "";
                        $PrivilegeMessage   .= in_array("Message/Complain", $Privilege) == true ? ($PrivilegeMessage != "" ? $Comma."申訴管理" : "申訴管理") : "";
                        $PrivilegeMessage   .= in_array("Message/QA", $Privilege) == true ? ($PrivilegeMessage != "" ? $Comma."問答管理" : "問答管理") : "";
                        $PrivilegePermission= in_array("Home/Permission", $Privilege) == true ? "權限設置" : "";
                        $PrivilegeChange_pw = in_array("Home/Change_pw", $Privilege) == true ? "變更密碼" : "";

                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="操作">
                                <input type="button" id="PermissionEdit" value="編輯" class="btn_small" data-value="{$Row["RowId"]}">
                                <input type="button" id="PermissionDel" value="刪除" class="btn_small btn_red" data-value="{$Row["RowId"]}">
                            </td>
                            <td data-th="狀態" class={$text_color}>{$IsOnline}</td>
                            <td data-th="管理員帳號">{$Row["AdminAccount"]}</td>
                            <td data-th="名稱">{$Row["NickName"]}</td>
                            <td data-th="管理權限">
                                <dl class="access">
                                    <dt>輪播圖管理：</dt>
                                    <dd>{$PrivilegeBanner}</dd>
                                </dl>
                                <dl class="access">
                                    <dt>商品管理：</dt>
                                    <dd>{$PrivilegeProduct}</dd>
                                </dl>
                                <dl class="access">
                                    <dt>會員管理：</dt>
                                    <dd>{$PrivilegeMember}</dd>
                                </dl>
                                <dl class="access">
                                    <dt>訂單管理：</dt>
                                    <dd>{$PrivilegeOrder}</dd>
                                </dl>
                                <dl class="access">
                                    <dt>銀行管理：</dt>
                                    <dd>{$PrivilegeBank}</dd>
                                </dl>
                                <dl class="access">
                                    <dt>訊息設置：</dt>
                                    <dd>{$PrivilegeMessage}</dd>
                                </dl>
                                <dl class="access">
                                    <dt>權限設置：</dt>
                                    <dd>{$PrivilegePermission}</dd>
                                </dl>
                            </td>
                            <td data-th="上次登入時間">{$Row["LastLoginTime"]}</td>
                        </tr>
EOF;
                    }
                    CDbShell::DB_close();
                    echo $Layout;
                    exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../permission.html");
    }

    function Change_pw() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['AdminPWOld']) == 0) {
                    throw new exception("舊密碼只能是英文數字組合長度6-15字!");
                }
                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['AdminPW']) == 0) {
                    throw new exception("新密碼只能是英文數字組合長度6-15字!");
                }
                if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['AdminPWCheck']) == 0) {
                    throw new exception("確認密碼只能是英文數字組合長度6-15字!");
                }
                if ($_POST['AdminPWCheck'] != $_POST['AdminPW']) {
                    throw new exception("確認密碼與新密碼不同!");
                }
                // echo $_POST['AdminPW'];
                // echo (CSession::GetVar("Account"));exit;
                CDbShell::query("SELECT * FROM administrator WHERE AdminAccount='".CSession::GetVar("Account")."'  AND AdminPW = '".MD5($_POST['AdminPWOld'])."' " );
                $Row = CDbShell::fetch_array();
                if(CDbShell::num_rows() > 0){
                    CDbShell::query("UPDATE administrator set AdminPW = MD5('".$_POST['AdminPW']."') where AdminAccount = '".$Row["AdminAccount"]."' ");
                    // $field = array("AdminPW");
                    // $value = array(MD5($_POST["AdminPW)"]));
                    // CDbShell::update("administrator", $field, $value, " AdminAccount = '".$Row["AdminAccount"]."' ");
                    echo "window.location.reload()";
                    exit;
                }else{
                    throw new exception("舊密碼輸入錯誤!");
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            }
        }
        include("../change_pw.html");
    }
 
    function Bank() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if ('ReCalculate' == $_POST['fun']) {
                    $field = array("WithdrawState","Auditors");
                    $value = array("0","");
                    CDbShell::update("withdrawapply", $field, $value, "`WithdrawNumber` = '".$_POST["WithdrawNumber"]."'");
                    echo "window.location.reload()";exit;
                }
                if ('Pass' == $_POST['fun']) {
                    $field = array("WithdrawState","Auditors");
                    $value = array("1",CSession::GetVar("admin_NickName"));
                    CDbShell::update("withdrawapply", $field, $value, "`WithdrawNumber` = '".$_POST["WithdrawNumber"]."'");
                    echo "window.location.reload()";exit;
                }
                if ('Reject' == $_POST['fun']) {
                    $field = array("WithdrawState","Auditors");
                    $value = array("2",CSession::GetVar("admin_NickName"));
                    CDbShell::update("withdrawapply", $field, $value, "`WithdrawNumber` = '".$_POST["WithdrawNumber"]."'");
                    echo "window.location.reload()";exit;
                }
                $_Condition = "";
                if($_POST["WithdrawNumber"] != "" && $_Condition == ""){
                    $_Condition = " WHERE w.WithdrawNumber like '%".$_POST["WithdrawNumber"]."%'" ;
                }else if($_POST["WithdrawNumber"] != ""){
                    $_Condition .= " AND w.WithdrawNumber like '%".$_POST["WithdrawNumber"]."%'" ;
                }
                if($_POST["MemberAccount"] != "" && $_Condition == ""){
                    $_Condition = " WHERE m.MemberAccount like '%".$_POST["MemberAccount"]."%'" ;
                }else if($_POST["MemberAccount"] != ""){
                    $_Condition .= " AND m.MemberAccount like '%".$_POST["MemberAccount"]."%'" ;
                }
                if($_POST["WithdrawState"] != "" && $_Condition == ""){
                    $_Condition = " WHERE w.WithdrawState = '".$_POST["WithdrawState"]."'" ;
                }else if($_POST["WithdrawState"] != ""){
                    $_Condition .= " AND w.WithdrawState = '".$_POST["WithdrawState"]."'" ;
                }
                if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" && $_Condition == ""){
                    $_Condition = " WHERE o.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'" ;
                }else if($_POST["Cellphone"] != ""){
                    $_Condition .= " AND o.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                }
                CDbShell::query("SELECT 
                    CASE WHEN w.WithdrawState = 1 THEN '通過' WHEN w.WithdrawState = 2 THEN '失敗' WHEN w.WithdrawState = 0 THEN '處理中' END as WithdrawState,
                    w.ApplyDate,
                    w.WithdrawNumber,
                    m.MemberAccount,
                    m.BankName,
                    m.BankAccount,
                    m.BankId,
                    m.BranchName,
                    w.TakeMoney,
                    w.Auditors,
                    CASE WHEN w.WithdrawState = 2 OR w.WithdrawState = 0 THEN '尚未入帳' WHEN w.WithdrawState = 1 THEN '已入帳' END as Remake
                    FROM withdrawapply w
                    LEFT JOIN member m ON m.MemberId = w.MemberId ".$_Condition);
                if (CDbShell::num_rows() > 0) {
                    $r=1;
                    while ($Row = CDbShell::fetch_array()) {
                        switch ($Row['WithdrawState']) {
                            case "通過":
                                $text_color = "text_green";
                            break;
                            case "處理中":
                                $text_color = "text_gray";
                            break;
                            case "失敗":
                                $text_color = "text_red";
                            break;
                        }
                        if($Row['WithdrawState'] == "處理中"){
                            $input = "<input type='button' id='Pass' value='通過' class='btn_small' data-value='{$Row["WithdrawNumber"]}'>
                                      <input type='button' id='Reject' value='拒絕' class='btn_small btn_gray' data-value='{$Row["WithdrawNumber"]}'>";
                        }else{
                            $input = "<input type='button' id='ReCalculate' value='重新結算' class='btn_small btn_yellow' data-value='{$Row["WithdrawNumber"]}'>";
                        }
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="操作">{$input}</td>
                            <td data-th="項次">{$r}</td>
                            <td data-th="狀態">
                                <span class={$text_color}>{$Row["WithdrawState"]}</span>
                            </td>
                            <td data-th="提交日期">{$Row["ApplyDate"]}</td>
                            <td data-th="提款編號">
                                <div>{$Row["WithdrawNumber"]}</div>
                                <!-- <input type="button" onclick="javascript:location.href='order_detail.html';" value="查看明細" class="btn_small"> -->
                            </td>
                            <td data-th="會員帳號">{$Row["MemberAccount"]}
                                <!-- <a href="members_edit.html"></a> -->
                            </td>
                            <td data-th="付款銀行">
                                <div>{$Row["BankName"]}</div>
                                <div>{$Row["BankAccount"]}</div>
                                <div>{$Row["BankId"]}</div>
                                <div>{$Row["BankName"]}{$Row["BranchName"]}</div>
                            </td>
                            <td data-th="單價" class="text_red">{$Row["TakeMoney"]}</td>
                            <td data-th="審核人員">{$Row["Auditors"]}
                                <!-- <a href="permission_edit.html"></a> -->
                            </td>
                            <td data-th="備註">{$Row["Remake"]}</td>
                        </tr>
EOF;
                        $r++;
                    }
                }
                CDbShell::DB_close();
                echo $Layout;exit;
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../bank.html");
    }

    function Permission_edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                // include_once 'Login/Checklogin';
                if("PermissionEditList" == $_POST["fun"]){
                    CDbShell::query("SELECT RowId, MemberLevel, AdminAccount, AdminPW, NickName, Privilege, LastLoginTime FROM administrator WHERE RowId = ".$_POST["PermissionRowId"]);
                    // if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();
                        $Privilege = explode("&",$Row['Privilege']);
                        $i = 0;

                        $data=array($Row['MemberLevel'],$Row['AdminAccount'],$Row['AdminPW'],$Row['NickName']);

                        while($i<count($Privilege)){
                            array_push($data,$Privilege[$i]);
                            $i ++; 
                        }
                        CDbShell::DB_close();
                        echo json_encode($data);exit;
                    // }    
                }   
                if("PermissionEdit" == $_POST["fun"]){
                    if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['AdminAccount']) == 0) {
                        throw new exception("帳號只能是英文數字組合長度6-15字!");
                    }
                    if (Trim($_POST["AdminAccount"]) == "") {
                        throw new exception("請輸入帳號!");
                    }
                    if (Trim($_POST["NickName"]) == "") {
                        throw new exception("請輸入名稱!");
                    }
                    if ($_POST["MemberLevel"] == "") {
                        throw new exception("請選擇階級!");
                    }
                    foreach($_POST["Permission"] as $key => $val){
                        $Permission .= $val.'&';
                    }//echo $Permission;exit;
                        
                    $field = array("AdminAccount","Privilege","NickName","MemberLevel");
                    $value = array($_POST["AdminAccount"],$Permission,$_POST["NickName"],$_POST["MemberLevel"]);
                    CDbShell::update("administrator", $field, $value, "RowId = ".$_POST["PermissionRowId"]);
                    echo "window.location.href='Home/Permission'";exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            }
        }
        include("../permission_edit.html");
    }

    function Permission_add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                // include_once 'Login/Checklogin';
                if("Permission_add" == $_POST["fun"]){
                    if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['AdminAccount']) == 0) {
                        throw new exception("帳號只能是英文數字組合長度6-15字!");
                    }
                    if (preg_match("/^[a-zA-Z0-9]{6,15}$/", $_POST['AdminPW']) == 0) {
                        throw new exception("密碼只能是英文數字組合長度6-15字!");
                    }
                    if (Trim($_POST["AdminAccount"]) == "") {
                        throw new exception("請輸入帳號!");
                    }
                    if (Trim($_POST["AdminPW"]) == "") {
                        throw new exception("請輸入密碼!");
                    }
                    if (Trim($_POST["NickName"]) == "") {
                        throw new exception("請輸入名稱!");
                    }
                    if ($_POST["MemberLevel"] == "") {
                        throw new exception("請選擇階級!");
                    }

                    foreach($_POST["Permission"] as $key => $val){
                        $Permission .= $val.'&';
                    }//echo $Permission;exit;
                        
                    $field = array("AdminAccount","AdminPW","Privilege","NickName","LoginCount","CreateDate","MemberLevel","IsAdmin","IsOnline");
                    $value = array($_POST["AdminAccount"],MD5($_POST["AdminPW"]),$Permission,$_POST["NickName"],0,date("Y-m-d H:i:s"),$_POST["MemberLevel"],0,0);
                    CDbShell::insert("administrator", $field, $value);
                    echo "window.location.href='Home/Permission'";exit;
                }

                if("Permission_del" == $_POST["fun"]){
                    CDbShell::query("DELETE FROM administrator WHERE RowId = ".$_POST["PermissionRowId"]);
                    echo "window.location.href='Home/Permission'";exit;
                }

                CDbShell::DB_close();
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            }
        }
        include("../permission_add.html");
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