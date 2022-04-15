<?php

class Operate
{
    
    public static function Hall()
    {
        header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');

        if (!class_exists('CDbShell')) {
            include_once '../BaseClass/CDbShell.php';
        }
        if (!class_exists('CSession')) {
            include_once '../BaseClass/CSession.php';
        }

        if (!class_exists('JSModule')) {
            include_once '../BaseClass/JSModule.php';
        }
        
        $Error =
            <<<EOF
            <script type="text/javascript">
                alert('遊戲維護中!');
                window.location.href='../';
            </script>
EOF;

        /*if (CSession::getVar('Account') != "teppg001") {
           echo $Error;
        }*/

        if (!empty($_SERVER['QUERY_STRING'])) {
            $_QUERY = CommonElement::decrypt($_SERVER['QUERY_STRING']);
            //echo $_SERVER["QUERY_STRING"]."<br>";
            list($a, $_G, $c, $_p, $ReturnUrl) = explode('|', $_QUERY);
            //echo $_G;
        }
        $c = trim($c);
        $ReturnUrl = trim($ReturnUrl);
        $Log =
            <<<EOF
			<script type="text/javascript">
			console.log('a => {$a}');
			console.log('g => {$_G}');
			console.log('c => {$c}');
			</script>
EOF;
        //echo $Log;
        if ('API' == $c) {
            $Login = Operate::CheckAPILogin($a, $_p);
        } else {
            $Login = Operate::CheckLogin();
        }
        //echo $Login;
        if (strlen($Login) > 10) {

            if ($_POST['func'] == "GetPlayer") {
                Operate::GetPlayer();
                exit;
            }

            if ($_POST['func'] == "Chat") {
                Operate::Chat();
                exit;
            }

            if ($_POST['func'] == "AddFriend") {
                Operate::AddFriend();
                exit;
            }

            if ($_POST['func'] == "GetFriend") {
                Operate::GetFriend();
                exit;
            }
    
            if ($_POST['func'] == "GetInvite") {
                Operate::GetInvite();
                exit;
            }

            if ($_POST['func'] == "GetBlack") {
                Operate::GetBlack();
                exit;
            }

            if ($_POST['func'] == "AgreeFriend") {
                Operate::AgreeFriend();
                exit;
            }
            
            if ($_POST['func'] == "AddBlack") {
                Operate::AddBlack();
                exit;
            }

            if ($_POST['func'] == "CancelBlack") {
                Operate::CancelBlack();
                exit;
            }

            if ($_POST['func'] == "GetIsExist") {
                Operate::GetIsExist();
                exit;
            }
            
            if ($_POST['func'] == "GetGivePointsData") {
                Operate::GetGivePointsData();
                exit;
            }
            if ($_POST['func'] == "GetFeedbackPointsData") {
                Operate::GetFeedbackPointsData();
                exit;
            }

            if ($_POST['func'] == "FeedbackIntoPoints") {
                Operate::FeedbackIntoPoints();
                exit;
            }
            if ($_POST['func'] == "TransferPoints") {
                Operate::TransferPoints();
                exit;
            }
            if ($_POST['func'] == "Card") {
                Operate::CardPoints();
                exit;
            }
            if ($_POST['func'] == "Store") {
                Operate::StorePoints();
                exit;
            }
            if ($_POST['func'] == "StoreBuy") {
                Operate::StoreBuy();
                exit;
            }
            if ($_POST['func'] == "ActivityInto") {
                Operate::ActivityInto();
                exit;
            }
            if ($_POST['func'] == "ActivityInto2") {
                Operate::ActivityInto2();
                exit;
            }
            if ($_POST['func'] == "ActivityInto3") {
                Operate::ActivityInto3();
                exit;
            }
            if ($_POST['func'] == "ActivityInto4") {
                Operate::ActivityInto4();
                exit;
            }
            if ($_POST['func'] == "GiftPointRecord") {
                Operate::GiftPointRecord();
                exit;
            }

            if ('test' != CSession::getVar('Account')) {
                CDbShell::Connect();
                CDbShell::query("SELECT M.MemberId, M.MemberAccount, M.NickName, M.Level, M.SpreadCode, MF.GamePoints FROM member AS M INNER JOIN memberfinance AS MF ON MF.MemberId = M.MemberId WHERE M.MemberAccount='".CSession::getVar('Account')."' AND M.MemberPassword = '".CSession::getVar('Password')."'");
                $MRow = CDbShell::fetch_array();
            }

            $MemberAccount = $MRow['MemberAccount'];

            if ('API' != $c) {
                $ReturnUrl = 'http://www.allwin-online.com/Game';
            }
            ini_set('allow_url_fopen', true);
            ini_set('allow_url_include', true);

            
            $parameter = array(
                "Motion"			=> "Marquee",
                "MemberId"			=> $MRow['MemberId']
            );
            $Marqueedata = json_encode($parameter);

            $WSocket =
                    <<<EOF
                    <script type="text/javascript">
                        var Marqueedata = '{$Marqueedata}';
                    </script>
EOF;
                    echo $WSocket;
            $WSocket =
                    <<<EOF
                    <script type="text/javascript">
                        var socket;

                        //var host = "ws://198.13.39.20:5864/";
                        var host = "ws://127.0.0.1:5864/";
                        socket = new WebSocket(host);
                        console.log('WebSocket - status ' + socket.readyState);

                        //try {                            

                            socket.onopen = function (msg) {
                                socket.send('{$Marqueedata}');

                                //const sendNowTime = setInterval(()=>{
                                //    socket.send({$MRow['MemberId']})
                                //},5000)
                            
                                //console.log("{$MRow['MemberId']} Welcome - status " + this.readyState);
                            };
                            socket.onmessage = function (msg) {
                                console.log("Received: " + msg.data);
                                var jsonData = JSON.parse(msg.data);

                                if (jsonData[0] == "Marquee" ) {
                                    $('span[id="Notice"]').html(jsonData[1]);
                                }else if (jsonData[0] == "Chat" ) {
                                    $('div[class="content_set"]').html(jsonData[1]);
                                    $('div[class="content_set"]').scrollTop(100000);
                                }
                            };
                            socket.onclose = function (msg) {
                                //console.log("Disconnected - status " + this.readyState);
                            };
                        /*} catch (ex) {
                            console.log(ex);
                        }*/
                        
                    </script>
EOF;
                    //echo $WSocket;                    

            $GameServerSocket = '45.32.250.55';
            
            $H5Js =	<<<EOF
            <script type="text/javascript">
                var GameServerSocket = "45.32.250.55";
                var layout           = "H";
                var loginType        = "3";
                var accountName      = "{$MemberAccount}";
                var code             = "";
                var channelId        = "";
                var gameId           = "";
                var token            = "{$Login}";
                var lang             = "";
                var gameMaker        = "";
                var homeURL          = "http://www.allwin-online.com/Game";
            </script>
EOF;
					
            echo $H5Js;

            $CurrentlyPage =
                <<<EOF
                <script type="text/javascript">
                    var Currently = 'Game';
                </script>
EOF;
        
            if ($_G == "23") {
                $basehref = '<base href="./game23/web-mobile/" />';                
                echo $CurrentlyPage;
                include '../Game.html';		
            }else  if ($_G == "24") {
                $basehref = '<base href="./game24/web-mobile/" />';
                echo $CurrentlyPage;
                include '../Game.html';	
            }else  if ($_G == "32") {
                $basehref = '<base href="./game32/web-mobile/" />';
                echo $CurrentlyPage;
                include '../Game.html';	
            }else  if ($_G == "33") {
                $basehref = '<base href="./game33/web-mobile/" />';
                echo $CurrentlyPage;
                include '../Game.html';	
            }else  if ($_G == "35") {
                $basehref = '<base href="./game35/web-mobile/" />';
                echo $CurrentlyPage;
                include '../Game.html';	
            }else  if ($_G == "35") {
                $basehref = '<base href="./game35/web-mobile/" />';
                echo $CurrentlyPage;
                include '../Game.html';	
            }else  if ($_G == "83") {
                $basehref = '<base href="./game83/web-mobile/" />';
                echo $CurrentlyPage;
                include '../Game.html';	
            }else {
                //srand(microtime());
                $G23 = CommonElement::encrypt(str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT).'|23|'.str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT));
                $G24 = CommonElement::encrypt(str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT).'|24|'.str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT));
                $G32 = CommonElement::encrypt(str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT).'|32|'.str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT));
                $G33 = CommonElement::encrypt(str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT).'|33|'.str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT));
                $G34 = CommonElement::encrypt(str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT).'|34|'.str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT));
                $G35 = CommonElement::encrypt(str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT).'|35|'.str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT));
                $G83 = CommonElement::encrypt(str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT).'|83|'.str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT));

                if ('test' != CSession::getVar('mode')) {
                    
                }

                CDbShell::query("SELECT * FROM dailinews ORDER BY CreateDate DESC");
                while($Row = CDbShell::fetch_array()) {
                    $NewsList[] = $Row;
                }

                $CurrentlyPage =
                <<<EOF
                <script type="text/javascript">
                    var Currently = 'Lobby';
                </script>
EOF;
                echo $CurrentlyPage;
                include '../lobby.html';
            }

        }else {
            $Error =
            <<<EOF
            <script type="text/javascript">
                alert('請先登入會!');
                window.location.href='../';
            </script>
EOF;
            echo $Error;
        }
    }

    public static function DealNotify() {
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

        if (strcmp($_IPV4, "2813554502") == 0) {

            $Validate = MD5("ValidateKey=PDUR393T9DP&HashKey=MB9PV6EXQR53AKP8Y99YWQGHT7&RtnCode=".$_POST["RtnCode"]."&TradeID=".$_POST["MerTradeID"]."&UserID=".$_POST["MerUserID"]."&Money=".$_POST['Amount']);
        
            if (0 == strcmp($_POST['Validate'], $Validate)) {
                
                CDbShell::Connect();
                if ($_POST['RtnCode'] == "1") {
                    CDbShell::query("SELECT * FROM storeddata WHERE OrderID = '".$_POST['MerTradeID']."' AND Status = 0");	
                    /*$_ErrorStr = CDbShell::GetErrorStr();
                    $fp = fopen('../operate/Log/_LOG_'.date("YmdHis").'.txt', 'a');
                    fwrite($fp, " ---------------- 開始 ---------------- ".PHP_EOL);
                    fwrite($fp, " _ErrorStr  >> ". $_ErrorStr .PHP_EOL);
                    fclose($fp);*/
                    if (CDbShell::num_rows() == 1) {

                        $SRow = CDbShell::fetch_array();

                        CDbShell::query("SELECT GamePoints FROM memberfinance WHERE MemberId = '".$SRow['MemberId']."'");
                        $MRow = CDbShell::fetch_array();

                        try {
                            CDbShell::begin();

                            CDbShell::query("UPDATE storeddata SET Status = 1, PayAmount = '".$_POST['Amount']."', PaymentDate ='".$_POST['PaymentDate']."', RtnCode = '".$_POST['RtnCode']."', RtnMessage = '".$_POST['RtnMessage']."' WHERE OrderID = '".$_POST['MerTradeID']."'");
                            if (CDbShell::GetErrorNo() != 0) {
                                $_ErrorStr = CDbShell::GetErrorStr();
                                CDbShell::rollback();
                                CDbShell::DB_close();
                                
                                throw new exception("-97 系統錯誤 > ".$_ErrorStr);
                            }
                            
                            CDbShell::query("INSERT INTO dianshuyidongmingxi_game (MemberId, GameId, BeforePoints, ChangePoints, AfterPoints, ChangeEvent, ZongYuE, Note, CreateDate) 
                            VALUES (".$SRow["MemberId"].", 0, ".$MRow["GamePoints"].", ".floatval($SRow['Points']).",".bcadd(floatval($MRow["GamePoints"]), floatval($SRow['Points'])).",11,".bcadd(floatval($MRow["GamePoints"]), floatval($SRow['Points'])).", '', CURRENT_TIMESTAMP(3) )");

                            if (CDbShell::GetErrorNo() != 0) {
                                $_ErrorStr = CDbShell::GetErrorStr();
                                CDbShell::rollback();
                                CDbShell::DB_close();
                                
                                throw new exception("-98 系統錯誤 > ".$_ErrorStr);
                            }

                            CDbShell::query("UPDATE memberfinance SET GamePoints = GamePoints + ".floatval($SRow['Points'])." WHERE MemberId = '".$SRow['MemberId']."'");
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
                        fwrite($fp,"SELECT * FROM storeddata WHERE OrderID = '".$_POST['MerTradeID']."' AND Status = 0".PHP_EOL);
                        fclose($fp);
                    }
                }else{
                    CDbShell::query("UPDATE storeddata SET RtnCode = '".$_POST['RtnCode']."', RtnMessage = '".$_POST['RtnMessage']."' WHERE OrderID = '".$_POST['MerTradeID']."'");
                        
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
        }else {
            header("Location: http://www.yahoo.com.tw");
        }
    }

    public static function get_client_ip($type = 0,$adv=false) {
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

    public static function Chat() {
        $WSocket =
        <<<EOF
            var socket;

            var host = "ws://127.0.0.1:5864/";
            try {
                socket = new WebSocket(host);
                console.log('WebSocket - status ' + socket.readyState);

                socket.onopen = function (msg) {
                    socket.send("Chat");

                    const sendNowTime = setInterval(()=>{
                        socket.send("Chat")
                    },5000)
                
                    //
                    //console.log("{$MRow['MemberId']} Welcome - status " + this.readyState);
                };
                socket.onmessage = function (msg) {
                    //console.log("Received: " + msg.data);

                    //$('span[id="Notice"]').html(msg.data);
                };
                socket.onclose = function (msg) {
                    //console.log("Disconnected - status " + this.readyState);
                };
            } catch (ex) {
                console.log(ex);
            }
            
        </script>
EOF;
        echo $WSocket;
    }

    public static function GetPlayer() {
        try {

            CDbShell::Connect();
            CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            CDbShell::query("SELECT MemberId, NickName, Level, LockStatus, SpreadCode FROM member WHERE MemberId != '".$MRow['MemberId']."' AND MemberLevel = 7 AND PauseAccount = '0' ORDER BY LockStatus DESC, rand()");
            while ($Row = CDbShell::fetch_array()) {
                unset($_Status);
                switch ($Row['LockStatus']) {
                    case 1:
                    case 2:
                        $_Status = '<span style="font-size:12px; border-radius: 25px; padding: 5px; background: linear-gradient(0deg, rgba(195, 134, 0, 1)30%, rgba(246, 134, 49, 0.8)60%);
                        background: -moz-linear-gradient(0deg, rgba(195, 134, 0, 1)30%, rgba(246, 134, 49, 0.8)60%);
                        background: -webkit-linear-gradient(0deg, rgba(195, 134, 0, 1)30%, rgba(246, 134, 49, 0.8)60%);
                        background: -o-linear-gradient(0deg, rgba(195, 134, 0, 1)30%, rgba(246, 134, 49, 0.8)60%">上線中</span>';
                        break;
                    case 3:
                        $_Status = '<span style="font-size:12px; border-radius: 25px; padding: 5px; background: linear-gradient(0deg, rgba(255, 112, 199, 1)30%, rgba(235, 157, 207, 1)60%);
                        background: -moz-linear-gradient(0deg, rgba(255, 112, 199, 1)30%, rgba(235, 157, 207, 1)60%);
                        background: -webkit-linear-gradient(0deg, rgba(255, 112, 199, 1)30%, rgba(235, 157, 207, 1)60%);
                        background: -o-linear-gradient(0deg, rgba(255, 112, 199, 1)30%, rgba(235, 157, 207, 1)60%);">遊戲中</span>';
                        break;
                    default:
                        $_Status = '<span style="padding: 5px; background: linear-gradient(0deg, rgba(0, 0, 0, 0)0%, rgba(0, 0, 0, 0)0%);
                        background: -moz-linear-gradient(0deg, rgba(0, 0, 0, 0)0%, rgba(0, 0, 0, 0)0%);
                        background: -webkit-linear-gradient(0deg, rgba(0, 0, 0, 0)0%, rgba(0, 0, 0, 0)0%);
                        background: -o-linear-gradient(0deg, rgba(0, 0, 0, 0)0%, rgba(0, 0, 0, 0)0%);">&ensp;&ensp;&ensp;&ensp;</span>';
                    break;
                }
                $Layout .=
			    <<<EOF
                <div class="add_fd">
                    {$_Status}
                    <img src="images/lobby/mb_man01.png?2019100101" class="fd_head">
                    <div class="fd_name">{$Row['NickName']}</div>
                    <div class="fd_lv">LV.{$Row['Level']}</div>
                    <div class="fd_btn">
                        <button id="private" type="button" class="orangebtn_s" data-id="{$MRow['MemberId']}" data-aimid="{$Row['MemberId']}">私聊</button>
                        <button type="button" id="addfriend" class="orangebtn_s" data-id="{$Row['SpreadCode']}">加入</button>
                    </div>
                </div>
EOF;
            }

            CDbShell::DB_close();
    
            echo $Layout;
            exit;

        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }
    }
    public static function CardPoints() {
        try {
            if (strlen(trim($_POST["card"])) != 10) {
                throw new exception("請輸入正確儲值卡號!");
            }

            CDbShell::Connect();
            CDbShell::query("SELECT member.MemberId, member.MemberAccount, MF.GamePoints FROM member INNER JOIN memberfinance AS MF ON member.MemberId = MF.MemberId WHERE member.MemberAccount='".CSession::getVar('Account')."' AND member. MemberPassword = '".CSession::getVar('Password')."'");
            if (CDbShell::num_rows() != 1) {
                throw new exception("請先登入會員!");
            }
            $MRow = CDbShell::fetch_array();

            $Result = CDbShell::query("SELECT * FROM moneycarddetail WHERE BINARY CardNo = '".$_POST["card"]."'");
            if (CDbShell::num_rows($Result1) == 1) {
            $CRow = CDbShell::fetch_array();
                if ($CRow["CardStatus"] == "1") {
                    throw new exception("此儲值卡己使用過!");
                    exit;
                }
                if ($CRow["Expiration"] < date("Y-m-d")) {
                    throw new exception("此儲值卡己過期!");
                    exit;
                }

                CDbShell::begin();

                CDbShell::query("UPDATE moneycarddetail SET MemberId = ".$MRow['MemberId'].", UsedDate = CURRENT_TIMESTAMP(), CardStatus = 1 WHERE RowId = '".$CRow['RowId']."'");
                if (CDbShell::GetErrorNo() != 0) {
                    $_ErrorStr = CDbShell::GetErrorStr();
                    CDbShell::rollback();
                    CDbShell::DB_close();
                    
                    throw new exception("-99 系統錯誤 > ".$_ErrorStr);
                }

                $Note = "玩家[".$MRow["MemberAccount"]."] 使用[".$CRow["CardNo"]."] 儲值卡 儲值".$CRow['Points']."點";
                CDbShell::query("INSERT INTO dianshuyidongmingxi_game (MemberId, GameId, BeforePoints, ChangePoints, AfterPoints, ChangeEvent, ZongYuE, Note, CreateDate) 
				VALUES (".$MRow["MemberId"].", 0, ".$MRow["GamePoints"].", ".floatval($CRow['Points']).",".bcadd(floatval($MRow["GamePoints"]), floatval($CRow['Points'])).",19,".bcadd(floatval($MRow["GamePoints"]), floatval($CRow['Points'])).", '".$Note."', CURRENT_TIMESTAMP(3) )");

                if (CDbShell::GetErrorNo() != 0) {
                    CDbShell::rollback();
                    CDbShell::DB_close();
                    
                    throw new exception("-99 系統錯誤");
                }

                CDbShell::query("UPDATE memberfinance SET GamePoints = GamePoints + ".floatval($CRow['Points'])." WHERE MemberId = '".$MRow['MemberId']."'");
                if (CDbShell::GetErrorNo() != 0) {
                    $_ErrorStr = CDbShell::GetErrorStr();
                    CDbShell::rollback();
                    CDbShell::DB_close();
                    
                    throw new exception("-99 系統錯誤 > ".$_ErrorStr);
                }

                CDbShell::commit();
                CDbShell::DB_close();

                JSModule::Message("儲值成功", "./Game");;
                exit;

            }else {
                throw new exception("請輸入正確儲值卡號!");
            }

        }catch(Exception $e) {
            JSModule::Message($e->getMessage(), "");
            exit;
        }
    }
    public static function StorePoints() {
        try {
            if (!is_numeric($_POST['paycanal']) || $_POST['paycanal'] < 0 || $_POST['paycanal'] > 2) {
                throw new exception("請選擇付費方式!");
            }

            $parameter = array(	
                1	    => 100,
                2       => 300,
                3       => 500,
                4       => 1000,
                5       => 3000,
                6		=> 5000,
                7       => 10000
            );

            if (!in_array($_POST["payamount"], $parameter)) {
                throw new exception("請選擇儲值遊戲點數!");
            }

            switch($_POST['paycanal']) {
                case "0":
                    $PayCanal = '超商付款';
                    $PayURL = "https://fuze-pay.com/StorePayment.php";
                    break;
                case "1":
                    $PayCanal = '虛擬轉帳';
                    $PayURL = "https://fuze-pay.com/VirAccountPayment.php";
                    break;
                case "2":
                    $PayCanal = 'WebATM';
                    $PayURL = "https://fuze-pay.com/WebATMPayment.php";
                    break;
            }

            CDbShell::Connect();
            CDbShell::query("SELECT MemberId, SpreadCode FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            mt_srand();
	        $CashFlowID = "WO".substr(date("Y"), -1).Date("mdHis").str_pad(floor(microtime() * 100),4,'0',STR_PAD_LEFT).str_pad(mt_rand(0,9999),4,'0',STR_PAD_LEFT);
        

            switch ($_POST["payamount"]) {
                case 100:
                    $_Points = 10000;
                    break;
                case 300:
                    $_Points = 30000+1000;
                    break;
                case 500:
                    $_Points = 50000+2000;
                    break;
                case 1000:
                    $_Points = 100000+5000;
                    break;
                case 3000:
                    $_Points = 300000+10000;
                    break;
                case 5000:
                    $_Points = 500000+20000;
                    break;
                case 10000:
                    $_Points = 1000000+20000;
                    break;
            }
            $field = array("MemberId", "OrderID", "PayCanal", "Amount", "Points", "Status");
            $value = array($MRow["MemberId"], $CashFlowID, $PayCanal, intval($_POST["payamount"]), $_Points, "0");
            CDbShell::insert("storeddata", $field, $value);

            $parameter = array(
                "HashKey"				=> "AEGYTJJQXECRXTSJJ768UU4GL",
                "HashIV"				=> "SX9XHW4DFQNJGPBBP8KQ59LQR",
                "MerTradeID"			=> $CashFlowID,
                "MerProductID"			=> "WP".$_POST["payamount"],
                "MerUserID"				=> $MRow["SpreadCode"],
                "Amount"				=> intval($_POST["payamount"]),
                "TradeDesc"				=> "Win".$_POST["payamount"]."點",
                "ItemName"				=> "WinOnline"
            );


            $sHtml = "<form id='rongpaysubmit' name='rongpaysubmit' action='".$PayURL."' method='POST'>";
            while (list ($key, $val) = each ($parameter)) 
            {
                if ($key != 'returnUrl') $sHtml.= "<input type='hidden' id='".$key."' name='".$key."' value='".$val."'/>";
            }
            //$sHtml = $sHtml."<input type='hidden' name='Validate' value='".$Validate."'/>";
            //$sHtml = $sHtml."<input type='hidden' id='returnUrl' name='returnUrl' value='".$returnUrl."'/>";
            
            //submit按钮控件请不要含有name属性
            $sHtml = $sHtml."<input type='submit' value='付款' style='display:none'></form>";

            $sHtml = $sHtml."<script>document.forms['rongpaysubmit'].submit();</script>";
            
            echo $sHtml;
            exit;
        }catch(Exception $e) {
            JSModule::Message($e->getMessage(), "");
            exit;
        }
    }
    public static function StoreBuy() {
        try {
            if (!is_numeric($_POST['PaymentType']) || $_POST['PaymentType'] < 0 || $_POST['PaymentType'] > 2) {
                throw new exception("請選擇付費方式!");
            }

            switch($_POST['PaymentType']) {
                case "2":
                    $PayCanal = '虛擬轉帳';
                    $PayURL = "http://www.17adpay.com/VirAccountPayment.php";
                    break;
                case "3":
                    $PayCanal = '超商付款';
                    $PayURL = "http://www.17adpay.com/StorePayment.php";
                    break;
                case "0":
                    $PayCanal = 'WebATM';
                    $PayURL = "http://www.17adpay.com/WebATMPayment.php";
                    break;
            }

            CDbShell::Connect();
            CDbShell::query("SELECT MemberId, SpreadCode FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            mt_srand();
	        // $CashFlowID = "EG".substr(date("Y"), -1).Date("mdHis").str_pad(floor(microtime() * 100),4,'0',STR_PAD_LEFT).str_pad(mt_rand(0,9999),4,'0',STR_PAD_LEFT);
        
            // switch ($_POST["payamount"]) {
            //     case 100:
            //         $_Points = 10000;
            //         break;
            //     case 300:
            //         $_Points = 30000+1000;
            //         break;
            //     case 500:
            //         $_Points = 50000+2000;
            //         break;
            //     case 1000:
            //         $_Points = 100000+5000;
            //         break;
            //     case 3000:
            //         $_Points = 300000+10000;
            //         break;
            //     case 5000:
            //         $_Points = 500000+20000;
            //         break;
            //     case 10000:
            //         $_Points = 1000000+20000;
            //         break;
            // }
            $_Points = 0;

            $field = array("MemberId", "OrderID", "PayCanal", "Amount", "Points", "Status");
            $value = array($MRow["MemberId"], $_POST["OrderNumber"], $PayCanal, intval($_POST["SumPricePlusHand"]), $_Points, "0");
            CDbShell::insert("storeddata", $field, $value);

            $parameter = array(
                "HashKey"				=> "JNYYYWP8WXS95HP85F9HU9BJ5",
                "HashIV"				=> "3NWU7VKNRTHNG8FJ6ERRVYWF8X",
                "MerTradeID"			=> $_POST["OrderNumber"],
                "MerProductID"			=> $_POST["ProductNumber"],
                "MerUserID"				=> $MRow["SpreadCode"],
                "Amount"				=> intval($_POST["SumPricePlusHand"]),
                "TradeDesc"				=> "Pay".$_POST["SumPricePlusHand"]."元",
                "ItemName"				=> $_POST["ProductTitle"]
            );


            $sHtml = "<form id='rongpaysubmit' name='rongpaysubmit' action='".$PayURL."' method='POST'>";
            while (list ($key, $val) = each ($parameter)) 
            {
                if ($key != 'returnUrl') $sHtml.= "<input type='hidden' id='".$key."' name='".$key."' value='".$val."'/>";
            }
            //$sHtml = $sHtml."<input type='hidden' name='Validate' value='".$Validate."'/>";
            //$sHtml = $sHtml."<input type='hidden' id='returnUrl' name='returnUrl' value='".$returnUrl."'/>";
            
            //submit按钮控件请不要含有name属性
            $sHtml = $sHtml."<input type='submit' value='付款' style='display:none'></form>";

            $sHtml = $sHtml."<script>document.forms['rongpaysubmit'].submit();</script>";
            
            echo $sHtml;
            exit;
        }catch(Exception $e) {
            JSModule::Message($e->getMessage(), "");
            exit;
        }
    }
    public static function AddFriend() {
        try {
            CDbShell::Connect();
            CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            CDbShell::query("SELECT MemberId FROM member WHERE SpreadCode = '".$_POST['MemberID']."'");				
            $FRow = CDbShell::fetch_array();
            
            CDbShell::query("SELECT * FROM memberfriends WHERE (MemberId = '".$MRow['MemberId']."' AND FriendId = '".$FRow['MemberId']."') OR (MemberId = '".$FRow['MemberId']."' AND FriendId = '".$MRow['MemberId']."')");	
            if (CDbShell::num_rows() >= 1) {
                throw new exception("您們己經是好友!");
            }

            $sql = "INSERT INTO memberfriends (MemberId, FriendId, Status) VALUES ('".$MRow ['MemberId']."', '".$FRow['MemberId']."', 1)";
            CDbShell::query($sql);
        /*$Error =
            <<<EOF
            <script type="text/javascript">
                alert('己送出加入好友通佑!')
            </script>
EOF;*/
            JSModule::jBoxMessage('加入好友成功!');
            exit;
        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }
    }

    public static function GetFriend() {
        try {

            CDbShell::Connect();
            CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            $Result1 = CDbShell::query("SELECT M.*, MF.FriendId FROM memberfriends AS MF INNER JOIN member AS M ON IF (MF.MemberId='".$MRow['MemberId']."', M.MemberId = MF.FriendId, M.MemberId = MF.MemberId) WHERE (MF.MemberId='".$MRow['MemberId']."' OR MF.FriendId='".$MRow['MemberId']."') AND MF.Status = '1'");
            while ($Row = CDbShell::fetch_array($Result1)) {

                $Result2 = CDbShell::query("SELECT * FROM memberblack  WHERE MemberId ='".$MRow['MemberId']."' AND BlackId ='".$Row['MemberId']."'");
                if (CDbShell::num_rows($Result2) == 0) {

                    $Layout .=
                    <<<EOF
                    <div class="add_fd">
                        <img src="images/lobby/mb_man01.png?2019100101" class="fd_head">
                        <div class="fd_name">{$Row['NickName']}</div>
                        <div class="fd_lv">LV.{$Row['Level']}</div>
                        <div class="fd_btn">
                            <button id="private" type="button" class="orangebtn_s" data-id="{$MRow['MemberId']}" data-aimid="{$Row['MemberId']}">私聊</button>
                            <button id="fgivePoints" type="button" class="bluebtn_s" data-id="{$Row['SpreadCode']}">贈點</button>
                            <button id="addblack" type="button" style="width: auto;" class="blackbtn_s" data-id="{$Row['SpreadCode']}">黑名單</button>
                        </div>
                    </div>
EOF;
                }
            }

            CDbShell::DB_close();
    
            echo $Layout;
            exit;

        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }
    }

    public static function GetInvite() {
        try {
            CDbShell::Connect();
            CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            CDbShell::query("SELECT M.*, MF.FriendId FROM memberfriends AS MF INNER JOIN member AS M ON M.MemberId = MF.MemberId WHERE MF.FriendId='".$MRow['MemberId']."' AND MF.Status = '0'");
            while ($Row = CDbShell::fetch_array()) {
                $Layout .=
			    <<<EOF
                <div class="add_fd">
                    <img src="images/lobby/mb_man01.png?2019100101" class="fd_head">
                    <div class="fd_name">{$Row['NickName']}</div>
                    <div class="fd_lv">LV.{$Row['Level']}</div>
                    <div class="fd_btn">
                        <button id="agreefriend" class="orangebtn_s" type="button" data-id="{$Row['SpreadCode']}">同意</button>
                        <button id="refusefriend" class="redbtn_s" type="button" data-id="{$Row['SpreadCode']}">拒絕</button>
                    </div>
                </div>
EOF;
            }
            
            CDbShell::DB_close();
    
            echo $Layout;
            exit;

        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }
    }

    public static function GetBlack() {
        try {
            CDbShell::Connect();
            CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            CDbShell::query("SELECT M.NickName, M.SpreadCode, MB.BlackId FROM memberblack AS MB LEFT JOIN member AS M ON M.MemberId = MB.BlackId WHERE MB.MemberId='".$MRow['MemberId']."' ORDER BY MB.CreateDate DESC");
            while ($Row = CDbShell::fetch_array()) {
                $Layout .=
			    <<<EOF
                <div class="add_fd">
                    <img src="images/lobby/mb_man01.png?2019100101" class="fd_head">
                    <div class="fd_name">{$Row['NickName']}</div>
                    <div class="fd_btn">
                        <button id="cancelblack" class="redbtn_s" type="button" data-id="{$Row['SpreadCode']}">取消</button>
                    </div>
                </div>
EOF;
            }
            
            CDbShell::DB_close();
    
            echo $Layout;
            exit;

        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }
    }
    public static function CancelBlack() {
        try {
            CDbShell::Connect();
            CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            CDbShell::query("SELECT MemberId FROM member WHERE SpreadCode = '".$_POST['MemberID']."'");				
            $BlackRow = CDbShell::fetch_array();

            CDbShell::query("DELETE FROM memberblack WHERE MemberId = '".$MRow['MemberId']."' AND BlackId = '".$BlackRow['MemberId']."'");

            JSModule::jBoxMessage('黑名單取消成功!');
            exit;

        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }
    }
    public static function AgreeFriend() {
        try {
            CDbShell::Connect();
            CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            CDbShell::query("SELECT MemberId FROM member WHERE SpreadCode = '".$_POST['MemberID']."'");				
            $AgreeRow = CDbShell::fetch_array();
            
            CDbShell::query("UPDATE memberfriends SET Status = 1 WHERE MemberId = '".$AgreeRow['MemberId']."' AND FriendId = '".$MRow['MemberId']."'");	
            
            CDbShell::DB_close();

            JSModule::jBoxMessage('成功加入好友!');
            exit;
        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }

    }
    public static function AddBlack() {
        try {
            CDbShell::Connect();
            CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            CDbShell::query("SELECT MemberId FROM member WHERE SpreadCode = '".$_POST['MemberID']."'");				
            $BlackRow = CDbShell::fetch_array();
            
            CDbShell::query("INSERT INTO memberblack (MemberId, BlackId) VALUES ('".$MRow ['MemberId']."', '".$BlackRow['MemberId']."')");	
            
            CDbShell::DB_close();

            JSModule::jBoxMessage('黑名單成功!');
            exit;
        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }

    }
    public static function GetIsExist() {
        CDbShell::Connect();
        CDbShell::query("SELECT Nickname FROM member WHERE SpreadCode = '".$_POST['Account']."'");				
        $Row = CDbShell::fetch_array();

        if (CDbShell::num_rows() != 1) {
            $Layout[0] = "0";
        }else {
            $Layout[0] = "1";
            $Layout[1] = $Row['Nickname'];
        }

        CDbShell::DB_close();

        echo json_encode($Layout);
    }

    public static function GetGivePointsData() {
        CDbShell::Connect();
        CDbShell::query("SELECT M.SpreadCode, M.Level, M.AllowTransfer, MP.GamePoints FROM member AS M INNER JOIN memberfinance AS MP ON M.MemberId = MP.MemberId WHERE M.MemberAccount='".CSession::getVar('Account')."' AND M.MemberPassword = '".CSession::getVar('Password')."'");
        $MRow = CDbShell::fetch_array();

        if ($MRow['AllowTransfer'] == 1) {
            $Layout[0] = "1";
        }else {
            if ($MRow['Level'] < 50) $Layout[0] = "0";
            else $Layout[0] = "1";
        }

        $Layout[1] = $MRow['SpreadCode'];
        $Layout[2] = $MRow['GamePoints'];

        if (!empty($_POST['MemberId'])) {
            CDbShell::query("SELECT Nickname, SpreadCode FROM member WHERE SpreadCode = '".$_POST['MemberId']."'");				
            $Row = CDbShell::fetch_array();

            $Layout[3] = $Row['SpreadCode'];
            $Layout[4] = $Row['Nickname'];
        }

        CDbShell::DB_close();

        echo json_encode($Layout);

    }

    public static function GetFeedbackPointsData() {
        CDbShell::Connect();
        CDbShell::query("SELECT M.NickName, M.SpreadCode, M.Level, M.AllowTransfer, MP.FeedbackPoints FROM member AS M INNER JOIN memberfinance AS MP ON M.MemberId = MP.MemberId WHERE M.MemberAccount='".CSession::getVar('Account')."' AND M.MemberPassword = '".CSession::getVar('Password')."'");
        $MRow = CDbShell::fetch_array();

        if ($MRow['AllowTransfer'] == 1) {
            $Layout[0] = "1";
        }else {
            if ($MRow['Level'] < 50) $Layout[0] = "0";
            else $Layout[0] = "1";
        }

        $Layout[1] = $MRow['SpreadCode'];
        $Layout[2] = number_format($MRow['FeedbackPoints'], 2);
        $Layout[3] = $MRow['NickName'];
        $Layout[4] = $MRow['Level'];
        $Layout[5] = "http://www.allwin-online.com/?rid=".$MRow['SpreadCode']." <br/><img src='QRCode.php?data=http://www.allwin-online.com/?rid=".$MRow['SpreadCode']."' />";

        /*if (!empty($_POST['MemberId'])) {
            CDbShell::query("SELECT SpreadCode FROM member WHERE SpreadCode = '".$_POST['MemberId']."'");				
            $Row = CDbShell::fetch_array();

            $Layout[3] = $Row['SpreadCode'];
        }*/

        CDbShell::DB_close();

        echo json_encode($Layout);

    }

    public static function FeedbackIntoPoints() {
        try {

            CDbShell::Connect();
            CDbShell::query("SELECT M.MemberId, M.Level, M.LockStatus, MF.GamePoints, MF.FeedbackPoints FROM member AS M INNER JOIN memberfinance AS MF ON M.MemberId = MF.MemberId WHERE m.MemberAccount = '".CSession::getVar('Account')."' AND m.MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            /*if (intval($MRow['Level']) < 50) {
                throw new exception("會員最少LV.50!");
            
            }*/

            if (intval($MRow['FeedbackPoints']) < 10) {
                throw new exception("回饋最少10點才能轉入遊戲!");
            }

            if ($MRow['LockStatus'] == 3) {
                throw new exception("您在遊戲中無法贈點,請先離開遊戲回到大廳!");
            }

            /*if ($RecMRow['PauseAccount'] != 0) {
                throw new exception("接收贈點玩家被停權!");
            }

            if ($RecMRow['LockStatus'] == 3) {
                throw new exception("接收贈點玩家在遊戲中,請等玩家離開遊戲再進行贈點!");
            }*/
            CDbShell::begin();

            CDbShell::query("INSERT INTO dianshuyidongmingxi_game (MemberId, GameId, BeforePoints, ChangePoints, AfterPoints, ChangeEvent, ZongYuE, Note, CreateDate) 
				VALUES (".$MRow["MemberId"].", 0, ".$MRow["FeedbackPoints"].", ".(floatval($MRow["FeedbackPoints"]) * -1).",".bcsub(floatval($MRow["FeedbackPoints"]), floatval($MRow["FeedbackPoints"])).",14,".bcsub(floatval($MRow["FeedbackPoints"]), floatval($MRow["FeedbackPoints"])).", '', CURRENT_TIMESTAMP(3) )");

            if (CDbShell::GetErrorNo() != 0) {
                CDbShell::rollback();
                CDbShell::DB_close();
                
                throw new exception("-99 系統錯誤");
            }

            CDbShell::query("INSERT INTO dianshuyidongmingxi_game (MemberId, GameId, BeforePoints, ChangePoints, AfterPoints, ChangeEvent, ZongYuE, Note, CreateDate) 
				VALUES (".$MRow["MemberId"].", 0, ".$MRow["GamePoints"].", ".floatval($MRow["FeedbackPoints"]).",".bcadd(floatval($MRow["GamePoints"]), floatval($MRow["FeedbackPoints"])).",13,".bcadd(floatval($MRow["GamePoints"]), floatval($MRow["FeedbackPoints"])).", '', CURRENT_TIMESTAMP(3) )");

            if (CDbShell::GetErrorNo() != 0) {
                CDbShell::rollback();
                CDbShell::DB_close();
                
                throw new exception("-99 系統錯誤");
            }

            CDbShell::query("UPDATE memberfinance SET GamePoints = GamePoints + FeedbackPoints, FeedbackPoints = 0 WHERE MemberId = '".$MRow["MemberId"]."'");	
            if (CDbShell::GetErrorNo() != 0) {
                CDbShell::rollback();
                CDbShell::DB_close();
                
                throw new exception("-99 系統錯誤");
            }
            
            CDbShell::commit();
            CDbShell::DB_close();

            //JSModule::jBoxMessage('成功加入好友!');
            $js = 
            <<<EOF
            jQuery(document).ready(function () {
                new jBox('Notice', {
                    autoClose: 3000,
                    position: {
                        x: 'center',
                        y: 'center'
                    },
                    stack: true,
                    animation: {
                        open: 'tada',
                        close: 'zoomIn'
                    },
                    content: '成功轉成點數！',
                    color: 'blue',
                    onCloseComplete: function() {
                        window.location.reload();
                    }
                });
            });
EOF;
            echo $js;
            exit;
        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }

    }

    public static function TransferPoints() {
        try {

            if (strlen($_POST['MemberAccount']) <= 1) {
                throw new exception("請輸入對方ID!");
            }
            if (preg_match("/^[0-9]{1,}$/", $_POST['TransferPoints']) == 0) {
                throw new exception("請輸入贈點點數!");
            }

            if (intval($_POST['TransferPoints']) <= 0) {
                throw new exception("請輸入贈點點數!");
            }

            if (preg_match("/^[0-9]{1,}$/", $_POST['TransferPoints']) == 0) {
                throw new exception("請輸入正確贈點點數!");
            }

            CDbShell::Connect();
            CDbShell::query("SELECT M.MemberId, M.NickName, M.Level, M.LockStatus, M.SpreadCode, M.AllowTransfer, MF.GamePoints FROM member AS M INNER JOIN memberfinance AS MF ON M.MemberId = MF.MemberId WHERE m.MemberAccount = '".CSession::getVar('Account')."' AND m.MemberPassword = '".CSession::getVar('Password')."'");
            $MRow = CDbShell::fetch_array();

            if ($MRow['AllowTransfer'] == 0) {
                if (intval($MRow['Level']) < 50) {
                    throw new exception("會員最少LV.50!");
                
                }
            }

            if ($MRow['LockStatus'] == 3) {
                throw new exception("您在遊戲中無法贈點,請先離開遊戲回到大廳!");
            }

            if (floatval($MRow['GamePoints']) < bcadd(bcmul($_POST['TransferPoints'] , 1.03, 2), 50000, 2)) {
                throw new exception("您點數不足無法贈點,贈點最低餘額最少50000點!");
            }

            CDbShell::query("SELECT M.MemberId, M.NickName, M.Level, M.LockStatus, M.PauseAccount, M.SpreadCode, MF.GamePoints FROM member AS M INNER JOIN memberfinance AS MF ON M.MemberId = MF.MemberId WHERE m.SpreadCode = '".$_POST['MemberAccount']."'");
            if (CDbShell::num_rows() != 1) {
                throw new exception("接收贈點玩家不存在!");
            }
            $RecMRow = CDbShell::fetch_array();
            if ($MRow['AllowTransfer'] == 0) {
                if (intval($RecMRow['Level']) < 50) {
                    throw new exception("接收贈點玩家最少LV.50!");
                
                }
            }

            if ($RecMRow['PauseAccount'] != 0) {
                throw new exception("接收贈點玩家被停權!");
            }

            if ($RecMRow['LockStatus'] == 3) {
                throw new exception("接收贈點玩家在遊戲中,請等玩家離開遊戲再進行贈點!");
            }

            $_GiveNote = "贈送給玩家 ".$RecMRow["NickName"]."[".$RecMRow["SpreadCode"]."] ". number_format($_POST['TransferPoints'])."分";
            $_AcceptNote = "收到玩家 ".$MRow["NickName"]."[".$MRow["SpreadCode"]."] 贈送 ". number_format($_POST['TransferPoints'])."分";
            CDbShell::begin();

            CDbShell::query("INSERT INTO dianshuyidongmingxi_game (MemberId, GameId, BeforePoints, ChangePoints, AfterPoints, ChangeEvent, ZongYuE, Note, CreateDate) 
				VALUES (".$MRow["MemberId"].", 0, ".$MRow["GamePoints"].", ".(floatval($_POST['TransferPoints']) * -1).",".bcsub(floatval($MRow["GamePoints"]), floatval($_POST['TransferPoints']), 2).",8,".bcsub(floatval($MRow["GamePoints"]), floatval($_POST['TransferPoints']), 2).", '".$_GiveNote."', CURRENT_TIMESTAMP(3) )");

            if (CDbShell::GetErrorNo() != 0) {
                CDbShell::rollback();
                CDbShell::DB_close();
                
                throw new exception("-99 系統錯誤");
            }

            $_fee = bcmul(floatval($_POST['TransferPoints']) , 0.03, 2);

            CDbShell::query("INSERT INTO dianshuyidongmingxi_game (MemberId, GameId, BeforePoints, ChangePoints, AfterPoints, ChangeEvent, ZongYuE, Note, CreateDate) 
				VALUES (".$MRow["MemberId"].", 0, ".bcsub(floatval($MRow["GamePoints"]), floatval($_POST['TransferPoints']), 2).", ".($_fee * -1).",".(floatval($MRow["GamePoints"]) - floatval($_POST['TransferPoints']) - $_fee).",15,".(floatval($MRow["GamePoints"]) - floatval($_POST['TransferPoints']) - $_fee).", '', CURRENT_TIMESTAMP(3) )");

            if (CDbShell::GetErrorNo() != 0) {
                CDbShell::rollback();
                CDbShell::DB_close();
                
                throw new exception("-99 系統錯誤");
            }

            CDbShell::query("INSERT INTO dianshuyidongmingxi_game (MemberId, GameId, BeforePoints, ChangePoints, AfterPoints, ChangeEvent, ZongYuE, Note, CreateDate) 
				VALUES (".$RecMRow["MemberId"].", 0, ".$RecMRow["GamePoints"].", ".floatval($_POST['TransferPoints']).",".bcadd(floatval($RecMRow["GamePoints"]), floatval($_POST['TransferPoints']), 2).",9,".bcadd(floatval($RecMRow["GamePoints"]), floatval($_POST['TransferPoints']), 2).", '".$_AcceptNote."', CURRENT_TIMESTAMP(3) )");

            if (CDbShell::GetErrorNo() != 0) {
                CDbShell::rollback();
                CDbShell::DB_close();
                
                throw new exception("-99 系統錯誤");
            }

            CDbShell::query("UPDATE memberfinance SET GamePoints = GamePoints - ".bcmul($_POST['TransferPoints'] , 1.03, 2)." WHERE MemberId = '".$MRow["MemberId"]."'");	
            if (CDbShell::GetErrorNo() != 0) {
                CDbShell::rollback();
                CDbShell::DB_close();
                
                throw new exception("-99 系統錯誤");
            }

            CDbShell::query("UPDATE memberfinance SET GamePoints = GamePoints + ".$_POST['TransferPoints']." WHERE MemberId = '".$RecMRow["MemberId"]."'");	
            if (CDbShell::GetErrorNo() != 0) {
                CDbShell::rollback();
                CDbShell::DB_close();
                
                throw new exception("-99 系統錯誤");
            }
            
            CDbShell::commit();
            CDbShell::DB_close();

            //JSModule::jBoxMessage('成功加入好友!');
            $js = 
            <<<EOF
            jQuery(document).ready(function () {
                new jBox('Notice', {
                    autoClose: 3000,
                    position: {
                        x: 'center',
                        y: 'center'
                    },
                    stack: true,
                    animation: {
                        open: 'tada',
                        close: 'zoomIn'
                    },
                    content: '贈點成功 (贈點明細請至會員中心查詢)！',
                    color: 'blue',
                    onCloseComplete: function() {
                        window.location.reload();
                    }
                });
            });
EOF;
            echo $js;
            exit;
        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
            exit;
        }

    }

    public static function GiftPointRecord() {
        CDbShell::Connect();
        CDbShell::query("SELECT MemberId FROM member WHERE MemberAccount = '".CSession::getVar('Account')."' AND MemberPassword = '".CSession::getVar('Password')."'");
        $MRow = CDbShell::fetch_array();

        if ($_POST['tab'] == "gift") {
            $_term = "ChangeEvent = 8";
        }else {
            $_term = "ChangeEvent = 9";
        }

        CDbShell::query("SELECT ChangePoints, CreateDate, Note FROM dianshuyidongmingxi_game WHERE MemberId = '".$MRow['MemberId']."' AND ".$_term." ORDER BY CreateDate DESC ");
        while ($Row = CDbShell::fetch_array()) {
            
            if ($Row['ChangePoints'] <= 0) {
                $_BGColor = ' style="background-color: #fb9e9e;"';
            }else {
                $_BGColor = ' style="background-color: #4db6ff;"';
            }
            $Layout[0] .=
            <<<EOF
            <tr{$_BGColor}>
                <td>{$Row['CreateDate']}</td>
                <td style="
                text-align: left;">{$Row['Note']}</td>
            </div>
EOF;
            $i++;
        }
        
        CDbShell::DB_close();

        echo json_encode($Layout);
        exit;
    }

    public static function MemberOnline()
    {
        if (!class_exists('CDbShell')) {
            include_once '../BaseClass/CDbShell.php';
        }
        if (!class_exists('CSession')) {
            include_once '../BaseClass/CSession.php';
        }
        if (!empty(CSession::getVar('Account')) && !empty(CSession::getVar('Password'))) {
            if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $myip = $_SERVER['REMOTE_ADDR'];
            } else {
                $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $myip = $myip[2];
            }

            CDbShell::Connect();
            CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::getVar('Account')."' AND MemberPassword = '".CSession::getVar('Password')."'");
            if (1 == CDbShell::num_rows()) {
                $MRow = CDbShell::fetch_array();

                CDbShell::query("SELECT * FROM membersession WHERE MemberId = '".$MRow['MemberId']."'");
                if (1 == CDbShell::num_rows()) {
                    $field = array('Ip', 'SessionID', 'LastOnlineTime');
                    $value = array($myip, session_id(), date('Y-m-d H:i:s'));
                    CDbShell::update('membersession', $field, $value, "MemberId = '".$MRow['MemberId']."'");
                } else {
                    $field = array('MemberId', 'Ip', 'SessionID', 'LastOnlineTime');
                    $value = array($MRow['MemberId'], $myip, session_id(), date('Y-m-d H:i:s'));
                    CDbShell::insert('membersession', $field, $value);
                }

                echo 'MemberOnline1'.CSession::getVar('Account');
            }
        } else {
            echo 'MemberOnline2';
        }
    }

    /*public static function ActivityInto() {
        
        if ($_POST['type'] == 1) {
            $_type = "WL.Bet >= 100 AND WL.Bet <= 500";
        }elseif($_POST['type'] == 2) {
            $_type = "WL.Bet >= 600 AND WL.Bet <= 1500";
        }elseif($_POST['type'] == 3) {
            $_type = "WL.Bet >= 1560";
        }
        $i = 1;
        if (Date("Y-m-d H:i:s") > "2020-06-30 23:59:59") {
            $Layout[0] = "2020-06-30 23:59:59";
        }else {
            $Layout[0] = Date("Y-m-d H:i:s");
        }
        //$Layout[0] = "2020-05-01 00:00:00";
        CDbShell::Connect();
        //CDbShell::query("SELECT M.NickName, M.Level, MF.LevelBet, MF.NextLevelBet FROM memberfinance AS MF INNER JOIN member AS M ON M.MemberId = MF.MemberId WHERE AccumulateBet > 0 ORDER BY MF.AccumulateBet DESC LIMIT 100");
        CDbShell::query("SELECT COUNT(WL.RowId) AS Num, WL.MemberId, M.NickName FROM `memberwinlosedetail2` AS WL INNER JOIN member AS M ON M.MemberId = WL.MemberId WHERE ".$_type." AND WL.GameId != '35' AND WL.CreateDate BETWEEN '2020-06-05 12:00:00' AND '2020-06-30 23:59:59' GROUP BY WL.MemberId ORDER BY COUNT(WL.RowId) DESC LIMIT 50");
        while ($Row = CDbShell::fetch_array()) {
                
            $Layout[1] .=
			    <<<EOF
                <tr>
                    <td>{$i}</td>
                    <td>{$Row['NickName']}</td>
                    <td>{$Row['Num']}</td>
                </div>
EOF;
                $i++;
            }
            
            CDbShell::DB_close();
    
            echo json_encode($Layout);
            exit;
    }*/
    # 快閃活動排名
    public static function ActivityInto() {
        
        $i = 1;
        if (Date("Y-m-d H:i:s") > "2021-01-30 17:00:00") {
            $Layout[0] = "2021-01-30 17:00:00";
        }else {
            $Layout[0] = Date("Y-m-d H:i:s");
        }
        /*if ($_POST['range'] == 1) {
            $_type .= " AND MAX(WL.Bet) >= 200 AND MAX(WL.Bet) <= 1000 ";
            $_ShowNum = " (COUNT(g.MemberId) + M.ActAdded4) AS Num ";
            $_Sort = " (COUNT(g.MemberId) + M.ActAdded4) DESC ";
        }else {
            $_type .= " AND MAX(WL.Bet) >= 1080 ";
            $_ShowNum = " (COUNT(g.MemberId) + M.ActAdded5) AS Num ";
            $_Sort = " (COUNT(g.MemberId) + M.ActAdded5) DESC ";
        }*/
        CDbShell::Connect();
        //CDbShell::query("SELECT M.NickName, M.Level, MF.LevelBet, MF.NextLevelBet FROM memberfinance AS MF INNER JOIN member AS M ON M.MemberId = MF.MemberId WHERE AccumulateBet > 0 ORDER BY MF.AccumulateBet DESC LIMIT 100");
        /*CDbShell::query("SELECT
        SUM(t.WinLose) AS Num, 
        t.NickName 
        FROM
            (SELECT
                WL.MemberId,
				M.NickName,
                SUM(WL.WinLose) AS WinLose
            FROM
                memberwinlosedetail2 AS WL
				INNER JOIN member AS M ON WL.MemberId = M.MemberId 
            WHERE WL.GameId != '35' AND M.Contest ='1'
            GROUP BY
                WL.SequenceID HAVING 
                MIN(WL.CreateDate) BETWEEN '2020-09-24 16:00:00' AND '2020-09-26 17:00:00'
                AND SUM(Bet) >= 200 AND SUM(Bet) <= 1000 ) t         
        GROUP BY t.MemberId
        ORDER BY
            SUM(t.WinLose) DESC");*/
            CDbShell::query("SELECT
            SUM(t.WinLose) AS Num, 
            M.NickName 
            FROM
                (SELECT
                    WL.MemberId,
                    #M.NickName,
                    SUM(WL.WinLose) AS WinLose
                FROM
                    memberwinlosedetail2 AS WL
                    #INNER JOIN  AS M ON WL.MemberId = M.MemberId 
                WHERE WL.GameId != '35' 
                AND CreateDate BETWEEN '2021-01-30 15:50:00' AND '2021-01-30 17:10:00' 
                AND WL.MemberId IN (SELECT MemberId FROM member WHERE Contest ='1')
                GROUP BY
                    WL.SequenceID HAVING 
                    MIN(WL.CreateDate) BETWEEN '2021-01-30 16:00:00' AND '2021-01-30 17:00:00'
                    AND SUM(Bet) >= 200 AND SUM(Bet) <= 1000 ) t INNER JOIN member AS M ON t.MemberId = M.MemberId          
            GROUP BY t.MemberId
            ORDER BY
                SUM(t.WinLose) DESC");
        while ($Row = CDbShell::fetch_array()) {
            $Row['Num'] = number_format($Row['Num']);
            $Layout[1] .=
			    <<<EOF
                <tr>
                    <td>{$i}</td>
                    <td>{$Row['NickName']}</td>
                    <td>{$Row['Num']}</td>
                </div>
EOF;
                $i++;
        }
        
        CDbShell::DB_close();

        echo json_encode($Layout);
        exit;
    }
    # 1月份活動排名.
    public static function ActivityInto2() {
        
        /*switch ($_POST['type']) {
            case "1":
                $_type = " MAX(CreateDate) BETWEEN '2020-12-01 00:00:00' AND '2020-12-08 23:59:59'";
                $_where = " AND CreateDate BETWEEN '2020-12-01 00:00:00' AND '2020-12-09 01:10:00'";
                if (Date("Y-m-d H:i:s") > "2020-12-08 23:59:59") {
                    $Layout[0] = "2020-12-08 23:59:59";
                }else {
                    $Layout[0] = Date("Y-m-d H:i:s");
                }
                break;
            case "2":
                $_type = " MAX(CreateDate) BETWEEN '2020-10-08 12:00:00' AND '2020-10-15 02:00:00'";
                $_where = " AND CreateDate BETWEEN '2020-10-08 11:00:00' AND '2020-10-15 03:00:00'";
                if (Date("Y-m-d H:i:s") > "2020-10-15 02:00:00") {
                    $Layout[0] = "2020-10-15 02:00:00";
                }else {
                    $Layout[0] = Date("Y-m-d H:i:s");
                }
                break;
            case "3":
                $_type = " MAX(CreateDate) BETWEEN '2020-10-15 12:00:00' AND '2020-10-22 02:00:00'";
                $_where = " AND CreateDate BETWEEN '2020-10-15 11:00:00' AND '2020-10-22 03:00:00'";
                if (Date("Y-m-d H:i:s") > "2020-10-22 02:00:00") {
                    $Layout[0] = "2020-10-22 02:00:00";
                }else {
                    $Layout[0] = Date("Y-m-d H:i:s");
                }
                break;
            case "4":
                $_type = " MAX(CreateDate) BETWEEN '2020-10-22 12:00:00' AND '2020-10-29 02:00:00'";
                $_where = " AND CreateDate BETWEEN '2020-10-22 11:00:00' AND '2020-10-29 03:00:00'";
                if (Date("Y-m-d H:i:s") > "2020-10-29 02:00:00") {
                    $Layout[0] = "2020-10-29 02:00:00";
                }else {
                    $Layout[0] = Date("Y-m-d H:i:s");
                }
                break;
        }*/

        $_type = " (";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-01 20:00:00' AND '2021-01-04 02:00:00' OR ";

        $_type .= "MAX(CreateDate) BETWEEN '2021-01-04 20:00:00' AND '2021-01-05 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-05 20:00:00' AND '2021-01-06 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-06 20:00:00' AND '2021-01-07 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-07 20:00:00' AND '2021-01-08 02:00:00' OR ";

        $_type .= "MAX(CreateDate) BETWEEN '2021-01-08 20:00:00' AND '2021-01-11 02:00:00' OR ";

        $_type .= "MAX(CreateDate) BETWEEN '2021-01-11 20:00:00' AND '2021-01-12 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-12 20:00:00' AND '2021-01-13 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-13 20:00:00' AND '2021-01-14 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-14 20:00:00' AND '2021-01-15 02:00:00' OR ";

        $_type .= "MAX(CreateDate) BETWEEN '2021-01-15 20:00:00' AND '2021-01-18 02:00:00' OR ";

        $_type .= "MAX(CreateDate) BETWEEN '2021-01-18 20:00:00' AND '2021-01-19 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-19 20:00:00' AND '2021-01-20 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-20 20:00:00' AND '2021-01-21 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-21 20:00:00' AND '2021-01-22 02:00:00' OR ";

        $_type .= "MAX(CreateDate) BETWEEN '2021-01-22 20:00:00' AND '2021-01-25 02:00:00' OR ";

        $_type .= "MAX(CreateDate) BETWEEN '2021-01-25 20:00:00' AND '2021-01-26 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-26 20:00:00' AND '2021-01-27 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-27 20:00:00' AND '2021-01-28 02:00:00' OR ";
        $_type .= "MAX(CreateDate) BETWEEN '2021-01-28 20:00:00' AND '2021-01-29 02:00:00' OR ";

        $_type .= "MAX(CreateDate) BETWEEN '2021-01-29 20:00:00' AND '2021-01-31 02:00:00')";

        $_where = " AND CreateDate BETWEEN '2021-01-01 20:00:00' AND '2021-01-31 02:20:00'";
        if (Date("Y-m-d H:i:s") > "2021-01-31 02:00:00") {
            $Layout[0] = "2021-01-31 02:00:00";
        }else {
            $Layout[0] = Date("Y-m-d H:i:s");
        }

        if ($_POST['range'] == 1) {
            /*if ($_POST['type'] == 1) {
                $_ShowNum = " (COUNT(WL.RowId)+M.ActAdded) AS Num, ";
                $_type .= " AND WL.Bet >= 200 AND WL.Bet <= 1000 ";
                $_Sort = " (COUNT(WL.RowId)+M.ActAdded) DESC ";
            }elseif ($_POST['type'] == 3) {
                $_ShowNum = " (COUNT(WL.RowId)+M.ActAdded2) AS Num, ";
                $_type .= " AND WL.Bet >= 200 AND WL.Bet <= 1000 ";
                $_Sort = " (COUNT(WL.RowId)+M.ActAdded2) DESC ";
            }
            else {*/
                $_ShowNum = " SUM(t.WinLose) AS Num, ";
                $_type .= " AND SUM(Bet) >= 200 AND SUM(Bet) <= 1000 ";
                $_Sort = " SUM(t.WinLose) DESC ";
            //}
        }elseif($_POST['range'] == 2) {
            /*if ($_POST['type'] == 3) {
                $_ShowNum = " (COUNT(WL.RowId)+M.ActAdded3) AS Num, ";
                $_type .= " AND WL.Bet >= 1080 ";
                $_Sort =  " (COUNT(WL.RowId)+M.ActAdded3) DESC ";
            }else {*/
                $_ShowNum = " SUM(t.WinLose) AS Num, ";
                $_type .= " AND SUM(Bet) >= 1080 ";
                $_Sort = " SUM(t.WinLose) DESC ";
            //}
        }

        //$_type = " WL.CreateDate BETWEEN '2020-09-01 12:00:00' AND '2020-09-30 02:00:00'";
        /*if (Date("Y-m-d H:i:s") > "2020-09-30 02:00:00") {
            $Layout[0] = "2020-09-30 02:00:00";
        }else {
            $Layout[0] = Date("Y-m-d H:i:s");
        }

        if ($_POST['range'] == 1) {
            $_ShowNum = " (SUM(t.WinLose)+M.ActAdded) AS Num, ";
            $_type .= " AND SUM(Bet) >= 200 AND SUM(Bet) <= 1000 ";
            $_Sort = " (SUM(t.WinLose)+M.ActAdded) DESC ";
        }else {
            $_ShowNum = " (SUM(t.WinLose)+M.ActAdded2) AS Num, ";
            $_type .= " AND SUM(Bet) >= 1080 ";
            $_Sort =  " (SUM(t.WinLose)+M.ActAdded2) DESC ";
        }*/
        $i = 1;
        
        //$Layout[0] = "2020-05-01 00:00:00";
        CDbShell::Connect();
        //CDbShell::query("SELECT M.NickName, M.Level, MF.LevelBet, MF.NextLevelBet FROM memberfinance AS MF INNER JOIN member AS M ON M.MemberId = MF.MemberId WHERE AccumulateBet > 0 ORDER BY MF.AccumulateBet DESC LIMIT 100");
        #CDbShell::query("SELECT ".$_ShowNum." WL.MemberId, M.NickName FROM `memberwinlosedetail2` AS WL INNER JOIN member AS M ON M.MemberId = WL.MemberId WHERE ".$_type." AND WL.GameId != '35' GROUP BY WL.MemberId ORDER BY ".$_Sort." LIMIT 50");
        CDbShell::query("SELECT
        ".$_ShowNum."
        M.NickName 
        FROM
            (SELECT
                MemberId,
                SUM(WinLose) AS WinLose
            FROM
                `memberwinlosedetail3`
            WHERE GameId != '35' ".$_where."
            GROUP BY
                SequenceID HAVING 
                ".$_type.") t 
        INNER JOIN member AS M ON M.MemberId = t.MemberId 
        GROUP BY t.MemberId
        ORDER BY
            ".$_Sort."
            LIMIT 50;");
        while ($Row = CDbShell::fetch_array()) {
            $Row['Num'] = number_format($Row['Num']);
            $Layout[1] .=
			    <<<EOF
                <tr>
                    <td>{$i}</td>
                    <td>{$Row['NickName']}</td>
                    <td>{$Row['Num']}</td>
                </div>
EOF;
                $i++;
            }
            
            CDbShell::DB_close();
    
            echo json_encode($Layout);
            exit;
    }
    #12活動排名
    public static function ActivityInto3() {
        
        /*switch ($_POST['type']) {
            case "1":
                $_type = " MAX(CreateDate) BETWEEN '2020-12-01 00:00:00' AND '2020-12-08 23:59:59'";
                $_where = " AND CreateDate BETWEEN '2020-12-01 00:00:00' AND '2020-12-09 01:10:00'";
                if (Date("Y-m-d H:i:s") > "2020-12-08 23:59:59") {
                    $Layout[0] = "2020-12-08 23:59:59";
                }else {
                    $Layout[0] = Date("Y-m-d H:i:s");
                }
                break;
            case "2":
                $_type = " MAX(CreateDate) BETWEEN '2020-10-08 12:00:00' AND '2020-10-15 02:00:00'";
                $_where = " AND CreateDate BETWEEN '2020-10-08 11:00:00' AND '2020-10-15 03:00:00'";
                if (Date("Y-m-d H:i:s") > "2020-10-15 02:00:00") {
                    $Layout[0] = "2020-10-15 02:00:00";
                }else {
                    $Layout[0] = Date("Y-m-d H:i:s");
                }
                break;
            case "3":
                $_type = " MAX(CreateDate) BETWEEN '2020-10-15 12:00:00' AND '2020-10-22 02:00:00'";
                $_where = " AND CreateDate BETWEEN '2020-10-15 11:00:00' AND '2020-10-22 03:00:00'";
                if (Date("Y-m-d H:i:s") > "2020-10-22 02:00:00") {
                    $Layout[0] = "2020-10-22 02:00:00";
                }else {
                    $Layout[0] = Date("Y-m-d H:i:s");
                }
                break;
            case "4":
                $_type = " MAX(CreateDate) BETWEEN '2020-10-22 12:00:00' AND '2020-10-29 02:00:00'";
                $_where = " AND CreateDate BETWEEN '2020-10-22 11:00:00' AND '2020-10-29 03:00:00'";
                if (Date("Y-m-d H:i:s") > "2020-10-29 02:00:00") {
                    $Layout[0] = "2020-10-29 02:00:00";
                }else {
                    $Layout[0] = Date("Y-m-d H:i:s");
                }
                break;
        }*/

        $_type = " (";
        $_type .= "MAX(CreateDate) NOT BETWEEN '2020-12-09 00:00:00' AND '2020-12-09 23:59:59' AND ";
        $_type .= "MAX(CreateDate) NOT BETWEEN '2020-12-16 00:00:00' AND '2020-12-16 23:59:59' AND ";
        $_type .= "MAX(CreateDate) NOT BETWEEN '2020-12-23 00:00:00' AND '2020-12-23 23:59:59')";

        $_where = " AND CreateDate BETWEEN '2020-12-01 00:00:00' AND '2020-12-30 23:59:59'";
        if (Date("Y-m-d H:i:s") > "2020-12-30 23:59:59") {
            $Layout[0] = "2020-12-30 23:59:59";
        }else {
            $Layout[0] = Date("Y-m-d H:i:s");
        }

        if ($_POST['range'] == 1) {
            /*if ($_POST['type'] == 1) {
                $_ShowNum = " (COUNT(WL.RowId)+M.ActAdded) AS Num, ";
                $_type .= " AND WL.Bet >= 200 AND WL.Bet <= 1000 ";
                $_Sort = " (COUNT(WL.RowId)+M.ActAdded) DESC ";
            }elseif ($_POST['type'] == 3) {
                $_ShowNum = " (COUNT(WL.RowId)+M.ActAdded2) AS Num, ";
                $_type .= " AND WL.Bet >= 200 AND WL.Bet <= 1000 ";
                $_Sort = " (COUNT(WL.RowId)+M.ActAdded2) DESC ";
            }
            else {*/
                $_ShowNum = " SUM(t.WinLose) AS Num, ";
                $_type .= " AND SUM(Bet) >= 200 AND SUM(Bet) <= 1000 ";
                $_Sort = " SUM(t.WinLose) DESC ";
            //}
        }elseif($_POST['range'] == 2) {
            /*if ($_POST['type'] == 3) {
                $_ShowNum = " (COUNT(WL.RowId)+M.ActAdded3) AS Num, ";
                $_type .= " AND WL.Bet >= 1080 ";
                $_Sort =  " (COUNT(WL.RowId)+M.ActAdded3) DESC ";
            }else {*/
                $_ShowNum = " SUM(t.WinLose) AS Num, ";
                $_type .= " AND SUM(Bet) >= 1080 ";
                $_Sort = " SUM(t.WinLose) DESC ";
            //}
        }

        //$_type = " WL.CreateDate BETWEEN '2020-09-01 12:00:00' AND '2020-09-30 02:00:00'";
        /*if (Date("Y-m-d H:i:s") > "2020-09-30 02:00:00") {
            $Layout[0] = "2020-09-30 02:00:00";
        }else {
            $Layout[0] = Date("Y-m-d H:i:s");
        }

        if ($_POST['range'] == 1) {
            $_ShowNum = " (SUM(t.WinLose)+M.ActAdded) AS Num, ";
            $_type .= " AND SUM(Bet) >= 200 AND SUM(Bet) <= 1000 ";
            $_Sort = " (SUM(t.WinLose)+M.ActAdded) DESC ";
        }else {
            $_ShowNum = " (SUM(t.WinLose)+M.ActAdded2) AS Num, ";
            $_type .= " AND SUM(Bet) >= 1080 ";
            $_Sort =  " (SUM(t.WinLose)+M.ActAdded2) DESC ";
        }*/
        $i = 1;
        
        //$Layout[0] = "2020-05-01 00:00:00";
        CDbShell::Connect();
        //CDbShell::query("SELECT M.NickName, M.Level, MF.LevelBet, MF.NextLevelBet FROM memberfinance AS MF INNER JOIN member AS M ON M.MemberId = MF.MemberId WHERE AccumulateBet > 0 ORDER BY MF.AccumulateBet DESC LIMIT 100");
        #CDbShell::query("SELECT ".$_ShowNum." WL.MemberId, M.NickName FROM `memberwinlosedetail2` AS WL INNER JOIN member AS M ON M.MemberId = WL.MemberId WHERE ".$_type." AND WL.GameId != '35' GROUP BY WL.MemberId ORDER BY ".$_Sort." LIMIT 50");
        CDbShell::query("SELECT
        ".$_ShowNum."
        M.NickName 
        FROM
            (SELECT
                MemberId,
                SUM(WinLose) AS WinLose
            FROM
                `memberwinlosedetail3`
            WHERE GameId != '35' ".$_where."
            GROUP BY
                SequenceID HAVING 
                ".$_type.") t 
        INNER JOIN member AS M ON M.MemberId = t.MemberId 
        GROUP BY t.MemberId
        ORDER BY
            ".$_Sort."
            LIMIT 50;");
        while ($Row = CDbShell::fetch_array()) {
            $Row['Num'] = number_format($Row['Num']);
            $Layout[1] .=
			    <<<EOF
                <tr>
                    <td>{$i}</td>
                    <td>{$Row['NickName']}</td>
                    <td>{$Row['Num']}</td>
                </div>
EOF;
                $i++;
            }
            
            CDbShell::DB_close();
    
            echo json_encode($Layout);
            exit;
    }

    public static function CheckLogin()
    {
        CDbShell::Connect();
        CDbShell::query("SELECT * FROM member WHERE MemberAccount='".CSession::getVar('Account')."'  AND MemberPassword = '".CSession::getVar('Password')."'");
        if (1 == CDbShell::num_rows()) {
            $Random = '';
            $Character = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '2', '3', '4', '5', '6', '7', '8', '9');

            for ($i = 0; $i < 10; ++$i) {
                $RandChar = '';
                $RandChar = $Character[rand(0, 31)];
                $Random .= $RandChar;
            }

            CDbShell::query("UPDATE member SET Verify = '".$Random."' WHERE MemberAccount='".CSession::getVar('Account')."' AND MemberPassword = '".CSession::getVar('Password')."'");

            $ValidTime = date('Y-m-d H:i:s', mktime(date('H'), date('i') + 5, date('s'), date('m'), date('d'), date('Y')));
            //echo $ValidTime."<br />";
            $LoginKey = CommonElement::encrypt(CSession::getVar('Account').'|'.$Random.'|'.$ValidTime);

            return $LoginKey;
        } else {
            return '0';
        }
    }

    public static function CheckAPILogin($MemberAccount, $MemberPassword)
    {
        CDbShell::Connect();
        CDbShell::query("SELECT * FROM member WHERE MemberAccount='".$MemberAccount."' AND MemberPassword ='".$MemberPassword."'");
        if (1 == CDbShell::num_rows()) {
            $Random = '';
            $Character = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '2', '3', '4', '5', '6', '7', '8', '9');

            for ($i = 0; $i < 10; ++$i) {
                $RandChar = '';
                $RandChar = $Character[rand(0, 31)];
                $Random .= $RandChar;
            }

            CDbShell::query("UPDATE member SET Verify = '".$Random."' WHERE MemberAccount='".$MemberAccount."'");

            $ValidTime = date('Y-m-d H:i:s', mktime(date('H'), date('i') + 5, date('s'), date('m'), date('d'), date('Y')));
            $LoginKey = CommonElement::encrypt($MemberAccount.'|'.$Random.'|'.$ValidTime);

            CSession::setVar("Account", $MemberAccount);
		    CSession::setVar("Password", $MemberPassword);

            return $LoginKey;
        } else {
            return '0';
        }
    }
}
