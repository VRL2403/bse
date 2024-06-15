
$(document).ready(function () {
    $('#submit-orders').prop('disabled', true);

    $(window).keydown(function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        }
    });
    var orders = [];
    var totalBuyTransactions = 0;
    var buyAmount = 0;

    $(document).ready(function () {
        var broker_id = sessionStorage.getItem("broker_id");
        if (broker_id) {
            $('a.brokerSelection[id="' + broker_id.toString() + '"]').click();
        }
    });

    function fetchAmount(teamId, sellList, defaultAmount) {
        for (var i = 0; i < sellList.length; i++) {
            if (sellList[i]['team_id'] === teamId) {
                return sellList[i]['sum_of_total'];
            }
        }
        // If team_id is not found, return the default amount
        return defaultAmount;
    }

    $('.brokerSelection').click(function () {
        var selectedValueName = $(this).text();
        var selectedValue = $(this).attr('id');
        var charges = $(this).attr('charges');
        console.log(charges, 'fsrgfsd');
        $('#broker-btn').text(selectedValueName);
        $('#broker').text(selectedValue);
        $("#brokerage_value").text(charges);
        sessionStorage.setItem("broker_id", selectedValue);
        data = {
            broker_id: selectedValue
        };
        call = '/admin/teams_tagged/' + selectedValue.toString();
        $.ajax({
            contentType: 'application/json',
            type: 'get',
            url: call,

            success: function (response) {
                let res = $.parseJSON(response);
                $('#selectedTeam').empty();
                if (res['data'].length > 0) {
                    $.each(res['data'], function (index, item) {
                        $('#selectedTeam').append(`<li><a class="teamSelection dropdown-item" href="javascript:;" id=` + item['id'] + `>` + item['team_name'] + `</a></li>`);
                        $('#broker-btn').prop('disabled', true);
                    });
                    $(".team-dropdown").removeClass("hidden");
                }
            }
        });
    });


    $('body').on('click', '.teamSelection', function () {
        var selectedValueName = $(this).text();
        var selectedValue = $(this).attr('id');

        call = '/admin/companies/team/' + selectedValue.toString();
        $.ajax({
            contentType: 'application/json',
            type: 'get',
            url: call,

            success: function (response) {
                let res = response;
                res.forEach(item => {
                    $('#company-' + item.id).find('.quantity-owned').html(item.quantity || 0);
                });
            }
        });

        $('#team').text(selectedValue);
        $('#team-btn').text(selectedValueName);
        if (selectedValue.length > 0) {
            $(".orderForm").removeClass("hidden");
            $(".round-info").removeClass("hidden")
        }
        var amount = 0;
        var limit_flag = $('#limit_flag').text();
        if (limit_flag == 1 | limit_flag == '1') {
            var amount_allocated = $('#amount_alloted').text();
            var amount_used_by_team = JSON.parse($('#amount_used').text());
            amount = fetchAmount(parseInt($('#team').text()), amount_used_by_team, amount_allocated);
            $('#amount_can_be_used').text(amount.toFixed(2));
        } else {
            var cash_available = JSON.parse($('#cash_available').text());
            var amount = 0;
            for (var i = 0; i < cash_available.length; i++) {
                if (cash_available[i]['team_id'] === parseInt($('#team').text())) {
                    amount = cash_available[i]['cash_in_hand'];
                }
            }
            $('#amount_can_be_used').text(amount.toFixed(2));
        }
        var cash_available = JSON.parse($('#cash_available').text());
        if (!jQuery.isEmptyObject(cash_available)) {
            for (var i = 0; i < cash_available.length; i++) {
                if (cash_available[i]['team_id'] === parseInt($('#team').text())) {
                    $("#cash_in_hand").html(cash_available[i]['cash_in_hand'].toFixed(2));
                    $("#order_past_cash_in_hand").html(cash_available[i]['cash_in_hand']);
                }
            }
        } else {
            $("#cash_in_hand").html(2000000);
            $("#order_past_cash_in_hand").html(2000000);
        }
    });

    var sellAmount = 0;
    $('input[name="sell_quantity"]').on('input', function (e) {
        e.preventDefault();
        sellAmount = 0;
        var row = $(this).closest('tr');
        // var companyId = $(this).closest('tr').find('td:first').text();
        var quantityOwned = parseInt($(this).closest('tr').find('td:nth-child(9)').text());
        var sellQuantity = parseInt($(this).val());
        teamId = $('#team').text();
        if (sellQuantity > quantityOwned) {
            row.find('.message').text("Error");
        } else {
            row.find('.message').text("");
        }

        if (sellQuantity.toString().length == 0) {
            row.find('.message').text('');
        }
    });

    $('.buy-quantity, .sell-quantity').on('input', function () {
        var buyInput = $(this);
        var buyInputVal = $(this).val();
        buyAmount = 0;
        totalBuyTransactions = 0;
        var row = $(this).closest('tr');
        var price = parseFloat(row.find('td:nth-child(3)').text());
        var charges = $('#brokerage_value').text();
        var buyQuantity = parseInt(row.find('.buy-quantity').val());
        var sellQuantity = parseInt(row.find('.sell-quantity').val());

        // Calculate and display the buy and sell values
        var buyP = isNaN(buyQuantity) ? '' : (buyQuantity * price).toFixed(2);
        var sellP = isNaN(sellQuantity) ? '' : (sellQuantity * price).toFixed(2);
        row.find('.buy-value').text(buyP);
        row.find('.sell-value').text(sellP);
        var buyP = parseFloat(buyP);
        var sellP = parseFloat(sellP);
        var brokageP = 0;
        if (!isNaN(buyP) && !isNaN(sellP)) {
            brokageP = ((buyP + sellP) * (parseFloat(charges) / 100)).toFixed(2);
        }
        else if (!isNaN(buyP)) {
            brokageP = ((buyP) * (parseFloat(charges) / 100)).toFixed(2);
        } else if (!isNaN(sellP)) {
            brokageP = ((sellP) * (parseFloat(charges) / 100)).toFixed(2);
        } else {
        }
        row.find('.brokerage-paid').text(brokageP);

        $('#companiesData tr').each(function () {
            var row = $(this);
            var price = parseInt(row.find('td:eq(2)').text());
            var buyQuantity = parseInt(row.find('.buy-quantity').val());
            var sellQuantity = parseInt(row.find('.sell-quantity').val());
            var charges = $('#brokerage_value').text();
            charges = parseFloat(charges) / 100;
            // If the quantity is greater than 0, add the order to the array
            if (buyQuantity > 0 || sellQuantity > 0) {
                console.log('HHHHH', buyQuantity, sellQuantity);
                if (isNaN(buyQuantity)) {
                    buyQuantity = 0;
                }
                if (isNaN(sellQuantity)) {
                    sellQuantity = 0;
                }
                buyAmount += (price * buyQuantity);
                console.log(price, buyQuantity, price * buyQuantity);
                totalBuyTransactions += (price * buyQuantity) + (((price * buyQuantity) + (price * sellQuantity)) * charges);
            }
        });
        if (limit_flag == 0 | limit_flag == '0' | limit_flag == undefined) {
            buyAmount = totalBuyTransactions;
        }
        $('#limit_used').text(buyAmount.toFixed(2));
        console.log(totalBuyTransactions);
        var amount = 0;
        var limit_flag = $('#limit_flag').text();
        if (limit_flag == 1 | limit_flag == '1') {
            var amount_allocated = $('#amount_alloted').text();
            var amount_used_by_team = JSON.parse($('#amount_used').text());
            amount = fetchAmount(parseInt($('#team').text()), amount_used_by_team, amount_allocated);
            console.log(amount);
        } else {
            var cash_available = JSON.parse($('#cash_available').text());
            var amount = 0;
            for (var i = 0; i < cash_available.length; i++) {
                if (cash_available[i]['team_id'] === parseInt($('#team').text())) {
                    amount = cash_available[i]['cash_in_hand'];
                }
            }
        }
        // var cash = $("#cash_in_hand").text();
        // $("#order_past_cash_in_hand").text(cash - totalBuyTransactions);
        // Check if the overall buy transactions are less than 100
        if (buyAmount <= amount) {
            console.log("Total buy transactions are less than 10 Lakhs.");
        } else {
            alert('Round Buying Limit Exceed');
            buyInput.val(buyInputVal.slice(0, -1)).trigger('input');
            // $('#submit-orders').prop('disabled', true);
            console.log("Total buy transactions are not less than 10 Lakhs.");
        }
    });

    $('.buy-quantity, .sell-quantity').keypress(function (e) {
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    });



    // $('input[name="buy_quantity"]').on('input', function (e) {
    //     e.preventDefault();
    //     buyAmount = 0;
    //     // var price = $(this).closest('tr').find('td:nth-child(3)').text();
    //     var buyInput = $(this);
    //     var buyInputVal = $(this).val();
    //     // var brokerage = $(this).closest('tr').find('td:nth-child(8)').text();
    //     var limit_flag = $('#limit_flag').text();
    //     if (limit_flag == 1 | limit_flag == '1') {
    //         var amount_allocated = $('#amount_alloted').text();
    //         var amount_used_by_team = JSON.parse($('#amount_used').text());
    //         var amount = fetchAmount(parseInt($('#team').text()), amount_used_by_team, amount_allocated);
    //     } else {
    //         var cash_available = JSON.parse($('#cash_available').text());
    //         var amount = 0;
    //         for (var i = 0; i < cash_available.length; i++) {
    //             if (cash_available[i]['team_id'] === parseInt($('#team').text())) {
    //                 amount = cash_available[i]['cash_in_hand'];
    //             }
    //         }
    //     }
    // });

    // Save a value in the browser session
    sessionStorage.setItem('active_round', $("#active_round").text());

    // Make an AJAX call every 10 seconds
    setInterval(function () {
        $.ajax({
            url: '/admin/active_round',
            type: 'GET',
            success: function (response) {
                // Check if the value returned by the AJAX call is not the same as the stored value
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

    $('#order_confirmation').modal({ backdrop: 'static', keyboard: false });
    $('#myForm').submit(function (e) {
        e.preventDefault();
        orders = [];
        var counter = 0;
        $('#companiesData tr').each(function () {
            var row = $(this);
            var price = parseInt(row.find('td:eq(2)').text());
            var buyQuantity = parseInt(row.find('.buy-quantity').val());
            var sellQuantity = parseInt(row.find('.sell-quantity').val());
            var charges = $('#brokerage_value').text();
            var msg = row.find('.message').text();
            if (msg.length > 0) {
                counter += 1;
            }
            charges = parseFloat(charges) / 100;
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
                    company_name: row.find('td:eq(1)').text(),
                    buy_quantity: buyQuantity,
                    sell_quantity: sellQuantity,
                    buy_value: price * buyQuantity,
                    sell_value: price * sellQuantity,
                    brokerage: (((price * buyQuantity) + (price * sellQuantity)) * charges).toFixed(2),
                };
                orders.push(order);
            }
        });
        // Send the orders to the server
        if (counter > 0) {
            alert('There are ' + counter.toString() + ' errors in the order being placed! Kindly review');
        } else {
            const modalBody = $('.modal-body tbody');
            modalBody.empty(); // Clear existing rows
            orders.forEach(item => {
                modalBody.append(`
                    <tr>
                        <td>${item.company_name}</td>
                        <td>${item.buy_quantity}</td>
                        <td>${item.buy_value}</td>
                        <td>${item.sell_quantity}</td>
                        <td>${item.sell_value}</td>
                        <td>${item.brokerage}</td>
                    </tr>
                `);
            });
            $('.order_total').text(totalBuyTransactions.toFixed(2));
            $('#order_confirmation').modal('show');
            $('#order_confirmation').removeClass('hidden').addClass('shown');

        }
    });

    $('#confirm_and_place').on('click', function (e) {
        $("#confirm_and_place").addClass('hidden');
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
                $('#myForm')[0].reset();
                $('#companiesData tr').each(function () {
                    // This will clear the second and third column of each row
                    $(this).find('td:eq(4), td:eq(6), td:eq(7), td:eq(8)').html('');

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
        $('#companiesData input').each(function () {
            if ($(this).val() != 0 && $(this).val() != '') {
                hasNonZero = true;
                return false;  // Break out of .each() loop
            }
        });
        return hasNonZero;
    }

    // Event handler for input change and focusout events
    $('#companiesData input').on('change focusout', function () {
        if (checkInputs()) {
            // If any input has a non-zero value, enable the submit button
            $('#submit-orders').prop('disabled', false);
        } else {
            // Otherwise, disable the submit button
            $('#submit-orders').prop('disabled', true);
        }
    });

    // Event handler for input focusin event
    $('#companiesData input').on('focusin', function () {
        // Disable the submit button when an input field is in focus
        $('#submit-orders').prop('disabled', true);
    });

});
