function sel2_datalokal(vselector, vdata={}, vdropdownParent=null, vAllow=false, vTags=false){
  $(vselector).select2({
    data: vdata,
    //dropdownAutoWidth: true,
    dropdownParent: vdropdownParent,
    placeholder: '- pilih -',
    multiple:vTags,
    allowClear: vAllow,
  });
}

function sel2_empty(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}

function sel2_datasql(vselector=null, vcostumtable=null, vfield=null, vdropdownParent=null, vallow=true, vtags=false) {
  let formVal = {
      table: vcostumtable.table,
      select: vcostumtable.select,
      find: vcostumtable.find,
      order: vcostumtable.order,
  };
  appAjax("apiweb/carigeneral", formVal).done(function (vRet) {
      //refresh data parent
      let dataparent = [{id: "", text: "-pilih-"}];
      if (vRet.status) {
          jQuery.each(vRet.db, function (i, val) {
              dataparent.push({ id: val['id'], text: val[vfield] });
          });
      }
      //console.log(dataparent);
      sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
  });
} 

function sel2_dataajax(vselector=null, urlapi=null, vcond=null, vlimit=0, vdropdownParent=null, vallow=false, vtags=false) {
  $(vselector).select2({
    placeholder: '- pilih -',
    multiple:vtags,
    minimumInputLength: 5,
    dropdownParent: vdropdownParent,
    allowClear: vallow,
    ajax: { 
      url: vBase_url + urlapi,
      type: "post",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          params:params.term, 
          vCari:vcond,
          vLimit:vlimit,
        };
      },
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: true
    }
  });
}

function sel2_dataapi(vselector=null, urlapi=null, vfield=null, vParam=null, vdropdownParent=null, vallow=true, vtags=false) {
  let formVal={
    vCari:vParam,
  }
  appAjax(urlapi, formVal).done(function (vRet) {
      //refresh data parent
      let dataparent = [{id: "", text: "-pilih-"}];
      if (vRet.status) {
          jQuery.each(vRet.db, function (i, val) {
              dataparent.push({ id: val['id'], text: val[vfield] });
          });
      }
      //console.log(dataparent);
      sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
  });
}  

function sel2_jeniskelamin(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"L",text:"Laki-Laki"}, 
          {id:"P",text:'Perempuan'}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}
        
function sel2_statuspeg(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"PNS",text:"PNS"}, 
          {id:"NON PNS",text:'NON PNS'}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}
      
function sel2_semester(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"0",text:"PENDEK"}, 
          {id:"1",text:"GANJIL"}, 
          {id:"2",text:"GENAP"}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}
      
function sel2_aktif1(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"1",text:"Ya"}, 
          {id:"0",text:'Tidak'}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}
        
function sel2_aktif2(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"y",text:"Ya"}, 
          {id:"n",text:'Tidak'}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}
      
function sel2_aktif3(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"YA",text:"YA"}, 
          {id:"TIDAK",text:'TIDAK'}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}

function sel2_aktif4(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"1",text:"Aktif"}, 
          {id:"0",text:'Tidak Aktif'}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}

function sel2_ukuranupload(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"1000",text:"1Mb"}, 
          {id:"2000",text:'2Mb'},
          {id:"3000",text:'3Mb'},
          {id:"4000",text:'4Mb'},
          {id:"5000",text:'5Mb'},
          {id:"6000",text:'6Mb'},
          {id:"7000",text:'7Mb'}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}

function sel2_jenisupload(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let dataparent=[{id: "", text: "-pilih-"},
          {id:"pdf",text:"PDF"}, 
          {id:"doc",text:"Word"},
          {id:"xls",text:"Excel"},
          {id:"ppt",text:"Power Point"},
          {id:"img",text:"Image"}];
  sel2_datalokal(vselector, dataparent, vdropdownParent, vallow, vtags);
}
      

function sel2_tahun(vselector, vdropdownParent=null, vallow=false, vtags=false){
  let thn=new Date().getFullYear();
  thn=thn+1;
  let list_thn=[];
  let x=1;
  list_thn[0]={id:"",text:""};
  for(i=(thn);i>=(thn-4);i--){
    list_thn[x]={id:i,text:i};
    x++;
  }
  sel2_datalokal(vselector, list_thn, vdropdownParent, vallow, vtags);
}