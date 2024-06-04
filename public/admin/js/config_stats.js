$(document).ready(function () {

    $('#cal_stats').click(function () {
        call = '/admin/cal_stats';
        $.ajax({
            contentType: 'application/json',
            type: 'get',
            url: call,

            success: function (response) {
                // handle success
                alert('Stats Calculated Successfully');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // handle error
                console.log(textStatus, errorThrown);
                alert('Error in calculating stats, please retry');
            }
        });
    });

    $('.round').click(function () {
        var selectedValue = $(this).attr('id');
        data = {
            round_id: selectedValue
        };
        call = '/admin/change_round/' + selectedValue.toString();
        $.ajax({
            contentType: 'application/json',
            type: 'get',
            url: call,

            success: function (response) {
                // handle success
                alert('Active Round Updated Successfully');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // handle error
                console.log(textStatus, errorThrown);
                alert('Error in changing active round, please retry');
            }
        });
    });
});