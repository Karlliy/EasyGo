<?php

ini_set('SHORT_OPEN_TAG',"On"); 				// 是否允许使用\"\<\? \?\>\"短标识。否则必须使用\"<\?php \?\>\"长标识。
ini_set('display_errors',"On"); 				// 是否将错误信息作为输出的一部分显示。
ini_set('error_reporting',E_ALL & ~E_NOTICE);

header('Content-Type: text/html; charset=utf-8');

if (!class_exists('CDbShell'))			include_once("./BaseClass/CDbShell.php");
if (!class_exists('CSession'))			include_once("./BaseClass/CSession.php");  
if (!class_exists('CUrlQuery'))			include_once("./BaseClass/CUrlQuery.php");
if (!class_exists('JSModule'))			include_once("./BaseClass/JSModule.php");
if (!class_exists('Operate'))			include_once("./Operate/Operate.php");
if (!class_exists('CommonElement'))		include_once("./BaseClass/CommonElement.php");
if (!class_exists('Setting'))			include_once("./BaseClass/Setting.php");

switch ($_SERVER["func"]) {
    case "GameList":
        GameList();
        break;
    case "Help":
        Help();
        break;
    case "NewsList":
        NewsList();
        break;
    
}

function GameList() {
    CDbShell::Add_S($_POST);
    CDbShell::Add_S($_GET);
    CDbShell::Add_S($_SESSION);
    CDbShell::Add_S($_COOKIE);
    CDbShell::Connect();

    if($_POST["val"] == 1) {
        $_Condition .= "WHERE ProductId = '1'";
    }else if($_POST["val"] == 2) {
        $_Condition .= "WHERE ProductId = '2'";
    }else if($_POST["val"] == 99) {
        $_Condition .= "WHERE ProductId = '99'";
    }
    CDbShell::query("SELECT GameId, CASE WHEN ProductId = 1 THEN '手機遊戲' WHEN ProductId = 2 THEN '線上遊戲' WHEN ProductId = 3 THEN '點數卡' END AS ProductId, 
                GameName, `FileName`, GameInfo FROM game " .$_Condition. " ORDER BY CreateDate DESC");
                
    if (CDbShell::num_rows() > 0) {
        while ($Row = CDbShell::fetch_array()) {
            $Layout .=
            <<<EOF
            <div class="ga_item" id="GameListbnt" data-value="{$Row['GameId']}">
                <img src="../happygobackSite/picturedata/{$Row['FileName']}" alt="遊戲" class="ga_pic">
                <div class="ga_title">{$Row['GameInfo']}</div>
                <div class="ga_shopping">
                    <input type="button" value="" class="fa fa-cart-plus-w">
                </div>
            </div>
EOF;
        }
        CDbShell::DB_close();
        echo $Layout;
        exit;
    }

}

function Help() {
        
    include("./help.html");
    exit;
}

function NewsList() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            CDbShell::Add_S($_POST);
            CDbShell::Add_S($_GET);
            CDbShell::Add_S($_SESSION);
            CDbShell::Add_S($_COOKIE);
            CDbShell::Connect();
            if('NewsList' == $_POST['fun']){
                CDbShell::query("SELECT `Row`, Open_or_Close, ReleaseTime, Personnel, Detail FROM `news` ORDER BY ReleaseTime desc");
                if (CDbShell::num_rows() > 0) {
                    while ($Row = CDbShell::fetch_array()) {
                        $Layout .=
                            <<<EOF
                            <li><a href="#" class="jsNews" id="jsNews" data-value="{$Row["Row"]}">{$Row["Detail"]}</a></li>
EOF;
                    }
                    CDbShell::DB_close();
            
                    echo $Layout;
                    exit;
                }else{
                    $Layout =
                        <<<EOF
                        <li>資料內容</li>
EOF;
                }
            }

            if('NewsListAll' == $_POST['fun']){
                CDbShell::query("SELECT `Row`, Open_or_Close, LEFT(ReleaseTime,10) as ReleaseTime, Personnel, Detail, CONCAT(LEFT(Detail,25),'...') as DetailList FROM `news` ORDER BY ReleaseTime desc");
                if (CDbShell::num_rows() > 0) {
                    while ($Row = CDbShell::fetch_array()) {
                        $Layout .=
                            <<<EOF
                            <ul class="dissbox">
                                <li class="diss_qa">
                                    <div class="diss_main" id="OpenNewsDetail">
                                        <div class="diss_list">
                                                <div class="title">{$Row["DetailList"]}</div>
                                                <div class="date">{$Row["ReleaseTime"]}</div>
                                        </div>
                                    </div>
                                    <ul class="diss_subs" id="diss_subs">
                                        <li>{$Row["Detail"]}</li>
                                    </ul>
                                </li>
                            </ul>
EOF;
                    }
                    CDbShell::DB_close();
            
                    echo $Layout;
                    exit;
                }else{
                    $Layout =
                        <<<EOF
                        <li>資料內容</li>
EOF;
                }
            }
        }catch(Exception $e) {
            JSModule::ErrorJSMessage($e->getMessage());
        }
    }
    include("./index.html");
}


include("./index.html");
