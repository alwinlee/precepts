<?php
    session_start();
    if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||$_SESSION["precepts_area"]!="precepts"){
        $json_ret=array("draw"=>$draw,"recordsTotal"=>0,"recordsFiltered"=>0,"data"=>"");
        echo json_encode($json_ret);
        exit;
    }

    header("Content-type: application/json; charset=utf-8");
    require_once("../lib/connmysql.php");
    require_once("../lib/common.php");
    ini_set("error_reporting",0);
    ini_set("display_errors","Off"); // On : open, O

    $code=-1;
    $desc="data unknown";
    $data=json_decode(file_get_contents('php://input'), true);

    if(isset($data['data']['draw'])==false||isset($data['data']['start'])==false||isset($data['data']['length'])==false||isset($data['data']['register'])==false){
        $json_ret=array("draw"=>$draw,"recordsTotal"=>0,"recordsFiltered"=>0,"data"=>"");
        echo json_encode($json_ret);
        exit;
    }

    // check db exist
    $currY=date('Y');
    $currM=date('m');
    //if ($currM>=10){$currY+=1;}
    $tbname="precepts_".$currY;
    check_precepts_db($tbname);

    $draw=$data['data']['draw'];
    $start=$data['data']['start'];
    $length=$data['data']['length'];
    $register=$data['data']['register'];

    //查詢登入會員資料
    $cond="1";
    if ($register==1){$cond="checkin=1";}
    $sql="select * from `".$tbname."` where ".$cond." order by `id` limit ".$start.",".($length);

    $record=mysql_query($sql);
    $numrows=mysql_num_rows($record);
    if($numrows<=0){
        $json_ret=array("draw"=>$draw,"recordsTotal"=>0,"recordsFiltered"=>0,"data"=>"");
        echo json_encode($json_ret);
        exit;
    }

    while($row=mysql_fetch_array($record, MYSQL_ASSOC))
    {
        $member[] = $row;
    }

    $sql="select COUNT(*) from `".$tbname."` where ".$cond;
    $record=mysql_query($sql);
    $totalrows=100;
    $numrows=mysql_num_rows($record);
    if($numrows>0){
        $row1=mysql_fetch_array($record, MYSQL_NUM);
        $totalrows=$row1[0];
    }

    $code=1;
    $desc="success";
    $json_ret=array("draw"=>$draw,"recordsTotal"=>$totalrows,"recordsFiltered"=>$totalrows,"data"=>$member);
    echo json_encode($json_ret);//header("Content-Type: text/html; charset=utf-8");
?>

