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