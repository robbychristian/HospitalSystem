@extends('layouts.app')

@section('content')
    <div class="main-page">
        <sidebar active="{{$active}}"></sidebar>
        
        <div class="main-content">
            
            <topbar page-name="{{$page}}"></topbar>
            
            @yield('page')
        </div>
    </div>
@endsection