@extends('layouts.app2')

@section('page')
{{-- user-data=" {{ json_encode( Auth::user() )}}" --}}
    <appointment-component 
        appointment-data="{{$appointment}}" is-admin="{{Auth::user()->isAdmin}}"
        hospital-data="{{$hospitals}}"
    ></appointment-component>
@endsection