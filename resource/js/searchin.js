$(document).ready(function() 
{
    $('#datagrid').hide();
    $('#editinsearch').click(function(){
        searchmember();
    });
});

function searchmember()
{
    $("#memberdetaildata").hide();

    if($('#datagrid').is(":visible")==false){
        $('#datagrid').show();
    }
    type=0;
    var columns=[{"sTitle": "報到","mData": "hascheckin","aTargets": [0]},
                 {"sTitle": "姓名","mData": "name","aTargets": [1]},
                 {"sTitle": "性別","mData": "sex","aTargets": [2]},
                 {"sTitle": "報到編號","mData": "barcode","aTargets": [3]},
                 {"sTitle": "區別","mData": "area","aTargets": [4]},
                 {"sTitle": "母班班級","mData": "classroom","aTargets": [5]},
                 {"sTitle": "報名","mData": "registerdesc","aTargets": [6]},
                 {"sTitle": "新受","mData": "rookiedesc","aTargets": [7]}];
    drawDataTable(columns,type);
}

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

    keyword=$('#keyword').val();
    var url="../api/search/querymember.php";
    var data={data:{keyword:keyword,draw:draw,start:start,length:len}};
    var jdata = JSON.stringify(data);
    $.ajax({
        type:"POST",
        url:url,
        data:jdata,
        async: true,
	  dataType : "json",
	  contentType : 'application/json; charset=utf-8',
        success:function(data){
            updatecell(data["data"]); // update cell to show the selection radio
            fnCallback(data);            
            $("#memberdetaildata").hide();
        }
    });
}

function updatecell(data)
{
    for(i=0;i<data.length;i++)
    {
        idx=data[i]['id'];
        serial=(i+1);
        
        tag='<span style="vertical-align: middle;color:red;">否</span>';
        if (data[i]['checkin']==1){
            tag='<span  style="vertical-align: middle;color:blue;">是</span>';            
        }        
        data[i]['hascheckin']=tag;
        
        tag="否";
        if (data[i]['rookie']==1){
            tag='<span  style="vertical-align: middle;color:blue;">是</span>';           
        }
        data[i]['rookiedesc']=tag;

        tag="無";
        if (data[i]['register']==1){
            tag='<span  style="vertical-align: middle;color:blue;">有</span>';
        }      
        data[i]['registerdesc']=tag;        
    }
}


function hideConfirmDataModalAndResearch()
{
    $('#confirm-data').modal('hide');
    searchmember();
}

function hideConfirmDataModal()
{
    $('#confirm-data').modal('hide');
}