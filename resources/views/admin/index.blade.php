@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Card 1</div>
                <div class="card-body">
                    {{-- Content for Card 1 --}}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Card 2</div>
                <div class="card-body">
                    {{-- Content for Card 2 --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection