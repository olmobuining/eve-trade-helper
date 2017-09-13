@extends('layouts.base')
@section('sidebar')
    @component('components.topbar')
    @endcomponent
    <!-- /.navbar-header -->
    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="{{ URL::route('logout') }}"><i class="fa fa-sign-out fa-fw"></i> {{ __('Logout') }}</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="/"><i class="fa fa-dashboard fa-fw"></i> {{ __('Start') }}</a>
                </li>

            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
@endsection

@section('basecontent')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{{ $character_name }}</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>


        @if (Session::has('flash_message'))
            <div class="alert-block ale alert-error alert">
                {{ Session::get('flash_message') }}
            </div>
        @endif
        @yield('content')
    </div>
@endsection
