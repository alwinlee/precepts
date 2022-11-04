<?php
    session_start();
    if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||$_SESSION["precepts_area"]!="precepts"){
        $code=-2;
        $desc="auth failed";
        $json_ret=array("code"=>$code,"desc"=>$desc);echo json_encode($json_ret);exit;
    }

    header("Content-type: application/json; charset=utf-8");
    require_once("../lib/connmysql.php");
    require_once("../lib/common.php");

    ini_set("error_reporting",0);
    ini_set("display_errors","Off"); // On : open, O

    $code=-1;
    $desc="data unknown";
    $jsonval=json_decode(file_get_contents('php://input'), true);

    if(isset($jsonval['result'])==false||isset($jsonval['data'])==false||$jsonval['result']==false){
        $json_ret=array("code"=>$code,"desc"=>$desc);echo json_encode($json_ret);exit;
    }

    // check db exist
    $currY=date('Y');
    $currM=date('m');
    //if ($currM>=10){$currY+=1;}
    $tbname="precepts_".$currY;
    check_precepts_db($tbname);

    // insert to db
    $sql="INSERT INTO `".$tbname."` VALUES (NULL,";
    $sql.="'".$jsonval['data']['barcode']."',";
    $sql.="'".$jsonval['data']['serial']."',";
    $sql.="'".$jsonval['data']['sex']."',";
    $sql.="'".$jsonval['data']['studentid']."',";
    $sql.="'".$jsonval['data']['area']."',";
    $sql.="'".$jsonval['data']['classroom']."',";
    $sql.="'".$jsonval['data']['name']."',";
    $sql.=$jsonval['data']['rookie'].",";
    $sql.="'".$jsonval['data']['course']."',";
    $sql.="'',";
    $sql.=$jsonval['data']['register'].",";
    $sql.="0,0,'1970-01-01','');";

    $record=mysql_query($sql);

    if (!$record) {
        $code=-1;
        $desc="record insert failed!".$sql;
    } else {
        $code=1;
        $desc="success".$sql;
    }

    $json_ret=array("code"=>$code,"desc"=>$desc,"result"=>$record);
    echo json_encode($json_ret);//header("Content-Type: text/html; charset=utf-8");
?>

