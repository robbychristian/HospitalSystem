@extends('layouts.app2')

@section('page')
    <dashboard-component user-data="{{ json_encode(Auth::user()) }}" doctors="{{ $doctors }}">
    </dashboard-component>
@endsection
