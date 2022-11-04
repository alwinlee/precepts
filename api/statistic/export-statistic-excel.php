<?php
    header("Content-Type: text/html; charset=utf-8");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-download");
    header("Content-Type: application/download");

    session_start();
    if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||$_SESSION["precepts_area"]!="precepts"){
        $code=-2;
        $desc="auth failed";
        $json_ret=array("code"=>$code,"desc"=>$desc);echo json_encode($json_ret);exit;
    }

    set_time_limit(1200); // page execution time = 1200 seconds

    ini_set("error_reporting", 0); //error_reporting(E_ALL & ~E_NOTICE);
    ini_set("display_errors","Off"); // On : open, Off : close
    ini_set('memory_limit', -1 );

    date_default_timezone_set('Asia/Taipei');//	date_default_timezone_set('Europe/London');
    if (PHP_SAPI=='cli'){die('This example should only be run from a Web Browser');}

    require_once("../lib/connmysql.php");
    require_once("../lib/common.php");
    require_once("../../resource/Classes/PHPExcel.php"); // PHPExcel // require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
    require_once("../../resource/Classes/PHPExcel/IOFactory.php"); // PHPExcel_IOFactory

    // check db exist
    $currY=date('Y');
    $currM=date('m');
    //if ($currM>=10){$currY+=1;}
    $tbname="precepts_".$currY;
    check_precepts_db($tbname);
    $type=$_POST["type"];

    $dateCurr=date('Y');
    $table_title=$dateCurr."受戒法會 報到統計";

    //------------------------------------------------------------------------------------------------------------------------------
    // Create new PHPExcel object
    $nSheet=0;
    $objPHPExcel=new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                 ->setLastModifiedBy("Maarten Balliauw")
                                 ->setTitle("Office 2007 XLSX Test Document")
                                 ->setSubject("Office 2007 XLSX Test Document")
                                 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                 ->setKeywords("office 2007 openxml php")
                                 ->setCategory("Test result file");
    $objWorkSheet=$objPHPExcel->setActiveSheetIndex($nSheet);

    $col=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
              "AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
              "BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ",
              "CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ");

    $xlstitle=array("項目","統計數據");
    $xlstitleW=array(40,20);

    $mainitem=-1;//21;
    $roundcnt=1; // 2: 考慮去/回
    $top=3;
    // each sub title
    for($w=0;$w<count($xlstitle);$w++)
    {
        $mainitem++;$item=$col[$mainitem].$top.":".$col[$mainitem].($top+$roundcnt);
        if ($xlstitle[$mainitem]!=""){
            $objWorkSheet->mergeCells($item);$item=$col[$mainitem].$top;
            $objWorkSheet->setCellValue($item,$xlstitle[$mainitem]);
        }
        $objWorkSheet->getStyle($item)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWorkSheet->getStyle($item)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $item=$col[$mainitem].($top-1);
        $objWorkSheet->getStyle($item)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWorkSheet->getStyle($item)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
     }
    // end ---

    $item="A1:".$col[$mainitem]."1";
    // main title
    $objWorkSheet->mergeCells($item);
    $objWorkSheet->setCellValue("A1",$table_title); //合併後的儲存格
    $objWorkSheet->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objWorkSheet->getStyle("A1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objWorkSheet->getStyle("A1")->getFont()->setSize(16);
    $objWorkSheet->getStyle("A1")->getFont()->setBold(true);
    $objWorkSheet->getRowDimension("1")->setRowHeight(30);
    $objWorkSheet->getRowDimension("3")->setRowHeight(20);
    if($roundcnt>=2){$objWorkSheet->getRowDimension("4")->setRowHeight(20);$objWorkSheet->getRowDimension("5")->setRowHeight(20);}
    else{$objWorkSheet->getRowDimension("4")->setRowHeight(40);}

    $idx=0;
    $iRow=$top+$roundcnt;

    // 填寫資料
    $reg_s=0;//高區報名人數
    $reg_checkin_s=0;//高區有報名的報到人數
    $not_reg_s=0;//高區未報名人數
    $not_reg_checkin_s=0;//高區未報名的報到人數
    $rates_reg_checkin_s=0;//高區報名報到率
    $rates_not_reg_checkin_s=0;//高區報名報到率

    $reg_ns=0;//他區報名人數
    $reg_checkin_ns=0;//他區有報名的報到人數
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

    $reg_n=0;//北區報名人數
    $reg_checkin_n=0;//北區有報名的報到人數
    $not_reg_n=0;//北區未報名人數
    $not_reg_checkin_n=0;//北區未報名的報到人數
    $rates_reg_checkin_n=0;//北區報名報到率
    $rates_not_reg_checkin_n=0;//北區報名報到率

    $reg_c=0;//中區報名人數
    $reg_checkin_c=0;//中區有報名的報到人數
    $not_reg_c=0;//中區未報名人數
    $not_reg_checkin_c=0;//中區未報名的報到人數
    $rates_reg_checkin_c=0;//中區報名報到率
    $rates_not_reg_checkin_c=0;//中區報名報到率

    $reg_j=0;//嘉區報名人數
    $reg_checkin_j=0;//嘉區有報名的報到人數
    $not_reg_j=0;//嘉區未報名人數
    $not_reg_checkin_j=0;//嘉區未報名的報到人數
    $rates_reg_checkin_j=0;//嘉區報名報到率
    $rates_not_reg_checkin_j=0;//嘉區報名報到率

    $reg_y=0;//園區報名人數
    $reg_checkin_y=0;//園區有報名的報到人數
    $not_reg_y=0;//園區未報名人數
    $not_reg_checkin_y=0;//園區未報名的報到人數
    $rates_reg_checkin_y=0;//園區報名報到率
    $rates_not_reg_checkin_y=0;//園區報名報到率

	$reg_tn=0;//南區報名人數
    $reg_checkin_tn=0;//南區有報名的報到人數
    $not_reg_tn=0;//南區未報名人數
    $not_reg_checkin_tn=0;//南區未報名的報到人數
    $rates_reg_checkin_tn=0;//南區報名報到率
    $rates_not_reg_checkin_tn=0;//南區報名報到率

	$reg_ov=0;//海外報名人數
    $reg_checkin_ov=0;//海外有報名的報到人數
    $not_reg_ov=0;//海外未報名人數
    $not_reg_checkin_ov=0;//海外未報名的報到人數
    $rates_reg_checkin_ov=0;//海外報名報到率
    $rates_not_reg_checkin_ov=0;//海外報名報到率

	$reg_zen=0;//淨人報名人數
    $reg_checkin_zen==0;//淨人有報名的報到人數
    $not_reg_zen==0;//淨人未報名人數
    $not_reg_checkin_zen==0;//淨人未報名的報到人數
    $rates_reg_checkin_zen==0;//淨人報名報到率
    $rates_not_reg_checkin_zen==0;//淨人報名報到率

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

    //北區
    $sql="select COUNT(*) from `".$tbname."` where (`area`='北區' AND `register`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_n=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='北區' AND `register`=1 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_checkin_n=$row[0];
    if ($reg_n>0) {$rates_reg_checkin_n=($reg_checkin_n*100/$reg_n);}

    $sql="select COUNT(*) from `".$tbname."` where (`area`='北區' AND `register`=0)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_n=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='北區' AND `register`=0 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_checkin_n=$row[0];
    if ($not_reg_n>0) {$rates_not_reg_checkin_n=($not_reg_checkin_n*100/$not_reg_n);}

    //中區
    $sql="select COUNT(*) from `".$tbname."` where (`area`='中區' AND `register`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_c=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='中區' AND `register`=1 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_checkin_c=$row[0];
    if ($reg_c>0) {$rates_reg_checkin_c=($reg_checkin_c*100/$reg_c);}

    $sql="select COUNT(*) from `".$tbname."` where (`area`='中區' AND `register`=0)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_c=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='中區' AND `register`=0 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_checkin_c=$row[0];
    if ($not_reg_c>0) {$rates_not_reg_checkin_c=($not_reg_checkin_c*100/$not_reg_c);}

    //嘉區
    $sql="select COUNT(*) from `".$tbname."` where (`area`='嘉區' AND `register`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_j=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='嘉區' AND `register`=1 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_checkin_j=$row[0];
    if ($reg_j>0) {$rates_reg_checkin_j=($reg_checkin_j*100/$reg_j);}

    $sql="select COUNT(*) from `".$tbname."` where (`area`='嘉區' AND `register`=0)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_j=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='嘉區' AND `register`=0 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_checkin_j=$row[0];
    if ($not_reg_j>0) {$rates_not_reg_checkin_j=($not_reg_checkin_j*100/$not_reg_j);}

    //園區
    $sql="select COUNT(*) from `".$tbname."` where (`area`='園區' AND `register`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_y=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='園區' AND `register`=1 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_checkin_y=$row[0];
    if ($reg_y>0) {$rates_reg_checkin_y=($reg_checkin_y*100/$reg_y);}

    $sql="select COUNT(*) from `".$tbname."` where (`area`='園區' AND `register`=0)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_y=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='園區' AND `register`=0 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_checkin_y=$row[0];
    if ($not_reg_y>0) {$rates_not_reg_checkin_y=($not_reg_checkin_y*100/$not_reg_y);}


    //南區
    $sql="select COUNT(*) from `".$tbname."` where (`area`='南區' AND `register`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_tn=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='南區' AND `register`=1 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_checkin_tn=$row[0];
    if ($reg_tn>0) {$rates_reg_checkin_tn=($reg_checkin_tn*100/$reg_tn);}

    $sql="select COUNT(*) from `".$tbname."` where (`area`='南區' AND `register`=0)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_tn=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='南區' AND `register`=0 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_checkin_tn=$row[0];
    if ($not_reg_tn>0) {$rates_not_reg_checkin_tn=($not_reg_checkin_tn*100/$not_reg_tn);}

	//海外
    $sql="select COUNT(*) from `".$tbname."` where (`area`='海外' AND `register`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_ov=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='海外' AND `register`=1 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_checkin_ov=$row[0];
    if ($reg_ov>0) {$rates_reg_checkin_ov=($reg_checkin_ov*100/$reg_ov);}

    $sql="select COUNT(*) from `".$tbname."` where (`area`='海外' AND `register`=0)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_ov=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='海外' AND `register`=0 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_checkin_ov=$row[0];
    if ($not_reg_ov>0) {$rates_not_reg_checkin_ov=($not_reg_checkin_ov*100/$not_reg_ov);}

	//淨人
    $sql="select COUNT(*) from `".$tbname."` where (`area`='淨人' AND `register`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_zen=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='淨人' AND `register`=1 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$reg_checkin_zen=$row[0];
    if ($reg_zen>0) {$rates_reg_checkin_zen=($reg_checkin_zen*100/$reg_zen);}

    $sql="select COUNT(*) from `".$tbname."` where (`area`='淨人' AND `register`=0)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_zen=$row[0];
    $sql="select COUNT(*) from `".$tbname."` where (`area`='淨人' AND `register`=0 AND `checkin`=1)";
    $record=mysql_query($sql);$row=mysql_fetch_array($record, MYSQL_NUM);$not_reg_checkin_zen=$row[0];
    if ($not_reg_zen>0) {$rates_not_reg_checkin_zen=($not_reg_checkin_zen*100/$not_reg_zen);}

    $c=0;
    $statistic[$c]["item"]="高區-有報名的人數";
    $statistic[$c++]["value"]=$reg_s;
    $statistic[$c]["item"]="高區-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_s;
    $statistic[$c]["item"]="高區-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_s,2)."%";
    $statistic[$c]["item"]="高區-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_s;
    $statistic[$c]["item"]="高區-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_s;
    $statistic[$c]["item"]="高區-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_s,2)."%";

    $statistic[$c]["item"]="其他區-有報名的人數";
    $statistic[$c++]["value"]=$reg_ns;
    $statistic[$c]["item"]="其他區-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_ns;
    $statistic[$c]["item"]="其他區-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_ns,2)."%";
    $statistic[$c]["item"]="其他區-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_ns;
    $statistic[$c]["item"]="其他區-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_ns;
    $statistic[$c]["item"]="其他區-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_ns,2)."%";

    $statistic[$c]["item"]="全區-有報名的人數";
    $statistic[$c++]["value"]=$reg_all;
    $statistic[$c]["item"]="全區-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_all;
    $statistic[$c]["item"]="全區-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_all,2)."%";
    $statistic[$c]["item"]="全區-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_all;
    $statistic[$c]["item"]="全區-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_all;
    $statistic[$c]["item"]="全區-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_all,2)."%";

    //北區
    $statistic[$c]["item"]="北區-有報名的人數";
    $statistic[$c++]["value"]=$reg_n;
    $statistic[$c]["item"]="北區-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_n;
    $statistic[$c]["item"]="北區-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_n,2)."%";
    $statistic[$c]["item"]="北區-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_n;
    $statistic[$c]["item"]="北區-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_n;
    $statistic[$c]["item"]="北區-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_n,2)."%";

    //中區
    $statistic[$c]["item"]="中區-有報名的人數";
    $statistic[$c++]["value"]=$reg_c;
    $statistic[$c]["item"]="中區-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_c;
    $statistic[$c]["item"]="中區-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_c,2)."%";
    $statistic[$c]["item"]="中區-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_c;
    $statistic[$c]["item"]="中區-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_c;
    $statistic[$c]["item"]="中區-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_c,2)."%";

    //嘉區
    $statistic[$c]["item"]="嘉區-有報名的人數";
    $statistic[$c++]["value"]=$reg_j;
    $statistic[$c]["item"]="嘉區-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_j;
    $statistic[$c]["item"]="嘉區-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_j,2)."%";
    $statistic[$c]["item"]="嘉區-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_j;
    $statistic[$c]["item"]="嘉區-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_j;
    $statistic[$c]["item"]="嘉區-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_j,2)."%";

    //園區
    $statistic[$c]["item"]="園區-有報名的人數";
    $statistic[$c++]["value"]=$reg_y;
    $statistic[$c]["item"]="園區-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_y;
    $statistic[$c]["item"]="園區-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_y,2)."%";
    $statistic[$c]["item"]="園區-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_y;
    $statistic[$c]["item"]="園區-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_y;
    $statistic[$c]["item"]="園區-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_y,2)."%";

	//南區
    $statistic[$c]["item"]="南區-有報名的人數";
    $statistic[$c++]["value"]=$reg_tn;
    $statistic[$c]["item"]="南區-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_tn;
    $statistic[$c]["item"]="南區-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_tn,2)."%";
    $statistic[$c]["item"]="南區-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_tn;
    $statistic[$c]["item"]="南區-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_tn;
    $statistic[$c]["item"]="南區-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_tn,2)."%";

	//海外
    $statistic[$c]["item"]="海外-有報名的人數";
    $statistic[$c++]["value"]=$reg_ov;
    $statistic[$c]["item"]="海外-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_ov;
    $statistic[$c]["item"]="海外-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_ov,2)."%";
    $statistic[$c]["item"]="海外-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_ov;
    $statistic[$c]["item"]="海外-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_ov;
    $statistic[$c]["item"]="海外-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_ov,2)."%";

	//淨人
    $statistic[$c]["item"]="淨人-有報名的人數";
    $statistic[$c++]["value"]=$reg_zen;
    $statistic[$c]["item"]="淨人-有報名的報到人數";
    $statistic[$c++]["value"]=$reg_checkin_zen;
    $statistic[$c]["item"]="淨人-有報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_reg_checkin_zen,2)."%";
    $statistic[$c]["item"]="淨人-未報名的人數";
    $statistic[$c++]["value"]=$not_reg_zen;
    $statistic[$c]["item"]="淨人-未報名的報到人數";
    $statistic[$c++]["value"]=$not_reg_checkin_zen;
    $statistic[$c]["item"]="淨人-未報名的報到率";
    $statistic[$c++]["value"]=number_format($rates_not_reg_checkin_zen,2)."%";

    for($i=0;$i<count($statistic);$i++)
    {
        $idx++;$iRow++;$c=0;
        $objWorkSheet->setCellValue($col[$c].$iRow,$statistic[$i]["item"])
                     ->setCellValue($col[++$c].$iRow,$statistic[$i]["value"]);
    }

    $iRow+=1;
    // SUM OF VALUE
    /*$sumitem=array($col[2],$col[3]);
    for($w=0;$w<count($sumitem);$w++)
    {
        $item="=SUM(".$sumitem[$w].($top+$roundcnt).":".$sumitem[$w].($iRow-1).")";
        $objWorkSheet->setCellValue($sumitem[$w].$iRow,$item);
        $objWorkSheet->setCellValue($sumitem[$w].($top-1),$item);
    }*/

    $item="D".($top+$roundcnt+1);
    $objWorkSheet->freezePane($item);
    // 設定欄位寛度
    for($w=0;$w<count($xlstitle);$w++){$objWorkSheet->getColumnDimension($col[$w])->setWidth($xlstitleW[$w]);}//$xlstitleW[$w]

     // set border
    $range="A".$top.":".$col[$mainitem].$iRow;
    $objWorkSheet->getStyle($range)->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    if ($roundcnt==2){$range="A3:".$col[$mainitem]."5";}else{$range="A3:".$col[$mainitem]."4";}
    $objWorkSheet->getStyle($range)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objWorkSheet->getStyle($range)->getFill()->getStartColor()->setRGB('DDFFDD');//$objWorkSheet->getStyle("A2")->getFill()->getStartColor()->setRGB('B7B7B7');

    $range="B5:B".$iRow;
    $objWorkSheet->getStyle($range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    // PERCENTAGE FORMAT
    $range="E".$top.":E".$iRow;
    $objPHPExcel->getActiveSheet()->getStyle($range)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
    $objWorkSheet->setTitle($table_title);// Rename worksheet
    //--------------------------------------------------------------------------------------------------------------------------------------------------
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');// Redirect output to a client’s web browser (Excel5)
    $fileheader="Content-Disposition: attachment;filename=\"".$table_title.".xls\"";//header('Content-Disposition: attachment;filename="simple.xls"');
    header($fileheader);
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');// If you're serving to IE 9, then the following may be needed

    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
?>