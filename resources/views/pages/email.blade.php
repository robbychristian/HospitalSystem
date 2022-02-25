@extends('layouts.app')

@section('content')
    <email-verification 
        email="{{ Auth::user()->email }}" verify-data="{{$verify}}"
        token='{{csrf_token()}}'
    ></email-verification>
@endsection