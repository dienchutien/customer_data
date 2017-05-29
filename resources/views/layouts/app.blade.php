<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo  config('cmconst.text.title') ?></title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
    <link href="<?php echo URL::to('/');?>/css/sb-admin-2.css" rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="<?php echo URL::to('/');?>/images/favicon.ico">
    <!-- Styles -->

    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <div id="wrapper">
        <div class="form-control hidden" id="alert"><?php echo (session('status')?session('status'):'')?></div>
        <nav class="navbar navbar-default header-page">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" class="navbar-brand"><img class="logo" src="/images/logo-dxmb.jpg" alt=""></a>
                <!-- Branding Image -->
                
            </div>
            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a style="color:#337ab7" href="#">CUSTOMER-TOOL</a></li>
                </ul>
                <!-- Modal -->
                
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (!Auth::guest())
                        
                        <li class="dropdown">
                            <a class="dropdown-toggle" role="button" href="javascript: GLOBAL_JS.v_fToggleLeftSide();">
                                <i class="fa fa-exchange"></i><span class="header_color"> Ẩn hiện menu trái</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" style="color:#337ab7" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
    
    
            @if (!Auth::guest())
            
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li class="">
                            <a href="#"><i class="fa fa-user"></i>Quản lý User<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li class=""><a class="" href="<?php echo Request::root()."/list_users"?>">Danh Sách User</a></li>
                                <li class=""><a class="" href="<?php echo Request::root()."/user/addedit"?>">Thêm mới User</a></li>
                            </ul>
                        </li>
<!--                        <li class="">
                            <a href="#"><i class="fa fa-user"></i>Quản lý Dự Án<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li class=""><a class="" href="<?php echo Request::root()."/list_projects"?>">Danh Sách Dự Án</a></li>
                                <li><a class="" href="<?php echo Request::root()."/projects/addedit"?>">Thêm Mới Dự Án</a></li>
                            </ul>
                        </li>
                        <li class="">
                            <a href="#"><i class="fa fa-user"></i>Quản lý Nhà Cung Cấp<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li class=""><a class="" href="<?php echo Request::root()."/list_supplier"?>">Danh Sách Nhà Cung Cấp</a></li>
                                <li><a class="" href="<?php echo Request::root()."/supplier/addedit"?>">Thêm Mới Nhà Cung Cấp</a></li>
                            </ul>                            
                        </li>
                        <li class="">
                            <a href="#"><i class="fa fa-user"></i>Quản lý Kênh<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li class=""><a class="" href="<?php echo Request::root()."/list_channel"?>">Danh sách kênh</a></li>
                                <li><a class="" href="<?php echo Request::root()."/channel/addedit"?>">Thêm Mới kênh</a></li>
                            </ul>                            
                        </li>
                        <li class="">
                            <a href="#"><i class="fa fa-user"></i>Quản lý chi nhánh<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li class=""><a class="" href="<?php echo Request::root()."/list_branch"?>">Danh Sách Chi Nhánh</a></li>
                                <li><a class="" href="<?php echo Request::root()."/branch/addedit"?>">Thêm Mới Chi Nhánh</a></li>
                            </ul>                            
                        </li>-->
                        <li class="">
                            <a href="#"><i class="fa fa-user"></i>Quản lý Tác Vụ<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li class=""><a class="" href="<?php echo Request::root()."/list_job"?>">Danh sách tác vụ</a></li>
                                <li><a class="" href="<?php echo Request::root()."/job/addedit"?>">Thêm tác vụ</a></li>
                                <li><a class="" href="<?php echo Request::root()."/job_statistics"?>"">Thống kê tác vụ</a></li>
                            </ul>                            
                        </li>
                        <li class="">
                            <a href="#"><i class="fa fa-lock"></i> Quản lý phân quyền<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="" href="<?php echo Request::root()."/list_role_group"?>">Danh sách nhóm quyền</a></li>
                                <li><a class="" href="<?php echo Request::root()."/role/insert"?>">Thêm mới nhóm quyền</a></li>
                            </ul>
                        </li>
                        <li class="">
                            <a href="#"><i class="fa fa-user"></i>Quản lý dữ liệu kh<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <li><a class="" href="<?php echo Request::root()."/customer/list"?>">Danh sách khách hàng</a></li>
                                <li><a class="" href="<?php echo Request::root()."/customer/addedit"?>">Thêm khách hàng</a></li>
                                <li><a class="" href="<?php echo Request::root()."/customer/statistics"?>">Thống kê</a></li>
                            </ul>                            
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
            @endif
        </nav>
        @if (Auth::guest())
            <div id="page-wrapper" class="no-margin">
        @else
            <div id="page-wrapper">
        @endif
            @yield('content')
        </div>
    </div>
    </div>
    
    <!-- /#page-wrapper -->
    <!-- JavaScripts -->
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="<?php echo URL::to('/');?>/js/metisMenu.min.js"></script>
    <script src="<?php echo URL::to('/');?>/js/sb-admin-2.js"></script>
    <script src="<?php echo URL::to('/');?>/js/global.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
</body>
</html>
