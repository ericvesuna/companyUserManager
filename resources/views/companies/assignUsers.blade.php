@extends('layouts.master')
@section('title','Assign Users')
@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <h2 class="text-center">Assign Users</h2>
        </div>
        <div class="col-lg-12">
            <p><strong>Company Name:- </strong>{{ $company->name}}</p>
            <form method="POST" action="{{route('companiesStoreAssignUsers', encrypt($company->id))}}">
                @csrf
                <div class="row"> 
                    @foreach($users as $user)
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="users[]" value="{{$user->id}}" id="userCheck{{$user->id}}" {{ in_array($user->id, $companyUsers) ? 'checked' : ''}}>
                                <label class="form-check-label" for="userCheck{{$user->id}}">
                                    {{$user->name}}
                                </label>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-lg-12 mt-2">
                        <input type="submit" value="Submit" class="btn btn-success">
                        <a href="{{route('companiesIndex')}}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection