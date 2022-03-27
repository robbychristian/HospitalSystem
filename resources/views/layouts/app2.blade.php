@extends('layouts.app')

@php
    $photo = Auth::user()->photoUrl;
    if( $photo == null || $photo == ''){
        $photo = '/img/avatar.png';
    }
    else{
        $sub = strtolower(substr($photo, 0, 5));
        if( $sub != 'https' ){
            $photo = Firebase::storage()->getBucket()->object($photo)->signedUrl(now()->addDays(1));
        }
    }
@endphp

@section('content')
    <div class="main-page">
        <sidebar 
            active="{{$active}}" token="{{ csrf_token() }}" 
            name="{{ 
                Auth::user()->isAdmin ?
                'ADMIN' :
                Auth::user()->name 
            }}" 
            profile-picture="{{ $photo }}"
            is-admin="{{ Auth::user()->isAdmin }}"
        ></sidebar>
        
        <div class="main-content">
            
            <topbar page-name="{{$page}}"></topbar>
            
            @yield('page')
        </div>
    </div>
@endsection

{{-- Auth::user()->isAdmin ?
                Auth::user()->photoUrl :
                Firebase::storage()->getBucket()->object(Auth::user()->photoUrl)->signedUrl(now()->addDays(1))  --}}