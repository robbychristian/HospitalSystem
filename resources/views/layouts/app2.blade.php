@extends('layouts.app')

@section('content')
    <div class="main-page">
        <sidebar 
            active="{{$active}}" token="{{ csrf_token() }}" 
            name="{{ Auth::user()->name }}" 
            profile-picture="{{ Firebase::storage()->getBucket()->object(Auth::user()->photoUrl)->signedUrl(now()->addDays(1)) }}"
        ></sidebar>
        
        <div class="main-content">
            
            <topbar page-name="{{$page}}"></topbar>
            
            @yield('page')
        </div>
    </div>
@endsection