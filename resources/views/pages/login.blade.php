@extends('layouts.app')

@section('content')
    @if (Session::get('Error'))
        <login-component data="{{ $data }}" csrf="{{ csrf_token() }}" error="{{ Session::get('Error') }}">
        </login-component>

    @else
        <login-component data="{{ $data }}" csrf="{{ csrf_token() }}" error="{{ Session::get('Error') }}">
        </login-component>
    @endif
@endsection
