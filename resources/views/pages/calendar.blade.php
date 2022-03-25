@extends('layouts.app2')

@section('page')
    <calendar-component 

            csrf="{{ csrf_token() }}" patients="{{ $patients }}" appointments="{{ $appointments }}"
            is-admin="{{ Auth::user()->isAdmin }}" doctors-data="{{ $doctors }}"
            user-data="{{ $user }}" hospital="{{ $hospital }}"

        >

        {{-- dr-user-data="{{ $drData }}" --}}
    </calendar-component>
@endsection
