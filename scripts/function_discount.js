document.addEventListener("DOMContentLoaded", function(){
    function appdiscount(){
        let curr_subtotal = parseFloat(document.getElementById('total_sub').value || 0);
        let type_disc = document.getElementById('disc_type').value;
        let amt_discount = 0;
    
        if (type_disc === "SC/PWD"){
           amt_discount = curr_subtotal * 0.20; 
        }
    
        let total_amount_final = curr_subtotal - amt_discount;
        document.getElementById('total_amt').total_amount_final = total_amt.toFixed(2);
    }

    document.getElementById('disc_type').addEventListener('change', appdiscount);
    appdiscount();
});


