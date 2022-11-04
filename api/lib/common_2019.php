<?php
  function check_precepts_db($tbname) {
    if ($tbname==""){return false;}
    $sql="SHOW TABLES LIKE '".$tbname."'";
    $sql_result=mysql_query($sql);
    $numrows = mysql_num_rows($sql_result);
    if ($numrows>=1){return true;}
    $sql ="CREATE TABLE IF NOT EXISTS `".$tbname."`(";
    $sql.="`id`        int(8) NOT NULL auto_increment,";
    $sql.="`barcode`   varchar(30) collate utf8_unicode_ci COMMENT '報到條碼',";
    $sql.="`serial`	   varchar(30) collate utf8_unicode_ci NOT NULL COMMENT '年度編號',";
    $sql.="`sex`	     varchar(4)  collate utf8_unicode_ci COMMENT '性別',";
    $sql.="`studentid` varchar(20) collate utf8_unicode_ci NOT NULL COMMENT '學員代號',";
    $sql.="`area`      varchar(4) collate utf8_unicode_ci COMMENT '區域',";
    $sql.="`classroom` varchar(12) collate utf8_unicode_ci COMMENT '母班班別',";
    $sql.="`name`      varchar(40) collate utf8_unicode_ci COMMENT '姓名',";
    $sql.="`rookie`    int(4) default 0 COMMENT '新受',";
    $sql.="`memo`      varchar(128) collate utf8_unicode_ci COMMENT '備註',";
    $sql.="`register`  int(4) default 0 COMMENT '是否報名',";
    $sql.="`apply`     int(4) default 0 COMMENT '參與',";
    $sql.="`checkin`   int(4) default 0 COMMENT '報到',";
    $sql.="`applydate` date default '1970-01-01' COMMENT '報到日期',";
    $sql.="`applyby`   varchar(20) collate utf8_unicode_ci COMMENT '報到者',";
    $sql.="PRIMARY KEY (`id`)";
    $sql.=")ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

    $sql_result=mysql_query($sql);
    return true;
  }

  function middleAlignment($objWorkSheet,$item,$text) {
    if ($text!=""){$objWorkSheet->setCellValue($item,$text);}
    $objWorkSheet->getStyle($item)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objWorkSheet->getStyle($item)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objWorkSheet->getStyle($item)->getAlignment()->setWrapText(true);
  }

  function checkAuth($idx, $auth, $expiredate, $groupexpire) {
    if ($auth[$idx]!="1"){
      return "NO"; // no auth
    }
    date_default_timezone_set('Asia/Taipei');
    $nowDate=date('Y-m-d');
    if ($nowDate<=$expiredate){
      return "YES"; //OK
    }
    if ($groupexpire[$idx]=="-"||$groupexpire[$idx]=="*"){
      return "NO";
    }
    return $groupexpire[$idx];
  }

  function getPDFtitle($title) {
    $invoice_title="<table border=\"0\"><tr><td style=\"width:510px;text-align:center;\"><h2>".$title."</h2></td>";
    $invoice_title.="<td style=\"width:170px;text-align:center;\">";
    $invoice_title.="<span style=\"font-size: 10pt;background-color:#E0E0E0;\">*請攜帶本通知單辦理報到*</span></td></tr></table>";
    return $invoice_title;
  }

  function getPDFstudent($class,$name,$params)
  {
    $student_info="<table border=\"0\"><tr>";
    $student_info.="<td style=\"width:60px;height:75px;text-align:left;\"><br><h3>班別：</h3></td>";
    $student_info.="<td style=\"width:280px;text-align:left;text-decoration:underline;\"><br><h3>".$class."&nbsp;&nbsp;&nbsp;&nbsp;".$name."&nbsp;&nbsp;大德</h3></td>";
    $student_info.="<td style=\"width:160px;text-align:right;\"><br><h3>報到序號：</h3></td>";
    $student_info.="<td style=\"width:180px;text-align:center;\"> <tcpdf method=\"write1DBarcode\" params=\"".$params."\" /> </td>";

    // EMPTY
    //$student_info.="<td style=\"width:280px;text-align:left;text-decoration:underline;\"><br><h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;大德</h3></td>";
    //$student_info.="<td style=\"width:160px;text-align:right;\"><br><h3></h3></td>";
    //$student_info.="<td style=\"width:180px;text-align:center;\">  </td>";

    $student_info.="</tr></table>";
    return $student_info;
  }

  function getPDFinfo($d, $m) {
    $info="<style>span{ color: black; font-size: 12pt; text-decoration:underline; background-color:#E0E0E0;}</style>";

    $info.="<table border=\"0\">";
    $info.="<tr><td style=\"width:30px;text-align:right;\">一、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="時間：".$d."，自行前往者<span>請於".$d."上午8:00前至現場服務台完成報到</span>。";
    $info.="</td></tr>";

    //$info.="<tr><td style=\"width:680px;height:4px;text-align:left;\">";
    //$info.="</td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="<span>為敬重受戒故，請大家準時參加。</span>";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";


    $info.="<tr><td style=\"width:30px;text-align:right;\">二、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="報到地點：<span style=\"font-size: 12pt;\">園區 宗仰大樓 1樓慈生藥局與里仁中間。</span>";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="<span style=\"font-size: 12pt;\">一張報到單領取一張受戒貼紙，請貼左胸上面入場，以利引導義工辨識。</span>。";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\">三、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="請注意下列事項：【搭遊覽車者，採車上報到】";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="1.請攜帶廣論、報到通知單、供養金、名牌、環保杯、身分證、健保卡。";
    $info.="</td></tr>";
    $info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="2.請勿攜帶任何食品，貴重手飾、物品，手機正行時請關機。";
    $info.="</td></tr>";
    //$info.="<tr><td style=\"width:30px;text-align:right;\"></td>";
    //$info.="<td style=\"width:650px;text-align:left;\">";
    //$info.="3.交通費用：(車資當天來回 290 元）";
    //$info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:30px;text-align:right;\">四、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="臨時有事不能參加，請儘速至各學支苑，教室或服務台辦理取消登記，以利車輛確定。";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";
    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr>";
    $info.="<td style=\"width:650px;text-align:right;\">";
    $info.="皈依圓根燈會 報名報到組 合十   ".$m;
    $info.="</td><td style=\"width:30px;text-align:right;\"></td></tr>";

    $info.="</table>";
    return $info;
  }

  function getPDFLn()
  {
    $html_line="<table><tr><td></td><td></td></tr></table>";
    return $html_line;
  }

  function getPDFSPLn()
  {
    $spLine="<table><tr><td style=\"color:#F0F0F0;text-align:center;\">";
    $spLine.="--------------------------------------------------------";
    $spLine.="--------------------------------------------------------";
    $spLine.="---------------------------</td></tr></table>";

    return $spLine;
  }


?>
