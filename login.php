<?php
    header("Content-Type: text/html; charset=utf-8");
    require_once("./api/lib/connmysql.php");
    session_start();
    date_default_timezone_set('Asia/Taipei');
    $nowDate=date('Y-m-d');

    // 將SESSION資料清除，並重導回首頁
    unset($_SESSION["precepts_account"]);
    unset($_SESSION["precepts_username"]);
    unset($_SESSION["precepts_auth"]);
    unset($_SESSION["precepts_userlevel"]);
    unset($_SESSION["precepts_key"]);
    unset($_SESSION["precepts_group"]);
    unset($_SESSION["precepts_subgroup"]);
    unset($_SESSION["precepts_groupexpire"]);
    unset($_SESSION["precepts_area"]);
    unset($_SESSION["precepts_expire"]);
    unset($_SESSION["precepts_expire1"]);
    unset($_SESSION["precepts_expire2"]);
    unset($_SESSION["precepts_expire3"]);
    unset($_SESSION["precepts_expire4"]);
    unset($_SESSION["precepts_expire5"]);
    unset($_SESSION["precepts_expire6"]);
    unset($_SESSION["precepts_expire7"]);
    unset($_SESSION["precepts_expire8"]);
    unset($_SESSION["precepts_expire9"]);
    unset($_SESSION["precepts_expire10"]);
    unset($_SESSION["precepts_expire11"]);
    unset($_SESSION["precepts_expire12"]);
    unset($_SESSION["precepts_expire13"]);
    unset($_SESSION["precepts_expire14"]);
    unset($_SESSION["precepts_expire15"]);
    unset($_SESSION["precepts_expire16"]);
    unset($_SESSION["precepts_expire17"]);
    unset($_SESSION["precepts_expire18"]);
    unset($_SESSION["precepts_expire19"]);
    unset($_SESSION["precepts_expire20"]);

    //查詢登入會員資料
    $sql="select * from `member_precepts` where `account`='".$_POST["account"]."' and `password`=PASSWORD('".$_POST["password"]."')";
    $record=mysql_query($sql);
    $numrows=mysql_num_rows($record);
    if($numrows<=0){unset($_SESSION["precepts_area"]);header("Location: index.php");exit;}

    $row=mysql_fetch_array($record, MYSQL_ASSOC);
    if ($nowDate>$row["expire"]){//帳號過期
        unset($_SESSION["precepts_area"]);header("Location: index.php");exit;
    }

    // success - keep session data
    $_SESSION["precepts_account"]=$row["account"];
    $_SESSION["precepts_username"]=$row["name"];
    $_SESSION["precepts_userlevel"]=$row["level"];
    $_SESSION["precepts_auth"]=$row["auth"];
    $_SESSION["precepts_key"]=$row["key"];
    $_SESSION["precepts_area"]="precepts";
    $_SESSION["precepts_expire"]=$row["expire"];

    $_SESSION["precepts_group"]=$row["group"];
    $_SESSION["precepts_subgroup"]=$row["subgroup"];
    $_SESSION["precepts_groupexpire"]=$row["groupexpire"];
    $_SESSION["precepts_expire"]=$row["expire"];
    $_SESSION["precepts_expire1"]=$row["expire1"];
    $_SESSION["precepts_expire2"]=$row["expire2"];
    $_SESSION["precepts_expire3"]=$row["expire3"];
    $_SESSION["precepts_expire4"]=$row["expire4"];
    $_SESSION["precepts_expire5"]=$row["expire5"];
    $_SESSION["precepts_expire6"]=$row["expire6"];
    $_SESSION["precepts_expire7"]=$row["expire7"];
    $_SESSION["precepts_expire8"]=$row["expire8"];
    $_SESSION["precepts_expire9"]=$row["expire9"];
    $_SESSION["precepts_expire10"]=$row["expire10"];
    $_SESSION["precepts_expire11"]=$row["expire11"];
    $_SESSION["precepts_expire12"]=$row["expire12"];
    $_SESSION["precepts_expire13"]=$row["expire13"];
    $_SESSION["precepts_expire14"]=$row["expire14"];
    $_SESSION["precepts_expire15"]=$row["expire15"];
    $_SESSION["precepts_expire16"]=$row["expire16"];
    $_SESSION["precepts_expire17"]=$row["expire17"];
    $_SESSION["precepts_expire18"]=$row["expire18"];
    $_SESSION["precepts_expire19"]=$row["expire19"];
    $_SESSION["precepts_expire20"]=$row["expire20"];
    header("Location: ./pages/checkin.php");
    exit;
