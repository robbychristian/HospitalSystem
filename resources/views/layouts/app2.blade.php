@extends('layouts.app')

@section('content')
    <div class="main-page">
        <sidebar></sidebar>
        
        <div class="main-content">
            
            <topbar page-name="{{$page}}"></topbar>
            
            @yield('page')
        </div>
    </div>
@endsection