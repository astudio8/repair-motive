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