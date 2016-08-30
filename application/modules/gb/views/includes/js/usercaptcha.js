$(document).ready(function() {
    $('input[name="go"]').on('click', function() {
        var goFromClient = $('input[name="go"]').val(),
            codeFromClient = $('input[name="code"]').val();
        $.ajax({
            url: "/gb/usercaptcha/go",
            type: "post",
            contentType: 'multipart/form-data',
            dataType: "json",
            data: {
                go: goFromClient,
                code: codeFromClient,
            },
            success: function(response) {
                if(response.success) {
                    $('#div_captcha_submit_result').append('<font color="green">'+response.success+'</font>');
                }
                if(response.error) {
                    $('#div_captcha_submit_result').append('<font color="red">'+response.error+'</font>');
                }
            },
            error: function() {
                console.log('Unable to transfer CAPTCHA to the server');
            }
        });
    });
});