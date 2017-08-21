//$('.dropdown-toggle').dropdown();
//$('select').select2();

Pace.on("done", function () {
    $(".loader-wrapper").fadeOut(500);
    $(".pace").remove();
});
//
//
//var message = "";
//$('button').click(function () {
//    message = $(this).data('confirmation');
//});
//$('form').on('beforeSubmit', function (event, jqXHR, settings) {
//    var form = $(this);
//    if (form.find('.has-error').length) {
//        return false;
//    }
//    console.log(message);
//    if (message) {
//        bootbox.confirm(message, function (result) {
//            if (result) {
//                $.ajax({
//                    url: form.attr('action'),
//                    type: 'post',
//                    data: form.serialize(),
//                    success: function (data) {
//                        window.location.reload();
//                    }
//                });
//            }
//        });
//    } else {
//        return true;
//    }
//    return false;
//});
