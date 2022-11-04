$(document).ready(function() 
{
    $('#checkinsearch').click(function(){
        searchStudent();
    });

    $("#keyword").on('input',function() 
    {
        if(isBarcode()==false){
            return ;
        }
        searchStudent();
    });		

    $('.registerin').on('click',function(){
         var idx=$(this).attr('idx');
         alert(idx+'-報名');
    });
    
    $(document).on('click', "button.btn btn-success register", function(event) {
        //event.preventDefault();
        var idx=$(this).attr('idx');
        alert(idx+'-報名');
    });
    
    $('.registerout').click(function(){
         var idx=$(this).attr('idx');
         alert(idx+'-取消');
    });    
});

function searchStudent()
{
    keyword=$('#keyword').val();
    if (keyword.length <=0){
        //$('#previous-keyword').val('');
        return;
    }
    $('#previous-keyword').val(keyword);
    
    api.query.findStudent(keyword, function(data){
        if(data['code']<=0){stud=[];showtable(stud);}
        else{
            showtable(data['student']);
        }                     
    },function(data){
        table='<div class="alert alert-danger" role="alert">查詢失敗！</div>';
        $('#searchdata').html(table); 
    });
}

function isBarcode()
{
    keyword=$('#keyword').val();
    if(keyword.length != 6){return false;}
    
    var strValid="1234567890";
    var bBarcode=true;
    for (i=0;i<keyword.length;i++)     
    {
        j=strValid.indexOf(keyword.charAt(i));
        if (j<0){bBarcode=false;break;}
        if (i==0&&j>4){bBarcode=false;break;}
    }
    return bBarcode; 
}

function hideModal()
{
    $('#registerReport').modal('hide');
    $('#registerCancel').modal('hide');
    $('#keyword').focus();
    $('#keyword').focus();
}

function gettable(data)
{
    table='<table class="table table-bordered">';
    table+='<thead><tr>';
    //table+='<th>報到</th><th>錄取編號</th><th>姓名</th><th>電話</th><th>學校</th><th colspan="2" class="text-center">登錄</th>';
    table+='<th class="text-center">報名</th>';
    table+='<th class="text-center">年度編號</th>';
    table+='<th class="text-center">姓名</th>';
    table+='<th class="text-center">區域</th>';
    table+='<th class="text-center">班級</th><th colspan="2" class="text-center">報到登錄</th>';
    table+='</tr></thead>';    

    table+='<tbody>';    
    for(i=0;i<data.length;i++)
    {
        idx=data[i]['id'];
        table+='<tr>';
        if (data[i]['register']==1){
            table+='<td class="text-center alert alert-success" style="vertical-align: middle;">是</td>';            
        }else{
            table+='<td class="text-center alert alert-danger" style="vertical-align: middle;">否</td>';
        }
        table+='<td class="text-center" style="vertical-align: middle;">'+data[i]['serial']+'</td>';
        table+='<td class="text-center" style="vertical-align: middle;">'+data[i]['name']+'</td>';
        table+='<td class="text-center" style="vertical-align: middle;">'+data[i]['area']+'</td>';
        table+='<td class="text-center" style="vertical-align: middle;">'+data[i]['classroom']+'</td>';
        table+='<td class="text-center" style="vertical-align: middle;"><button type="button" class="btn btn-success checkin" idx="'+idx+' barcode="'+data[i]['barcode']+'">報到</button></td>';
        table+='<td class="text-center" style="vertical-align: middle;"><button type="button" class="btn btn-danger checkout" idx="'+idx+' barcode="'+data[i]['barcode']+'">取消</button></td>';
        table+='</tr>';        
    }    
    table+='</tbody>'; 
    
    table+='</table>';
    return table;
}

function gettableex(data)
{
    table='<table style="font-size:18px;" class="table table-bordered">';
    table+='<thead><tr>';
    table+='<th class="text-center">報名</th><th class="text-center">年度編號/區域班級/姓名</th><th class="text-center">報名設定</th>';
    table+='</tr></thead>';    

    table+='<tbody>';    
    for(i=0;i<data.length;i++)
    {
        idx=data[i]['id'];
        table+='<tr>';
        
        if (data[i]['register']==1){
            table+='<td rowspan="3" class="text-center alert alert-success" style="vertical-align: middle;">有</td>';            
        }else{
            table+='<td rowspan="3" class="text-center alert alert-danger" style="vertical-align: middle;">無</td>';
        }
        table+='<td class="text-center" style="vertical-align: middle;">'+data[i]['serial']+'</td>';
        table+='<td  rowspan="3" class="text-center" style="vertical-align: middle;">';
        if (data[i]['register']!=1){
            table+='<button type="button" id="registerin_'+idx+'" class="btn btn-lg btn-success registerin" idx="'+idx+'">報名</button></td>';
        }else{
            table+='<button type="button" class="btn btn-lg btn-danger registerout" idx="'+idx+'">取消報名</button></td>';
        }        
        table+='</tr>';
        
        table+='<tr><td class="text-center" style="vertical-align: middle;">'+data[i]['classroom']+'</td></tr>';
        table+='<tr><td class="text-center" style="vertical-align: middle;color:blue;">'+data[i]['name']+'</td></tr>';        
    
        table+='<tr><td colspan="3" class="text-center" style="vertical-align: middle;"></td></tr>';
    
    }    
    table+='</tbody>';    
    table+='</table>';
    
    return table;
}
function showtable(data)
{
    if(data.length<=0){
        table='<div class="alert alert-danger" role="alert">學員資料不存在，請重新設定查詢條件！</div>';
        $('#searchdata').html(table);        
        return;
    }
    table = gettableex(data);

    $('#searchdata').html(table);
    $('.registerin').click(function(event) 
    {
        var idx=$(this).attr('idx');
        memberRegisterin(idx,1,false);
    });
    
    $('.registerout').click(function(event) 
    {
        var idx=$(this).attr('idx');
        memberRegisterin(idx,0,false); 
    });
}

function memberRegisterin(idx, register)
{
   api.register.setRegisterin(idx,register,function(data){
        keyword=$('#keyword').val();
        if (keyword==""){keyword=$('#previous-keyword').val();}
        api.query.findStudent(keyword, function(data){
            if(data['code']<=0){stud=[];showtable(stud);}
            else{
                showtable(data['student']);
                if (register==1){
                    $('#registerReport').modal('show'); 
                }else{
                    $('#registerCancel').modal('show');                    
                }
                tm=1200;
                setTimeout(hideModal, tm);
                $('#keyword').val('');
                $('#keyword').focus();
            }                     
        },function(data){
            table='<div class="alert alert-danger" role="alert">查詢失敗！</div>';
            $('#searchdata').html(table); 
        });                 
    },function(data){
         
    });
}