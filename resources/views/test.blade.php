@extends('layouts.app')

@section('content')
    <test-component data="{{json_encode($data)}}"></test-component>
@endsection