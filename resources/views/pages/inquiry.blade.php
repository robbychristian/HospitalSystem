@extends('layouts.app2')

@section('page')
    <inquiry-component 
        patient-data="{{$patient}}" user-data="{{ json_encode( Auth::user() )}}"
        now="{{ now()->format('d/m/Y') }}" csrf='{{csrf_token()}}'
    ></inquiry-component>
@endsection