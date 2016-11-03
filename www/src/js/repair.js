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