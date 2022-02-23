@extends('layouts.app')

@section('content')
    <div class="main-page">
        <sidebar 
            active="{{$active}}" token="{{ csrf_token() }}" 
            name="{{ Auth::user()->name }}"
        ></sidebar>
        
        <div class="main-content">
            
            <topbar page-name="{{$page}}"></topbar>
            
            @yield('page')
        </div>
    </div>
@endsection