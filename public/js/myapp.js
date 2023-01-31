
// Function exec ajax
function appAjax(vurl, vdata, vtype="post", vtimeout=5000,vasync = true) {
  return $.ajax({
    url: vurl,
    data: vdata,
    timeout: vtimeout,
    type: vtype,
    dataType: "json",
    async: vasync,
    //dataType: "script",
    success: function (vRet) {
      vretval = vRet;
    },
    error: function (request, status, error) {
      var errors = $.parseJSON(request.responseText);
      jQuery.each( errors['errors'], function( key, value ) {
        let tmppesan="";
        jQuery.each( value, function( i, msg ) {
          tmppesan=tmppesan+" "+msg;
        });
        let configs = {
              title:"Terjadi Kesalahan",
              message:tmppesan,
              status: TOAST_STATUS.DANGER,
              timeout: 5000
        }
        Toast.create(configs);          
      });     
    },
  });
}

function showmymessage(pmessages=["tidak ada"],pstatus=true,pwaktu=5000){
  jQuery.each( pmessages, function( key, value ) {
    let confsts=TOAST_STATUS.INFO;
    let vtitle="Web Info";
    if(pstatus){
      confsts=TOAST_STATUS.SUCCESS;
      vtitle="Berhasil";
    }else{
      confsts=TOAST_STATUS.DANGER;
      vtitle="Terjadi Kesalahan";
    }
    let configs = {
          title: vtitle,
          message: value,
          status: confsts,
          timeout: pwaktu
    }
    Toast.create(configs);          
  });     
}

$(".goUrl").click(function (event) {
  if (confirm("Apakah anda yakin ?")) {
    var url = $(this).attr("data-url");
    window.location.href = url;
  }
});

function convDate(vTgl, vTo = "YMD") {
  let tmpTgl = vTgl;
  if (vTo == "YMD") tmpTgl = moment(vTgl, "DD-MM-YYYY").format("YYYY-MM-DD");
  else if (vTo == "DMY")
    tmpTgl = moment(vTgl, "YYYY-MM-DD").format("DD-MM-YYYY");
  return tmpTgl;
}

