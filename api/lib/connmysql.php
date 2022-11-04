<?php
    //資料庫連線設定
    //*/
    $db_host="localhost";
    $db_table="bwfocebmbd";
    $db_username="root";
    $db_password="rinpoche";
    /*/
    $db_host="dbserver.cffa4tx9mjmb.ap-southeast-1.rds.amazonaws.com";
    $db_table="bwfocebmbd";
    $db_username="bwfocebmbd";
    $db_password="Candrakirti2019";
    //*/
    if(!@mysql_connect($db_host,$db_username,$db_password)){
        die("資料連結失敗！");
    }

    if(!@mysql_select_db($db_table)) {//連接資料庫
        die("資料庫選擇失敗！");
    }
    mysql_query("SET NAMES 'utf8'");//設定字元集與連線校對
?>
