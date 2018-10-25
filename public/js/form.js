$('form').on('submit', function (e) {
    const _this = $(this);
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: $(this).serialize(),
        success: function (data) {
            $.each(data.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        },
        error: function (data) {
            const response = data.responseJSON;
            _this.find('.help-block').remove();
            _this.prepend('<div class="has-error help-block">' + response.error + '</div>');
        },
        dataType: "json"
    });
});