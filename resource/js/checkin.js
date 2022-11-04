$(document).ready(function() {
  // init value of auto checkin
  var autocheckin = localStorage.getItem("preceptsautocheckin");
  if (autocheckin=="yes"){
    $('#autocheckin').prop('checked', true);
  }else{
    $('#autocheckin').prop('checked', false);
  }

  $('#auto-checkin-idx').val(0);

  $('#checkinsearch').click(function(){
    searchStudent();
  });

  $('#keyword').on('input',function(){
    if(isBarcode() == false){
      return ;
    }
    searchStudent();
  });

  $('#autocheckin').on('click',function(){
    if ($('#autocheckin').is(':checked')){
      localStorage.setItem("preceptsautocheckin", "yes");
    }else{
      localStorage.setItem("preceptsautocheckin", "no");
    }
  });

  $('.checkin').on('click',function(){
     var idx=$(this).attr('idx');
     alert(idx+'-報到');
  });

  $(document).on('click', "button.btn btn-success checkin", function(event) {
    //event.preventDefault();
    var idx=$(this).attr('idx');
    alert(idx+'-報到');
  });

  $('.checkout').click(function(){
     var idx=$(this).attr('idx');
     alert(idx+'-取消');
  });
});

function searchStudent() {
  $('#auto-checkin-idx').val(0);
  keyword=$('#keyword').val();
  if (keyword.length <=0){
    //$('#previous-keyword').val('');
    return;
  }
  $('#previous-keyword').val(keyword);

  api.query.findStudent(keyword, function(data){
    if(data['code']<=0){stud=[];showtable(stud);}
    else{
      bBarcode = isBarcode();
      autocheckin = false;
      if ($('#autocheckin').is(':checked')){autocheckin = true;}
      count = data['student'].length;
      checkin = 0;
      if (count==1){checkin = data['student'][0]['checkin'];}

      showtable(data['student']);
      // use auto checking , isBarcode , only one student finded and not checkin
      if(bBarcode&&autocheckin&&count==1&&checkin==0){
        var idx = parseInt(data['student'][0]['id']);
        //alert('need auto checkin');
        $('#auto-checkin-idx').val(idx);
        setTimeout(autoCheckin, 200);
        //memberCheckin(idx, 1,true);
      }
    }
  },function(data){
    table='<div class="alert alert-danger" role="alert">查詢失敗！</div>';
    $('#searchdata').html(table);
  });
}

function isBarcode() {
  keyword=$('#keyword').val();
  if(keyword.length != 10) {
    return false;
  }

  var strValid = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
  var bBarcode=true;
  for (let i = 1, len = keyword.length; i < len; i++) {
    j = strValid.indexOf(keyword.charAt(i));
    if (j<0){bBarcode=false;break;}
    if (i==0&&j>9){bBarcode=false;break;}
  }
  return bBarcode;
}

function hideModal() {
  $('#statusReport').modal('hide');
  $('#statusCancel').modal('hide');
  $('#keyword').focus();
  $('#keyword').focus();
}

function autoCheckin() {
  var idx = parseInt($('#auto-checkin-idx').val());
  if (idx <= 0 || idx > 90000){
    return;
  }
  memberCheckin(idx, 1,true);
}

function gettable(data) {
  table='<table class="table table-bordered">';
  table+='<thead><tr>';
  //table+='<th>報到</th><th>錄取編號</th><th>姓名</th><th>電話</th><th>學校</th><th colspan="2" class="text-center">登錄</th>';
  table+='<th class="text-center">報到否</th>';
  table+='<th class="text-center">報到編號</th>';
  table+='<th class="text-center">姓名</th>';
  table+='<th class="text-center">區域</th>';
  table+='<th class="text-center">班級</th><th colspan="2" class="text-center">報到登錄</th>';
  table+='</tr></thead>';

  table+='<tbody>';
  for(i=0;i<data.length;i++)
  {
    idx=data[i]['id'];
    table+='<tr>';
    if (data[i]['checkin']==1){
      table+='<td class="text-center alert alert-success" style="vertical-align: middle;">是</td>';
    }else{
      table+='<td class="text-center alert alert-danger" style="vertical-align: middle;">否</td>';
    }
    table+='<td class="text-center" style="vertical-align: middle;">'+data[i]['barcode']+'</td>';
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
  table+='<th class="text-center">已報到</th><th class="text-center">報到編號/區域班級/姓名</th><th class="text-center">報到登錄</th>';
  table+='</tr></thead>';

  table+='<tbody>';
  for(i=0;i<data.length;i++)
  {
    idx=data[i]['id'];
    table+='<tr>';

    if (data[i]['checkin']==1){
      table+='<td rowspan="3" class="text-center alert alert-success" style="vertical-align: middle;">是</td>';
    }else{
      table+='<td rowspan="3" class="text-center alert alert-danger" style="vertical-align: middle;">否</td>';
    }
    table+='<td class="text-center" style="vertical-align: middle;">'+data[i]['barcode']+'</td>';
    table+='<td  rowspan="3" class="text-center" style="vertical-align: middle;">';
    if (data[i]['checkin']!=1){
      table+='<button type="button" id="checkin_'+idx+'" class="btn btn-lg btn-success checkin" idx="'+idx+'">報     到</button></td>';
    }else{
      table+='<button type="button" class="btn btn-lg btn-danger checkout" idx="'+idx+'">取     消</button></td>';
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
  $('.checkin').click(function(event)
  {
    var idx=$(this).attr('idx');
    memberCheckin(idx,1,false);
  });

  $('.checkout').click(function(event)
  {
    var idx=$(this).attr('idx');
    memberCheckin(idx,0,false);
  });
}

function memberCheckin(idx, checkin, autocheckin)
{
   api.checkin.setCheckin(idx,checkin,function(data){
    keyword=$('#keyword').val();
    if (keyword==""){keyword=$('#previous-keyword').val();}
    api.query.findStudent(keyword, function(data){
      if(data['code']<=0){stud=[];showtable(stud);}
      else{
        showtable(data['student']);
        if (checkin==1){
          $('#statusReport').modal('show');
        }else{
          $('#statusCancel').modal('show');
        }
        tm=1500;
        if (autocheckin){
          tm=800;
        }
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