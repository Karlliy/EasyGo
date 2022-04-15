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
        case "News":
            News();
            break;
        case "MailSystem" ;
            MailSystem();
            break;
        case "MailSystemEdit":
            MailSystemEdit();
            break;
        case "MailSystemAdd":
            MailSystemAdd();
            break;
        case "MailMember" ;
            MailMember();
        break;
        case "MailMemberEdit" ;
            MailMemberEdit();
        break;
        case "MailMemberAdd" ;
            MailMemberAdd();
        break;
        case "Complain":
            Complain();
            break;
        case "ComplainReply":
            ComplainReply();
            break;
        case "QA":
            QA();
            break;
        case "QAList":
            QAList();
            break;
        case "QADetail":
            QADetail();
            break;
    }

    function News() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();

                if('AddNews' == $_POST['fun']){
                    $field = array("Open_or_Close","ReleaseTime","Personnel","Detail");
                    $value = array("0",$_POST["ReleaseTime"],$_POST["AddPersonnel"],$_POST["Detail"]);
                    CDbShell::insert("news", $field, $value);
                    echo "window.location.href='Message/News'";exit;
                }

                if('EditNews' == $_POST['fun']){
                    $field = array("Open_or_Close","ReleaseTime","Personnel","Detail");
                    $value = array($_POST["Open_or_Close"],$_POST["ReleaseTime"],$_POST["Personnel"],$_POST["Detail"]);
                    CDbShell::update("news", $field, $value, "`Row` = ".$_POST["Row"]);
                    echo "window.location.href='Message/News'";exit;
                }

                if('DelNews' == $_POST['fun']){
                    // $field = array("ReleaseTime","Personnel","Detail");
                    // $value = array($_POST["ReleaseTime"],$_POST["Personnel"],$_POST["Detail"]);
                    // CDbShell::update("news", $field, $value, "`Row` = ".$_POST["Row"]);
                    CDbShell::query("DELETE FROM news WHERE `Row` = ".$_POST["Row"] );
                    echo "window.location.href='Message/News'";exit;
                }

                if('NewsList' == $_POST['fun']){
                    $_Condition = "";
                    if($_POST["Personnel"] != "" && $_Condition == ""){
                        $_Condition = " WHERE Personnel LIKE '%".$_POST["Personnel"]."%'" ;
                    }else if($_POST["Personnel"] != ""){
                        $_Condition .= " AND Personnel LIKE '%".$_POST["Personnel"]."%'" ;
                    }
                    if($_POST["Open_or_Close"] != "" && $_Condition == ""){
                        $_Condition = " WHERE Open_or_Close = ".$_POST["Open_or_Close"] ;
                    }else if($_POST["Open_or_Close"] != ""){
                        $_Condition .= " AND Open_or_Close = ".$_POST["Open_or_Close"] ;
                    }
                    if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" && $_Condition == ""){
                        $_Condition = " WHERE ReleaseTime BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'" ;
                    }else if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" ){
                        $_Condition .= " AND ReleaseTime BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                    }
                    CDbShell::query("SELECT `Row`, -- CASE WHEN Open_or_Close = 1 THEN '開' ELSE '關' end as Open_or_Close, 
                    Open_or_Close, ReleaseTime, Personnel, Detail FROM `news` ".$_Condition." ORDER BY ReleaseTime desc");
                    if (CDbShell::num_rows() > 0) {
                        $r = 1;
                        while ($Row = CDbShell::fetch_array()) {
                            $Open_or_Close = $Row['Open_or_Close'] == 1 ? 'checked' : '' ;
                            $Layout .=
                                <<<EOF
                                <tr>
                                    <td data-th="操作">
                                        <input type="button" id="item_edit" name="item_edit" value="編輯" class="btn_small" data-value="{$Row['Row']}">
                                        <input type="button" id="item_del"  name="item_del" value="刪除" class="btn_small btn_red" data-value="{$Row['Row']}">
                                    </td>
                                    <td data-th="項次">{$r}</td>
                                    <!--滑動Switch開關-->
                                    <!--<td data-th="顯示狀態">{$Row['Open_or_Close']}</td>-->
                                    <td data-th="顯示狀態">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="onoffswitch" id="myonoffswitch" class="onoffswitch-checkbox" {$Open_or_Close} disabled>
                                            <label for="myonoffswitch" class="onoffswitch-label">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td data-th="發布時間">{$Row['ReleaseTime']}</td>
                                    <td data-th="消息內容">
                                        <div class="text_omit">{$Row['Detail']}</div>
                                    </td>
                                    <td data-th="管理者帳號">
                                        <a href="permission_edit.html">{$Row['Personnel']}</a>
                                    </td>
                                </tr>
EOF;
                        $r++;
                        }
                    }else{
                        $Layout =
                            <<<EOF
                            <tr>
                                <tr>
                                    <td colspan="6" data-th="" class="text_center">無資料內容</td>
                                </tr>
                            </tr>
EOF;
                    }
                    CDbShell::DB_close();
                
                    echo $Layout;
                    exit;
                }
                CDbShell::query("SELECT `Row`, Open_or_Close, ReleaseTime, Personnel, Detail FROM `news` WHERE `Row` = ".$_POST['val']." ORDER BY ReleaseTime desc");
                if (CDbShell::num_rows() > 0) {
                    $Row = CDbShell::fetch_array();
                    $data=array($Row['Row'],$Row['Open_or_Close'],$Row['ReleaseTime'],$Row['Personnel'],$Row['Detail']);
                    echo json_encode($data);exit;
                }


                
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../news.html");
    }

    function MailSystem() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                $_Condition = "";
                if($_POST["SystemKeyWordSearch"]){
                    $_Condition .= " AND AnnouncementInfo like '%".$_POST["SystemKeyWordSearch"]."%' ";
                }
                if($_POST["SystemPersonnelSearch"]){
                    $_Condition .= " AND CreatePersonnel like '%".$_POST["SystemPersonnelSearch"]."%' ";
                }
                if($_POST["datepick1"] != "" && $_POST["datepick2"] != ""){
                    $_Condition .= " AND CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                }
                CDbShell::query("SELECT RowId, AnnouncementTitle, AnnouncementInfo, CreatePersonnel, CreateDate FROM announcement WHERE AnnouncementKind = '0' AND IsDelete = '0' ".$_Condition);
                if (CDbShell::num_rows() > 0) {
                    $r=1;
                    while ($Row = CDbShell::fetch_array()) {
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="操作">
                                <input type="button" id='MailSystemChk' value="查看" class="btn_small" data-value="{$Row["RowId"]}">
                                <input type="button" id="MailSystemDel" value="刪除" class="btn_small btn_red" data-value="{$Row["RowId"]}">
                            </td>
                            <td data-th="項次">{$r}</td>
                            <td data-th="發布時間">{$Row["CreateDate"]}</td>
                            <td data-th="發布人員">{$Row["CreatePersonnel"]}</td>
                            <td data-th="系統信標題">
                                <div class="text_omit">{$Row["AnnouncementTitle"]}</div>
                            </td>
                            <td data-th="系統信內容">
                                <div class="text_omit">{$Row["AnnouncementInfo"]}</div>
                            </td>
                        </tr>
EOF;
                        $r++;
                    }
//                 }else{
//                     $Layout .=
//                         <<<EOF
//                         <tr>
//                             <td colspan="6" data-th="" class="text_center">無資料內容</td>
//                         </tr>
// EOF;
                }
                CDbShell::DB_close();
                echo $Layout;
                exit;
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../mail_system.html");
    }

    function MailSystemEdit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if("MailSystemEdit" == $_POST['fun']){
                    CDbShell::query("SELECT RowId, AnnouncementTitle, AnnouncementInfo, CreatePersonnel, CreateDate 
                        FROM announcement 
                        WHERE AnnouncementKind = '0' and RowId =".$_POST["RowId"] );
                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();
                        $data= array($Row["CreateDate"],$Row["CreatePersonnel"],$Row["AnnouncementTitle"],$Row["AnnouncementInfo"]);
                        echo json_encode($data);exit;
                    }
                }
                if("MailSystemDel" == $_POST['fun']){
                    $field = array("IsDelete");
                    $value = array("1");
                    CDbShell::update("announcement", $field, $value, "RowId = ".$_POST["RowId"]);
                    echo "window.location.reload()";exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../mail_system_edit.html");
    }

    function MailSystemAdd() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if("MailSystemCreatePersonnel" == $_POST['fun']){
                    $data= array(CSession::GetVar("admin_NickName"));
                    echo json_encode($data);exit;
                }
                if($_POST['MailSystemTitle'] == ""){
                    throw new exception("請輸入標題!");
                }
                if($_POST['MailSystemInfo'] == ""){
                    throw new exception("請輸入內容!");
                }
                if($_POST['MailSystemTitle'] != "" && $_POST['MailSystemInfo'] != ""){

                    $field = array("AnnouncementTitle","AnnouncementInfo","CreatePersonnel","CreateDate","AnnouncementKind","IsDelete");
                    $value = array($_POST["MailSystemTitle"],$_POST["MailSystemInfo"],$_POST["MailSystemPersonnel"],$_POST["MailSystemTime"],"0","0");
                    CDbShell::insert("announcement", $field, $value);
                    $Id = CDbShell::insert_id();

                    CDbShell::query("SELECT RowId FROM announcement WHERE RowId = ".$Id );
                    // echo ("SELECT RowId FROM announcement WHERE RowId = ".$Id );exit;
                    $Rowa = CDbShell::fetch_array();

                    $R = CDbShell::query("SELECT MemberId FROM member");
                    if (CDbShell::num_rows($R) > 0) {
                        while ($Row = CDbShell::fetch_array($R)) {
                            $field = array("MemberId","AnnouncementRowId","CreateDate","AnnouncementState","IsDelete");
                            $value = array($Row["MemberId"],$Rowa["RowId"],date('Y/m/d H:i:s'),"0","0");
                            CDbShell::insert("mailsystemlog", $field, $value);
                        }
                    }
                    echo "window.location.href='Message/MailSystem'";exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            }
        }
        include("../mail_system_add.html");
    }

    function MailMember() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                $_Condition = "";
                if($_POST["MemberKeyWordSearch"]){
                    $_Condition .= " AND a.AnnouncementInfo like '%".$_POST["MemberKeyWordSearch"]."%' ";
                }
                if($_POST["MemberAccountSearch"]){
                    $_Condition .= " AND m.MemberAccount like '%".$_POST["MemberAccountSearch"]."%' ";
                }
                if($_POST["MemberPersonnelSearch"]){
                    $_Condition .= " AND a.CreatePersonnel like '%".$_POST["MemberPersonnelSearch"]."%' ";
                }
                if($_POST["AnnouncementState"]!=""){
                    $_Condition .= " AND a.AnnouncementState = '".$_POST["AnnouncementState"]."' ";
                }
                if($_POST["datepick1"] != "" && $_POST["datepick2"] != ""){
                    $_Condition .= " AND a.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                }
                CDbShell::query("SELECT a.RowId, a.MemberId, m.MemberAccount, a.AnnouncementTitle, a.AnnouncementInfo, a.AnnouncementState, a.CreatePersonnel, a.CreateDate 
                FROM announcement a
                LEFT JOIN member m ON m.MemberId = a.MemberId
                WHERE a.AnnouncementKind = '1' AND IsDelete = '0' ".$_Condition);
                if (CDbShell::num_rows() > 0) {
                    $r=1;
                    while ($Row = CDbShell::fetch_array()) {
                        switch ($Row['AnnouncementState']) {
                            case "0":
                                $text_color = "text_red";
                                $AnnouncementState ="未查看";
                            break;
                            case "1":
                                $text_color = "text_gray";
                                $AnnouncementState ="已查看";
                            break;
                        }
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="操作">
                                <input type="button" id='MailMemberChk' value="查看" class="btn_small" data-value="{$Row["RowId"]}">
                                <input type="button" id="MailMemberDel" value="刪除" class="btn_small btn_red" data-value="{$Row["RowId"]}">
                            </td>
                            <td data-th="項次">{$r}</td>
                            <td data-th="發布時間">{$Row["CreateDate"]}</td>
                            <td data-th="寄送會員帳號">
                                <a href="members_edit.html">{$Row["MemberAccount"]}</a>
                            </td>
                            <td data-th="發布人員">{$Row["CreatePersonnel"]}</td>
                            <td data-th="會員信標題">
                                <div class="text_omit">{$Row["AnnouncementTitle"]}</div>
                            </td>
                            <td data-th="會員信內容">
                                <div class="text_omit">{$Row["AnnouncementInfo"]}</div>
                            </td>
                            <td data-th="狀態" class="{$text_color}">{$AnnouncementState}</td>
                        </tr>
EOF;
                        $r++;
                    }
//                 }else{
//                     $Layout .=
//                         <<<EOF
//                         <tr>
//                             <td colspan="8" data-th="" class="text_center">無資料內容</td>
//                         </tr>
// EOF;
                }
                CDbShell::DB_close();
                echo $Layout;
                exit;
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../mail_members.html");
    }

    function MailMemberEdit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if("MailMemberEdit" == $_POST['fun']){
                    CDbShell::query("SELECT a.RowId, m.MemberAccount, a.AnnouncementTitle, a.AnnouncementInfo, a.CreatePersonnel, a.CreateDate 
                        FROM announcement a
                        LEFT JOIN member m ON m.MemberId = a.MemberId
                        WHERE a.AnnouncementKind = '1' and a.RowId =".$_POST["RowId"] );
                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();
                        $data= array($Row["CreateDate"],$Row["MemberAccount"],$Row["CreatePersonnel"],$Row["AnnouncementTitle"],$Row["AnnouncementInfo"]);
                        echo json_encode($data);exit;
                    }
                }
                if("MailMemberDel" == $_POST['fun']){
                    $field = array("IsDelete");
                    $value = array("1");
                    CDbShell::update("announcement", $field, $value, "RowId = ".$_POST["RowId"] );
                    echo "window.location.reload()";exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../mail_members_edit.html");
    }

    function MailMemberAdd() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if("MailMemberCreatePersonnel" == $_POST['fun']){
                    $data= array(CSession::GetVar("admin_NickName"));
                    echo json_encode($data);exit;
                }
                if($_POST['MailMemberMemberAccount'] == ""){
                    throw new exception("請輸入會員帳號!");
                }
                if($_POST['MailMemberTitle'] == ""){
                    throw new exception("請輸入標題!");
                }
                if($_POST['MailMemberInfo'] == ""){
                    throw new exception("請輸入內容!");
                }
                if($_POST['MailMemberMemberAccount'] != "" && $_POST['MailMemberTitle'] != "" && $_POST['MailMemberInfo'] != ""){
                    $MailMemberMemberAccount = explode(',', $_POST['MailMemberMemberAccount']);
                    foreach($MailMemberMemberAccount as $key => $value){
                        $MemberAccount = $value;
                        CDbShell::query("SELECT MemberId FROM  member WHERE MemberAccount = '".$MemberAccount."'");
                        $Row = CDbShell::fetch_array();
                        $field = array("MemberId","AnnouncementTitle","AnnouncementInfo","AnnouncementState","CreatePersonnel","CreateDate","AnnouncementKind","IsDelete");
                        $value = array($Row["MemberId"],$_POST["MailMemberTitle"],$_POST["MailMemberInfo"],"0",$_POST["MailMemberPersonnel"],$_POST["MailMemberTime"],"1","0");
                        CDbShell::insert("announcement", $field, $value);
                    }
                    echo "window.location.href='Message/MailMember'";exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            }
        }
        include("../mail_members_add.html");
    }

    function Complain() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                $_Condition = "";
                if($_POST["AppealEvent"] != "" && $_Condition == ""){
                    $_Condition = " WHERE a.AppealEvent = '".$_POST["AppealEvent"]."'" ;
                }else if($_POST["AppealEvent"] != ""){
                    $_Condition .= " AND a.AppealEvent = '".$_POST["AppealEvent"]."'" ;
                }
                if($_POST["AppealTitle"] != "" && $_Condition == ""){
                    $_Condition = " WHERE a.AppealTitle LIKE '%".$_POST["AppealTitle"]."%'" ;
                }else if($_POST["AppealTitle"] != ""){
                    $_Condition .= " AND a.AppealTitle LIKE '%".$_POST["AppealTitle"]."%'" ;
                }
                if($_POST["AppealMemberAccount"] != "" && $_Condition == ""){
                    $_Condition = " WHERE m.MemberAccount LIKE '%".$_POST["AppealMemberAccount"]."%'" ;
                }else if($_POST["AppealMemberAccount"] != ""){
                    $_Condition .= " AND m.MemberAccount LIKE '%".$_POST["AppealMemberAccount"]."%'" ;
                }
                if($_POST["AppealPersonnel"] != "" && $_Condition == ""){
                    $_Condition = " WHERE a.AppealPersonnel LIKE '%".$_POST["AppealPersonnel"]."%'" ;
                }else if($_POST["AppealPersonnel"] != ""){
                    $_Condition .= " AND a.AppealPersonnel LIKE '%".$_POST["AppealPersonnel"]."%'" ;
                }
                if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" && $_Condition == ""){
                    $_Condition = " WHERE a.AppealDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'" ;
                }else if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" ){
                    $_Condition .= " AND a.AppealDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                }
                CDbShell::query("SELECT 
                    a.RowId,
                    CASE WHEN a.AppealState = 0 THEN '處理中' WHEN a.AppealState = 1 THEN '已處理' WHEN a.AppealState = 2 THEN '待處理' WHEN a.AppealState = 3 THEN '已取消' end as AppealState,
                    ae.AppealName,
                    a.AppealTitle,
                    a.AppealDate,
                    m.MemberAccount,
                    a.AppealPersonnel
                    FROM appeal a
                    LEFT JOIN member m ON m.MemberId = a.AppealMemberId
                    LEFT JOIN appealevent ae ON a.AppealEvent = ae.RowId ".$_Condition);
                if (CDbShell::num_rows() > 0) {
                    $r=1;
                    while ($Row = CDbShell::fetch_array()) {
                        switch ($Row['AppealState']) {
                            case "處理中":
                                $text_color = "text_red";
                            break;
                            case "已處理":
                                $text_color = "text_orange";
                            break;
                            case "待處理":
                                $text_color = "text_green";
                            break;
                            case "已取消":
                                $text_color = "text_gray";
                            break;
                        }
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="操作">
                                <input type="button" id="ComplainChk" value="回覆" class="btn_small" data-value="{$Row["RowId"]}">
                                <input type="button" id="ComplainDel" value="刪除" class="btn_small btn_red" data-value="{$Row["RowId"]}">
                            </td>
                            <td data-th="項次">{$r}</td>
                            <td data-th="處理狀態" class="{$text_color}">{$Row["AppealState"]}</td>
                            <td data-th="申訴類別">{$Row["AppealName"]}</td>
                            <td data-th="申訴主題">
                                <div class="text_omit">{$Row["AppealTitle"]}</div>
                            </td>
                            <td data-th="申訴時間">{$Row["AppealDate"]}</td>
                            <td data-th="投訴會員">{$Row["MemberAccount"]}</td>
                            <td data-th="受理客服">{$Row["AppealPersonnel"]}</td>
                        </tr>
EOF;
                        $r++;
                    }
//                 }else{
//                     $Layout .=
//                         <<<EOF
//                         <tr>
//                             <td colspan="8" data-th="" class="text_center">無資料內容</td>
//                         </tr>
// EOF;
                }
                CDbShell::DB_close();
                echo $Layout;
                exit;
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            }
        }
        include("../complain.html");
    }

    function ComplainReply() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if("ComplainReplyList" == $_POST['fun']){
                    CDbShell::query("SELECT 
                        a.RowId,
                        a.AppealDate,
                        ae.AppealName,
                        m.MemberAccount,
                        ob.ProductNumber,
                        a.OrderNumber,
                        a.AppealObject,
                        a.AppealTitle,
                        a.AppealContent,
                        a.Appealfile,
                        a.ReplyDate,
                        a.ReplyTitle,
                        a.ReplyContent
                        FROM appeal a
                        LEFT JOIN member m ON m.MemberId = a.AppealMemberId
                        LEFT JOIN appealevent ae ON a.AppealEvent = ae.RowId
                        LEFT JOIN ordertobuy ob ON ob.OrderNumber = a.OrderNumber WHERE a.RowId=".$_POST["RowId"] );
                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();
                        $data= array($Row["RowId"],$Row["AppealDate"],$Row["AppealName"],$Row["MemberAccount"],$Row["ProductNumber"],$Row["OrderNumber"],$Row["AppealObject"]
                                    ,$Row["AppealTitle"],$Row["AppealContent"],$Row["Appealfile"],$Row["ReplyDate"],$Row["ReplyTitle"],$Row["ReplyContent"],CSession::GetVar("admin_NickName"));
                        echo json_encode($data);exit;
                    }
                }
                if("ComplainReply" == $_POST['fun']){
                    if($_POST['ReplyTitle'] == ""){
                        throw new exception("請輸入回覆主題!");
                    }
                    if($_POST['ReplyContent'] == ""){
                        throw new exception("請輸入回覆內容!");
                    }
                    $field = array("AppealPersonnel","ReplyTitle","ReplyContent","ReplyDate","AppealState");
                    $value = array($_POST["AppealPersonnel"],$_POST["ReplyTitle"],$_POST["ReplyContent"],$_POST["ComplainTime"],"1");
                    CDbShell::update("appeal", $field, $value, " RowId =".$_POST["RowId"]);
                    
                    echo "window.location.href='Message/Complain'";exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());exit;
            }
        }
        include("../complain_reply.html");
    }

    function QA() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                $_Condition = "";
                if($_POST["ProductNumber"] != ""){
                    $_Condition .= " AND qa.ProductNumber like '%".$_POST["ProductNumber"]."%'";
                }
                if($_POST["BuyMemberId"] != ""){
                    $_Condition .= " AND qa.BuyMemberId = '".$_POST["BuyMemberId"]."'";
                }
                if($_POST["datepick1"] != "" && $_POST["datepick2"] != ""){
                    $_Condition .= " AND qa.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                }
                if('QAFormList' == $_POST['fun']){
                    CDbShell::query("SELECT qa.ProductNumber,o.ProductTitle,o.MemberId,count(qa.QAStatus) as QAStatus
                    FROM quesandans qa
                    LEFT JOIN `order` o ON o.ProductNumber = qa.ProductNumber 
                    WHERE QAStatus = 1 " .$_Condition. " GROUP BY OrderNumber ORDER BY qa.CreateDate DESC ");
                    if (CDbShell::num_rows() > 0) {
                        $r = 1;
                        while ($Row = CDbShell::fetch_array()) {
                            $Layout .=
                                <<<EOF
                                <tr>
                                    <td data-th="操作">
                                        <input type="button" id="QAListBtn" onclick="javascript:location.href='Message/QAList'" value="查看" class="btn_small" data-value="{$Row["ProductNumber"]}">
                                    </td>
                                    <td data-th="項次">{$r}</td>
                                    <td data-th="商品編號">{$Row["ProductNumber"]}</td>
                                    <td data-th="商品標題">
                                        <div class="text_omit">{$Row["ProductTitle"]}</div>
                                    </td>
                                    <td data-th="賣家編號">
                                        <a href="members_edit.html">{$Row["MemberId"]}</a>
                                    </td>
                                    <td data-th="提問人數">{$Row["QAStatus"]}</td>
                                </tr>
EOF;
                        $r++;
                        }
//                     }else{
//                         $Layout =
//                             <<<EOF
//                             <tr>
//                                 <td colspan="7" data-th="" class="text_center">無資料內容</td>
//                             </tr>
// EOF;
                    }
                    CDbShell::DB_close();
                
                    echo $Layout;
                    exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../qa.html");
    }

    function QAList() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if('TopQAListDel' == $_POST['fun']){
                    CDbShell::query("DELETE FROM quesandans WHERE RowId ='".$_POST["RowId"]."' ");
                    echo "window.location.reload()";exit;
                }
                if('TopQAList' == $_POST['fun']){
                    CDbShell::query("SELECT MemberAccount, qa.ProductNumber, o.ProductTitle, count(qa.QAStatus) as QAStatus
                    FROM quesandans qa
                    LEFT JOIN `order` o ON o.ProductNumber = qa.ProductNumber 
                    LEFT JOIN member m ON m.MemberId = qa.SellMemberId
                    WHERE QAStatus = 1 and qa.ProductNumber ='".$_POST["QAProductNumber"]."' ");
                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();
                        $data=array($Row['MemberAccount'],$Row['ProductNumber'],$Row['ProductTitle'],$Row['QAStatus']);
                        echo json_encode($data);exit;
                    }
                }
                if('QAList' == $_POST['fun']){
                    CDbShell::query("SELECT qa.RowId, CASE WHEN QAStatus = 0 THEN '答' WHEN QAStatus = 1 THEN '問' end as QAStatus, MemberAccount, qa.ProductNumber, qa.QAInfo, qa.CreateDate
                    FROM quesandans qa
                    LEFT JOIN `order` o ON o.ProductNumber = qa.ProductNumber 
                    LEFT JOIN member m ON m.MemberId = qa.BuyMemberId
                    WHERE qa.ProductNumber = '".$_POST["QAProductNumber"]."'
                    ORDER BY qa.CreateDate DESC");
                    // echo $_POST["QAProductNumber"];exit;
                    if (CDbShell::num_rows() > 0) {
                        $r = 1;
                        while ($Row = CDbShell::fetch_array()) {
                            switch ($Row['QAStatus']) {
                                case "答":
                                    $text_color = "text_red";
                                break;
                                case "問":
                                    $text_color = "text_green";
                                break;
                            }
                            $MemberAccount = substr_replace($Row["MemberAccount"], '*****', 3, -2);
                            $Layout .=
                                <<<EOF
                                <tr>
                                    <td data-th="操作">
                                        <input type="button" id="QAListDetail" value="查看" class="btn_small" data-value="{$Row["RowId"]}">
                                        <input type="button" id="item_del" value="刪除" class="btn_small btn_red" data-value="{$Row["RowId"]}">
                                    </td>
                                    <td data-th="項次">{$r}</td>
                                    <td data-th="問與答">{$Row["QAStatus"]}</td>
                                    <td data-th="帳號">{$MemberAccount}</td>
                                    <td data-th="時間">{$Row["CreateDate"]}</td>
                                    <td data-th="問答">
                                        <div class="text_omit">{$Row["QAInfo"]}</div>
                                    </td>
                                </tr>
EOF;
                        $r++;
                        }
//                     }else{
//                         $Layout =
//                             <<<EOF
//                             <tr>
//                                 <td colspan="5" data-th="" class="text_center">無資料內容</td>
//                             </tr>
// EOF;
                    }
                    CDbShell::DB_close();

                    echo $Layout;
                    exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../qa_list.html");
    }

    function QADetail() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                CDbShell::query("SELECT QAStatus FROM quesandans WHERE RowId = ".$_POST["QARowId"]);
                $Row = CDbShell::fetch_array();
                if('TopQADetail' == $_POST['fun']){
                    CDbShell::query("SELECT 
                        q.ProductNumber,
                        o.ProductTitle,
                        pt.TypeName,
                        o.MemberId,
                        o.CreateDate
                        FROM quesandans q
                        LEFT JOIN `order` o ON o.ProductNumber = q.ProductNumber
                        LEFT JOIN producttype pt ON pt.TypeId = o.TypeId
                        WHERE q.RowId = ".$_POST["QARowId"]);
                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();
                        $data=array($Row['ProductNumber'],$Row['ProductTitle'],$Row['TypeName'],$Row['MemberId'],$Row['CreateDate']);
                        echo json_encode($data);exit;
                    }
                }
                if('QADetail' == $_POST['fun']){
                    if($Row["QAStatus"] == "1"){ //問
                        CDbShell::query("SELECT 
                        q.CreateDate,
                        m.MemberAccount,
                        q.QAInfo
                        FROM quesandans q
                        LEFT JOIN member m ON m.MemberId = q.BuyMemberId
                        WHERE q.RowId = ".$_POST["QARowId"]." and QAStatus = '".$Row["QAStatus"]."'" );
                        if (CDbShell::num_rows() > 0) {
                            while ($Row = CDbShell::fetch_array()) {
                                $Layout .=
                                <<<EOF
                                <tr>
                                    <th class="bg_gray_green">提問時間</th>
                                    <td data-th="提問時間" class="text_orange">{$Row["CreateDate"]}</td>
                                </tr>
                                <tr>
                                    <th class="bg_gray_green">提問人</th>
                                    <td data-th="提問人">{$Row["MemberAccount"]}</td>
                                </tr>
                                <tr>
                                    <th class="bg_gray_green">提問內容</th>
                                    <td data-th="提問內容">{$Row["QAInfo"]}</td>
                                </tr>
EOF;
                            }
                        }
                    }else if($Row["QAStatus"] == "0"){ //答
                        CDbShell::query("SELECT 
                        q.CreateDate,
                        m.MemberAccount,
                        q.QAInfo
                        FROM quesandans q
                        LEFT JOIN member m ON m.MemberId = q.SellMemberId
                        WHERE q.RowId = ".$_POST["QARowId"]." and QAStatus = '".$Row["QAStatus"]."'" );
                        if (CDbShell::num_rows() > 0) {
                            while ($Row = CDbShell::fetch_array()) {
                                $Layout .=
                                <<<EOF
                                <tr>
                                    <th class="bg_gray_red">回覆時間</th>
                                    <td data-th="回覆時間" class="text_blue">{$Row["CreateDate"]}</td>
                                </tr>
                                <tr>
                                    <th class="bg_gray_red">回覆</th>
                                    <td data-th="回覆">{$Row["QAInfo"]}</td>
                                </tr>
EOF;
                            }
                        }
                    }echo $Layout;exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            }
        }
        include("../qa_detail.html");
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