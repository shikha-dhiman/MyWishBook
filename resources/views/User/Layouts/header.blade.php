<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>MyWishBook</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Pignose Calender -->
    <link href="{{asset('user_asset/plugins/pg-calendar/css/pignose.calendar.min.css')}}" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="{{asset('user_asset/plugins/chartist/css/chartist.min.css')}}">
    <link rel="stylesheet" href="{{asset('user_asset/plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css')}}">
    <link href="{{asset('user_asset/plugins/tables/css/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="{{asset('user_asset/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('user_asset/css/custom.css')}}" rel="stylesheet">
</head>

<body>
     <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <div class="nav-header">
            <div class="brand-logo">
                <a href="{{url('/')}}">
                    <b class="logo-abbr"><img src="{{asset('user_asset/images/logo.png')}}" alt=""> </b>
                    <span class="logo-compact"><img src="{{asset('user_asset/images/logo-compact.png')}}" alt=""></span>
                    <span class="brand-title">
                        <!-- <img src="{{asset('user_asset/images/logo-text.png')}}" alt=""> --><h2 class="text-white">MyWishBook</h2>
                    </span>

                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->
