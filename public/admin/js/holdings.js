$(document).ready(function () {
    $('.teamSelection').click(function () {
        $('.holdingsTable').removeClass('shown').addClass('hidden');
        var selectedValueName = $(this).text();
        var selectedValue = $(this).attr('id');
        $('#team-btn').text(selectedValueName);
        call = '/admin/holdings/' + selectedValue.toString();
        $.ajax({
            contentType: 'application/json',
            type: 'get',
            url: call,

            success: function (response) {
                // handle success

                const modalBody = $('#holdings tbody');
                modalBody.empty(); // Clear existing rows
                response.holdings.forEach(item => {
                    modalBody.append(`
                        <tr>
                            <td>${item.company_name}</td>
                            <td>${item.quantity}</td>
                            <td>${item.current_value}</td>
                            <td>${item.total_value}</td>
                        </tr>
                    `);
                });
                $('.holdingsTable').removeClass('hidden').addClass('shown');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // handle error
                console.log(textStatus, errorThrown);
                alert('Error in changing active round, please retry');
            }
        });
    });
});