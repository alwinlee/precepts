$(document).ready(function() 
{
    // 統計數據功能：錄取人數，確認參加人數，報到人數，男女學員人數，總報到率(報到人數/確認參加人數)
    $('#datagrid').hide();
    $('#list').click(function(){
        if($('#datagrid').is(":visible")==false){
            $('#datagrid').show();
        }
        var type=$('#exporttype').val();
        if (type==6||type==7){
            var columns=[{"sTitle": "報到","mData": "checkindesc","aTargets": [0]},
                         {"sTitle": "姓名","mData": "name","aTargets": [1]},
                         {"sTitle": "性別","mData": "sex","aTargets": [2]},
                         {"sTitle": "學員代號","mData": "studentid","aTargets": [3]},
                         {"sTitle": "區別","mData": "area","aTargets": [4]},
                         {"sTitle": "班級","mData": "classroom","aTargets": [5]}];
            drawDataTable(columns,type);
        }else if (type==8){
            var columns=[{"sTitle": "項目","mData": "item","aTargets": [0]},
                         {"sTitle": "統計值","mData": "value","aTargets": [1]}];        
            drawDataTable(columns,type);
        }
    });
    
    $('#export').click(function(){
        var parameter="";
        var type=$('#exporttype').val();
        //var groupkey=$('#groupkey').val();
        //if(type==2||type==3){ // do something first 
        //    ucamp.statistic.updateFee(0, function(data){                
        //    },function(data){
        //    });
        //}
        parameter+='<input type="hidden" name="type" value="'+type+'" />"';
        //parameter+='<input type="hidden" name="groupkey" value="'+groupkey+'" />"';
        if (type==6){
            $('<form action="../api/statistic/export-all-excel.php" method="post">'+parameter+'</form>').appendTo('body').submit().remove();   
        }else if (type==7){
            $('<form action="../api/statistic/export-checkin-excel.php" method="post">'+parameter+'</form>').appendTo('body').submit().remove();   
        }else if (type==8){
            $('<form action="../api/statistic/export-statistic-excel.php" method="post">'+parameter+'</form>').appendTo('body').submit().remove();   
        }
        else if (type>=10&&type<20){
            $('<form action="../api/statistic/print-register.php" method="post">'+parameter+'</form>').appendTo('body').submit().remove();  
        }
              
    });
});

function drawDataTable(columns, type) 
{
    var oTable = $('#searchdata').html('<table id="datagrid" class="table table-striped table-bordered" cellspacing="0" width="100%"></table>').children('table').dataTable({
        "processing":true,
        "serverSide":true,
        "bPaginate":true,
        "bFilter":false,
        "aoColumnDefs": columns,
        "ordering":false,
        "pageLength":15,
        "lengthMenu":[20,25,50],
        "searching":false,
        "bLengthChange":false,
        "fnServerData":retrieveData,
        //"dom":'<"optoolbar"> frtip',
        "bDestroy": true,
        "language": {
            "processing": "資料查詢中...",
            "emptyTable": "無資料",
            "info": "顯示 _START_ 到 _END_ 條資料 共 _TOTAL_ 條資料",
            "infoEmpty": "無資料!",
            "infoFiltered": "(在 _MAX_ 條資料中查詢)",
            "lengthMenu": "顯示 _MENU_ 條資料",
            "search": "查詢:",
            "zeroRecords": "沒有找到對應的資料",
            "paginate": {
                "previous":"上一頁",
                "next": "下一頁",
                "last": "末頁",
                "first": "首頁"
            }
        }
    });
}

function retrieveData(sSource, aoData, fnCallback)
{
    var draw=0;
    var start=0;
    var len=0;
    for(i=0;i<aoData.length;i++){
        if(aoData[i].name=="draw"){draw=aoData[i].value;}
        if(aoData[i].name=="start"){start=aoData[i].value;}
        if(aoData[i].name=="length"){len=aoData[i].value;}
    }
    
    var register=0;
    var url="../api/statistic/findMember.php";
    var type=$('#exporttype').val();
    if (type==7){register=1;}
    if (type==8){register=0;url="../api/statistic/calcStatistic.php";}
    //alert(url);
    var data={data:{draw:draw,start:start,length:len,register:register}};
    var jdata = JSON.stringify(data);
    $.ajax({
        type:"POST",
        url:url,
        data:jdata,
        async: true,
	  dataType : "json",
	  contentType : 'application/json; charset=utf-8',
        success:function(data){
            if (type!=8){updatecell(data["data"],type);}
            fnCallback(data);
        }
    });
}

function updatecell(data, type)
{
   for(i=0;i<data.length;i++)
   {
        if (data[i]['checkin']==1){
            tag='<td class="text-center alert alert-success" style="vertical-align: middle;">是</td>';            
        }else{
            tag='<td class="text-center alert alert-danger" style="vertical-align: middle;">否</td>';
        }
       data[i]['checkindesc']=tag;
    }
}
