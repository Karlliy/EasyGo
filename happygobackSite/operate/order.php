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
        case "Order":
            Order();
            break;
        case "Deposit":
            Deposit();
            break;   
        case "Withdrawal" ;
            Withdrawal();
            break;
        case "SelectList" ;
            SelectList();
            break;
        case "Order_list":
            Order_list();
            break;
        case "Order_detail";
            Order_detail();
            break;
    }

    function Order() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                $_Condition = "";
                if($_POST["ProductNumber"]){
                    if (strlen($_Condition) == 0) $_Condition .= "WHERE ";
                    else $_Condition .= " AND";
                    $_Condition .= " o.ProductNumber like '%".$_POST["ProductNumber"]."%' ";
                }
                if($_POST["ProductId"]){
                    if (strlen($_Condition) == 0) $_Condition .= "WHERE ";
                    else $_Condition .= " AND";
                    $_Condition .= " o.ProductId = '".$_POST["ProductId"]."' ";
                }
                if($_POST["TypeId"]){
                    if (strlen($_Condition) == 0) $_Condition .= "WHERE ";
                    else $_Condition .= " AND";
                    $_Condition .= " o.TypeId = '".$_POST["TypeId"]."' ";
                }
                if($_POST["MemberAccount"]){
                    if (strlen($_Condition) == 0) $_Condition .= "WHERE ";
                    else $_Condition .= " AND";
                    $_Condition .= " m.MemberAccount like '%".$_POST["MemberAccount"]."%' ";
                }
                if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" && $_Condition == ""){
                    $_Condition = " WHERE o.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'" ;
                }else if($_POST["datepick1"] != "" && $_POST["datepick2"] != "" ){
                    $_Condition .= " AND o.CreateDate BETWEEN '".$_POST["datepick1"]."' AND '".$_POST["datepick2"]."'";
                }
                CDbShell::query("SELECT
                p.ProductName,
                o.ProductNumber,
                pt.TypeName,
                o.ProductTitle,
                o.Price,
                o.KuTsuenQuantity,
                o.ChiuHsiaoQuantity,
                o.HsiaoShouQuantity,
                o.HandlingFee,
                o.Price*o.HsiaoShouQuantity as total             
                FROM `order` o
                LEFT JOIN member m ON m.MemberId = o.MemberId
                LEFT JOIN product p on p.ProductId = o.ProductId
                LEFT JOIN producttype pt on pt.TypeId = o.TypeId ".$_Condition);
                $row = 0;
                if (CDbShell::num_rows() > 0) {
                    while ($Row = CDbShell::fetch_array()) {
                        $row = $row + 1;
                        $Price               =number_format($Row['Price']);
                        $KuTsuenQuantity     =number_format($Row['KuTsuenQuantity']);
                        $ChiuHsiaoQuantity   =number_format($Row['ChiuHsiaoQuantity']);
                        $HsiaoShouQuantity   =number_format($Row['HsiaoShouQuantity']);
                        $HandlingFee         =number_format($Row['HandlingFee']);
                        $total               =number_format($Row['total']);

                        $SumPrice            +=$Row['Price'];
                        $SumKuTsuenQuantity  +=$Row['KuTsuenQuantity'];
                        $SumChiuHsiaoQuantity+=$Row['ChiuHsiaoQuantity'];
                        $SumHsiaoShouQuantity+=$Row['HsiaoShouQuantity'];
                        $SumHandlingFee      +=$Row['HandlingFee'];
                        $Sumtotal            +=$Row['total'];
                        $Layout .=
                        <<<EOF
                        <tr>
                            <td data-th="操作">
                                <input type="button" id="viewbtn" value="查看" class="btn_small" data-value="{$Row['ProductNumber']}">
                            </td>
                            <td data-th="項次">{$row}</td>
                            <td data-th="商品主分類">{$Row['ProductName']}</td>
                            <td data-th="商品編號">{$Row['ProductNumber']}</td>
                            <td data-th="商品類型">{$Row['TypeName']}</td>
                            <td data-th="商品標題">
                                <div class="text_omit">{$Row['ProductTitle']}</div>
                            </td>
                            <td data-th="單價" class="text_red" style="text-align:right">{$Price}</td>
                            <td data-th="庫存量" style="text-align:right">{$KuTsuenQuantity}</td>
                            <td data-th="取消量" style="text-align:right">{$ChiuHsiaoQuantity}</td>
                            <td data-th="銷售量" class="text_pink" style="text-align:right">{$HsiaoShouQuantity}</td>
                            <td data-th="手續費" style="text-align:right">{$HandlingFee}</td>
                            <td data-th="小計" class="text_blue" style="text-align:right">{$total}</td>
                        </tr>
EOF;
                    }
                    $SumPrice             = number_format($SumPrice);
                    $SumKuTsuenQuantity   = number_format($SumKuTsuenQuantity);
                    $SumChiuHsiaoQuantity = number_format($SumChiuHsiaoQuantity);
                    $SumHsiaoShouQuantity = number_format($SumHsiaoShouQuantity);
                    $SumHandlingFee       = number_format($SumHandlingFee);
                    $Sumtotal             = number_format($Sumtotal);
                    if ($_POST["formlist"]==1) {
                        $Layoutfoot =
                        <<<EOF
                            <tr class="tb_foot">
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th="">總計</td>
                                <td data-th="" class="text_red" style="text-align:right">{$SumPrice}</td>
                                <td data-th="" style="text-align:right">{$SumKuTsuenQuantity}</td>
                                <td data-th="" style="text-align:right">{$SumChiuHsiaoQuantity}</td>
                                <td data-th="" class="text_pink" style="text-align:right">{$SumHsiaoShouQuantity}</td>
                                <td data-th="" style="text-align:right">{$SumHandlingFee}</td>
                                <td data-th="" class="text_blue" style="text-align:right">{$Sumtotal}</td>
                            </tr>
EOF;
                        CDbShell::DB_close();
                        echo $Layoutfoot;exit;
                    }else if ($_POST["formlist"]==2){
                        $data[0] = $SumPrice;
                        $data[1] = $SumKuTsuenQuantity;
                        $data[2] = $SumChiuHsiaoQuantity;
                        $data[3] = $SumHsiaoShouQuantity;
                        $data[4] = $SumHandlingFee;
                        $data[5] = $Sumtotal;
                        echo json_encode($data);exit;
                    }
                    CDbShell::DB_close();
                    echo $Layout;exit;
                }else{
                    if ($_POST["formlist"]==1) {
                        $Layoutfoot =
                        <<<EOF
                        <tr>
                            <td colspan="12" data-th="" class="text_center">無資料內容</td>
                        </tr>
EOF;
                        CDbShell::DB_close();
                        echo $Layoutfoot;exit;
                    }else if ($_POST["formlist"]==2){
                        $data[0] = 0;
                        $data[1] = 0;
                        $data[2] = 0;
                        $data[3] = 0;
                        $data[4] = 0;
                        $data[5] = 0;
                        echo json_encode($data);exit;
                    }
                    CDbShell::DB_close();
                    echo $Layout;exit;
                    $Layout =
                        <<<EOF
                        <tr>
                            <td colspan="12" data-th="" class="text_center">無資料內容</td>
                        </tr>
EOF;
                        CDbShell::DB_close();
                        echo $Layout;exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../order.html");
    }

    function Deposit() {

        include("../deposit.html");
    }

    function Withdrawal() {

        include("../withdrawal.html");
    }

    function SelectList() {
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
                if ('ProductId' == $_POST['fun']) {
                    $Row = CDbShell::fetch_row_field("SELECT ProductId, ProductName FROM product ");
                    echo json_encode($Row);
                    exit;
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }

    }

    function Order_list() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if('Order_list_top' == $_POST['fun']){
                    CDbShell::query("SELECT
                    o.ProductTitle,
                    o.ProductNumber,
                    p.ProductName,
                    case when o.GamePlatform = 1 then 'Android' when o.GamePlatform = 2 then 'iOS' when o.GamePlatform = 3 then '電腦' when o.GamePlatform = 4 then 'Steam' end as GamePlatform,
                    o.GameCoinQuantity,
                    LEFT(o.CreateDate,10) as CreateDate,
                    pt.TypeName,
                    o.GameServer,
                    o.Price,
                    ml.LevelName,
                    o.PointCardKind,
                    case when o.Currency = 1 then '台幣' else '無' end as Currency,
                    o.KuTsuenQuantity,
                    m.Memberaccount as Sellmember,
                    m2.Memberaccount as Buymember,
                    g.GameName,
                    o.CurrencyValue             
                    FROM `order` o
                    LEFT JOIN ordertobuy ob ON ob.ProductNumber = o.ProductNumber
                    LEFT JOIN member m ON m.MemberId = o.MemberId
                    LEFT JOIN member m2 ON m2.MemberId = ob.MemberId
                    LEFT JOIN memberlevel ml ON ml.RowId = m.MemberLevel
                    LEFT JOIN product p on p.ProductId = o.ProductId
                    LEFT JOIN producttype pt on pt.TypeId = o.TypeId
                    LEFT JOIN game g on g.GameId = o.GameId
                    where o.ProductNumber = '".$_POST["ProductNumber"]."' ");

                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();

                        CSession::SetVar("Sellmember", $Row["Sellmember"]);
                        CommonElement::Add_S($_SESSION);

                        $data= array($Row["ProductTitle"],$Row["ProductNumber"],$Row["ProductName"],$Row["GamePlatform"],$Row["GameCoinQuantity"]
                        ,$Row["CreateDate"],$Row["TypeName"],$Row["GameServer"],$Row["Price"],$Row["LevelName"],$Row["PointCardKind"],$Row["Currency"],$Row["KuTsuenQuantity"]
                        ,$Row["Sellmember"],$Row["Buymember"],$Row["GameName"],$Row["CurrencyValue"]);
                        echo json_encode($data);exit;
                    }
                }
                if('Order_list_foot' == $_POST['fun']){
                    CDbShell::query("SELECT
                    ob.CreateDate,
                    ob.ProductNumber,
                    ob.OrderNumber,
                    m.Memberaccount as Buymember,
                    ob.Quantity,
                    ob.SumPrice,
                    case when ob.SumPricePlusHand-ob.SumPrice is null then 0 else ob.SumPricePlusHand-ob.SumPrice end as HandlingFee,
                    case when ob.State = 3 then '已完成' when ob.State = 4 then '已取消' when ob.State = 5 then '已退款' else '處理中' end as `Status`,
                    0 as RefundAmount,
                    ob.PaymentMethod,
                    case when ob.State = 1 then '未收款' when ob.State = 2 then '已收款' when ob.State = 3 then '已收款' else '已取消' end as PaymentStatus,
                    case when ob.State = 1 then '未移交' when ob.State = 2 then '未移交' when ob.State = 3 then '已移交' else '已取消' end as CommodityTransfer,
                    case when ob.OrderNumber in (SELECT OrderNumber FROM evaluate) then '已評價' else '未評價' end as EvaluateBuyer
                    FROM ordertobuy ob
                    LEFT JOIN member m ON m.MemberId = ob.MemberId
                    LEFT JOIN member m2 ON m2.MemberId = ob.SellMemberId
                    WHERE m2.MemberAccount = '".$_POST["Sellmember"]."' AND ob.ProductNumber = '".$_POST["ProductNumber"]."' ");
                    $row = 0;
                    if (CDbShell::num_rows() > 0) {
                        while ($Row = CDbShell::fetch_array()) {
                            $row = $row + 1;
                            $Quantity     =number_format($Row['Quantity']);
                            $SumPrice     =number_format($Row['SumPrice']);
                            $HandlingFee  =number_format($Row['HandlingFee']);
                            $RefundAmount =number_format($Row['RefundAmount']);

                            $SumQuantity     +=$Row['Quantity'];
                            $SumSumPrice     +=$Row['SumPrice'];
                            $SumHandlingFee  +=$Row['HandlingFee'];
                            $SumRefundAmount +=$Row['RefundAmount'];
                            $CountStatusF     +=($Row['Status']=='已完成'? 1:0);
                            $CountStatusC     +=($Row['Status']=='已取消'? 1:0);
                            $CountStatusR     +=($Row['Status']=='已退款'? 1:0);
                            switch ($Row['Status']) {
                                case "已完成":
                                    $text_color1 = "text_black";
                                break;
                                case "已取消":
                                    $text_color1 = "text_gray";
                                break;
                                case "已退款":
                                    $text_color1 = "text_red";
                                break;
                            }
                            switch ($Row['PaymentStatus']) {
                                case "未收款":
                                    $text_color2 = "text_pink";
                                break;
                                case "已收款":
                                    $text_color2 = "text_black";
                                break;
                                case "已取消":
                                    $text_color2 = "text_gray";
                                break;
                            }
                            switch ($Row['CommodityTransfer']) {
                                case "未移交":
                                    $text_color3 = "text_pink";
                                break;
                                case "已移交":
                                    $text_color3 = "text_black";
                                break;
                                case "已取消":
                                    $text_color3 = "text_gray";
                                break;
                            }
                            switch ($Row['EvaluateBuyer']) {
                                case "未評價":
                                    $text_color4 = "text_pink";
                                break;
                                case "已評價":
                                    $text_color4 = "text_black";
                                break;
                            }
                            $Layout .=
                            <<<EOF
                            <tr>
                                <td data-th="操作">
                                    <input type="button" id="DetailBtn" value="查看" class="btn_small" data-value="{$Row['OrderNumber']}">
                                    <input type="button" onclick="javascript:location.href=''" value="提現" class="btn_small btn_red">
                                </td>
                                <td data-th="項次">{$row}</td>
                                <td data-th="下單時間">{$Row['CreateDate']}</td>
                                <td data-th="訂單編號">{$Row['OrderNumber']}</td>
                                <td data-th="購買人帳號">{$Row['Buymember']}</td>
                                <td data-th="購買量" style="text-align:right">{$Quantity}</td>
                                <td data-th="付款金額" class="text_bold text_green" style="text-align:right">{$SumPrice}</td>
                                <td data-th="手續費" style="text-align:right">{$HandlingFee}</td>
                                <td data-th="退款金額" class="text_bold text_red" style="text-align:right">{$RefundAmount}</td>
                                <td data-th="訂單狀態" class={$text_color1}>{$Row['Status']}</td>
                                <td data-th="付款方式">{$Row['PaymentMethod']}</td>
                                <td data-th="收款狀態" class={$text_color2}>{$Row['PaymentStatus']}</td>
                                <td data-th="商品移交" class={$text_color3}>{$Row['CommodityTransfer']}</td>
                                <td data-th="評價買家" class={$text_color4}>{$Row['EvaluateBuyer']}</td>
                            </tr>
EOF;
                        }
                        $SumQuantity     =number_format($SumQuantity);
                        $SumSumPrice     =number_format($SumSumPrice);
                        $SumHandlingFee  =number_format($SumHandlingFee);
                        $SumRefundAmount =number_format($SumRefundAmount);
                        if ($_POST["formlist"]==1) {
                            $Layoutfoot =
                            <<<EOF
                            <tr class="tb_foot">
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th="">總計</td>
                                <td data-th="" style="text-align:right">{$SumQuantity}</td>
                                <td data-th="" class="text_green" style="text-align:right">{$SumSumPrice}</td>
                                <td data-th="" style="text-align:right">{$SumHandlingFee}</td>
                                <td data-th="" class="text_red" style="text-align:right">{$SumRefundAmount}</td>
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th=""></td>
                                <td data-th=""></td>
                            </tr>
EOF;
                            CDbShell::DB_close();
                            echo $Layoutfoot;exit;
                        }else if ($_POST["formlist"]==2){
                            $data[0] = $row;
                            $data[1] = $CountStatusF;
                            $data[2] = $SumSumPrice;
                            $data[3] = $CountStatusC;
                            $data[4] = $CountStatusR;
                            $data[5] = $SumHandlingFee;
                            echo json_encode($data);exit;
                        }
                        CDbShell::DB_close();
                        echo $Layout;exit;
                    }else{
                        $Layout=
                        <<<EOF
                        <tr>
                            <td colspan="14" data-th="" class="text_center">無資料內容</td>
                        </tr>
EOF;
                        echo $Layout;exit;
                    }
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        $JS =
        <<<EOF
        <script>
        var JSProductNumber = '{$_POST["ProductNumber"]}'
        </script>
EOF;
        echo $JS;
        include("../order_list.html");
    }
    
    function Order_detail() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                CDbShell::Add_S($_POST);
                CDbShell::Add_S($_GET);
                CDbShell::Add_S($_SESSION);
                CDbShell::Add_S($_COOKIE);
                CDbShell::Connect();
                if('Order_detail' == $_POST['fun']){
                    CDbShell::query("SELECT
                    ob.OrderNumber,
                    o.ProductNumber,
                    o.ProductTitle,
                    ml.LevelName,
                    m.MemberAccount as SellMember,
                    o.KuTsuenQuantity,
                    o.Price,
                    ob.CreateDate,
                    m2.MemberId,
                    m2.MemberAccount as BuyMember,
                    ob.Quantity,
                    ob.SumPrice,
                    0 as RefundQuantity,
                    0 as RefundAmount,
                    ob.SumPricePlusHand - ob.SumPrice as HandlingFee,
                    case when ob.PayAddress is null then ob.PaymentMethod else concat(ob.PaymentMethod,'-',PayAddress) end as PaymentMethod,
                    case when ob.PaymentCode is null then ob.PayNumber else ob.PaymentCode end as PayCode,
                    ob.PayDate,
                    case when ob.State = 1 then '未收款' when ob.State = 2 then '已收款' when ob.State = 3 then '已收款' else '已取消' end as PaymentStatus,
                    case when ob.State = 5 then '已退款' else '未退款' end as `Status`,
                    case when r.ShenHeuState = 1 then '通過' when r.ShenHeuState = 2 then '未通過' else '未審核' end as ShenHeuState,
                    r.ShenHeuPerson,
                    r.Remark
                    FROM `order` o
                    LEFT JOIN ordertobuy ob on ob.ProductNumber = o.ProductNumber
                    LEFT JOIN member m on m.MemberId = o.MemberId
                    LEFT JOIN member m2 on m2.MemberId = ob.MemberId
                    LEFT JOIN memberlevel ml on ml.RowId = m.MemberLevel
                    LEFT JOIN orderreview r on r.OrderNumber = ob.OrderNumber
                    WHERE ob.OrderNumber = '".$_POST["OrderNumber"]."' ");
                
                    if (CDbShell::num_rows() > 0) {
                        $Row = CDbShell::fetch_array();

                        $PaymentStatus = ($Row['PaymentStatus']=='已收款') ? '' : 'text_pink';
                        $Status = ($Row['Status']=='已退款') ? '' : 'text_pink';
                        $ShenHeuState = ($Row['ShenHeuState']=='通過') ? 'text_green' : 'text_red';

                        $data= array($Row["OrderNumber"],$Row["ProductNumber"],$Row["ProductTitle"],$Row["LevelName"],$Row["SellMember"],$Row["KuTsuenQuantity"],$Row["Price"]
                        ,$Row["CreateDate"],$Row["MemberId"],$Row["BuyMember"],$Row["Quantity"],$Row["SumPrice"],$Row["RefundQuantity"],$Row["RefundAmount"],$Row["HandlingFee"]
                        ,$Row["PaymentMethod"],$Row["PayCode"],$Row["PayDate"],$Row["PaymentStatus"],$Row["Status"],$Row["ShenHeuState"],$Row["ShenHeuPerson"],$Row["Remark"]
                        ,$PaymentStatus,$Status,$ShenHeuState);
                        echo json_encode($data);exit;
                    }
                }
            }catch(Exception $e) {
                JSModule::ErrorJSMessage($e->getMessage());
            } 
        }
        include("../order_detail.html");
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