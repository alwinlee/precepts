$(document).ready(function () {
  let d = new Date();
  $('#members-dbtable-name').val('precepts_' + d.getFullYear());
  $('#manage-export-month').val(d.getFullYear() + '.' + String('00' + (d.getMonth() + 1)).slice(-2));
  $('#manage-export-command').val("`studentid`='TW00103008' OR `studentid`='TW00104039' OR `area`='高區' OR `classroom` LIKE '高22宗%'");

  $('#members-data-import').bind("click", function () {
    $('#members-data-import').blur();
    $('#members-import-file').click();
  });
  $('#manage-export-report').on("click", function (e) {
    e.stopImmediatePropagation();
    $('#manage-export-report').blur();
    export_report();
  });
  let wizardImport = document.getElementById('members-import-file');
  if (wizardImport.addEventListener) {
    wizardImport.addEventListener('change', fnMemberDataImport, false);
  }

  function to_json(workbook) {
    let result = {};
    workbook.SheetNames.forEach(function (sheetName) {
      let roa = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
      if (roa.length > 0) {
        result[sheetName] = roa;
      }
    });
    return result;
  }

  function fnMemberDataImport(e) {
    let table_name = $('#members-dbtable-name').val();
    if (!table_name) {
      table_name = 'precepts';
    }
    let files = e.target.files;
    let f = files[0];
    let reader = new FileReader();
    let name = f.name;
    $('#members-import-file').prop("value", "");
    reader.onload = function (e) {
      let data = e.target.result;
      let wb = undefined;
      try {
        let buf = '';
        let bytes = new Uint8Array(data);
        for (let i = 0, len = bytes.byteLength; i < len; i++) {
          buf += String.fromCharCode(bytes[i]);
        }
        wb = XLSX.read(buf, { type: 'binary', cellDates: true, cellStyles: true });

        let output = to_json(wb);
        let sheetsitem = Object.keys(output);
        if (!sheetsitem || !(sheetsitem instanceof Array) || sheetsitem.length <= 0) {
          alert('Data Error!!!');
          return;
        }
        let headers = {
          area: '區域',
          sn: '報到序號',
          name: '學員姓名',
          sid: '學員代碼',
          class: '學員班級',
          status: '學員狀態',
          sex: '性別',
          age: '年紀',
          series: '行政組織',
          short: '行政組織縮寫',
          register: '報名宗仰大樓',
          rookie: '新受',
          course: '場次',
          memo: '備註',
        };
        let sheetindex = 0;

        let items = output[sheetsitem[sheetindex]];
        if (items.length <= 0) {
          alert('No data!!!');
          return;
        }
        let keys = Object.keys(headers);
        //[['北', 'A'], ['桃', 'B'], ['竹', 'C'], ['中', 'D'], ['嘉', 'E'], ['園', 'F'], ['南', 'G'], ['高', 'H'], ['海', 'I'], ['外', 'I']];
        let area_map = {
          '北': 'A', '桃': 'B', '竹': 'C', '中': 'D', '嘉': 'E', '園': 'F', '南': 'G', '高': 'H', '海': 'I', '外': 'I'
        };
        let area_key = Object.keys(area_map).join('|');
        let area_regex = new RegExp(area_key, 'g');
        let set = [];
        let da = output[sheetsitem[sheetindex]];
        for (let w = 0, classname = '', sub = [], len = da.length; w < len; w++ , classname = '') {
          classname = (da[w][headers.class] ? da[w][headers.class] : '');
          if (classname.indexOf('-') < 0) {
            classname += (da[w][headers.short] ? '-' + da[w][headers.short] : '');
          }

          sub = {
            area: da[w][headers.area] ? da[w][headers.area] : '高',
            sn: da[w][headers.sn] ? da[w][headers.sn] : '',
            name: da[w][headers.name] ? da[w][headers.name] : '',
            sid: da[w][headers.sid] ? da[w][headers.sid] : '',
            class: classname,
            status: da[w][headers.status] ? da[w][headers.status] : '',
            sex: da[w][headers.sex] ? da[w][headers.sex] : '',
            age: da[w][headers.age] ? da[w][headers.age] : '',
            series: da[w][headers.series] ? da[w][headers.series] : '',
            short: da[w][headers.short] ? da[w][headers.short] : '',
            register: da[w][headers.register] ? da[w][headers.register] : 0,
            rookie: da[w][headers.rookie] ? da[w][headers.rookie] : 0,
            course: da[w][headers.course] ? da[w][headers.course] : '1',
            memo: da[w][headers.memo] ? da[w][headers.memo] : '',
            id: 'NULL',
            barcode: da[w][headers.sn].replace(area_regex, function (match) { return area_map[match]; }),
          };

          set.push(sub);
        }

        let sql = "DROP TABLE IF EXISTS `" + table_name + "`;\r\n";
        sql += "CREATE TABLE IF NOT EXISTS `" + table_name + "`(\r\n";
        sql += "  `id` int(8) NOT NULL AUTO_INCREMENT,\r\n";
        sql += "  `barcode` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '報到條碼',\r\n";
        sql += "  `serial` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '年度編號',\r\n";
        sql += "  `sex` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '性別',\r\n";
        sql += "  `studentid` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '學員代號',\r\n";
        sql += "  `area` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '區域',\r\n";
        sql += "  `classroom` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '母班班別',\r\n";
        sql += "  `name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '姓名',\r\n";
        sql += "  `rookie` int(4) DEFAULT '0' COMMENT '新受',\r\n";
        sql += "  `course` varchar(40) collate utf8_unicode_ci COMMENT '場次',\r\n";
        sql += "  `memo` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '備註',\r\n";
        sql += "  `register` int(4) DEFAULT '0' COMMENT '是否報名',\r\n";
        sql += "  `apply` int(4) DEFAULT '0' COMMENT '參與',\r\n";
        sql += "  `checkin` int(4) DEFAULT '0' COMMENT '報到',\r\n";
        sql += "  `applydate` date DEFAULT '1970-01-01' COMMENT '報到日期',\r\n";
        sql += "  `applyby` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '報到者',\r\n";
        sql += "  PRIMARY KEY(`id`)\r\n";
        sql += ") ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;\r\n\r\n";

        for (let i = 0, sub = '', len = set.length; i < len; i++ , sub = '') {
          sub = "insert into `" + table_name + "` (`id`, `barcode`, `serial`, `sex`, `studentid`, `area`, `classroom`, `name`, ";
          sub += "`rookie`, `course`, `memo`, `register`, `apply`, `checkin`, `applydate`, `applyby`) values (";
          sub += set[i].id + ",";
          sub += "'" + set[i].barcode + "', ";
          sub += "'" + set[i].sn + "', ";
          sub += "'" + set[i].sex + "', ";
          sub += "'" + set[i].sid + "', ";
          sub += "'" + set[i].area + "區', ";
          sub += "'" + set[i].class + "', ";
          sub += "'" + set[i].name + "', ";
          sub += "" + set[i].rookie + ", ";
          sub += "'" + set[i].course + "', ";
          sub += "'" + set[i].memo + "', ";
          sub += set[i].register + ",";
          sub += " 0, 0,'1970-01-01','');\r\n";
          sql += sub;
        }

        // ,0,0,0,0,0,0,0,0,0,'');
        let blob = new Blob([sql], { type: 'plain/text' });
        // Determine which approach to take for the download
        if (navigator.msSaveOrOpenBlob) {
          navigator.msSaveOrOpenBlob(blob, table_name + '.sql');
        } else {
          let anchor = document.body.appendChild(document.createElement('a'));
          if ('download' in anchor) {
            anchor.download = table_name + '.sql';
            anchor.href = URL.createObjectURL(blob);
            anchor.click();
          }
        }
      } catch (err) {
        alert('Data parser exception!!!');
        return;
      }
    };
    reader.readAsArrayBuffer(f);
  }

  function export_report() {
    let command = $('#manage-export-command').val();
    let date = $('#manage-export-date').val();
    let month = $('#manage-export-month').val();
    let time = $('#manage-export-time').val();
    let parameter = '<input type="hidden" name="command" value="' + command + '">"';
    parameter += '<input type="hidden" name="date" value="' + date + '">"';
    parameter += '<input type="hidden" name="month" value="' + month + '">"';
    parameter += '<input type="hidden" name="time" value="' + time + '">"';
    $('<form action="../api/report/print-report.php" method="post">' + parameter + '</form>').appendTo('body').submit().remove();
  }
});




