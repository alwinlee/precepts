<?php
  function check_precepts_db($tbname) {
    if ($tbname==""){return false;}
    $sql="SHOW TABLES LIKE '".$tbname."'";
    $sql_result=mysql_query($sql);
    $numrows = mysql_num_rows($sql_result);
    if ($numrows>=1){return true;}
    $sql ="CREATE TABLE IF NOT EXISTS `".$tbname."`(";
    $sql.="`id`         int(8) NOT NULL auto_increment,";
    $sql.="`barcode`    varchar(30) collate utf8_unicode_ci COMMENT '報到條碼',";
    $sql.="`serial`     varchar(30) collate utf8_unicode_ci NOT NULL COMMENT '年度編號',";
    $sql.="`sex`	      varchar(4)  collate utf8_unicode_ci COMMENT '性別',";
    $sql.="`studentid`  varchar(20) collate utf8_unicode_ci NOT NULL COMMENT '學員代號',";
    $sql.="`area`	      varchar(4) collate utf8_unicode_ci COMMENT '區域',";
    $sql.="`classroom`  varchar(12) collate utf8_unicode_ci COMMENT '母班班別',";
    $sql.="`name`       varchar(40) collate utf8_unicode_ci COMMENT '姓名',";
    $sql.="`rookie`     int(4) default 0 COMMENT '新受',";
    $sql.="`course`     varchar(40) collate utf8_unicode_ci COMMENT '場次',";
    $sql.="`memo`       varchar(128) collate utf8_unicode_ci COMMENT '備註',";
    $sql.="`register`   int(4) default 0 COMMENT '是否報名',";
    $sql.="`apply`	    int(4) default 0 COMMENT '參與',";
    $sql.="`checkin`    int(4) default 0 COMMENT '報到',";
    $sql.="`applydate`  date default '1970-01-01' COMMENT '報到日期',";
    $sql.="`applyby`    varchar(20) collate utf8_unicode_ci COMMENT '報到者',";
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
    $invoice_title="<table border=\"0\"><tr><td style=\"width:490px;text-align:center;\"><h2>".$title."</h2></td>";
    $invoice_title.="<td style=\"width:190px;text-align:center;\">";
    $invoice_title.="<span style=\"font-size: 12pt;background-color:#E0E0E0;\">*請攜帶本通知單辦理報到*</span></td></tr></table>";
    return $invoice_title;
  }

  function getPDFstudent($class,$name,$params) {
    $student_info="<table border=\"0\"><tr>";
    $student_info.="<td style=\"width:60px;height:65px;text-align:left;\"><h3>班別：</h3><br><h3>姓名：</h3></td>";
    $student_info.="<td style=\"width:300px;text-align:left;text-decoration:underline;\"><h3>".$class."</h3><br><h3>".$name."&nbsp;&nbsp;大德</h3></td>";
    $student_info.="<td style=\"width:100px;text-align:right;\"><br><h5>報到序號：</h5></td>";
    $student_info.="<td style=\"width:220px;text-align:center;\"> <tcpdf method=\"write1DBarcode\" params=\"".$params."\" /> </td>";

    // EMPTY
    //$student_info.="<td style=\"width:280px;text-align:left;text-decoration:underline;\"><br><h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;大德</h3></td>";
    //$student_info.="<td style=\"width:160px;text-align:right;\"><br><h3></h3></td>";
    //$student_info.="<td style=\"width:180px;text-align:center;\">  </td>";

    $student_info.="</tr></table>";
    return $student_info;
  }

  function getPDFinfo_old($d, $m, $tm) {
    $info="<style>span{ color: black; font-size: 14pt; text-decoration:underline; background-color:#e0e0e0;} td{ font-size: 14pt;}</style>";

    $info.="<table border=\"0\">";
    $info.="<tr><td style=\"width:45px;text-align:right;\">一、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="時間：".$d."，自行前往者<span>請於 ".$tm." 前報到完畢</span>。";
    $info.="</td></tr>";

    //$info.="<tr><td style=\"width:680px;height:4px;text-align:left;\">";
    //$info.="</td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="<span>為敬重受戒故，請大家準時參加。</span>";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">二、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="報到地點：<span>園區宗仰大樓一樓里仁兩側</span>。";
    $info.="</td></tr>";
    $info.="<tr><td style=\"width:45px;text-align:right;\"> </td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="  ◆ 搭遊覽車者，慈生藥局旁報到入場<br>";
    $info.="  ◆ 自行前往者，鐵皮屋旁報到入場";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">三、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="報到方式：<span>一張報到單領取一張座位卡及受戒貼紙，憑座位卡入場。</span>";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">四、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="請攜帶：原子筆、廣論、報到通知單、供養金、名牌、環保杯、身分證、健保卡。";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">五、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="正行時：手機請關機。";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">六、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="配合政府秋冬防疫專案";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.=" ◆ 參加人員實聯制，遊覽車位和場內座位皆固定";
    $info.="</td></tr>";
    $info.="<tr><td style=\"width:45px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.=" ◆ 衛生防護措施";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width: 70px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="1. 出入大眾運輸、教育學習、宗教祭祀等場所，應佩帶口罩<br>";
    $info.="2. 入場量體溫、如果體溫  ≧38℃，安排就醫或返家休息<br>";
    $info.="3. 噴酒精進行手部消毒或勤洗手<br>";
    $info.="4. 保持社交距離<br>";
    $info.="5. 其他依政府規定應配合之防疫措施";
    $info.="</td></tr>";
    //$info.="<tr><td style=\"width:45px;text-align:right;\"></td>";
    //$info.="<td style=\"width:650px;text-align:left;\">";
    //$info.="3.交通費用：(車資當天來回 290 元）";
    //$info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">七、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="臨時有事無法參加者，請務必跟報名窗口取消報名。";
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

  function getPDFinfo($d, $m, $tm) {
    $info="<style>span{ color: black; font-size: 14pt; text-decoration:underline; background-color:#e0e0e0;} td{ font-size: 14pt;}</style>";

    $info.="<table border=\"0\">";
    $info.="<tr>";
    $info.="<td style=\"width:700px;text-align:left;\">報到須知</td>";
    $info.="</tr>";
    $info.="</table>";

    $info.="<table border=\"0\">";
    $info.="<tr><td style=\"width:45px;text-align:right;\">一、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="日期：".$d."  <span>".$tm." 前報到完畢。 為敬重受戒故，請準時參加！</span>。";
    $info.="</td></tr>";

    //$info.="<tr><td style=\"width:680px;height:4px;text-align:left;\">";
    //$info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">二、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="報到地點：園區宗仰大樓一樓慈生藥局前空地。";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">三、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="報到方式：<span>一張報到單領取一張受戒貼紙，憑受戒貼紙入場。</span>";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">四、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="請攜帶：報到通知單、供養金、名牌、環保杯、健保卡。";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">五、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="學員應配合防疫措施";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="一、衛生防護措施";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:75px;text-align:right;\"></td>";
    $info.="<td style=\"width:620px;text-align:left;\">";
    $info.="1.法會現場須全程配戴口罩配合相關防疫措施。";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="二、行前檢測事宜";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:75px;text-align:right;\"></td>";
    $info.="<td style=\"width:620px;text-align:left;\">";
    $info.="1.於正行前一天，請正行學員自行快篩，並拍下快篩檢測結果照片。";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:75px;text-align:right;\"></td>";
    $info.="<td style=\"width:620px;text-align:left;\">";
    $info.="2.正行當日：";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="◆ 自行開車前往者：請出示快篩陰之拍照紀錄，由保健組確認後，再到報到組報到。";
    $info.="</td></tr>";


    $info.="<tr><td style=\"width:45px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="◆ 搭乘遊覽車前往：車長會進行體溫量測；於上車前，出示快篩陰之拍照紀錄，由";
    $info.="</td></tr>";

    $info.="<tr><td style=\"width:200px;text-align:right;\"></td>";
    $info.="<td style=\"width:495px;text-align:left;\">";
    $info.="該車長確認後，始得上車前往會場。";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\"></td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="※敬請共同遵守防疫規定，代人著想，自利利他※";
    $info.="</td></tr>";

    $info.="<tr><td></td><td></td></tr>";

    $info.="<tr><td style=\"width:45px;text-align:right;\">六、</td>";
    $info.="<td style=\"width:650px;text-align:left;\">";
    $info.="臨時有事無法參加者，請務必跟報名窗口取消報名。";
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
