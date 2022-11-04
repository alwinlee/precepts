<?php
    session_start();
    date_default_timezone_set('Asia/Taipei');
    require_once("../api/lib/common.php");

    echo "<nav class=\"navbar navbar-default navbar-static-top\" role=\"navigation\" style=\"margin-bottom: 0\">";
    echo "<div class=\"navbar-header\">";
    echo "<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\">";
    echo "<span class=\"sr-only\">Toggle navigation</span>";
    echo "<span class=\"icon-bar\"></span>";
    echo "<span class=\"icon-bar\"></span>";
    echo "<span class=\"icon-bar\"></span>";
    echo "</button>";
    echo "<a class=\"navbar-brand\" href=\"index.html\"><img src=\"../resource/img/ucamp_icon.png\"></a>";
    echo "</div>";
    echo "<ul class=\"nav navbar-top-links navbar-left\"> ";

    $currY=date('Y');
    $currM=date('m');
    //if ($currM>=10){$currY+=1;}

    echo "<li><a class=\"navbar-brand\" href=\"checkin.php\" >".$currY."年 受戒法會報到管理</a></li>";
    echo "</ul>";

    echo "<ul class=\"nav navbar-top-links navbar-right\">";
    echo "<li><a class=\"glyphicon glyphicon-log-out\" href=\".\logout.php\">     </a></li>";
    echo "</ul>";

    echo "<div class=\"navbar-default sidebar\" role=\"navigation\">";
    echo "<div class=\"sidebar-nav navbar-collapse\">";
    echo "<ul class=\"nav nav-first-level\" id=\"side-menu\">";

    //echo "<li class=\"sidebar-search\">";
    //echo "<div class=\"input-group custom-search-form\">";
    //echo "<input type=\"text\" class=\"form-control\" placeholder=\"查詢 ...\"  id=\"keyowrd\">";
    //echo "<span class=\"input-group-btn\">";
    //echo "<button class=\"btn btn-default\" type=\"button\"  id=\"search\">";
    //echo "<i class=\"fa fa-search\"></i>";
    //echo "</button>";
    //echo "</span>";
    //echo "</div>";
    //echo "</li>";

    if(isset($_SESSION["precepts_auth"])){
        $auth=$_SESSION["precepts_auth"];
        $groupexpire=$_SESSION["precepts_groupexpire"];

        $expire=$_SESSION["precepts_expire1"];
        $menu=checkAuth(0, $auth, $expire, $groupexpire);
        if($menu!="NO"&&$menu!=""){//if($auth[0]=="1"){
            echo "<li><a href=\"checkin.php\"><i class=\"glyphicon glyphicon-plus\"></i> 學員報到</a></li>";
        }
        $expire=$_SESSION["precepts_expire2"];
        $menu=checkAuth(0, $auth, $expire, $groupexpire);
        if($menu!="NO"&&$menu!=""){//if($auth[0]=="1"){
            echo "<li><a href=\"searchin.php\"><i class=\"glyphicon glyphicon-search\"></i> 學員查詢</a></li>";
        }
        $expire=$_SESSION["precepts_expire3"];
        $menu=checkAuth(2, $auth, $expire, $groupexpire);
        if($menu!="NO"&&$menu!=""){//if($auth[5]=="1"){
            echo "<li><a href=\"registerin.php\"><i class=\"glyphicon glyphicon-saved\"></i> 報名設定</a></li>";
        }

        $expire=$_SESSION["precepts_expire4"];
        $menu=checkAuth(2, $auth, $expire, $groupexpire);
        if($menu!="NO"&&$menu!=""){//if($auth[5]=="1"){
            echo "<li><a href=\"newin.php\"><i class=\"glyphicon glyphicon-check\"></i> 新增學員</a></li>";
        }

        $expire=$_SESSION["precepts_expire5"];
        $menu=checkAuth(2, $auth, $expire, $groupexpire);
        if($menu!="NO"&&$menu!=""){//if($auth[5]=="1"){
            echo "<li><a href=\"statistic.php\"><i class=\"glyphicon glyphicon-cloud-download\"></i> 匯出報表</a></li>";
        }

        $expire=$_SESSION["precepts_expire20"];
        $menu=checkAuth(10, $auth, $expire, $groupexpire);
        if($menu!="NO"&&$menu!=""){//if($auth[9]=="1"){
            echo "<li><a href=\"authmanage.php\"><i class=\"glyphicon glyphicon-user\"></i> 權限管理</a></li>";
            echo "<li><a href=\"manage.php\"><i class=\"glyphicon glyphicon-cog\"></i> 管理工具</a></li>";
        }
    }

    echo "<li class=\"sidebar-search\">";
    echo "<div class=\"input-group custom-search-form\">";
    echo "<span class=\"input-group-btn\">";
    echo "</span>";
    echo "</div>";
    echo "</li>";
    echo "<li><a href=\"logout.php\"><i class=\"glyphicon glyphicon-log-out\"></i> 登出</a></li>";
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    echo "</nav>";
