@extends('layouts.app2')

@section('page')
    <calendar-component csrf="{{ csrf_token() }}" patients="{{ $patients }}" appointments="{{ $appointments }}">
    </calendar-component>
@endsection
