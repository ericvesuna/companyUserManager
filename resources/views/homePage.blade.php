@extends('layouts.master')
@section('title','Home')
@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <h2 class="text-center">Company User Management</h2>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="row">
                <div class="card col-lg-3 m-2">
                    <div class="card-body">
                        <h5 class="card-title">Total Users Count</h5>
                        <h3>{{$usersCount}}</h3>
                        <a href="{{route('usersIndex')}}" class="btn btn-primary">Click Here</a>
                    </div>
                </div>
                <div class="card col-lg-3 m-2">
                    <div class="card-body">
                        <h5 class="card-title">Total Companies Count</h5>
                        <h3>{{$companiesCount}}</h3>
                        <a href="{{route('companiesIndex')}}" class="btn btn-primary">Click Here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection