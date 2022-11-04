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
$menu=checkAuth(1, $auth, $expire, $groupexpire);
if($menu!="YES"){
    header("Location: ../index.php");exit;
}
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
    <link href="../resource/dataTables/css/jquery.dataTables.min.css" rel="stylesheet"><!-- MetisMenu CSS -->
    <link href="../resource/css/ucamp.css" rel="stylesheet"><!-- Custom CSS -->
    <link href="../resource/css/font-awesome.min.css" rel="stylesheet" type="text/css"><!-- Custom Fonts -->

    <style type="text/css">
    html, body{height:100%; margin:0;padding:0;font-family:Meiryo,"微軟正黑體","Microsoft JhengHei";}
    .container-fluid{height:90%;display:table;width:100%;padding:0;}
    .row-fluid{height:100%; display:table-cell; vertical-align: middle;}
    .centering{float:none;margin:0 auto;}
    .righting{float:right;margin:0 auto;}
    .sx-checkbox {width: 24px; height: 18px;}
    .mx-checkbox {width: 30px; height: 30px;}
    .sx-radio {width: 18px; height: 18px;}
    .mx-radio {width: 24px; height: 24px;}
    .lx-radio {width: 30px; height: 30px;}
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <?php include("menu.php"); ?>
        <input type='hidden' id='previous-keyword' class='previous-keyword' name='previous-keyword' value='' />
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12" align="center">
                        <h3 class="page-header">受戒學員報名設定</h3>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-2" align="center"></div>
                    <div class="col-lg-8" align="center">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="請輸入報到編號或學員代號或姓名關鍵字 ..." id="keyword">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="checkinsearch">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-2" align="center"></div>
                </div>

                <!-- /.row -->
                <div class="row"><div class="col-lg-12" align="center"><br></div></div>

                <div class="row">

                    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="registerReport">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">學員報名</h4>
                          </div>
                          <div class="modal-body text-center">
                            成功!
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="registerCancel">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">學員取消報名</h4>
                          </div>
                          <div class="modal-body text-center">
                            成功!
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="statusDataError">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">資料錯誤</h4>
                          </div>
                          <div class="modal-body text-center">
                            .......!
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-1" align="center"></div>
                    <div class="col-lg-10" align="center" id="searchdata"></div>
                    <div class="col-lg-1" align="center"></div>
                </div>
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
    <script src="../resource/js/api.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}"></script>
    <script src="../resource/js/registerin.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}" type="text/javascript" charset="utf-8"></script>
</body>

</html>
