<?php
    session_start();
    if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||$_SESSION["precepts_area"]!="precepts"){
        $json_ret=array("draw"=>$draw,"recordsTotal"=>0,"recordsFiltered"=>0,"data"=>"");
        echo json_encode($json_ret);
        exit;
    }

    set_time_limit(300); // page execution time = 1200 seconds
    header("Content-type: application/json; charset=utf-8");

    ini_set("error_reporting", 0); //error_reporting(E_ALL & ~E_NOTICE);
    ini_set("display_errors","Off"); // On : open, Off : close
    ini_set('memory_limit', -1 );

    require_once("../lib/connmysql.php");
    require_once("../lib/common.php");

    // check db exist
    $currY=date('Y');
    $currM=date('m');
    //if ($currM>=10){$currY+=1;}
    $tbname="precepts_".$currY;
    check_precepts_db($tbname);

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

    //查詢登入會員資料
    /*
    if($register>0){
        $sql="select * from `student` where `lack`>0 order by `id` limit ".$start.",".($length);
    }else{
        $sql="select * from `student` where `over`>0 order by `id` limit ".$start.",".($length);
    }

    $record=mysql_query($sql);
    $numrows=mysql_num_rows($record);
    if($numrows<=0){
        //$student[];
        $json_ret=array("draw"=>$draw,"recordsTotal"=>0,"recordsFiltered"=>0,"data"=>"");
        echo json_encode($json_ret);
        exit;
    }

    while($row=mysql_fetch_array($record, MYSQL_ASSOC))
    {
        $student[] = $row;
    }
    if($register>=0){
        $sql="select COUNT(*) from `student` where `lack`>0";
    }else{
        $sql="select COUNT(*) from where `over`>0";
    }
    $record=mysql_query($sql);
    $totalrows=100;
    $numrows=mysql_num_rows($record);
    if($numrows>0){
        $row1=mysql_fetch_array($record, MYSQL_NUM);
        $totalrows=$row1[0];
    }*/
    $reg_s=0;//高區報名人數
    $reg_checkin_s=0;//高區有報名的報到人數
    $not_reg_s=0;//高區未報名人數
    $not_reg_checkin_s=0;//高區未報名的報到人數
    $rates_reg_checkin_s=0;//高區報名報到率
    $rates_not_reg_checkin_s=0;//高區報名報到率

    $reg_ns=0;//他區未報名人數
    $reg_checkin_ns=0;//他區未報名的報到人數
    $not_reg_ns=0;//他區未報名人數
    $not_reg_checkin_ns=0;//他區未報名的報到人數
    $rates_reg_checkin_ns=0;//他區報名報到率
    $rates_not_reg_checkin_ns=0;//他區報名報到率

    $reg_all=0;//全區未報名人數
    $reg_checkin_all=0;//全區未報名的報到人數
    $not_reg_all=0;//全區未報名人數
    $not_reg_checkin_all=0;//全區未報名的報到人數
    $rates_reg_checkin_all=0;//全區報名報到率
    $rates_not_reg_checkin_all=0;//全區報名報到率

    //高區
    $sql="select COUNT(*) from `".$tbname."` where (`area`='高區' AND `register`=1)";
    $record=mysql_query($sql);
    $row=mysql_fetch_array($record, MYSQL_NUM);
    $reg_s=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='高區' AND `register`=1 AND `checkin`=1)";
    $record=mysql_query($sql);
    $row=mysql_fetch_array($record, MYSQL_NUM);
    $reg_checkin_s=$row[0];
    if ($reg_s>0) {
        $rates_reg_checkin_s=($reg_checkin_s*100/$reg_s);
    }

    $sql="select COUNT(*) from `".$tbname."` where (`area`='高區' AND `register`=0)";
    $record=mysql_query($sql);
    $row=mysql_fetch_array($record, MYSQL_NUM);
    $not_reg_s=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='高區' AND `register`=0 AND `checkin`=1)";
    $record=mysql_query($sql);
    $row=mysql_fetch_array($record, MYSQL_NUM);
    $not_reg_checkin_s=$row[0];
    if ($not_reg_s>0) {
        $rates_not_reg_checkin_s=($not_reg_checkin_s*100/$not_reg_s);
    }

    // 其他區
    $sql="select COUNT(*) from `".$tbname."` where (`area`!='高區' AND `register`=1)";
    $record=mysql_query($sql);
    $row=mysql_fetch_array($record, MYSQL_NUM);
    $reg_ns=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`!='高區' AND `register`=1 AND `checkin`=1)";
    $record=mysql_query($sql);
    $row=mysql_fetch_array($record, MYSQL_NUM);
    $reg_checkin_ns=$row[0];
    if ($reg_ns>0) {
        $rates_reg_checkin_ns=($reg_checkin_ns*100/$reg_ns);
    }

    $sql="select COUNT(*) from `".$tbname."` where (`area`!='高區' AND `register`=0)";
    $record=mysql_query($sql);
    $row=mysql_fetch_array($record, MYSQL_NUM);
    $not_reg_ns=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`!='高區' AND `register`=0 AND `checkin`=1)";
    $record=mysql_query($sql);
    $row=mysql_fetch_array($record, MYSQL_NUM);
    $not_reg_checkin_ns=$row[0];
    if ($not_reg_ns>0) {
        $rates_not_reg_checkin_ns=($not_reg_checkin_ns*100/$not_reg_ns);
    }

    // 全區
    $reg_all=$reg_s+$reg_ns;
    $reg_checkin_all=$reg_checkin_s+$reg_checkin_ns;
    if ($reg_all>0) {
        $rates_reg_checkin_all=($reg_checkin_all*100/$reg_all);
    }
    $not_reg_all=$not_reg_s+$not_reg_ns;
    $not_reg_checkin_all=$not_reg_checkin_s+$not_reg_checkin_ns;
    if ($not_reg_all>0) {
        $rates_not_reg_checkin_all=($not_reg_checkin_all*100/$not_reg_all);
    }

    $statistic[0]["item"]="高區-報名人數/報到人數/報到率";
    $statistic[0]["value"]=$reg_s."/".$reg_checkin_s."/".number_format($rates_reg_checkin_s,2)."%";
    $statistic[1]["item"]="高區-未報名人數/報到人數/報到率";
    $statistic[1]["value"]=$not_reg_s."/".$not_reg_checkin_s."/".number_format($rates_not_reg_checkin_s,2)."%";

    $statistic[2]["item"]="其他區-報名人數/報到人數/報到率";
    $statistic[2]["value"]=$reg_ns."/".$reg_checkin_ns."/".number_format($rates_reg_checkin_ns,2)."%";
    $statistic[3]["item"]="其他區-未報名人數/報到人數/報到率";
    $statistic[3]["value"]=$not_reg_ns."/".$not_reg_checkin_ns."/".number_format($rates_not_reg_checkin_ns,2)."%";

    $statistic[4]["item"]="全區-報名人數/報到人數/報到率";
    $statistic[4]["value"]=$reg_all."/".$reg_checkin_all."/".number_format($rates_reg_checkin_all,2)."%";
    $statistic[5]["item"]="全區-未報名人數/報到人數/報到率";
    $statistic[5]["value"]=$not_reg_all."/".$not_reg_checkin_all."/".number_format($rates_not_reg_checkin_all,2)."%";

    $code=1;
    $desc="success";
    $json_ret=array("draw"=>$draw,"recordsTotal"=>6,"recordsFiltered"=>6,"data"=>$statistic);
    echo json_encode($json_ret);//header("Content-Type: text/html; charset=utf-8");
?>

