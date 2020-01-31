jQuery(document).ready(function() {
    jQuery('#ajaxSubmit').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "{{ url('/index') }}",
            method: 'post',
            data: {
                code: jQuery('#code').val(),

            },
            success: function(result) {
                if (result.errors) {
                    jQuery('.alert-danger').html('');

                    jQuery.each(result.errors, function(key, value) {
                        jQuery('.alert-danger').show();
                        jQuery('.alert-danger').append('<li>' + value + '</li>');
                    });
                } else {
                    jQuery('.alert-danger').hide();
                    $('#open').hide();
                    $('#myModal').modal('hide');
                }
            }
        });
    });
})