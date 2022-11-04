<?php
    date_default_timezone_set('Asia/Taipei');
    echo "<div class=\"modal fade bs-example-modal-sm\" id=\"statusDataError\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\">";
    echo "<div class=\"modal-dialog modal-sm\">";
    echo "<div class=\"modal-content\">";
    echo "<div class=\"modal-header\">";
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
    echo "<h4 class=\"modal-title\" id=\"myModalLabel\">受戒法會學員報到</h4>";
    echo "</div>";
    echo "<div class=\"modal-body text-center\" id=\"errmsg\">";
    echo "資料未填寫完全!";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";                      

    echo "<div class=\"modal fade\" id=\"confirm-insert\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">";
    echo "<div class=\"modal-dialog\">";
    echo "<div class=\"modal-content\">";
    echo "<div class=\"modal-header\">";
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>";
    echo "<h4 class=\"modal-title\" id=\"myModalLabel\">學員已在名單(學員代號或年度編號重覆)</h4>";
    echo "</div>";
    echo "<div class=\"modal-body\" id=\"confirm-insert-data-information\">";
    echo "</div>";
    echo "<div class=\"modal-footer\">";
    //echo "<button type=\"button\" class=\"btn btn-default btn-ok\" id=\"btn-force-submit\">仍要報名</button>";
    echo "<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">取消</button>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

    echo "<div class=\"modal fade\" id=\"confirm-data\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">";
    echo "<div class=\"modal-dialog\">";
    echo "<div class=\"modal-content\">";
    echo "<div class=\"modal-header\">";
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>";
    echo "<h4 class=\"modal-title\" id=\"myModalLabel\">新增受戒學員</h4>";
    echo "</div>";
    echo "<div class=\"modal-body\" id=\"confirm-data-information\">";
    echo "</div>";
    echo "<div class=\"modal-footer\">";
    echo "<button type=\"button\" class=\"btn btn-danger\" id=\"confirm-data-information-ok\" data-dismiss=\"modal\">確定</button>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";


    echo "<div class=\"modal fade\" id=\"confirm-remove\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">";
    echo "<div class=\"modal-dialog\">";
    echo "<div class=\"modal-content\">";
    echo "<div class=\"modal-header\">";
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>";
    echo "<h4 class=\"modal-title\" id=\"myModalLabel\">刪除確認</h4>";
    echo "</div>";
    echo "<div class=\"modal-body\" id=\"confirm-remove-data-information\">";
    echo "請確認是否刪除受戒學員？</div>";
    echo "<div class=\"modal-footer\">";
    echo "<button type=\"button\" class=\"btn btn-danger btn-ok\" id=\"btn-remove-submit\">是</button>";
    echo "<button type=\"button\" class=\"btn btn-success\" id=\"btn-remove-cancel\" data-dismiss=\"modal\">否</button>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    