@extends('layouts.app2')

@section('page')
    <calendar-component csrf="{{ csrf_token() }}" patients="{{ $patients }}" appointments="{{ $appointments }}"
        is-admin="{{ Auth::user()->isAdmin }}" doctors-data="{{ $doctors }}" user-data="{{ json_encode( Auth::user() )}}"
        hospital="{{$hospital}}"
    >
    </calendar-component>
@endsection
