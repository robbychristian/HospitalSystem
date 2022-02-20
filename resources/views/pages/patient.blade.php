@extends('layouts.app2')

@section('page')
    <patient-component patient="{{$patient}}" user-data=" {{ json_encode( Auth::user() )}}"></patient-component>
@endsection