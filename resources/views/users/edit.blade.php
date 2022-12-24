@extends('layouts.master')
@section('title','Edit User')
@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <h2 class="text-center">Edit User</h2>
        </div>
        <div class="col-lg-12">
            <form method="POST" action="{{route('usersUpdate', encrypt($user->id))}}">
                @csrf
                <div class="row"> 
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Name <span class="required text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{$user->name}}" required="required">
                            <div style="width: 100%;margin-top: 0.25rem;font-size: 80%;color: #fd397a;">{{ $errors->first('name')}}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Email <span class="required text-danger">*</span></label>
                            <input type="text" name="email" class="form-control" placeholder="Enter Email" value="{{$user->email}}" required="required">
                            <div style="width: 100%;margin-top: 0.25rem;font-size: 80%;color: #fd397a;">{{ $errors->first('email')}}</div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <input type="submit" value="Submit" class="btn btn-success">
                        <a href="{{route('usersIndex')}}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection