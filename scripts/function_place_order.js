document.addEventListener('DOMContentLoaded', function(){
    gcashref();
    document.getElementById('disc_type').addEventListener('change', appdiscount);
    document.getElementById('paid_amount').addEventListener('change', cashchange);
    document.getElementById('btnconfirm_order').addEventListener('click', orderconfirm);
    appdiscount();
});

function gcashref(){
    document.getElementById('payment_method').addEventListener('change', function(){
        document.getElementById('ref_gcash').disabled = this.value !== 'GCash';
        //document.getElementById('paid_amount').disabled = this.value === 'GCash';
        document.getElementById('change_amount').value = this.value === 'GCash' ? '' : '0.00';
    });
}

function formatPeso(amount){
    amount = parseFloat(amount) || 0;

    if (amount > 1000){
        return amount.toLocaleString('en-PH', {
            minimumFractionDigits: 2, maximumFractionDigits: 2
        });
    }

    return amount.toFixed(2);
}

//with 12 percent VAT exempt as per panel - commented out code due to beneficiary NON-VAT in OR
// function appdiscount(){
//     let curr_subtotal = parseFloat(document.getElementById('total_sub').value) || 0;
//     let type_disc = document.getElementById('disc_type').value;
//     let amt_discount = 0;
//     let final_subtotal = curr_subtotal;

//     if (type_disc === "SC/PWD"){
//         final_subtotal = curr_subtotal / 1.12;
//         amt_discount = final_subtotal * 0.20; 
//     }

//     let total_amount_final = final_subtotal - amt_discount;
//     let total_amount_final_format = total_amount_final.toFixed(2);

//     document.getElementById('return_total_amt').textContent = "₱" + total_amount_final_format;
//     document.getElementById('total_amt').value = total_amount_final_format;
//     cashchange();
// }

//NON-VAT OR function
function appdiscount(){
    let curr_subtotal = parseFloat(document.getElementById('total_sub').value) || 0;
    let type_disc = document.getElementById('disc_type').value;
    let amt_discount = 0;

    if (type_disc === "SC/PWD"){
        amt_discount = curr_subtotal * 0.20; 
    }

    let total_amount_final = curr_subtotal - amt_discount;
    //let total_amount_final_format = total_amount_final.toFixed(2);

    document.getElementById('return_total_amt').textContent = "₱" + formatPeso(total_amount_final);
    document.getElementById('total_amt').value = total_amount_final.toFixed(2);
    cashchange();
}

function cashchange(){
    let amt_total = parseFloat(document.getElementById('total_amt').value) || 0;
    let cash_tendered = parseFloat(document.getElementById('paid_amount').value) || 0;

    let change_amt = cash_tendered - amt_total;
    //let change_amt_final_format = change_amt.toFixed(2);

    let change_disp = change_amt >= 0 ? change_amt : 0.00;

    document.getElementById('return_change_amount').textContent = "₱" + formatPeso(change_disp);
    document.getElementById('change_amount').value = change_disp.toFixed(2);
}

function orderconfirm(){

    document.getElementById('btnconfirm_order').disabled = true;
    document.getElementById('btnvoid_order').disabled = true;
    document.getElementById('itemadd_cart').disabled = true;
    
    document.querySelectorAll('.itemremove_cart').forEach(button => {
        button.disabled = true;
        button.style.opacity = '0.5';
        button.style.cursor = 'not-allowed';
    });

    
    document.getElementById('customerfield').disabled = false;
    document.getElementById('order_quantity').disabled = true;
    document.getElementById('payment_method').disabled = false;
    document.getElementById('btnpay_order').disabled = false;
    document.getElementById('paid_amount').disabled = false;
    document.getElementById('btn_sales_invoice').disabled = false;
    
    const salesBtn = document.getElementById('btn_sales_invoice');
    if (salesBtn) salesBtn.disabled = false;
}