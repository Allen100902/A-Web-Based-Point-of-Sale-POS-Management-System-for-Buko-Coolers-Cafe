document.getElementById('raw_prodlist').addEventListener('change', function(){
    var sel_rawlistcode = this.value;
    var input_rawlistdesc = document.getElementById('raw_name');

    if (sel_rawlistcode && rawDescData[sel_rawlistcode]){
        input_rawlistdesc.value = rawDescData[sel_rawlistcode];
    } else {
        input_rawlistdesc.value = '';
    }
});