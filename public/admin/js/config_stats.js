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


    $('.company').click(function () {
        var selectedValueName = $(this).text();
        var selectedValue = $(this).attr('id');
        $('#companies-btn').text(selectedValueName);
        $(".company_value").val(selectedValue);
    });

    $('#dividend').on('input', function () {
        var inputVal = $(this).val();
        if (inputVal === '' | inputVal == undefined) {
            // If the input is empty, show the error message
            $('#errorMsg').text('This field cannot be empty').show();
            $("#dividend-submit").attr('disabled', true);
        } else {
            // If the input is not empty, hide the error message
            $('#errorMsg').hide();
            $("#dividend-submit").attr('disabled', false);
        }
    });

    $('#dividendForm').on('submit', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        console.log(formData);
        $.ajax({
            url: 'submit-dividend', // Backend route to handle the submission
            type: 'POST',
            data: formData,
            success: function (response) {
                alert(response.message);
                location.reload();
            },
            error: function (response) {
                alert('Something went wrong');
                // Handle error
            }
        });
    });

});