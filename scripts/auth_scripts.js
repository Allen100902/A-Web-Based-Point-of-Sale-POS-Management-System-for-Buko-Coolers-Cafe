document.getElementById("btnvoid_order").addEventListener("click", function(e) {
    // Show popup
    document.getElementById("auth_popup").style.display = "flex";
});

// Close popup
document.getElementById("auth_close").addEventListener("click", function() {
    document.getElementById("auth_popup").style.display = "none";
});
