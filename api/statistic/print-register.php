<?php
    header("Content-Type: text/html; charset=utf-8");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-download");
    header("Content-Type: application/download");

    session_start();
    if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||$_SESSION["precepts_area"]!="precepts"){
        echo "-1";exit;
    }

    ini_set('memory_limit',-1);
    ini_set("error_reporting",0);
    ini_set("display_errors","Off"); // On : open, Off : close

    set_time_limit(1200);
    date_default_timezone_set('Asia/Taipei');//	date_default_timezone_set('Europe/London');
    if (PHP_SAPI=='cli'){die('This example should only be run from a Web Browser');}


    require_once("../lib/connmysql.php");
    require_once("../../resource/tcpdf/tcpdf.php");
    require_once("../lib/common.php");

    // page information
    $curY=date('Y');
    $table_title= $curY." 皈依暨圓根燈會【受五戒】報到通知單";

    // page setting
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Noman');
    $pdf->SetTitle($table_title);
    $pdf->SetSubject($table_title);

    $tablename = "";//"報到通知單";
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $tablename, $table_title);

    // set header and footer fonts
    $pdf->setHeaderFont(Array('droidsansfallback', 'center', 8));
    $pdf->setFooterFont(Array('droidsansfallback', 'right', 8));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(8, 13, 8);//(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // set font
    $pdf->SetFont('droidsansfallback','', 8);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // define barcode style
    $barcodestyle = array('position'=>'','align'=>'C','stretch'=>false,
                          'fitwidth'=>true,'cellfitalign'=>'','border'=>true,'hpadding'=>'auto',
                          'vpadding'=>'auto','fgcolor'=>array(0,0,0),
                          'bgcolor'=>false, //array(255,255,255),
                          'text'=>true,'font'=>'helvetica','fontsize'=>8,'stretchtext'=>4);

    $invoice_title=getPDFtitle($table_title);
    $info=getPDFinfo();
    $html_line=getPDFLn();
    $spLine=getPDFSPLn();

    // check db exist
    $currY=date('Y');
    $currM=date('m');
    //if ($currM>=10){$currY+=1;}
    $tbname="precepts_".$currY;
    check_precepts_db($tbname);
    //$sql="select * from `".$tbname."` where `area`='南區' order by `id` ";//" limit 10";
    //$sql="SELECT * FROM `".$tbname."` where `area` = '園區' order by `classroom` desc, `sex` desc, `studentid` asc;";
    //$sql="select * from `".$tbname."` where (`barcode` = 'H22265') order by `id` ";
    //$sql="SELECT * FROM `".$tbname."` WHERE (`register`=1 AND `applydate`='2018-11-14') order by `classroom` desc, `sex` desc, `studentid` asc;";
    //$sql="SELECT * FROM `".$tbname."` WHERE (`register`=1 AND `applydate`='2018-11-13') order by `classroom` desc, `sex` desc, `studentid` asc;";
    //$sql="SELECT * FROM `".$tbname."` WHERE (`name`='沈蔡秀蓮') order by `classroom` desc, `sex` desc, `studentid` asc;";
    //$sql="SELECT * FROM `".$tbname."` WHERE (`applydate`='1970-01-02') order by `classroom` desc, `sex` desc, `studentid` asc;";

    //$sub = "`studentid`='TW00103433' OR `studentid`='TW00103814' OR `studentid`='TW00104904' OR `studentid`='TW00108233' OR `studentid`='TW00103976' OR `studentid`='TW00119589' OR `studentid`='TW00118365' OR `studentid`='TW00107647' OR `studentid`='TW00120136'";
    //$sub = "`studentid`='TW00103433' OR `studentid`='TW00103814'";
    //$sub = "`studentid`='TW00106141' OR `studentid`='TW00112220' OR `studentid`='TW00121637' OR `studentid`='TW00104293' OR `studentid`='TW00104194' OR `studentid`='TW00119928' OR `studentid`='TW00137791' OR `studentid`='TW00143829' OR `studentid`='TW00106675' OR `studentid`='TW00110953' OR `studentid`='TW00122369' OR `studentid`='TW00138683' OR `studentid`='TW00119927' OR `studentid`='TW00123365' OR `studentid`='TW00143828' OR `studentid`='TW00106636' OR `studentid`='TW00108576' OR `studentid`='TW00120810' OR `studentid`='TW00124248' OR `studentid`='TW00139750' OR `studentid`='TW00126224'";
    $sub="`studentid`='TW00103008' OR `studentid`='TW00104039' OR `studentid`='TW00126975'";
    $sql="SELECT * FROM `".$tbname."` WHERE (".$sub.") order by `classroom` desc, `sex` desc, `studentid` asc;";
    $result=mysql_query($sql);

    $count=0;
    while($row=mysql_fetch_array($result,MYSQL_ASSOC))
    {
        if ($count%2==0){
            $pdf->AddPage();// add a page
            $pdf->SetFont('droidsansfallback', '', 12);
        }else{
            $pdf->writeHTML($html_line, true, false, false, false, '');
            $pdf->writeHTML($spLine, true, false, false, false, '');
        }
        $stu_class=$row["classroom"];
        $stu_name=$row["name"];
        $stu_barcode=$row["barcode"];
        $params=$pdf->serializeTCPDFtagParameters(array($stu_barcode, 'C39', '', '', '', 16, 0.4, $barcodestyle, 'N'));
        $student_info=getPDFstudent($stu_class,$stu_name,$params);

        $pdf->writeHTML($invoice_title, true, false, false, false, '');
        $pdf->writeHTML($student_info, true, false, false, false, '');
        $pdf->writeHTML($info, true, false, false, false, '');
        $count++;
    }


/*
    // ---------------------------------------------------------
    $pdf->AddPage();// add a page
    $pdf->SetFont('droidsansfallback', '', 12);
    //$pdf->writeHTML($sql, true, false, false, false, '');
    $pdf->writeHTML($invoice_title, true, false, false, false, '');
    $pdf->writeHTML($student_info, true, false, false, false, '');
    $pdf->writeHTML($info, true, false, false, false, '');


    $pdf->writeHTML($html_line, true, false, false, false, '');
    $pdf->writeHTML($spLine, true, false, false, false, '');

    $pdf->writeHTML($invoice_title, true, false, false, false, '');
    $pdf->writeHTML($student_info, true, false, false, false, '');
    $pdf->writeHTML($info, true, false, false, false, '');
*/




    $filename = "receipt-list.pdf";//$classid.
    $pdf->Output($filename, 'D');

	//============================================================+
	// END OF FILE
	//============================================================+
?>