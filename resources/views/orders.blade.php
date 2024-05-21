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
                <div class="col-2">
                    <label>Select a Broker House:</label><br />
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-dark dropdown-toggle" id="broker-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            Broker House
                        </button>
                        <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                            @foreach($broker_houses as $key => $data)
                            <li><a class="brokerSelection border-radius-md" href="javascript:;" id="{{$data->id}}">{{$data->broker_name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-4 team-dropdown hidden">
                    <label>Select a Team:</label><br />
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-dark dropdown-toggle" id="team-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            Teams
                        </button>
                        <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton" id="selectedTeam">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row orderForm hidden">
                <h1 class="hidden" id="active_round">{{$active_round_id}}</h1>
                <h1 class="hidden" id="team"></h1>
                <h1 class="hidden" id="broker"></h1>
                <h3 style="text-align:center">{{$active_round_display_name}}</h3>
                <form id="myForm">
                    @csrf
                    <table>
                        <thead>
                            <tr>
                                <th>Company ID</th>
                                <th>Company Name</th>
                                <th>Price</th>
                                <th>Buy Quantity</th>
                                <th>Buy Value</th>
                                <th>Sell Quantity</th>
                                <th>Sell Value</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            @foreach ($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->company_name }}</td>
                                <td>{{ $company->price }}</td>
                                <td><input type="number" name="buy_quantity" min="0" class="buy-quantity"></td>
                                <td class="buy-value" min="0"></td>
                                <td><input type="number" name="sell_quantity" min="0" class="sell-quantity"></td>
                                <td class="sell-value" min="0"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br />
                    <button type="submit" class="btn btn-sm btn-dark btn-icon d-flex align-items-center mb-0">
                        <span class="btn-inner--text">Submit</span>
                    </button>
                </form>
            </div>
        </div>
    </main>

</x-app-layout>
<script src="{{asset('admin/js/jquery.min.js?v=0.1')}}"></script>
<script src="{{asset('admin/js/orders.js?v=0.1')}}"></script>