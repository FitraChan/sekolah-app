<!DOCTYPE html>
<!--
Template Name: Tinker - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" class="light" >
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{asset('dist/images/logo.png')}}" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Tinker admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Tinker Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>@yield('tittle')</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{asset('dist/css/app.css')}}" />

        <link href="https://unpkg.com/tabulator-tables@6.2.5/dist/css/tabulator.min.css" rel="stylesheet">

<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.5/dist/js/tabulator.min.js"></script>
        <!-- END: CSS Assets-->

        {{-- tabulator --}}
        

        

        {{-- sweatalert --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        

    </head>
    <!-- END: Head -->
    <body class="py-5 md:py-0 bg-black/[0.15] dark:bg-transparent">
        @if (empty($side))
            @php
                $side = '';
            @endphp
        @endif
        @if (empty($drop_down))
            @php
                $drop_down = '';
            @endphp
        @endif
        <!-- BEGIN: Mobile Menu -->
        <div class="mobile-menu md:hidden">
            <div class="mobile-menu-bar">
                <a href="" class="flex mr-auto">
                    <img alt="Midone - HTML Admin Template" class="w-6" src="{{asset('/dist/images/logo.png')}}">
                </a>
                <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
            </div>
            <div class="scrollable">
                <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
                <ul class="scrollable__content py-2">
                    
                    
                  
                  
                   
                    <li class="menu__devider my-6"></li>
                </ul>
            </div>
        </div>
        <!-- END: Mobile Menu -->
        <div class="flex mt-[4.7rem] md:mt-0 overflow-hidden">
            <!-- BEGIN: Side Menu -->
            <nav class="side-nav">
                <a href="" class="intro-x flex items-center pl-5 pt-4 mt-3">
                    <img alt="Midone - HTML Admin Template" class="w-6" src="{{asset('dist/images/logo.png')}}">
                    <span class="hidden xl:block text-white text-lg ml-3"> SIAMI </span> 
                </a>
                <div class="side-nav__devider my-6"></div>
                <ul>

        
                    <!-- <li>
                        <a href="{{route('admin')}}" class="side-menu {{$side == 'admin'? 'side-menu--active':''}}">
                            <div class="menu__icon"> <i data-lucide="home"></i> </div>
                            <div class="menu__title"> Dashboard </div>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('role.index') }}" class="side-menu {{ $side == 'role' ? 'side-menu--active' : '' }}">

                            <div class="menu__icon">
                                <i data-lucide="shield"></i>
                            </div>

                            <div class="menu__title">
                                Management Role
                            </div>

                        </a>
                    </li> -->



                     <li>
                        <a href="{{route('admin')}}" class="side-menu {{$side == 'admin'? 'side-menu--active':''}}">
                            <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                            <div class="side-menu__title"> Dashboard</div>
                        </a>
                    </li>

                    
                    <li>
                        <a href="{{ route('role.index') }}" class="side-menu {{ $side == 'role' ? 'side-menu--active' : '' }}">

                            <div class="side-menu__icon">
                                <i data-lucide="shield"></i>
                            </div>

                            <div class="side-menu__title">
                                Management Role
                            </div>

                        </a>
                    </li>
                   
                   

                   
                   
                   
                </ul>
            </nav>
            <!-- END: Side Menu -->
            <!-- BEGIN: Content -->
            <div class="content">
                <!-- BEGIN: Top Bar -->
                <div class="top-bar -mx-4 px-4 md:mx-0 md:px-0">
                    <!-- BEGIN: Breadcrumb -->
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        @yield('top-nav')
                        
                    </nav>
                    <!-- END: Breadcrumb -->
                    <!-- BEGIN: Account Menu -->
                    <div class="intro-x dropdown w-8 h-8">
                        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-tw-toggle="dropdown">
                            <img alt="Midone - HTML Admin Template">
                        </div>
                        <div class="dropdown-menu w-56">
                            <ul class="dropdown-content bg-primary text-white">
                                <li class="p-2">
                                    <div class="font-medium"></div>
                                    <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500">Guru</div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-white/[0.08]">
                                </li>
                                <li>
                                    <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profile </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-white/[0.08]">
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}" class="dropdown-item hover:bg-white/5"> <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- END: Account Menu -->
                </div>
                <!-- END: Top Bar -->
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 2xl:col-span-12">
                        <div class="grid grid-cols-12 gap-6">
                            <!-- BEGIN: General Report -->
                            <div class="col-span-12 mt-8">
                                @include('layout.response')
                                @yield('body')

                            </div>
                            <!-- END: General Report -->
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Content -->
        </div>
        
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="{{asset('dist/js/app.js')}}"></script>
        <!-- END: JS Assets-->
        {{-- ckeditor --}}
        <script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
        {{-- ckeditor --}}
    </body>
</html>