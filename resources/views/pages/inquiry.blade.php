@extends('layouts.app2')

@section('page')
    <inquiry-component 
        patient-data="{{$patient}}" user-data="{{ json_encode( Auth::user() )}}"
        now="{{ now()->format('d-M-Y') }}"
    ></inquiry-component>
@endsection