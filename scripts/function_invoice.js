document.addEventListener("DOMContentLoaded", function(){
    invoice_fetch();
});

function invoice_fetch(){
    fetch("invoice_get_latest.php")
    .then(response => response.json())
    .then(data => {document.getElementById("si_inputs").value = data.invoice_num})
    .catch(error => console.error("ERR_ORDER_SYS_1: Operation error when fetching invoice number: ", error));
}