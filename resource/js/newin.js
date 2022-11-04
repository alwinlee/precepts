$(document).ready(function()
{
  $('#confirm-data-information-ok').click(function(event){
    $('#confirm-data-information').html("");
  });

  $('#basic-testdata').click(function(event){
    applyTest();
  });

  $('#basic-demo').click(function(event){
    //$('#confirm-insert').modal('show');
    var json = inputdata();
    api.addin.checkdup(0,json, function(data){
       alert(data);
    },function(data){
       alert('Error');
    });
  });

  $('#basic-submit').click(function(event)
  {
    if (!checkinputdata()){return;}
    var json = inputdata();
    api.newin.checkdup(0,json, function(data){
       if(data['code']>=1){ // duplication
        var msg = json['data']['name']+' 已經在受戒總檔<br>姓名：'+json['data']['name']+'<br>班級：'+json['data']['classroom'];
        msg += '<br>學員代號：'+json['data']['studentid']+'<br>年度編號：'+json['data']['serial']+'<br>請重新輸入！';
        $('#confirm-insert-data-information').html(msg);
        $('#confirm-insert').modal('show');
      }else{
         insertdata(json);
       }
    },function(data){

    });
  });
});

function insertdata(json)
{
  api.newin.insert(0,json, function(data){
    if(data['code']<=0){
      $('#confirm-data-information').html('新增失敗!!!<br><br>(錯誤碼 : '+data['code']+'-'+data['desc']+')');
      $('#confirm-data').modal('show');
      setTimeout(hideConfirmDataModal, 2000);
    }else{
      $('#confirm-data-information').html('新增成功!!!<br><br>(姓名 : '+json['data']['name']+', 學員代號：'+json['data']['studentid']+')');
      $('#confirm-data').modal('show');
      setTimeout(hideConfirmDataModal, 2000);
      cleantable();
    }
  },function(data){
    $('#confirm-data-information').html('報名失敗!!!<br><br>功能異常');
    $('#confirm-data').modal('show');
  });
}
/* *
*  check the user inpurt data
* */
function checkinputdata() {
  var errmsg="";
  var name=$('#basic-name').val(); // 姓名
  var studentid=$('#basic-studentid').val(); //代號
  var sex=$('#basic-sex').val(); //性別

  var area=$('#basic-area').val(); //學區
  var classroom=$('#basic-classroom').val(); //班級

  var serial=$('#basic-serial').val(); //報到序號
  var register=$('#basic-register').val(); // 報名
  var rookie=$('#basic-rookie').val(); // 新受

  if (name==""){errmsg+="姓名<br>";} // if (errmsg!=""){errmsg+=",";}
  if (studentid==""){errmsg+="學員代號<br>";}
  if (sex=="0"){errmsg+="性別<br>";}
  if (area=="0"){errmsg+="學區<br>";}
  if (classroom==""){errmsg+="班級<br>";}
  if (register=="x"){errmsg+="報名<br>";}
  if (rookie=="x"){errmsg+="新受<br>";}

  if (errmsg!=""){
    //table='<div class="alert alert-danger" role="alert">未填寫'+errmsg+'！</div>';
    $('#confirm-data-information').html("以下資料未填寫： <br>"+errmsg);
    //$('#errmsg').text('未填寫'+errmsg+'!!!');
    $('#confirm-data').modal('show');
    //setTimeout(hideConfirmDataModal, 3000);
    return false;
  }

  return true;
}

function phoneFormat(strPhone) {
  if (strPhone.length < 9){return false;} // 字數夠才檢查

  var nFirstZeroPos=-1;
  var nExtPos=-1;

  var strValid="0123456789#";
  var bVaildNumber=true;
  for (i=0;i<strPhone.length;i++)
  {
    j=strValid.indexOf(strPhone.charAt(i));
    if (j<0){bVaildNumber=false;break;}
    if (j==0){if (nFirstZeroPos<0){nFirstZeroPos=i;}}
    if (j==10){if (nExtPos >=0){bVaildNumber=false;break;}nExtPos=i;}  // (
  }

  if (bVaildNumber==false){return false;}
  if (nFirstZeroPos>1||nFirstZeroPos<0){return false;}
  if (nExtPos>0){
    if (nExtPos<9){return false;}
    if (nExtPos>=(strPhone.length-1)){return false;}
  }
  return true;
}

function inputdata() {
  result=true;
  var id="NULL";
  var name=$('#basic-name').val(); // 姓名
  var studentid=$('#basic-studentid').val(); //代號
  var barcode = $('#basic-studentid').val(); //報到序號
  var sex=$('#basic-sex option:selected').text();//$('#basic-sex').val(); //性別
  var area=$('#basic-area option:selected').text();//$('#basic-area').val(); //學區
  var classroom=$('#basic-classroom').val(); //班級
  var serial=$('#basic-serial').val(); //報到序號
  var register=$('#basic-register').val(); // 報名
  var rookie=$('#basic-rookie').val(); // 新受
  var course = $('#basic-course').val(); //場次

  var json = {
    result:result,
    data: {
      id: id, barcode: barcode, name:name,studentid:studentid,sex:sex, area: area, classroom: classroom,
    serial: serial, register: register, rookie: rookie, course: course
    }
  };
  return json;//JSON.stringify(json); // '{"name":"binchen"}'
}

function cleantable() {
  $('#basic-name').val('');
  $('#basic-studentid').val('');
  $('#basic-sex').val('0');
  $('#basic-area').val('0');
  $('#basic-classroom').val('');
  $('#basic-serial').val('');
  $('#basic-register').val('x');
  $('#basic-rookie').val('x');
  $('#basic-course').val('');
}

function applyTest() {
  $('#basic-name').val('林美秀');
  $('#basic-sex').val('M');
  $('#basic-area').val('E');
  $('#basic-studentid').val('3000191');
  $('#basic-classroom').val('南13備02-高雄十全');
  $('#basic-serial').val('南21016');
  $('#basic-register').val('0');
  $('#basic-rookie').val('0');
}

function hideConfirmDataModal() {
  $('#confirm-data').modal('hide');
}


