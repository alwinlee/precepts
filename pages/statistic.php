<?php
session_start();
if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||isset($_SESSION["precepts_auth"])==false||$_SESSION["precepts_area"]!="precepts"){
    header("Location: ../index.php");
}
// check auth
require_once("../api/lib/common.php");
$auth=$_SESSION["precepts_auth"];
$groupexpire=$_SESSION["precepts_groupexpire"];
$expire=$_SESSION["precepts_expire1"];
$menu=checkAuth(1, $auth, $expire, $groupexpire);
if($menu=="NO"){header("Location: ../index.php");exit;}
$groupkey="*";
if ($menu!="YES"){$groupkey=$menu;}

$user=$_SESSION["precepts_account"];
?>
<!DOCTYPE html>
<html lang="en">
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
    <link href="../resource/dataTables/css/jquery.dataTables.min.css" rel="stylesheet"><!-- MetisMenu CSS -->
    <link href="../resource/css/ucamp.css" rel="stylesheet"><!-- Custom CSS -->
    <link href="../resource/css/font-awesome.min.css" rel="stylesheet" type="text/css"><!-- Custom Fonts -->
    <style type="text/css">
    html, body{height:100%; margin:0;padding:0;font-family:Meiryo,"微軟正黑體","Microsoft JhengHei";}
    .container-fluid{height:90%;display:table;width:100%;padding:0;}
    .row-fluid{height:100%; display:table-cell; vertical-align: middle;}
    .centering{float:none;margin:0 auto;}
    .righting{float:right;margin:0 auto;}
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
                        <h3 class="page-header">資料查詢與匯出</h3>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-2" align="center"></div>
                    <div class="col-lg-8" align="center">
                        <div class="input-group custom-search-form">
                            <!--<input type="text" class="form-control" placeholder="請輸入組別、錄取編號、姓名、電話或學校關鍵字查詢 ..." id="keyword">-->
                            <select class="form-control" id="exporttype">
                              <?php
                                  echo "<option value=8>報到統計</option>";
                                  echo "<option value=7>報到學員</option>";
                                  echo "<option value=6>所有學員</option>";
                                  //echo "<option value=7>學員名冊(分區)</option>";
                                  if ($user=="root"){
                                      echo "<option value=10>報到單</option>";
                                  }

                                  //echo "<option value=11>報到單(中區)</option>";
                                  //echo "<option value=12>報到單(園區)</option>";
                                  //echo "<option value=13>報到單(雲嘉)</option>";
                                  //echo "<option value=14>報到單(南區)</option>";
                              ?>
                            </select>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="list">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="export">
                                    <i class="fa fa-download"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-2" align="center"></div>
                </div>
                 <!-- /.row -->
                 <div class="row"><div class="col-lg-12" align="center"><br></div></div>

                <div class="row">
                    <div class="col-lg-12" align="center" id="searchdata">
                    <table id="datagrid" class="table table-striped table-bordered" cellspacing="0" width="100%" style="display:none;" disabled>
                        <thead>
                            <th>報到</th>
                            <th>錄取編號</th>
                            <th>姓名</th>
                            <th>學校</th>
                            <th>科系</th>
                        </thead>
                    </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12" align="center" id="searchdata">
                    </div>
                </div>
                 <!-- /.value -->

                 <?php
                     include("data.php");
                     echo "<input type='hidden' id='groupkey' class='groupkey' name='groupkey' value='".$groupkey."' />";
                 ?>
                 <?php include("dialog.php"); ?>
                 <!-- /.row show data-->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <script src="../resource/js/jquery-2.1.4.min.js"></script><!-- jQuery -->
    <script src="../resource/js/bootstrap.min.js"></script><!-- Bootstrap Core JavaScript -->
    <script src="../resource/js/metisMenu.min.js"></script><!-- Metis Menu Plugin JavaScript -->
    <script src="../resource/dataTables/js/jquery.dataTables.min.js"></script><!-- Metis Menu Plugin JavaScript -->
    <script src="../resource/js/api.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}" type="text/javascript" charset="utf-8"></script>
    <script src="../resource/js/statistic.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}" type="text/javascript" charset="utf-8"></script>
</body>

</html>
