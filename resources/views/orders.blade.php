<x-app-layout>
    <style>
        .hidden {
            display: none !important;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-3">
                    <label>Select a Broker House:</label><br />
                    <div class="dropdown">
                        <button type="button" class="btn btn-dark dropdown-toggle" id="broker-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            Broker House
                        </button>
                        <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                            @foreach($broker_houses as $key => $data)
                            <li><a class="brokerSelection border-radius-md dropdown-item" href="javascript:;" id="{{$data->id}}" charges="{{$data->charges}}">{{$data->broker_name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-3 team-dropdown hidden">
                    <label>Select a Team:</label><br />
                    <div class="dropdown">
                        <button type="button" class="btn btn-dark dropdown-toggle" id="team-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            Teams
                        </button>
                        <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton" id="selectedTeam">
                        </ul>
                    </div>
                </div>
                <div class="col-2">
                </div>
                <div class="col-4 round-info hidden">
                    <label>Round Limit:</label><label id="amount_can_be_used"></label><br />
                    <label>Cash In Hand: </label><label id="cash_in_hand"></label><br />
                    <label>Limit Used: </label><label id="limit_used">0</label><br />
                    <label class="hidden">Cash In Hand After Order Execution: </label><label id="order_past_cash_in_hand" class="hidden"></label>
                </div>
            </div>
            <div class="row orderForm hidden">
                <h1 class="hidden" id="amount_used">{{json_encode($amount_used)}}</h1>
                <h1 class="hidden" id="cash_available">{{json_encode($cash_available)}}</h1>
                <h1 class="hidden" id="amount_alloted">{{$amount_alloted}}</h1>
                <h1 class="hidden" id="active_round">{{$active_round_id}}</h1>
                <h1 class="hidden" id="limit_flag">{{$limit_flag}}</h1>
                <h1 class="hidden" id="team"></h1>
                <h1 class="hidden" id="broker"></h1>
                <h1 class="hidden" id="brokerage_value"></h1>
                <h3 style="text-align:center" id="active_round_name">{{$active_round_display_name}}</h3>
                <form id="myForm">
                    @csrf
                    <table style="font-size: medium;">
                        <thead>
                            <tr>
                                <th>Company ID</th>
                                <th>Company Name</th>
                                <th>Price</th>
                                <th>Buy Quantity</th>
                                <th>Buy Value</th>
                                <th>Sell Quantity</th>
                                <th>Sell Value</th>
                                <th>Brokerage</th>
                                <th>Quantity Owned</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody id="companiesData">
                            @foreach ($companies as $company)
                            <tr id="company-{{ $company->id }}">
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->company_name }}</td>
                                <td>{{ $company->price }}</td>
                                <td><input type="number" name="buy_quantity" min="0" class="buy-quantity"></td>
                                <td class="buy-value" min="0"></td>
                                <td><input type="number" name="sell_quantity" min="0" class="sell-quantity"></td>
                                <td class="sell-value" min="0"></td>
                                <td class="brokerage-paid" min="0"></td>
                                <td class="quantity-owned"></td>
                                <td class="message" style="color: red;"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br />
                    <button type="submit" class="btn btn-sm btn-dark btn-icon d-flex align-items-center mb-0" id='submit-orders'>
                        <span class="btn-inner--text">Submit</span>
                    </button>
                </form>
            </div>
        </div>
    </main>
    @include('order_confirmation_modal')
</x-app-layout>
<script src="{{asset('admin/js/jquery.min.js?v=0.1')}}"></script>
<script src="{{asset('admin/js/orders.js?v=0.1')}}"></script>