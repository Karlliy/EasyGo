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
	if (!class_exists('Operate'))			include_once("./operate/Operate.php");
	if (!class_exists('CommonElement'))		include_once("./BaseClass/CommonElement.php");
	if (!class_exists('Setting'))			include_once("./BaseClass/Setting.php");
// echo 123; exit;	
switch ($_SERVER["func"]) {
    case "Login":
        Login();
        break;
    case "Register":
        Register();
        break;    
}
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if ($_POST["func"] == "Login") {
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
									CDbShell::query("UPDATE member SET LastLoginIp = '".$myip."', LastLoginDate = '".Date('Y-m-d H:i:s')."' WHERE MemberAccount='".$_POST["Account"]."' AND MemberPassword = '".MD5($_POST["Password"])."'" );
									
									CDbShell::query("INSERT INTO MemberIpHistory(MemberId, Ip, CreateDate) VALUES (".$Row["MemberId"].", '".$myip."', NOW())");
									//$ValidTime = date("Y-m-d H:i:s",mktime (date("H"),date("i") + 5,date("s"),date("m") ,date("d") ,date("Y")));
									//CSession::setVar("LoginKey", Cryptographic::encrypt($_POST["Account"]."|".$Random."|".$ValidTime));

									echo "window.open('member/center','_self')";
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
		}else if ($_POST["func"] == "Register") {
			
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

				// if (0 != strcmp($_POST['Password'], $_POST['ConfirmPassword'])) {
				// 	throw new exception("密碼與確認密碼不正確!");
				// }
				// if (preg_match("/^[a-zA-Z0-9" . chr(0x80) . "-" . chr(0xff) . "]+$/", $_POST['RealName']) == 0) {
				// 	throw new exception("真實姓名只能是中英文 或 數字組合!");
				// }
				
				// if (preg_match("/^[a-zA-Z0-9" . chr(0x80) . "-" . chr(0xff) . "]+$/", $_POST['NickName']) == 0) {
				// 	throw new exception("暱稱只能是中英文 或 數字組合!");
				// }

				// if (preg_match("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}\$/", trim($_POST['EMail'])) == 0)
				// {
				// 	throw new exception("請輸入正確電子信箱！");
				// }

				// CDbShell::query("SELECT MemberId FROM member WHERE Email = '".trim($_POST["EMail"])."'");
				// if (CDbShell::num_rows() > 0) {
				// 	CDbShell::DB_close();
				// 	throw new exception("此EMail己經註冊成會員了！");
				// }

				// CDbShell::Connect();
				// $_Introducer = 0;
				// if (strlen($_POST['Introducer']) > 0) {
				// 	if (preg_match("/^[0-9]{6,7}$/", $_POST['Introducer']) == 0) {
				// 		throw new exception("請輸入正確推薦人ID!");
				// 	}

				// 	CDbShell::query("SELECT MemberId FROM member WHERE SpreadCode = '". $_POST['Introducer']."'");
				// 	if (CDbShell::num_rows() != 1) {
				// 		throw new exception("推薦人ID不存在!");
				// 	}

				// 	$InRow = CDbShell::fetch_array();
				// 	$_Introducer = $InRow['MemberId'];
				// }

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

					// GetRandom(40, $_MailCaptcha);

					// $_VerifyURL = "http://www.allwin-online.com/?".$_MailCaptcha;
					// ob_start();
					// include("./MailVerify.html");
					// $order_buffer = ob_get_contents();
					// ob_end_clean();
					// include_once('./PHPMailer/class.smtp.php');
					// include_once('./PHPMailer/class.phpmailer.php');
					
					// $mail = new PHPMailer();
					// //$mail->IsMail();
					
					// $mail->IsSMTP( );                     
					// //使用SMTP寄送信件		 
					// $mail->Host = "smtp.gmail.com";         
					// //指定發送信件的伺服器		 
					// $mail->SMTPAuth = true; 
					
					// //$mail->Username = "guanji.co@gmail.com";
					// //$mail->Password = "qxbolqgilcxdnvme";
					// $mail->Username = "willy90796m@gmail.com";
					// $mail->Password = "finpxlrckiyomczb";
					// $mail->Mailer = "smtp";
					// $mail->SMTPSecure = 'ssl';
					// $mail->Port = 465;
					
					// $mail->CharSet="utf-8";			//設定e-mail編碼		 
					// $mail->Encoding = "base64";		//設定信件編碼，大部分郵件工具都支援此編碼方式		
					// //$mail->SMTPDebug  = 2;
					// $mail->From = 'willy90796m@gmail.com';          
					// //指定寄件者的email位址		 
					// $mail->FromName = "WinOnline Game";
					// //寄件者的名稱sender's name
					// $mail->Subject = "WinOnline 註冊會員驗證通知信函";
					// //email的主旨
					// $mail->MsgHTML($order_buffer);
					// //信件內容
					// $mail->AddAddress(trim($_POST["EMail"])); 
					// //指定收件者的email位址
					// $mail->IsHTML(true);                  //設定信件內容為HTML
					// if(!$mail->Send()){
					// 	throw new exception("Mailer Error: " . $mail->ErrorInfo);
					// }else {

						$sql = "INSERT INTO member (MemberAccount, MemberPassword, CellPhone, PauseAccount, MemberLevel, MemberKind, CreateDate, RegisterIp, LastLoginIp, SpreadCode)
												VALUES ('".$_POST['Account']."', '".MD5($_POST['Password'])."', '".$_POST['Mobile']."', '0', 7, 1, '".Date('Y-m-d H:i:s')."', '".$_IP."', '".$_IP."', '".$SpreadCode."')";
						CDbShell::query($sql);
						$NewMemverId = CDbShell::insert_id();
						$_Points = 0;
						CDbShell::query("INSERT INTO memberfinance (MemberId, GamePoints) VALUES('".$NewMemverId."', ".$_Points.")");
								
						// CDbShell::query("CALL nsp_membertree_insert(".$NewMemverId.")");

						CDbShell::DB_close();
						// $js .= "alert(\"己傳送驗證碼至這個".trim($_POST["EMail"])."，驗證後就可以進行會員登入.\");";
						$js .= "window.open('member.html','_self');";

						echo $js;
					// }
				}else {
					throw new exception("帳號己註冊!");
				}
			}catch(Exception $e) {
				//CDbShell::DB_close();
				JSModule::ErrorJSMessage($e->getMessage());
			} 
		}else if ($_POST["func"] == "checklogin"){
            if(CSession::GetVar("Account") != "" && CSession::GetVar("Password") != ""){
                $data[0] = "1";
            }else{
                $data[0] = "0";
            }
            $return = json_encode($data);
            echo $return;
            exit;
        }else if ($_POST["func"] == "SearchMember") {
			SearchMember();
		}else if ($_POST["func"] == "LogOut") {
			CSession::ClearVar("Account");
			CSession::ClearVar("Password");
			$LogOut =
				<<<EOF
				window.location.href='./';
EOF;
			echo $LogOut;
			exit;
		}else if ($_POST["func"] == "ResetPassword") {

			try {
				if (preg_match("/^[a-zA-Z0-9]{6,10}$/", $_POST['Account']) == 0) {
					throw new exception("帳號只能是英文數字組合長度6-10字!");
				}

				if (preg_match("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}\$/", $_POST['EMail']) == 0)
				{
					throw new exception("請輸入正確電子信箱！");
				}

				CDbShell::connect();
				CDbShell::query("SELECT MemberId, NickName, EMail FROM member WHERE MemberAccount = '".$_POST["Account"]."' AND Email = '".$_POST["EMail"]."'");
				if (CDbShell::num_rows() == 0) {
					CDbShell::DB_close();
					throw new exception("帳號和EMail,請再確認當初註冊會員的EMail！");
				}
				$Row = CDbShell::fetch_array(); 

				GetRandom(mt_rand(6,8), $_NewPassword);

				CDbShell::query("UPDATE member SET MemberPassword = MD5('".$_NewPassword."')  WHERE MemberId = '". $Row['MemberId']."'" );
		
				CDbShell::DB_close();

				ob_start();
				include("./ForgetPassword.html");
				$order_buffer = ob_get_contents();
				ob_end_clean();
				include_once('./PHPMailer/class.smtp.php');
				include_once('./PHPMailer/class.phpmailer.php');
				
				$mail = new PHPMailer();
				//$mail->IsMail();
				
				$mail->IsSMTP( );                     
				//使用SMTP寄送信件		 
				$mail->Host = "smtp.gmail.com";         
				//指定發送信件的伺服器		 
				$mail->SMTPAuth = true; 
				$mail->Username = "willy90796m@gmail.com";
				$mail->Password = "finpxlrckiyomczb";
				$mail->Mailer = "smtp";
				$mail->SMTPSecure = 'ssl';
				$mail->Port = 465;
				
				$mail->CharSet="utf-8";			//設定e-mail編碼		 
				$mail->Encoding = "base64";		//設定信件編碼，大部分郵件工具都支援此編碼方式		
				//$mail->SMTPDebug  = 2;
				$mail->From = 'willy90796m@gmail.com';          
				//指定寄件者的email位址		 
				$mail->FromName = "WinOnline Game";
				//寄件者的名稱sender's name
				$mail->Subject = "WinOnline Game 重設密碼通知信函";
				//email的主旨
				$mail->MsgHTML($order_buffer);
				//信件內容
				$mail->AddAddress($Row['EMail']); 
				//指定收件者的email位址
				$mail->IsHTML(true);                  //設定信件內容為HTML
				if(!$mail->Send())
				{
					throw new exception("Mailer Error: " . $mail->ErrorInfo);
				}else {
					$Layout =
						<<<EOF
						new jBox('Notice', {
							autoClose: 1000,
							position: {
							x: 'center',
							y: 'center'
							},
							stack: true,
							animation: {
							open: 'tada',
							close: 'zoomIn'
							},
							//title: '錯誤!',
							content: '新的密碼己經寄到您的信箱',
							color: 'blue'
							});
							$('div[id="popup_forgotPW"]').hide();
EOF;
					echo $Layout;
					exit;
					
				}
			}catch(Exception $e) {
				//CDbShell::DB_close();
				JSModule::ErrorJSMessage($e->getMessage());
			} 
		}
	}else {
		CSession::ClearAll();
		$CurrentlyPage =
		<<<EOF
		<script type="text/javascript">
			var Currently = 'login';
		</script>
EOF;
		echo $CurrentlyPage;
		// include("login.html");
        include("index.html");
	}
	//include("index.html");

	function SearchMember() {
		try {
			if (preg_match("/^[0-9]{7}$/", $_POST['MemberId']) == 0) {
				throw new exception("會員ID只能是7位數字!");
			}

			CDbShell::Connect();
			CDbShell::query("SELECT * FROM member WHERE SpreadCode = '".$_POST['MemberId']."'");				
				
			if (CDbShell::num_rows() == 0) {
				throw new exception("搜尋不到會員ID!");
			}
			$MRow = CDbShell::fetch_array();
			$Layout[0] = 'OK';
			$Layout[1] =
			<<<EOF
			<div class="add_fd">
				<img src="images/lobby/mb_man01.png?2019100101" class="fd_head">
				<div class="fd_name">{$MRow['NickName']}</div>
				<div class="fd_lv">LV.{$MRow['Level']}</div>
				<div class="fd_btn">
					<button type="button" id="addfriend" class="orangebtn_s" data-id="{$MRow['SpreadCode']}">加入</button>
				</div>
			</div>
EOF;
			CDbShell::DB_close();

			echo json_encode($Layout);
		}catch(Exception $e) {
			//CDbShell::DB_close();
			$Layout[0] = 'Error';
			
			$js .= "jQuery(document).ready(function() {	";	
			$js .= "new jBox('Notice', {";
			$js .= "autoClose: 3000,";
			$js .= "position: {";
			$js .= "x: 'center',";
			$js .= "y: 'center'";
			$js .= "},";
			$js .= "stack: true,";
			$js .= "animation: {";
			$js .= "open: 'tada',";
			$js .= "close: 'zoomIn'";
			$js .= "},";
			$js .= "title: '錯誤!',";
			$js .= "content: '".$e->getMessage()."',";
			$js .= "color: 'red',";
			$js .= "});";
			$js .= "});";
			//JSModule::ErrorJSMessage($e->getMessage());
			$Layout[1] = $js;
			echo json_encode($Layout);
		} 
	}

	function GetRandom($length, &$randoma ) {

		$randoma = "";
		//mt_srand(mktime());
			
		for ($i=1; $i<=$length; $i=$i+1)
		{
			//亂數$c設定三種亂數資料格式大寫、小寫、數字，隨機產生
			$c=mt_rand(1,3);
			//在$c==1的情況下，設定$a亂數取值為97-122之間，並用chr()將數值轉變為對應英文，儲存在$b
			if($c==1){$a=mt_rand(97,122);$b=chr($a);}
			//在$c==2的情況下，設定$a亂數取值為65-90之間，並用chr()將數值轉變為對應英文，儲存在$b
			if($c==2){$a=mt_rand(65,90);$b=chr($a);}
			//在$c==3的情況下，設定$b亂數取值為0-9之間的數字
			if($c==3){$b=mt_rand(0,9);}
			//使用$randoma連接$b
			$randoma = $randoma.$b;
		}
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