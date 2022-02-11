@extends('layouts.app2')

@section('page')
    <test-component csrf="{{ csrf_token() }}"></test-component>
@endsection
