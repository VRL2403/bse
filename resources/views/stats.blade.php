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
            <div class="row orderForm hidden">
                <form id="myForm">
                    @csrf
                    <table>
                        <thead>
                            <tr>
                                <th>Team Name</th>
                                <th>Cash Ledger</th>
                                <th>Portfolio Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $data)
                            <tr>
                                <td id="{{ $data->id }}">{{$data->team_name}}</td>
                                <td>{{ $data->cash_ledger }}</td>
                                <td>{{ $data->portfolio_value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br />
                </form>
            </div>
        </div>
    </main>

</x-app-layout>
<script src="{{asset('admin/js/jquery.min.js?v=0.1')}}"></script>