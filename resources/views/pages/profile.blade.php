@extends('layouts.app2')
@section('page')
    <profile-component profile-data="{{ json_encode($details) }}" csrf="{{ csrf_token() }}"></profile-component>
@endsection
