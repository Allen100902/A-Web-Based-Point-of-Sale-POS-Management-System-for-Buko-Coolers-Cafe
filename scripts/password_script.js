function pass_show()
    {
        var init = document.getElementById("password_visible");
        var init_retype = document.getElementById("password_visible_retype");

        if (init.type === "password"){
            init.type = "text";
            init_retype.type = "text";
        } else {
            init.type = "password";
            init_retype.type = "password";
        }
    }