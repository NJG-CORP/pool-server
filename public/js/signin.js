var popupForgotPasswordFormLink = $('.open-popup-forgot-password');
var popupForgotPasswordForm = $('#popup-forgot-password');
var popupForgotPasswordSuccess = $('#popup-forgot-password-success').magnificPopup({
    type:'inline',
});


var popupForgotPasswordError = $('#popup-forgot-password-error').magnificPopup({
    type:'inline',
});

popupForgotPasswordFormLink.magnificPopup({
    type:'inline',
    midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
});
$('#form-forgot-password').submit(function(e){
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    const _this = $(this);
    $.ajax({
        method: 'POST',
        url: '/password/forgot',
        dataType: "json",
        data: _this.serialize(),
        beforeSend: function( xhr){
            popupForgotPasswordForm.html('Отправка...');
        },
        success: function(data) {
            $.magnificPopup.close(popupForgotPasswordForm);
            $.magnificPopup.open({
                items: {
                    src: popupForgotPasswordSuccess,
                    type: 'inline'
                }
            });
        },
        error: function(data) {

        }
    });
});