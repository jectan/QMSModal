$(function () {
    $("#anonymous").click(function () {
        if ($(this).is(":checked")) {
            $("#basicInfo").hide();
        } else {
            $("#basicInfo").show();
        }
    });
    });