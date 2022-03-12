@extends('layouts.app2')

@section('page')
    <calendar-component csrf="{{ csrf_token() }}" patients="{{ $patients }}" appointments="{{ $appointments }}"
        is-admin="{{ Auth::user()->isAdmin }}">
    </calendar-component>
@endsection
