<!DOCTYPE html>
<html dir="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <script type="text/javascript" src="{{ asset('javascript/jquery/jquery-2.1.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('javascript/bootstrap/js/bootstrap.min.js') }}"></script>

    <link href="{{ asset('stylesheet/bootstrap.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('javascript/font-awesome/css/font-awesome.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('javascript/summernote/summernote.css') }}" rel="stylesheet" />

    <script type="text/javascript" src="{{ asset('javascript/summernote/summernote.js') }}"></script>
    
    <link type="text/css" href="{{ asset('stylesheet/stylesheet.css') }}" rel="stylesheet" media="screen" />

    <script src="{{ asset('javascript/common.js') }}"  type="text/javascript"></script>


    <link rel="stylesheet" href="{{ asset('stylesheet/jquery-ui.css') }}">
    <script src="{{ asset('javascript/jquery/jquery-ui.js') }}"></script>





<style>

/*body{*/
/*    overflow-y: hidden !important;*/
/*}*/

/*#content{*/
/*    height: 550px;*/
/*    overflow-y: scroll;*/
/*}*/

.dropbtn {
    background-color: #f05a28;
    color: white;
    padding: 13px;
    font-size: 14px;
    border: none;
    cursor: pointer;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    overflow-y: auto;
    height: 150px;
}

.dropdown-content span {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content span:hover {background-color: #f05a28;cursor: pointer;color:#fff;}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #f05a28;
}
#header{
    background-color:#03A9F4;
}
</style>

@yield('styles')

</head>
<body style="background-color: #fff">
    <div id="divLoading" class="show"></div>
    <div id="container">
        <header id="header" class="navbar navbar-static-top" 
            <?php if(session()->get('hq_sid') == 1 ){ ?>
            style="background-color: #F05A28 !important;"
            <?php } ?>
        >
            <div class="navbar-header">
                @guest
                @else
                <a type="button" id="button-menu" id="menu-toggle" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
                @endguest
                <a href="" class="navbar-brand" style="color: #fff; font-size: 20px; font-weight: bold;">{{ config('app.name') }}</a>
            </div>

            @guest
            @else
            <ul class="nav pull-right">
                <li><a href="" title="Store" onClick="openNavStore()"  class="di_store_name" style="border-left:none;">{{ session()->get('storeName') }} [{{ session()->get('sid') }}] <i class="fa fa-chevron-down"></i></a></li>
                
                <?php if(session()->get('hq_sid') == 1 ){ ?>
                    <li><a href="" title="Head quarters"  class="di_store_hq_name" onClick="openHqStore()"> Head Quarters<i class="fa fa-chevron-down"></i></a></li>
                <?php } ?>
                
                <?php if(session()->get('hq_sid') != 1 ){ ?>
                    <li id="quick_links"><a href="" title="Reports" onClick="openNavReports()" class="di_reports"><i class="fa fa-bar-chart"></i></a></li>
                <?php } ?>
                <li><a href="" title="Settings"><i class="fa fa-cog"></i></a></li>
                <li><a href="{{ route('dashboard_quick_links') }}" title="Quick Links"><i class="fa fa-external-link"></i></a></li>
                <li>
                    <a  href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out fa-lg"></i>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
            <div id="mySidenavStore" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onClick="closeNavStore()">&times;</a>
                  <div class="side_content_div">
                    <h5>Stores</h5>
                    <div class="side_inner_content_div">
                      <p><input type="text" name="" placeholder="search store" style="width:75%;" class="form-control" id="store_search"></p><br>
                        @if(session()->has('stores'))
                            @foreach (session()->get('stores') as $store)
                                <a class="change_store" style="font-size: 13px; margin-bottom: -5px" href="{{ route('dashboard') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('store-form{{ $store->id }}').submit();">
                                    <?php if($store->hq_sid == 1) { ?>
                                        {{ $store->name }} [{{ $store->id }}] [HQ]
                                    <?php } else { ?>
                                        {{ $store->name }} [{{ $store->id }}]
                                    <?php }?>
                                    
                                </a>
                                <form id="store-form{{ $store->id }}" action="{{ route('dashboard') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="sid" id="sid_{{$store->id}}" value="{{ $store->id }}">
                                </form>
                            @endforeach
                        @endif
                    </div>
                  </div>
              </div>
              
              <!-- this is side drawer for HQ-->
              <div id="myHqStore" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onClick="closeHqStore()">&times;</a>
                  <div class="side_content_div">
                    <h5>Stores</h5>
                    <div class="side_inner_content_div">
                      <p><input type="text" name="" placeholder="search store" style="width:75%;" class="form-control" id="store_search"></p><br>
                        @if(session()->has('stores_hq'))
                              @foreach (session()->get('stores_hq') as $store)
                                <a class="change_store" style="font-size: 13px; margin-bottom: -5px" href="{{ route('dashboard') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('store-form{{ $store->id }}').submit();">
                                    {{ $store->name }} [{{ $store->id }}]
                                </a>
                                <form id="store-form{{ $store->id }}" action="{{ route('dashboard') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="sid" id="sid_{{$store->id}}" value="{{ $store->id }}">
                                </form>
                            @endforeach
                        @endif
                    </div>
                  </div>
              </div>
              <!-- End of Side Drawer for HQ-->
              
              
              <div id="mySidenavReports" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onClick="closeNavReports()">&times;</a>
                <div class="side_content_div">
                    <h5>Reports</h5>
                    <div class="side_inner_content_div">
                        <input type="text" name="" placeholder="search report" style="width:75%;" class="form-control" id="report_search"><br>
                        <p><a class="report_name" href="{{ route('EodReport') }}">End Of Day</a></p>
                        <p><a class="report_name" href="{{ route('EodShift') }}">End Of Shift</a></p>
                        <p><a class="report_name" href="{{ route('CardReport') }}">Credit Card Report</a></p>
                        <p><a class="report_name" href="{{ route('scan_data_report') }}">Scan Data Report</a></p>
                        <p><a class="report_name" href="{{ route('SalesTransaction') }}">Sales Transaction</a></p>
                        <p><a class="report_name" href="{{ route('TaxReport') }}">Tax Report</a></p>
                        <p><a class="report_name" href="{{ route('HourlySalesReport') }}">Hourly Sales</a></p>
                        <p><a class="report_name" href="{{ route('POReport') }}">PO History</a></p>
                        <p><a class="report_name" href="{{ route('InventoryReport') }}">Inventory on Hand</a></p>
                        <p><a class="report_name" href="{{ route('ProfitReport') }}">Profit & Loss</a></p>
                        <p><a class="report_name" href="{{ route('PaidoutReport') }}">Paid Out Report</a></p>
                        <p><a class="report_name" href="{{ route('BelowCostReport') }}">Below Cost</a></p>
                        <p><a class="report_name" href="{{ route('salesreport') }}">Sales Report</a></p>
                        <p><a class="report_name" href="{{ route('zeromovementreport') }}">Zero Movement</a></p>
                        <p><a class="report_name" href="{{ route('productlisting') }}">Product Listing Report</a></p>
                        <p><a class="report_name" href="{{ route('employeereport') }}">Employee Loss Prevention Report</a></p>
                        <p><a class="report_name" href="{{ route('itemsummary') }}">Item Summary</a></p>
                        <p><a class="report_name" href="{{ route('saleshistoryreport') }}">Sales History Report</a></p>
                        <p><a class="report_name" href="{{ route('salesanalyticsreport') }}">Sales Analytics</a></p>
                    </div>
                </div>
              </div>
            @endguest
        </header>
        @include('layouts.menu')
        @yield('main-content')
        <footer id="footer"><?php echo '&copy; 2020 All Rights Reserved.'; ?><br /></footer>
    </div>    
        <a href="javascript:void(0)" class="scrollToTop" title="Go To Top"><i class="fa fa-arrow-up"></i></a>
        
        @yield('script_files')


        <script>
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });
        </script>


        <style>
            li a.disabled {
              pointer-events: none;
              cursor: no-drop !important;

            }
            li a.disabled:hover {

              cursor: no-drop !important;

            }
        </style>

        <script type="text/javascript">
        $(document).ready(function(){
            /*to top*/
                $(window).scroll(function(){
                if ($(this).scrollTop() > 200) {
                    $('.scrollToTop').fadeIn();
                } else {
                    $('.scrollToTop').fadeOut();
                }
            });
            //Click event to scroll to top
            $('.scrollToTop').click(function(){
                $('html, body').animate({scrollTop : 0},800);
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


        $(window).scroll(function(){
            if ($(this).scrollTop() > 52) {
                $('.sidenav').css('top','0px');
            } else {
                $('.sidenav').css('top','45px');
            }
        });
        </script>

        <script type="text/javascript">
            $(document).on('keyup', '#store_search', function(event) {
                event.preventDefault();
                $('a.change_store').hide();
                var txt = $(this).val();

                if(txt != ''){
                  $('a.change_store').each(function(){
                    if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                      $(this).show();
                    }
                  });
                }else{
                  $('a.change_store').show();
                }
              });

            $(document).on('keyup', '#report_search', function(event) {
                event.preventDefault();
                $('a.report_name').hide();
                var txt = $(this).val();

                if(txt != ''){
                  $('a.report_name').each(function(){
                    if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                      $(this).show();
                    }
                  });
                }else{
                  $('a.report_name').show();
                }
              });
        </script>

        <style type="text/css">
            #header .navbar-header a.navbar-brand{
                color: #fff;
                font-size: 20px;
                font-weight: bold;
            }
        </style>



        <script type="text/javascript">
            $(document).on('click', '#forgotten_link', function(event) {
                event.preventDefault();
                $('#forgottenModal').modal('show');
            });

            $(document).on('click', '.editable_all_selected', function(event) {
              event.preventDefault();
              $(this).select();
            });
        </script>

        <!-- rotating logo -->

        <script type="text/javascript">

          $(document).on('click', '.breadcrumb li a, ul.pagination > li, #header > ul > li:eq(2),#header > ul > li:eq(3), #header > ul > li:eq(4), #mySidenavStore > div.side_content_div > div.side_inner_content_div > p:not(:eq(0)), #mySidenavReports > div.side_content_div > div.side_inner_content_div > p:not(:eq(0)), #myHqStore > div.side_content_div > div.side_inner_content_div > p:not(:eq(0)), .edit_btn_rotate, .cancel_btn_rotate, .add_new_btn_rotate, .save_btn_rotate, .show_all_btn_rotate', function(event) {
            $("div#divLoading").addClass('show');
          });

          $(document).on('click', '#menu li a', function(event) {
            if (!$(this).hasClass("parent")) {
              $("div#divLoading").addClass('show');
            }
          });
        </script>

        <!-- rotating logo -->

        <script type="text/javascript">
          $(document).on('paste', '.nordqty_class, .npackqty_class, input[name="iqtyonhand"], .ndebitqty_class, .ntransferqty_class', function(event) {
            event.preventDefault();

          });
        </script>

        <script type="text/javascript">
          function isValid(str) {
              return !/[~`!@#$%\^&*()+=\-\[\]\\';._,/{}|\\":<>\?]/g.test(str);
          }
        </script>

        <script type="text/javascript">
            $(window).load(function() {
              $("div#divLoading").removeClass('show');
            });

            $(window).on('beforeunload', function(){
              $("div#divLoading").addClass('show');
            });
        </script>

    @yield('scripts')

</body>
</html>
