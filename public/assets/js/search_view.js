$('.shape').click(function (event) {
    event.preventDefault();
    event.stopPropagation();
    $(this).toggleClass('activeshape');
    prepareshape();
});

function prepareshape()
{
    var shape = '';
    $(".activeshape").each(function (index, value) {
        if ($.trim(shape) != "")
        {
            shape += ",";
        }
        if ($.trim($(this).attr("data-shape")) != "")
        {
            shape += $(this).attr("data-shape");
        }
    });
    $('#shapes').val(shape);
}