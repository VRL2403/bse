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
            <div class="row pt-8">
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
            </div>
        </div>
    </main>

</x-app-layout>
<script src="{{asset('admin/js/jquery.min.js?v=0.1')}}"></script>
<script src="{{asset('admin/js/config_stats.js?v=0.1')}}"></script>