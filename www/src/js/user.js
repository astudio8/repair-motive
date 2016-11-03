var User = {

    load: function () {
        "use strict";

        this.login();
        this.register();
        this.updateEmail();
        this.updatePassword();
        this.deleteCarFlashMsg();
    },

    //Log a user in
    login: function () {
        "use strict";

        $('body').on('click', "#login-btn", function () {

            $.ajax({
                type: "POST",
                url : "/user/login",
                data : $("#user-login-form").serialize(),
                success : function (response) {

                    var rsp = $.parseJSON(response);

                    //Game stats saved to database
                    if (rsp.status === 'success') {

                        //Update Login Form
                        $("#login-response").html("<div class='card-panel light-green lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");

                        //Send them to a logged in state/view
                        location.assign('/dashboard');

                    } else {

                        $("#login-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");
                    }
                }
            }, "json");

            return false;
        });
    },

    //Register a user
    register: function () {
        "use strict";

        $('body').on('click', "#signup-btn", function () {

            $.ajax({
                type: "POST",
                url : "/user/signup",
                data : $("#signup-form").serialize(),
                success : function (response) {

                    var rsp = $.parseJSON(response);

                    if (rsp.status === 'success') {

                        $("#signup-response").html("<div class='card-panel light-green lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");

                        //Send them to a logged in state/view
                        location.assign('/dashboard');
                    } else {

                        $("#signup-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");
                    }
                }
            }, "json");

            return false;
        });
    },

    //Update player email
    updateEmail: function () {
        "use strict";

        $('body').on('click', "#edit-email-btn", function () {

            $.ajax({
                type: "POST",
                url : "/user/updateemail",
                data : $("#edit-email-form").serialize(),
                success : function (response) {

                    var rsp = $.parseJSON(response);

                    if (rsp.status === 'success') {

                        $("#edit-email-response").html("<div class='card-panel light-green lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");

                    } else {

                        $("#edit-email-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");
                    }
                }
            }, "json");

            return false;
        });
    },

    //Update user password
    updatePassword: function () {
        "use strict";

        $('body').on('click', "#edit-password-btn", function () {

            $.ajax({
                type: "POST",
                url : "/user/updatepassword",
                data : $("#edit-password-form").serialize(),
                success : function (response) {

                    var rsp = $.parseJSON(response);

                    if (rsp.status === 'success') {

                        $("#edit-password-response").html("<div class='card-panel light-green lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");

                    } else {

                        $("#edit-password-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");
                    }
                }
            }, "json");

            return false;
        });
    },

    deleteCarFlashMsg: function () {

        $(".vehicle-delete-alert").slideDown(function() {
            setTimeout(function() {
                $(".vehicle-delete-alert").slideUp();
            }, 5000);
        });
    }
};

$(document).ready(function () {
    "use strict";

    User.load();
});