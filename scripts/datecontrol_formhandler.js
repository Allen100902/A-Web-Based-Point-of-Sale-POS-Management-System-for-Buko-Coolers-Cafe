// Get date values
console.log("Date control JS loaded");
document.addEventListener("DOMContentLoaded", function() {
  var currdate = new Date();

  var curr_year = currdate.getFullYear();
  var curr_month = ("0" + (currdate.getMonth() + 1)).slice(-2);
  var curr_day = ("0" + currdate.getDate()).slice(-2);

  var dateFormat = curr_year + '-' + curr_month + '-' + curr_day;

  var start_param = document.getElementById("startdate");
  var end_param = document.getElementById("enddate");

  start_param.setAttribute("max", dateFormat);
  end_param.setAttribute("max", dateFormat);

  start_param.addEventListener("change", function(){
    end_param.value = "";
    end_param.setAttribute("min", start_param.value);
  });

  end_param.addEventListener("change", function(){
    if (end_param.value < start_param.value){
      alert("ERR_VIEW_4: Date filtering parameters are not valid! End date cannot be earlier than start date!");
      end_param.value = "";
    }
  });
});