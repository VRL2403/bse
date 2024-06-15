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
            @csrf
            <button type="button" class="btn btn-dark" id="cal_stats">Calculate Stats</button>

            <hr />
            <div class="row pt-2">
                <h3>Set Active Round</h3>
                <div class="row">
                    <div class="col-4">
                        <div class="dropdown">
                            <button type="button" class="btn btn-dark dropdown-toggle" id="rounds-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                Rounds
                            </button>
                            <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                                @foreach($rounds as $key => $data)
                                <li><a class="round dropdown-item" href="javascript:;" id="{{$data->id}}">{{$data->round_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                        <p><b>Current Active Round: </b>{{$active_round_display_name}}</p>
                    </div>
                </div>
                <br />
                <hr />
                <h3>Dividends</h3>
                <div class="row">
                    <form id="dividendForm">
                        @csrf
                        <div class="col-12">
                            <div class="dropdown">
                                <button type="button" class="btn btn-dark dropdown-toggle" id="companies-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                    Companies
                                </button>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                                    @foreach($companies as $key => $data)
                                    <li><a class="company dropdown-item" href="javascript:;" id="{{$data['id']}}">{{$data['company_name'] }}</a></li>
                                    @endforeach
                                </ul>
                                <input name="company_id" class="company_value hidden"></input>
                            </div>
                            <input type="number" name="dividend" id="dividend" placeholder="Enter dividend value">
                            <button id="dividend-submit" class="btn btn-dark" disabled>Submit</button>
                            <div id="errorMsg" style="color: red; display: none;"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>
<script src="{{asset('admin/js/jquery.min.js?v=0.1')}}"></script>
<script src="{{asset('admin/js/config_stats.js?v=0.1')}}"></script>