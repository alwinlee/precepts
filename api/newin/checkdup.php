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

    // check duplication
    if ($jsonval['data']['serial']!=""){
        $sql="select * from `".$tbname."` where (`studentid`='".$jsonval['data']['studentid']."' OR `serial`='".$jsonval['data']['serial']."');";
    }else{
        $sql="select * from `".$tbname."` where (`studentid`='".$jsonval['data']['studentid']."');";
    }

    $record=mysql_query($sql);
    $numrows=mysql_num_rows($record);
    if ($numrows>0) {
        $code=1;
        $desc="duplication!";
        $record=false;
    } else {
        $code=0;
        $desc="no duplication data";
        $record=true;
    }

    $json_ret=array("code"=>$code,"desc"=>$desc,"result"=>$record);
    $ret = json_encode($json_ret);
    echo $ret;//header("Content-Type: text/html; charset=utf-8");
?>

