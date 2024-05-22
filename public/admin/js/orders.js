
$(document).ready(function () {
    $('#submit-orders').prop('disabled', true);


    $('.brokerSelection').click(function () {
        var selectedValueName = $(this).text();
        var selectedValue = $(this).attr('id');
        $('#broker-btn').text(selectedValueName);
        $('#broker').text(selectedValue);
        data = {
            broker_id: selectedValue
        }
        $.ajax({
            data: data,
            type: 'get',
            url: '/admin/teams_tagged',

            success: function (response) {
                let res = $.parseJSON(response);
                $('#selectedTeam').empty();
                if (res['data'].length > 0) {
                    $.each(res['data'], function (index, item) {
                        $('#selectedTeam').append(`<li><a class="teamSelection border-radius-md" href="javascript:;" id=` + item['id'] + `>` + item['team_name'] + `</a></li>`);
                    });
                    $(".team-dropdown").removeClass("hidden");
                }
            }
        });
    });

    $('body').on('click', '.teamSelection', function () {
        var selectedValueName = $(this).text();
        var selectedValue = $(this).attr('id');
        $('#team').text(selectedValue);
        $('#team-btn').text(selectedValueName);
        if (selectedValue.length > 0) {
            $(".orderForm").removeClass("hidden");
        }
    });

    $('input[name="sell_quantity"]').blur(function () {
        var companyId = $(this).closest('tr').find('td:first').text();
        var sellQuantity = $(this).val();
        var teamId = $('#team').text();
        $.ajax({
            url: '/admin/check_sell_quantity',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                team_id: teamId,
                company_id: companyId,
                sell_quantity: sellQuantity
            },
            success: function (response) {
                // handle success
                console.log(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // handle error
                console.log(textStatus, errorThrown);
            }
        });
    });

    $('.buy-quantity, .sell-quantity').on('input', function () {
        var row = $(this).closest('tr');
        var price = parseFloat(row.find('td:nth-child(3)').text());
        var buyQuantity = parseInt(row.find('.buy-quantity').val());
        var sellQuantity = parseInt(row.find('.sell-quantity').val());

        // Calculate and display the buy and sell values
        row.find('.buy-value').text(isNaN(buyQuantity) ? '' : (buyQuantity * price).toFixed(2));
        row.find('.sell-value').text(isNaN(sellQuantity) ? '' : (sellQuantity * price).toFixed(2));
    });

    var totalBuyTransactions = 0;

    $('input[name="buy_quantity"]').blur(function () {
        var price = $(this).closest('tr').find('td:nth-child(3)').text();
        var buyQuantity = $(this).val();

        // Calculate the total buy transactions
        totalBuyTransactions += price * buyQuantity;
        // Check if the overall buy transactions are less than 100
        if (totalBuyTransactions < 10000000) {
            console.log("Total buy transactions are less than 10 Lakhs.");
        } else {
            console.log("Total buy transactions are not less than 10 Lakhs.");
        }
    });

    // Save a value in the browser session
    sessionStorage.setItem('active_round', $("#active_round").text());
    console.log(sessionStorage.getItem('active_round'));
    // Make an AJAX call every 10 seconds
    setInterval(function () {
        $.ajax({
            url: '/admin/active_round',
            type: 'GET',
            success: function (response) {
                // Check if the value returned by the AJAX call is not the same as the stored value
                console.log(response, sessionStorage.getItem('active_round'));
                if (response.toString() !== sessionStorage.getItem('active_round').toString()) {
                    // Refresh the page
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // handle error
                console.log(textStatus, errorThrown);
            }
        });
    }, 10000);  // 10000 milliseconds = 10 seconds



    $('#myForm').submit(function (e) {
        e.preventDefault();

        var orders = [];
        $('#myTable tr').each(function () {
            var row = $(this);
            var price = parseInt(row.find('td:eq(2)').text());
            var buyQuantity = parseInt(row.find('.buy-quantity').val());
            var sellQuantity = parseInt(row.find('.sell-quantity').val());
            // If the quantity is greater than 0, add the order to the array
            if (buyQuantity > 0 || sellQuantity > 0) {
                if (isNaN(buyQuantity)) {
                    buyQuantity = 0;
                }
                if (isNaN(sellQuantity)) {
                    sellQuantity = 0;
                }
                var order = {
                    team_id: $('#team').text(),
                    round_id: $('#active_round').text(),
                    company_id: row.find('td:first').text(),
                    buy_quantity: buyQuantity,
                    sell_quantity: sellQuantity,
                    buy_value: price * buyQuantity,
                    sell_value: price * sellQuantity
                };
                orders.push(order);
            }
        });
        // Send the orders to the server
        $.ajax({
            url: '/admin/save_order',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                orders: orders
            },
            success: function (response) {
                // handle success
                console.log(response);
                $('#myForm')[0].reset();
                $('#myTable tr').each(function () {
                    // This will clear the second and third column of each row
                    $(this).find('td:eq(4), td:eq(6)').html('');
                });
                alert('Data Submitted Successfully');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // handle error
                console.log(textStatus, errorThrown);
                alert('Error in submitting, please retry');
                location.reload();
            }
        });
    });

    // Function to check if any input has a non-zero value
    function checkInputs() {
        var hasNonZero = false;
        $('#myTable input').each(function () {
            if ($(this).val() != 0 && $(this).val() != '') {
                hasNonZero = true;
                return false;  // Break out of .each() loop
            }
        });
        return hasNonZero;
    }

    // Event handler for input change and focusout events
    $('#myTable input').on('change focusout', function () {
        if (checkInputs()) {
            // If any input has a non-zero value, enable the submit button
            $('#submit-orders').prop('disabled', false);
        } else {
            // Otherwise, disable the submit button
            $('#submit-orders').prop('disabled', true);
        }
    });

    // Event handler for input focusin event
    $('#myTable input').on('focusin', function () {
        // Disable the submit button when an input field is in focus
        $('#submit-orders').prop('disabled', true);
    });

});
