<?php
    session_start();
    if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||$_SESSION["precepts_area"]!="zucamp"){
        $code=-2;
        $desc="auth failed";
        $json_ret=array("code"=>$code,"desc"=>$desc);echo json_encode($json_ret);exit;
    }

    header("Content-type: application/json; charset=utf-8");
    require_once("../lib/connmysql.php");
    ini_set("error_reporting",0);
    ini_set("display_errors","Off"); // On : open, O

    $code=-1;
    $desc="data unknown";
/*
    $data=json_decode(file_get_contents('php://input'), true);

    if(isset($data['data']['draw'])==false||isset($data['data']['start'])==false||isset($data['data']['length'])==false||isset($data['data']['register'])==false){
        $json_ret=array("code"=>$code,"desc"=>$data);echo json_encode($json_ret);exit;
    }
    $draw=$data['data']['draw'];
    $start=$data['data']['start'];
    $length=$data['data']['length'];
    $register=$data['data']['register'];
*/

    $traffitem=array("台北專車","桃園專車","新竹專車","台中專車","台南專車","高雄專車","火車站接駁","自行前往","");//台北專車,桃園專車,新竹專車,台中專車,台南專車,高雄專車,火車站接駁,自行前往
    $trafffee=array(500,460,450,275,200,150,0,0,0);

    $sql="select * from `student` order by `id`";
    $record=mysql_query($sql);
    $numrows=mysql_num_rows($record);
    if($numrows<=0){
        $code=0;
        $desc="data not found";
        $json_ret=array("code"=>$code,"desc"=>$desc);echo json_encode($json_ret);exit;
    }

    while($row=mysql_fetch_array($record, MYSQL_ASSOC))
    {
        $gofee=0;$backfee=0;$realgofee=0;$realbackfee=0;$diff=0;$cost=0;

        for($i=0;$i<count($traffitem);$i++){
            if($row["go"]==$traffitem[$i]){$gofee=$trafffee[$i];}
            if($row["back"]==$traffitem[$i]){$backfee=$trafffee[$i];}
            if($row["realgo"]==$traffitem[$i]){$realgofee=$trafffee[$i];}
            if($row["realback"]==$traffitem[$i]){$realbackfee=$trafffee[$i];}
            if($gofee>0&&$backfee>0&&$realgofee>0&&$realbackfee>0){ // 都找到相關車次的費用了
                break;
            }
        }
        $command[]="update `student` set `costgo`=".$realgofee.",`costback`=".$realbackfee." where `id`=".$row["id"];
    }

    // execute all command
    mysql_query("SET autocommit=0");
    for($i=0;$i<count($command);$i++){
        $ret=mysql_query($command[$i]);
    }
    mysql_query("COMMIT");

    $code=1;
    $desc="success";
    $json_ret=array("sql"=>$command);
    echo json_encode($json_ret);//header("Content-Type: text/html; charset=utf-8");
?>

