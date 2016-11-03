var Car = {

    load: function () {
        "use strict";

        this.addCar();
        this.editCar();
        this.deleteCar();
        this.comboListener();
    },

    //Combo options for adding a new car
    comboListener: function () {

        $("#yearSelect").change(function () {
            var year = $(this).val();
            console.log("Year selected: "+ year);

            //Get all the makes from that year
            $.ajax({
                type: "POST",
                url: "/car/makeyear",
                data:'year='+year,
                success: function(data){

                    //Remove all the previous options
                    $('#carMake').empty()

                    //Inject the makers into the Manufacturer box
                    data = $.parseJSON(data);

                    $.each(data, function(k,v) {

                        $("#carMake").append(
                            $('<option></option>').val(v.car_make).html(v.car_make)
                        );
                    });

                }
            });
        });

        //Select a manufacturer
        $("#carMake").change(function () {

            var year = $("#yearSelect").val();
            var maker = $("#carMake").val();

            //Get all the makes from that year
            $.ajax({
                type: "POST",
                url: "/car/modelmakeyear",
                data:'year='+year+'&make='+maker,
                success: function(data){

                    //Remove all the previous options
                    $('#carModel').empty()

                    //Inject the makers into the Manufacturer box
                    data = $.parseJSON(data);

                    $.each(data, function(k,v) {

                        $("#carModel").append(
                            $('<option></option>').val(v.car_model).html(v.car_model)
                        );
                    });

                }
            });
        });
    },

    //Add a new car to garage
    addCar: function () {
        "use strict";

        $('body').on('click', "#add-car-btn", function () {

            $.ajax({
                type: "POST",
                url : "/car/add",
                data : $("#add-car-form").serialize(),
                success : function (response) {

                    var rsp = $.parseJSON(response);

                    if (rsp.status === 'success') {

                        //Hide the Add Car Button
                        $("#add-car-btn").hide();

                        $("#add-car-response").html("<div class='card-panel light-green lighten-12'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");

                        //Refresh the page
                        window.setTimeout(function(){location.reload()},2000)
                    } else {

                        $("#add-car-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");
                    }
                }
            }, "json");

            return false;
        });
    },

    //Add a new car to garage
    editCar: function () {
        "use strict";

        $('body').on('click', "#edit-car-btn", function () {

            $.ajax({
                type: "POST",
                url : "/car/update",
                data : $("#edit-car-form").serialize(),
                success : function (response) {

                    var rsp = $.parseJSON(response);

                    if (rsp.status === 'success') {

                        $("#edit-car-response").html("<div class='card-panel light-green lighten-12'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");

                    } else {

                        $("#edit-car-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");
                    }
                }
            }, "json");

            return false;
        });
    },

    //Delete Car
    deleteCar: function () {
        "use strict";

        $('body').on('click', "#delete-car-btn", function () {

            var carData = 'vehicleid='+$(this).data('vehicleid');

            $.ajax({
                type: "POST",
                url : "/car/delete",
                data : carData,
                success : function (response) {

                    var rsp = $.parseJSON(response);

                    if (rsp.status === 'success') {

                        $("#delete-car-btn").hide();


                        $(".modal-footer").append("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>You have deleted the vehicle, updating your information. One moment.</span> \
                        </div>");

                        //Redirect to the dashboard
                        window.setTimeout(
                            function(){
                                location.assign('/dashboard');
                            } ,2500
                        );

                    } else {

                        $("#delete-car-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");
                    }
                }
            }, "json");

            return false;
        });
    }
};

$(document).ready(function () {
    "use strict";

    Car.load();
});
(function($){

    $(document).ready(function() {
        $('select').material_select();
        $('.button-collapse').sideNav();
        $('.modal-trigger').leanModal();

        console.log ("What ya' doing poking around here? Adam sees you!");
    });

})(jQuery);

//Send Contact Email
$('body').on('click', "#contact-btn", function () {

    $.ajax({
        type: "POST",
        url : "/contact-send",
        data : $("#contact-form").serialize(),
        success : function (response) {

            var rsp = $.parseJSON(response);

            if (rsp.status === 'success') {

                $("#contact-btn").hide();
                $("#contact-response").html("<div class='card-panel light-green lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");

            } else {

                $("#contact-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");
            }

        }
    }, "json");

    return false;
});

//Reset Password Process
$('body').on('click', "#respass-btn", function () {

    $.ajax({
        type: "POST",
        url : "/password/reset",
        data : $("#respass-form").serialize(),
        success : function (response) {

            var rsp = $.parseJSON(response);

            if (rsp.status === 'success') {

                $("#respass-btn").hide();
                $("#respass-response").html("<div class='card-panel light-green lighten-12'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                    </div>");

            } else {

                $("#respass-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                    </div>");
            }

        }
    }, "json");

    return false;
});
var Repair = {

    load: function () {
        "use strict";

        this.addRepair();
        this.commonRepairSearch();
    },

    //Add a new car to garage
    addRepair: function () {
        "use strict";

        $('body').on('click', "#add-repair-btn", function () {

            $.ajax({
                type: "POST",
                url : "/repair/add",
                data : $("#add-repair-form").serialize(),
                success : function (response) {

                    var rsp = $.parseJSON(response);

                    if (rsp.status === 'success') {

                        //Reset the form
                        $("#add-repair-form").closest('form').find("input[type=text]").val("");

                        $("#add-repair-response").html("<div class='card-panel light-green lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");

                    } else {

                        $("#add-repair-response").html("<div class='card-panel red lighten-1'> \
                        <span class='white-text'>"+ rsp.msg +"</span> \
                        </div>");
                    }
                }
            }, "json");

            return false;
        });
    },

    commonRepairSearch: function () {

        $(".repair-search").remoteList({
            minLength: 3,
            source: function(val, response){
                $.ajax({
                    type: 'POST',
                    url: '/repaircommon',
                    dataType: 'json',
                    data: {
                        item: val
                    },
                    success: function(data){

                        $.each(data, function(i, item){
                            item.value = item.repair;
                        });
                        response(data);
                    }
                });
            },

            select: function(){
                if(window.console){
                    console.log($(this).remoteList('selectedOption'), $(this).remoteList('selectedData'))
                }
            }
        });
    }
};

$(document).ready(function () {
    "use strict";

    Repair.load();
});
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