<x-app-layout>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Companies list</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Company Name
                                            </th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">
                                                Opening Bell</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Round 1</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Round 2</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">
                                                Round 3</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Round 4</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Round 5</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">
                                                Round 6</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Round 7</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Round 8</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">
                                                Round 9</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Round 10</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Closing Bell</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($companies as $key => $data)
                                        <tr>
                                            <td>{{ $data->company_name }}</td>
                                            <td>{{ $data->opening_bell_price }}</td>
                                            <td>{{ $data->round_one_price }}</td>
                                            <td>{{ $data->round_two_price }}</td>
                                            <td>{{ $data->round_three_price }}</td>
                                            <td>{{ $data->round_four_price }}</td>
                                            <td>{{ $data->round_five_price }}</td>
                                            <td>{{ $data->round_six_price }}</td>
                                            <td>{{ $data->round_seven_price }}</td>
                                            <td>{{ $data->round_eight_price }}</td>
                                            <td>{{ $data->round_nine_price }}</td>
                                            <td>{{ $data->round_ten_price }}</td>
                                            <td>{{ $data->closing_bell_price }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>

</x-app-layout>