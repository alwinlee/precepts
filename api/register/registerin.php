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

     // check db exist
    $currY=date('Y');
    $currM=date('m');
    //if ($currM>=10){$currY+=1;}
    $tbname="precepts_".$currY;
    check_precepts_db($tbname);

    $code=-1;
    $desc="data unknown";
    $data=json_decode(file_get_contents('php://input'), true);

    if(isset($data['id'])==false||isset($data['register'])==false){
        $json_ret=array("code"=>$code,"desc"=>$desc);echo json_encode($json_ret);exit;
    }

    $id = $data['id'];
    $register = $data['register'];
    if($id <= 0 || $register < 0 || $register > 1){
        $json_ret=array("code"=>$code,"desc"=>$desc);echo json_encode($json_ret);exit;
    }

    //更新學生報名資料
    $sql="update `".$tbname."` set `register`=".$register." where `id`=".$id." limit 1";
    $record=mysql_query($sql);

    $code=1;
    $desc="success";
    $json_ret=array("code"=>$code,"desc"=>$desc,"result"=>$record);
    echo json_encode($json_ret);//header("Content-Type: text/html; charset=utf-8");
?>

