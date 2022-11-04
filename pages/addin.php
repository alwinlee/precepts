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
                        <h4 class="page-header">新增義工報名</h4>

                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-1" align="center"></div>
                    <div class="col-lg-10" align="left">
                    <div class="panel panel-success">
                            <div class="panel-heading"> <h1 class="panel-title">報名填寫說明</h1></div>
                            <div class="panel-body">
                            <p>1. 一律網路報名，各欄均須填寫，請勿重複報名。</p>
                            <p>2. 邀請之親友，請填其姓名，若非廣論學員，「母班班別」欄填邀請人班級。</p>
                    </div>
                    </div>
                    </div>
                    <div class="col-lg-1" align="center"></div>
                </div>

                <!-- /.row -->
                <div class="row">
                <div class="col-lg-1" align="center"></div>
                <div class="col-lg-10" align="center">
                    <div class="col-lg-12" align="center" id="checkdata"> </div>
                    <div class="input-group  has-error">
                        <span class="input-group-addon" id="basic-lblname">姓　　名 ： </span>
                        <input type="text" class="form-control" id="basic-name">
                    </div>
                    <div class="input-group  has-error">
                        <span class="input-group-addon" id="basic-lbltel">電　　話 ： </span>
                        <input type="text" class="form-control" id="basic-tel" placeholder="範例 : 0911222333 或 079876543 或 079876543#123">
                        <span class="input-group-addon" id="basic-lblsex">性　　別 ： </span>
                        <select class="form-control" id="basic-sex">
                            <option value='0'>-</option>
                            <option value='M'>男</option>
                            <option value='F'>女</option>
                        </select>
                    </div>
                    <div class="input-group has-success">
                        <span class="input-group-addon" id="basic-lblage">年　　齡 ： </span>
                        <select class="form-control" id="basic-age">
                            <?php
                                echo "<option value=0>-</option>";
                                for($i=6;$i<=100;$i++) {
                                    echo "<option value=".$i.">".$i."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <!--<hr>-->
                    <div class="input-group  has-error">
                        <span class="input-group-addon" id="basic-lblarea">區　　別 ： </span>
                        <select  class="form-control" id="basic-area">
                            <option value='0'>-</option>
                            <option value='A'>北區</option>
                            <option value='B'>中區</option>
                            <option value='C'>雲嘉</option>
                            <option value='D'>園區</option>
                            <option value='E'>南區</option>
                            <option value='F'>海外</option>
                        </select>

                        <span class="input-group-addon" id="basic-lblclassroom">教　　室 ： </span>
                        <input type="text" class="form-control" id="basic-classarea" placeholder="範例 : 小港" aria-describedby="basic-lblclassroom">
                    </div>
                    <div class="input-group  has-error"><!---->
                        <span class="input-group-addon" id="basic-lblgroup">母班班別 ： </span>
                        <select style="width:25%" class="form-control" id="basic-clsarea">
                            <option value='0'>-</option>
                            <option value='A'>北</option>
                            <option value='B'>中</option>
                            <option value='C'>嘉</option>
                            <option value='D'>園</option>
                            <option value='E'>南</option>
                            <option value='F'>海外</option>
                        </select>
                        <select style="width:25% " class="form-control" id="basic-clsyear">
                            <?php
                                echo "<option value=0>-</option>";
                                $currY=date('Y')-2000;
                                for($i=$currY-6;$i<=$currY;$i++) {
                                    echo "<option value='".$i."'>".$i."</option>";
                                }
                            ?>
                        </select>
                        <select style="width:25% " class="form-control" id="basic-clsserial">
                            <option value='0'>-</option>
                            <option value='1'>春</option>
                            <option value='2'>秋</option>
                            <option value='3'>增</option>
                            <option value='4'>善</option>
                            <option value='5'>備</option>
                            <option value='6'>宗</option>
                        </select>
                        <select style="width:25% " class="form-control" id="basic-clsid">
                            <?php
                                echo "<option value=0>-</option>";
                                for($i=1;$i<=300;$i++) {
                                    $value = str_pad($i, 3, '0', STR_PAD_LEFT);
                                    echo "<option value='".$value."'>".$value."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <hr>
                    <div class="input-group  has-error">
                        <span class="input-group-addon" id="basic-lblgroup">義工組別 ： </span>
                        <select style="width:60%" class="form-control" id="basic-group">
                            <option value=0>-</option>
                            <option value=1>秘書大組</option>
                            <option value=2>教育大組</option>
                            <option value=3>庶務大組</option>
                            <option value=4>總務大組</option>
                            <option value=5>善法實踐</option>
                        </select>
                        <select style="width:40% " class="form-control" id="basic-subgroup">
                            <option value=0>-</option>
                        </select>
                    </div>
                    <div class="input-group  has-error">
                        <span class="input-group-addon" id="basic-lblgroup">參加日期 ： </span>
                            <div class="input-group-btn" style="width:19%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="01.29(日)初二" readonly>
                                <input type="checkbox" class="form-control sx-checkbox" id="basic-join1">
                            </div>
                            <div class="input-group-btn" style="width:8%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="住宿" readonly>
                                <input type="checkbox" class="form-control sx-checkbox" id="basic-live1">
                            </div>
                            <div class="input-group-btn" style="width:19%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="01.30(一)初三" readonly>
                                <input type="checkbox" class="form-control sx-checkbox" id="basic-join2">
                            </div>
                            <div class="input-group-btn" style="width:8%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="住宿" readonly>
                                <input type="checkbox" class="form-control sx-checkbox" id="basic-live2">
                            </div>
                            <div class="input-group-btn" style="width:19%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="01.31(二)初四" readonly>
                                <input type="checkbox" class="form-control sx-checkbox" id="basic-join3">
                            </div>
                            <div class="input-group-btn" style="width:8%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="住宿" readonly>
                                <input type="checkbox" class="form-control sx-checkbox" id="basic-live3">
                            </div>
                            <div class="input-group-btn" style="width:19%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="02.01(三)初五" readonly>
                                <input type="checkbox" class="form-control sx-checkbox" id="basic-join4">
                            </div>
                    </div>
                    <div class="input-group  has-success">
                        <span class="input-group-addon" id="basic-lblspecialcase">義工類別 ： </span>
                        <select class="form-control" id="basic-type">
                            <option value='0'>-</option>
                            <option value='1'>總護持</option>
                            <option value='2'>副總護持</option>
                            <option value='3'>大會助理</option>
                            <option value='4'>顧問</option>
                            <option value='5'>大組長</option>
                            <option value='6'>副大組長</option>
                            <option value='7'>大組助理</option>
                            <option value='8'>小組長</option>
                            <option value='9'>副小組長</option>
                            <option value='10'>義工</option>
                            <option value='11'>見習幹部</option>
                            <option value='12'>見習助理</option>
                        </select>
                        <span class="input-group-addon" id="basic-lblnofity">通知單發放 ： </span>
                        <select class="form-control" id="basic-nofity" aria-describedby="basic-lblnofity">
                            <option value=0>-</option>
                            <option value=1>研討母班</option>
                            <option value=2>各組組長</option>
                        </select>
                    </div>
                    <div class="input-group  has-success">
                        <span class="input-group-addon" id="basic-lblspecialcase">身體特殊狀況 ： </span>
                        <input type="text" class="form-control" id="basic-specialcase" placeholder="範例 : 無法搬重物">
                        <span class="input-group-addon" id="basic-lblrequest">住宿特殊希求 ： </span>
                        <input type="text" class="form-control" id="basic-request" placeholder="範例 : 需高床">
                    </div>
                    <hr>

                    <div class="input-group  has-success">
                        <span class="input-group-addon" id="basic-lblgroup">交通需求 ： </span>
                            <div class="input-group-btn" style="width:25%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="搭去程" readonly>
                                <input type="input" class="form-control" id="basic-trafficgo" placeholder="範例 : AC">
                            </div>

                            <div class="input-group-btn" style="width:25%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="搭回程" readonly>
                                <input type="input" class="form-control" id="basic-trafficback" placeholder="範例 : AC">
                            </div>
                            <?php
                                if($userlevel>=8) {
                                    echo "<div class=\"input-group-btn\" style=\"width:25%\">";
                                    echo "<input type=\"text\" style=\"text-align: center;\" class=\"form-control\" id=\"basic-d2\" value=\"已交車資\" readonly>";
                                    echo "<input type=\"checkbox\" class=\"form-control mx-checkbox\" id=\"basic-pay\">";
                                    echo "</div>";
                                }else{
                                    echo "<div class=\"input-group-btn\" style=\"width:25%\">";
                                    echo "<input type=\"text\" style=\"text-align: center;\" class=\"form-control\" id=\"basic-d2\" value=\"已交車資\" readonly>";
                                    echo "<input type=\"checkbox\" class=\"form-control mx-checkbox\" id=\"basic-pay\" disabled readonly>";
                                    echo "</div>";
                                    //echo "<input type=\"hidden\" class=\"basic-pay\" id=\"basic-pay\" value=0>";
                                }
                            ?>
                            <div class="input-group-btn" style="width:25%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-d2" value="自行往返" readonly>
                                <input type="checkbox" class="form-control mx-checkbox" id="basic-trafficself">
                            </div>
                    </div>
                    <div class="input-group  has-success">
                        <span class="input-group-addon" id="basic-lblgroup">前行打掃 ： </span>
                             <div class="input-group-btn" style="width:30%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-lbljoinclean1" value="01.14(六)" readonly>
                                <input type="checkbox" class="form-control mx-checkbox" id="basic-joinclean1">
                            </div>
                            <div class="input-group-btn" style="width:70%">
                                <input type="text" style="text-align: center;" class="form-control" id="basic-lbljoincleantraffic" value="搭車" readonly>
                                <input type="text" class="form-control" id="basic-joincleantraffic" placeholder="範例：AC (未填則為自行往返)">
                            </div>
                    </div>
                    <div class="input-group  has-success">
                        <span class="input-group-addon" id="basic-lblmemo">備　　註 ： </span>
                        <input type="text" class="form-control" id="basic-memo">
                    </div>
                    <div class="col-lg-1" align="center"></div>
                </div>

                 <!-- /.row -->
                 <div class="row"><div class="col-lg-12" align="center"></div></div>
                 <hr>
                 <div class="row">
                     <div class="col-lg-1" align="center"></div>
                     <div class="col-lg-10" align="center">
                         <button type="button" class="btn btn-primary btn-lg btn-block" id="basic-submit">報　　名</button>
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
    <script src="../resource/js/ucamp.js"></script><!-- Custom Theme JavaScript -->
    <script src="../resource/js/api.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}"></script>
    <script src="../resource/js/addin.js?{D6477554-DAA6-4C24-A6EA-A8E238F91AA6}" type="text/javascript" charset="utf-8"></script>
</body>

</html>
