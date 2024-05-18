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
                                    <h6 class="font-weight-semibold text-lg mb-0">Teams list</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">Team Name
                                            </th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">
                                                Member One</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">
                                                Member Two</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">
                                                Member Three</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">
                                                Member Four</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teams as $key => $data)
                                        <tr>
                                            <td>{{ $data->team_name }}</td>
                                            <td>{{ $data->first_team_member }}</td>
                                            <td>{{ $data->second_team_member }}</td>
                                            <td>{{ $data->third_team_member }}</td>
                                            <td>{{ $data->fourth_team_member }}</td>
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