function toggleNav() {
    var sidenav = document.getElementById("sidenav");
    var mainContent = document.getElementById("navleft");
    
    if (sidenav.offsetWidth === 290) {
        sidenav.style.width = "0px"; 
        mainContent.style.marginLeft = "0px"; 
    } else {
        sidenav.style.width = "290px"; 
        mainContent.style.marginLeft = "290px"; 
    }
}
