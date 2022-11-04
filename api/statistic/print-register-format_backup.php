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

    set_time_limit(120);
    date_default_timezone_set('Asia/Taipei');//	date_default_timezone_set('Europe/London');
    if (PHP_SAPI=='cli'){die('This example should only be run from a Web Browser');}


    require_once("../lib/connmysql.php");
    require_once("../lib/common.php");
    require_once("../../resource/tcpdf/tcpdf.php"); // PHPExcel_IOFactory

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
    $pdf->SetMargins(8, 10, 8);//(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
    $barcodestyle = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => true,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0,0,0),
        'bgcolor' => false, //array(255,255,255),
        'text' => true,
        'font' => 'helvetica',
        'fontsize' => 8,
        'stretchtext' => 4
    );
    //$params = $pdf->serializeTCPDFtagParameters(array('20150001', 'S25', '', '', '', 18, 0.4, array('position'=>'S', 'border'=>false, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>false, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>2), 'N'));
    $params = $pdf->serializeTCPDFtagParameters(array('2016S0001', 'C39', '', '', '', 16, 0.4, $barcodestyle, 'N'));
    //$params = $pdf->serializeTCPDFtagParameters(array('20150001', 'S25', '', '', '', 18, 0.4, $barcodestyle, 'N'));
    //$params = $pdf->serializeTCPDFtagParameters(array('CODE 128', 'C128', '', '', '', 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));

    //$invoice_title="<table><tr><td style=\"width:680px;text-align:center;\"><h3>".$table_title."</h3></td></tr>";
    //$invoice_title.="<tr><td style=\"width:680px;\" align=\"right\"><span style=\"background-color:green;\"><h5>請攜帶本通知單辦理報到</h5></span></td></tr></table>";
    $invoice_title="<table border=\"0\"><tr><td style=\"width:510px;text-align:center;\"><h2>".$table_title."</h2></td>";
    $invoice_title.="<td style=\"width:170px;text-align:center;\">";
    $invoice_title.="<span style=\"font-size: 10pt;background-color:#E0E0E0;\">*請攜帶本通知單辦理報到*</span></td></tr></table>";

    $class="南10宗01-高雄";
    $student_name="王志榮";
    $student_info="<table border=\"0\"><tr>";
    $student_info.="<td style=\"width:60px;height:75px;text-align:left;\"><br><h3>班別：</h3></td>";
    $student_info.="<td style=\"width:280px;text-align:left;text-decoration:underline;\"><br><h3>".$class."&nbsp;&nbsp;&nbsp;&nbsp;".$student_name."&nbsp;&nbsp;大德</h3></td>";
    $student_info.="<td style=\"width:100px;text-align:right;\"><br><h3>報到序號：</h3></td>";
    $student_info.="<td style=\"width:240px;text-align:center;\"> <tcpdf method=\"write1DBarcode\" params=\"".$params."\" /> </td>";
    $student_info.="</tr></table>";

    $info="<style>
    span{
        color: black;
        font-size: 14pt;
        text-decoration:underline;
        background-color:#E0E0E0;
    }
    </style>";

    $info.="<table border=\"0\">";

    $info.="<tr><td style=\"width:30px;text-align:right;\">一、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="時間：11月26日(六)，自行前往者<span>請於11月26日上午8:00前至現場服務台完成報到</span>。";
    $info.="</td></tr>";

    //$info.="<tr><td style=\"width:680px;height:4px;text-align:left;\">";
    //$info.="</td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="<span>為敬重受戒故，請大家準時參加</span>。";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";


    $info.="<tr><td style=\"width:30px;text-align:right;\">二、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="報到地點：<span style=\"font-size: 12pt;\">園區 宗仰大樓 1樓慈生藥局與里仁中間，請務必領取貼紙並張貼左胸上面入場</span>，";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="<span style=\"font-size: 12pt;\">以利引導義工辨識</span>";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\">三、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="請注意下列事項：【搭遊覽車者，採車上報到】";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="1.請攜帶小本廣論、報到通知單、供養金、名牌、環保杯、身分證、健保卡。";
    $info.="</td></tr>";
    $info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="2.請勿攜帶任何食品，貴重手飾、物品，手機正行時請關機。";
    $info.="</td></tr>";
    $info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="3.交通費用：(車資當天來回 290 元）";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\">四、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="臨時有事不能參加，請儘速至高雄學苑3樓302教室或淨智服務台辦理取消登記，俾利車輛確定。";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr>";
    $info.="<td style=\"width:650px;text-align:right;\">";
    $info.="淨智組 合十   2016.11";
    $info.="</td><td style=\"width:30px;text-align:right;\"></td></tr>";

    $info.="</table>";



/*
四、臨時有事不能參加，請儘速至高雄學苑3樓302教室或淨智服務台辦理取消登記，俾利車輛確定。
    一、時間：12月5日(六)，自行前往者請於12月5日上午8:00前
至現場服務台完成報到。為敬重受戒故，請大家準時參加。
二、報到地點：園區 宗仰大樓1樓慈生藥局與里仁中間
請務必領取貼紙並張貼左胸上面入場，以利引導義工辨識
三、請注意下列事項： 【搭遊覽車者，採車上報到】
1.請攜帶小本廣論、報到通知單、供養金、名牌、環保杯、身分證、健保卡。
2.請勿攜帶任何食品，貴重手飾、物品，手機正行時請關機。
3.交通費用：(車資當天來回 300 元）
四、臨時有事不能參加，請儘速至高雄學苑3樓302教室或淨智服務台辦理取消登記，俾利車輛確定。

淨智組 合十   2015.11
*/
 $html='
    <style>
    span{
        color: navy;
        font-family: fruitb;
        font-size: 20pt;
    }
    p1 {
        color: red;
        font-family: fruit;
        font-size: 20pt;
    }
    </style>
    Normal text  <span>My text in bold</span>
    ';

    $html_line="<table><tr><td></td><td></td></tr></table>";
    $spLine="<table><tr><td style=\"color:#F0F0F0;text-align:center;\">----------------------------------------------------------------------------------------------------------------------------------------------</td></tr></table>";
    // ---------------------------------------------------------
    $pdf->AddPage();// add a page
    $pdf->SetFont('droidsansfallback', '', 12);
    //$pdf->writeHTML($sql, true, false, false, false, '');
    $pdf->writeHTML($invoice_title, true, false, false, false, '');
    $pdf->writeHTML($student_info, true, false, false, false, '');
    $pdf->writeHTML($info, true, false, false, false, '');


    $pdf->writeHTML($html_line, true, false, false, false, '');
    $pdf->writeHTML($spLine, true, false, false, false, '');
    //$pdf->writeHTML($html_line, true, false, false, false, '');
    //$pdf->writeHTML($html, true, false, true, false, '');
    //$pdf->writeHTML($str,true, false,false,false,'left');

    //$barcodestyle['position'] = 'R';
    //$pdf->write1DBarcode('20150001', 'S25', '', '', '', 18, 0.4, $barcodestyle, 'N');

    //$pdf->writeHTML("----------------------------------------------------------------------------------------------------------------------------------------------", true, false, false, false, '');

    $pdf->writeHTML($invoice_title, true, false, false, false, '');
    $pdf->writeHTML($student_info, true, false, false, false, '');
    $pdf->writeHTML($info, true, false, false, false, '');

    $filename = "receipt-list.pdf";//$classid.
    $pdf->Output($filename, 'D');

	//============================================================+
	// END OF FILE
	//============================================================+
?>