@extends('layouts.app')

<<<<<<< HEAD
<<<<<<< HEAD
@section('page')
    <test-component csrf="{{ csrf_token() }}"></test-component>
=======
@section('content')
    <login-component data="{{$data}}"></login-component>
>>>>>>> parent of 5434b79 (Polished Login)
=======
@section('content')
    <login-component data="{{$data}}"></login-component>
>>>>>>> parent of 5434b79 (Polished Login)
@endsection
