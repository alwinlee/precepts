<?php
session_start();
if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||isset($_SESSION["precepts_auth"])==false||$_SESSION["precepts_area"]!="precepts"){
    header("Location: ../index.php");
}
// check auth
require_once("../api/lib/common.php");
$auth=$_SESSION["precepts_auth"];
$userlevel=$_SESSION["precepts_userlevel"];
$groupexpire=$_SESSION["precepts_groupexpire"];
$expire=$_SESSION["precepts_expire1"];
$menu=checkAuth(0, $auth, $expire, $groupexpire);
if($menu!="YES"){
    header("Location: ../index.php");exit;
}

date_default_timezone_set('Asia/Taipei');
$currDate=date('Y-m-d');
$apply=$_SESSION["precepts_account"];
$debug="NO";
//$debug="YES";
?>
<!DOCTYPE html>
<html lang="en"><META content="IE=11.0000" http-equiv="X-UA-Compatible">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../resource/img/ucamp.ico">
    <link rel="shortcut icon" href="../resource/img/ucamp.ico">
    <title>受戒法會報到管理</title>
    <link href="../resource/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap Core CSS -->
    <link href="../resource/css/metisMenu.min.css" rel="stylesheet"><!-- MetisMenu CSS -->
    <link href="../resource/css/ucamp.css" rel="stylesheet"><!-- Custom CSS -->
    <link href="../resource/css/font-awesome.min.css" rel="stylesheet" type="text/css"><!-- Custom Fonts -->
    <style type="text/css">
    html, body{height:100%; margin:0;padding:0;font-family:Meiryo,"微軟正黑體","Microsoft JhengHei";}
    .container-fluid{height:90%;display:table;width:100%;padding:0;}
    .row-fluid{height:100%; display:table-cell; vertical-align: middle;}
    .centering{float:none;margin:0 auto;}
    .righting{float:right;margin:0 auto;}
    .sx-checkbox {width: 24px; height: 24px;}
    .mx-checkbox {width: 30px; height: 30px;}
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <?php include("menu.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12" align="center">
                        <h4 class="page-header">新增受戒學員</h4>

                    </div>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                <div class="col-lg-1" align="center"></div>
                <div class="col-lg-10" align="center">
                    <div class="col-lg-12" align="center" id="checkdata"> </div>
                    <div class="input-group  has-error">
                        <span class="input-group-addon" id="basic-lblname">姓  名 ： </span>
                        <input type="text" class="form-control" id="basic-name">

                        <span class="input-group-addon" id="basic-lblstudentid">學員代號 ： </span>
                        <input type="text" class="form-control" id="basic-studentid" placeholder="範例 : 3001688" aria-describedby="basic-lblclassroom">

                        <span class="input-group-addon" id="basic-lblsex">性  別 ： </span>
                        <select class="form-control" id="basic-sex">
                            <option value='0'>-</option>
                            <option value='M'>男</option>
                            <option value='F'>女</option>
                        </select>

                    </div>
                    <br>
                    <div class="input-group  has-error">
                        <span class="input-group-addon" id="basic-lblarea">學　　區 ： </span>
                        <select  class="form-control" id="basic-area">
                            <option value='0'>-</option>
                            <option value='A'>北區</option>
                            <option value='B'>桃區</option>
                            <option value='C'>竹區</option>
                            <option value='D'>中區</option>
                            <option value='E'>嘉區</option>
                            <option value='F'>園區</option>
                            <option value='G'>南區</option>
                            <option value='H'>高區</option>
                            <option value='I'>海外</option>
                        </select>

                        <span class="input-group-addon" id="basic-lblclassroom">班　級 ： </span>
                        <input type="text" class="form-control" id="basic-classroom" placeholder="範例 : 高15宗03-高雄" aria-describedby="basic-lblclassroom">

                        <span class="input-group-addon" id="basic-lblcourse">場  次 ： </span>
                        <input type="text" class="form-control" id="basic-course" placeholder="範例 : 上午場 (非必填)" aria-describedby="basic-lblcourse">
                    </div>
                    <br>
                    <div class="input-group  has-error">
                        <span class="input-group-addon" id="basic-lblserial">報到序號 ： </span>
                        <input type="text" class="form-control" id="basic-serial" placeholder="範例 : 高20168 (非必填)" aria-describedby="basic-lblserial">
                        <span class="input-group-addon" id="basic-lblregister">報  名 ： </span>
                        <select class="form-control" id="basic-register">
                            <option value='x'>-</option>
                            <option value='0'>無</option>
                            <option value='1'>有</option>
                        </select>
                        <span class="input-group-addon" id="basic-lblrookie">新  受： </span>
                        <select class="form-control" id="basic-rookie">
                            <option value='x'>-</option>
                            <option value='0'>否</option>
                            <option value='1'>是</option>
                        </select>
                    </div>
                    <br>
                </div>

                 <!-- /.row -->
                 <div class="row"><div class="col-lg-12" align="center"></div></div>
                 <hr>
                 <div class="row">
                     <div class="col-lg-1" align="center"></div>
                     <div class="col-lg-10" align="center">
                         <button type="button" class="btn btn-primary btn-lg btn-block" id="basic-submit">新　　增</button>
                     </div>
                     <div class="col-lg-1" align="center"></div>
                 </div>
                 <br><br><br><br><br><br>
                 <?php
                     if ($debug=="YES"){
                         echo "<div class=\"row\">";
                         echo "<div class=\"col-lg-4\" align=\"center\"></div>";
                         echo "<div class=\"col-lg-4\" align=\"center\">";
                         echo "<button type=\"button\" class=\"btn btn-primary btn-lg btn-block\" id=\"basic-testdata\">測試</button>";
                         echo "<button type=\"button\" class=\"btn btn-primary btn-lg btn-block\" id=\"basic-demo\">demo</button>";
                         echo "</div>";
                         echo "<div class=\"col-lg-4\" align=\"center\"></div>";
                         echo "</div>";
                     }
                 ?>
                 <!---->
                 <!-- /.value -->
                 <?php include("data.php"); ?>
                 <?php include("dialog.php"); ?>

                 <div class="col-lg-12" align="center" id="searchdata"></div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <script src="../resource/js/jquery-2.1.4.min.js"></script><!-- jQuery -->
    <script src="../resource/js/bootstrap.min.js"></script><!-- Bootstrap Core JavaScript -->
    <script src="../resource/js/metisMenu.min.js"></script><!-- Metis Menu Plugin JavaScript -->
    <script src="../resource/js/api.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}"></script>
    <script src="../resource/js/newin.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}" type="text/javascript" charset="utf-8"></script>
</body>

</html>
