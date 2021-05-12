<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="htmlcss bootstrap menu, navbar, hover nav menu CSS examples" />
    <meta name="description" content="Bootstrap navbar hover examples for any type of project, Bootstrap 4" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    
    <title>Customer-Alberta | @yield('title')</title>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css" rel="stylesheet"
        type="text/css" />
    <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> --}}
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    <!-- Bootstrap files (jQuery first, then Popper.js, then Bootstrap JS) -->
    {{-- <link href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css" rel="stylesheet"
        type="text/css" /> --}}



    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> --}}

    {{-- <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.24/datatables.min.css" /> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" >
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" >


</head>

<body class="bg-light">
    <header class="section-header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary headermenublue top">
            <div class="container-fluid">
                <div class="logo">
                    <img src="/docs/4.0/assets/brand/bootstrap-solid.svg" width="30" height="30" alt="">
                    <a href="#" class="logo-name">ALBERTA POS</a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="header_date_text">
                        <li class="nav-item text-items"><?php echo date('l jS \of F Y h:i:s A'); ?></li>
                        <li class="nav-item text-items"> {{ Auth::user()->fname . ' ' . Auth::user()->lname }}</li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link  dropdown-toggle stores-menu" href="#" id="storename"
                                data-toggle="dropdown"> {{ session()->get('storeName') }}
                                [{{ session()->get('sid') }}] </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><input type="text" name="" placeholder="search store" class="form-control"
                                        id="store_search"></li>
                                @if (session()->has('stores'))
                                    @foreach (session()->get('stores') as $store)
                                        <li class="nav-item" id="nav-item">
                                            <a class="change_store dropdown-item"
                                                style="font-size: 13px; margin-bottom: -5px"
                                                href="{{ route('dashboard') }}"
                                                onclick="event.preventDefault();
                                                        document.getElementById('store-form{{ $store->id }}').submit();">
                                                <?php if ($store->hq_sid == 1) { ?>
                                                {{ $store->name }} [{{ $store->id }}] [HQ]
                                                <?php } else { ?>
                                                {{ $store->name }} [{{ $store->id }}]
                                                <?php } ?>

                                            </a>
                                        </li>
                                        <form id="store-form{{ $store->id }}" action="{{ route('dashboard') }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            <input type="hidden" name="sid" id="sid_{{ $store->id }}"
                                                value="{{ $store->id }}">
                                        </form>
                                    @endforeach
                                @endif
                            </ul>




                        </li>
                        <li class="nav-item"><a href="" title="Settings" class="icons"><i class="fa fa-cog"></i></a>
                        </li>
                        <li class="nav-item"><a href="" title="Notification" class="icons"><i class="fa fa-bell"
                                    aria-hidden="true"></i></a></li>
                        <li class="nav-item">
                            <a style="color: #fff !important;" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out fa-lg"></i>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </div> <!-- navbar-collapse.// -->
            </div>
        </nav>

    </header> <!-- section-header.// -->

    <!-- ========================= SECTION CONTENT ========================= -->

    @include('layouts.navigation')


    @section('main-content')

    @show



    @include('layouts.footer')

    <script type="text/javascript">
        $(document).ready(function() {
            /*to top*/
            $(window).scroll(function() {
                if ($(this).scrollTop() > 200) {
                    $('.scrollToTop').fadeIn();
                } else {
                    $('.scrollToTop').fadeOut();
                }
            });
            //Click event to scroll to top
            $('.scrollToTop').click(function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });
        });

    </script>

    <script type="text/javascript">
        $(document).on('click', '.change_store', function(event) {
            var store_id = $(this).attr('data-store-id');
            $('form#form_store_change #change_store_id').val(store_id);
            $('form#form_store_change').submit();

        });

    </script>

    <script>
        function openNavStore() {
            document.getElementById("mySidenavStore").style.width = "250px";
            document.getElementById("mySidenavReports").style.width = "0";
            document.getElementById("myHqStore").style.width = "0";
        }

        function closeNavStore() {
            document.getElementById("mySidenavStore").style.width = "0";
        }

        function openHqStore() {
            document.getElementById("myHqStore").style.width = "250px";
            document.getElementById("mySidenavReports").style.width = "0";
            document.getElementById("mySidenavStore").style.width = "0";
        }

        function closeHqStore() {
            document.getElementById("myHqStore").style.width = "0";
        }

        $(document).on('click', '.di_store_name,.di_reports', function(event) {
            event.preventDefault();
            /* Act on the event */
        });

        $(document).on('click', '.di_store_name,.di_reports, .di_store_hq_name', function(event) {
            event.preventDefault();
            /* Act on the event */
        });

        function openNavReports() {
            document.getElementById("mySidenavReports").style.width = "250px";
            document.getElementById("mySidenavStore").style.width = "0";
            document.getElementById("myHqStore").style.width = "0";
        }

        function closeNavReports() {
            document.getElementById("mySidenavReports").style.width = "0";
        }

        $(document).on('click', '.di_store_name', function(event) {
            event.preventDefault();
            /* Act on the event */
        });


        $(window).scroll(function() {
            if ($(this).scrollTop() > 52) {
                $('.sidenav').css('top', '0px');
            } else {
                $('.sidenav').css('top', '45px');
            }
        });

    </script>

    <script type="text/javascript">
        $(document).on('keyup', '#store_search', function(event) {
            event.preventDefault();
            $('a.change_store').hide();
            var txt = $(this).val();

            if (txt != '') {
                $('a.change_store').each(function() {
                    if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1) {
                        $(this).show();
                    }
                });
            } else {
                $('a.change_store').show();
            }
        });

        $(document).on('keyup', '#report_search', function(event) {
            event.preventDefault();
            $('a.report_name').hide();
            var txt = $(this).val();

            if (txt != '') {
                $('a.report_name').each(function() {
                    if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1) {
                        $(this).show();
                    }
                });
            } else {
                $('a.report_name').show();
            }
        });

    </script>

</body>

</html>
