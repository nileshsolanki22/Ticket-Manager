(function ($) {
    $(function () {

        $('#priority').multiselect({
            includeSelectAllOption: true

        });

        $('#status').multiselect({
            includeSelectAllOption: true

        });

        $('#apply').click(function () {
            alert($('#priority').val());
            alert($('#status').val());

            $.ajax({
                type: 'post',
                dataType: 'json', // http://en.wikipedia.org/wiki/JSON
                url: 'subtract.php',
                data: {hours: hours.value, memberID: memberID.value},
                success: function (response) {
                    if (response.type == 'success') {
                        alert('Bravo! ' + response.result);
                    } else {
                        alert('Error!');
                    }
                    ;
                }
            });


        })

    });

})(jQuery);
