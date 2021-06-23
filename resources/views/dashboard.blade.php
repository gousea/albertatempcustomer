@extends('layouts.layout')

@section('title')
    Dashboard
@stop

@section('main-content')

    <link rel="stylesheet" href="{{ asset('asset/css/dashboard.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Dashboard</span>
                </div>
                <div class="nav-submenu">
                    {{-- <a type="button" class="btn btn-gray headerblack  buttons_menu text-uppercase"
                        href="{{ route('dashboardlayout') }}"> Edit layout
                    </a> --}}
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
    <div class="container section-content">
        
    </div>

@endsection
