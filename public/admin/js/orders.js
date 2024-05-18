
$(document).ready(function () {
    $('.brokerSelection').click(function () {
        var selectedValueName = $(this).text();
        var selectedValue = $(this).attr('id');
        console.log(selectedValueName);
        console.log(selectedValue);
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
                }
                $(".team-dropdown").removeClass("hidden");
            }
        });
    });

    $('body').on('click', '.teamSelection', function () {
        var selectedValueName = $(this).text();
        var selectedValue = $(this).attr('id');
        console.log(selectedValueName, 'ffdhg');
        console.log(selectedValue, 'hghgf');
        $('#team').text(selectedValue);
        $('#team-btn').text(selectedValueName);
    });

    $('input[name="sell_quantity"]').blur(function () {
        var companyId = $(this).closest('tr').find('td:first').text();
        var sellQuantity = $(this).val();
        console.log(companyId, sellQuantity);
        $.ajax({
            url: '/your-endpoint',
            type: 'POST',
            data: {
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
                // console.log(response, response, sessionStorage.getItem('active_round'));
                if (response !== sessionStorage.getItem('active_round')) {
                    // Refresh the page
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // handle error
                console.log(textStatus, errorThrown);
            }
        });
    }, 10000000);  // 10000 milliseconds = 10 seconds



    $('#myForm').submit(function (e) {
        console.log('ghhghj');
        e.preventDefault();

        var orders = [];
        $('#myTable tr').each(function () {
            var row = $(this);
            var buyQuantity = parseInt(row.find('.buy-quantity').val());
            var sellQuantity = parseInt(row.find('.sell-quantity').val());

            // If the quantity is greater than 0, add the order to the array
            if (buyQuantity > 0 || sellQuantity > 0) {
                var order = {
                    team_id: $('#team').text(),
                    round_id: $('#active_round').text(),
                    company_id: row.find('td:first').text(),
                    buy_quantity: buyQuantity,
                    sell_quantity: sellQuantity,
                    buy_value: parseFloat(row.find('.buy-value').text()),
                    sell_value: parseFloat(row.find('.sell-value').text())
                };
                orders.push(order);
            }
        });
        // Send the orders to the server
        $.ajax({
            url: '/your-endpoint',
            type: 'POST',
            data: {
                orders: orders
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

});
