@extends('layouts.app2')

@section('page')
    <announcement-component announcement-data="{{ $announcements }}" user-data="{{ json_encode(Auth::user()) }}"
        csrf="{{ csrf_token() }}">
    </announcement-component>
@endsection
