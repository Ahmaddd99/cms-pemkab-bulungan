@extends('layouts.master')
@section('content')
<div class="row">
    <h3>Halo {{Auth::user()->name}}</h3>
</div>
<div class="row">
    <div class="card col-8">
        <div class="card-header">
            <h5>Dashboard</h5>
        </div>
        <div class="card-body">
            <h5>Petunjuk</h5>
            <ul>
                <li>test 1</li>
                <li>test 2</li>
                <li>test 3</li>
                <li>test 4</li>
            </ul>
        </div>
    </div>
</div>
@endsection
