<?php
session_start();
if(isset($_SESSION["precepts_account"])==false||$_SESSION["precepts_account"]==""||$_SESSION["precepts_area"]!="precepts"){
  header("Location: login.html");
}
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
  .sx-checkbox {width: 24px; height: 24px;}
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

    <!-- Page Content -->
    <div id="page-wrapper">
      <div class="container-fluid">
        <br>
        <div class="row">
          <input id="members-import-file" type='file' style="display:none;">
          <div class="col-lg-12">
            <input id="members-dbtable-name" value="precepts_">
            <button class="btn btn-white" id="members-data-import" style="display: nonex;">
              選取檔案 &nbsp;&nbsp; <i class="ace-icon fa fa-folder-open-o icon-only red"></i>
            </button>
          </div>
        </div>
        <hr>

        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <input class="form-control" id="manage-export-command" value="" placeholder="command"> <br>
            <input class="" id="manage-export-date" value="11月20日(日)">
            <input class="" id="manage-export-month" value="2022.11">
            <input class="" id="manage-export-time" value="上午8點">
            <button class="btn btn-white" id="manage-export-report" style="display: nonex;">
              匯出報到單 &nbsp;&nbsp; <i class="ace-icon fa fa-check icon-only red"></i>
            </button>
          </div>
        </div>
         <!-- /.row -->
         <div class="row"><div class="col-lg-12" align="center"><br></div></div>

        <div class="row">
          <div class="col-lg-12" align="center" id="searchdata">
          </div>
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
  <script src="../resource/dataTables/js/jquery.dataTables.min.js"></script><!-- Metis Menu Plugin JavaScript -->

  <script src="../resource/js/js-xlsx/xlsx.full.min.js"></script>
  <script src="../resource/js/js-xlsx/jszip.js"></script>
  <script src="../resource/js/js-xlsx/Blob.js"></script>
  <script src="../resource/js/js-xlsx/FileSaver.js"></script>

  <script src="../resource/js/api.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}" type="text/javascript" charset="utf-8"></script>
  <script src="../resource/js/manage.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}" type="text/javascript" charset="utf-8"></script>
</body>

</html>
