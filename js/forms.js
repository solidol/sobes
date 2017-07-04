/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    
    $(".datepicker").datepicker($.datepicker.regional[ "uk" ]);
    
    $("#selecttype").change(function(){
        $("#prefix_2_span").html(this.value);
    });
    
    $("#cbinput_number").change(function () {
        if ($(this).prop("checked"))
        {
            $("#external_number").val('');
            $("#external_number").prop("readOnly", false);
        } else
        {
            $("#external_number").val('-');
            $("#external_number").prop("readOnly", "readOnly");
        }
    });


    $("#topicstartername").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "/default.php/ajax/people/shortsearch",
                dataType: "jsonp",
                data: {
                    term: encodeURIComponent(request.term)
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 3,
        select: function (event, ui) {
            $("#topicstarter").val(ui.item.id);
            $("#prefix_1_span").html(ui.item.label[0]);
            $("#num_prefix_1").val(ui.item.label[0]);
            //log("Selected: " + ui.item.value + " aka " + ui.item.id);
        }
    });
});

