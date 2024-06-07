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
                    <label>Select a Team:</label><br />
                    <div class="dropdown">
                        <button type="button" class="btn btn-dark dropdown-toggle" id="team-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            Teams
                        </button>
                        <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                            @foreach($teams as $key => $data)
                            <li><a class="teamSelection dropdown-item" href="javascript:;" id="{{$data->id}}">{{$data->team_name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4 px-5">
            <div class="row holdingsTable hidden">
                <table id="holdings">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Quantity</th>
                            <th>Current Value/ Share</th>
                            <th>Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </main>

</x-app-layout>
<script src="{{asset('admin/js/jquery.min.js?v=0.1')}}"></script>
<script src="{{asset('admin/js/holdings.js?v=0.1')}}"></script>