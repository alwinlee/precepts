<?php
    session_start();
    if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||$_SESSION["precepts_area"]!="zucamp"){
        $json_ret=array("draw"=>$draw,"recordsTotal"=>0,"recordsFiltered"=>0,"data"=>"");
        echo json_encode($json_ret);
        exit;
    }

    header("Content-type: application/json; charset=utf-8");
    require_once("../lib/connmysql.php");
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
    $draw=$data['data']['draw'];
    $start=$data['data']['start'];
    $length=$data['data']['length'];
    $register=$data['data']['register'];

    $sql="select `realback`,COUNT(Id) from `student` GROUP BY `realback`";
    $record=mysql_query($sql);

    while($row=mysql_fetch_array($record, MYSQL_ASSOC))
    {
        $student[] = $row;
    }

    $code=1;
    $desc="success";
    $json_ret=array("draw"=>$draw,"recordsTotal"=>6,"recordsFiltered"=>6,"data"=>$student);
    echo json_encode($json_ret);//header("Content-Type: text/html; charset=utf-8");
?>

