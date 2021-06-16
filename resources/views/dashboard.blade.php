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
        <div class="col-md-12 col-xs-12 store-news">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-xs-8 pr-5">
                    <div class="text-muted">
                        <div class="heading">
                            <h6>Store Statistics</h6>
                        </div>
                        <div class="grid-container">
                            <div class="box grid-item1">
                                <div class="icons">
                                    <i class="fa fa-eye fa-3x text-muted transperent-icons" aria-hidden="true"></i>
                                </div>
                                <div class="text-numbers">
                                    {{-- <p class="buttons_menu a font-weight-bold">$2,193.00</p> --}}
                                    <h6>$ {{ $output['sales']['today'] }}</h6>
                                    <p class="buttons_menu c text-muted font-weight-bold">Today's Sales</p>
                                </div>
                            </div>
                            <div class="box grid-item2">
                                <div class="icons">
                                    <i class="fa fa-cutlery fa-3x text-muted transperent-icons" aria-hidden="true"></i>
                                </div>
                                <div class="text-numbers">
                                    <p class="buttons_menu a font-weight-bold">$ {{ $output['sales']['week'] }}</p>
                                    <p class="buttons_menu c text-muted font-weight-bold">Weekly sales</p>
                                </div>
                            </div>
                            <div class="box grid-item3">
                                <div class="icons">
                                    <i class="fa fa-clock-o fa-3x text-muted transperent-icons" aria-hidden="true"></i>
                                </div>
                                <div class="text-numbers">
                                    <p class="buttons_menu a font-weight-bold">{{ $output['customers']['today'] }}</p>
                                    <p class="buttons_menu c text-muted font-weight-bold">Today's Customers</p>
                                </div>
                            </div>
                            <div class="box grid-item4">
                                <div class="icons">
                                    <i class="fa fa-users fa-3x text-muted transperent-icons" aria-hidden="true"></i>
                                </div>
                                <div class="text-numbers">
                                    <p class="buttons_menu a font-weight-bold">{{ $output['customers']['week'] }}</p>
                                    <p class="buttons_menu c text-muted font-weight-bold">Weekly Customers</p>
                                </div>
                            </div>
                            <div class="box grid-item5">
                                <div class="icons">
                                    {{-- <i class="fa fa-eye fa-3x text-muted transperent-icons" aria-hidden="true"></i> --}}
                                    <i class="fa fa-th-large fa-3x text-muted transperent-icons" aria-hidden="true"></i>
                                </div>
                                <div class="text-numbers">
                                    <p class="buttons_menu a font-weight-bold">14</p>
                                    <p class="buttons_menu c text-muted font-weight-bold">New Items Added</p>
                                </div>
                                <br>
                                <div class="holder">
                                    <div class="ellipse"></div>
                                    <div class="ellipse ellipse2"></div>
                                </div>
                                {{-- <div class="usage chartist-chart" style="height:65px"><div class="chartist-tooltip" style="top: 27.0156px; left: 156.453px;"></div><svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-line" style="width: 100%; height: 100%;"><g class="ct-grids"></g><g><g class="ct-series ct-series-a"><path d="M30,60L30,37.5C36.576,45,43.152,60,49.728,60C56.304,60,62.879,6,69.455,6C76.031,6,82.607,55.5,89.183,55.5C95.759,55.5,102.335,24,108.911,24C115.487,24,122.063,46.5,128.638,46.5C135.214,46.5,141.79,12.75,148.366,6C154.942,-0.75,161.518,-3,168.094,-7.5L168.094,60Z" class="ct-area"></path><path d="M30,37.5C36.576,45,43.152,60,49.728,60C56.304,60,62.879,6,69.455,6C76.031,6,82.607,55.5,89.183,55.5C95.759,55.5,102.335,24,108.911,24C115.487,24,122.063,46.5,128.638,46.5C135.214,46.5,141.79,12.75,148.366,6C154.942,-0.75,161.518,-3,168.094,-7.5" class="ct-line"></path><line x1="30" y1="37.5" x2="30.01" y2="37.5" class="ct-point" ct:value="5"></line><line x1="49.72767857142857" y1="60" x2="49.73767857142857" y2="60" class="ct-point" ct:value="0"></line><line x1="69.45535714285714" y1="6" x2="69.46535714285714" y2="6" class="ct-point" ct:value="12"></line><line x1="89.18303571428572" y1="55.5" x2="89.19303571428573" y2="55.5" class="ct-point" ct:value="1"></line><line x1="108.91071428571429" y1="24" x2="108.9207142857143" y2="24" class="ct-point" ct:value="8"></line><line x1="128.63839285714286" y1="46.5" x2="128.64839285714285" y2="46.5" class="ct-point" ct:value="3"></line><line x1="148.36607142857144" y1="6" x2="148.37607142857144" y2="6" class="ct-point" ct:value="12"></line><line x1="168.09375" y1="-7.5" x2="168.10375" y2="-7.5" class="ct-point" ct:value="15"></line></g></g><g class="ct-labels"></g></svg></div> --}}
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-xs-4">
                    <div class="news-updates">
                        <div class="heading text-muted">
                            <h6>News & Updates</h6>
                        </div>
                        <div class="content text-muted">
                            <p>Alberta POS is proud to unveil version 4.0</p>
                            <p>Read our Latest Blog on "Wine Time"</p>
                            <p>We will be at Allen Brothers Trade Show on Oct 2, 2019</p>
                            <p>Mobile App has a new Update! Check the App Store Now</p>
                            <p>Edit Quick Item Bug has been resolved!</p>
                            <p>Check out our new TV Commercial</p>
                            <p>Check out our new TV Commercial</p>
                            <p>Check out our new TV Commercial</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section id="graph">
            <div class="container">
                <div class="row mt-3">
                    <div class="col-lg-8 col-md-8 col-xs-8 ">
                        <div class="box box-info">
                            {{-- <div class="box-header with-border d-flex justify-content-between">
                                <h4 class="box-title text-capitalize">last 7 day sales</h4>
                                <div class="box-tools pull-right dropdown">
                                    <button type="button" class="btn outline-secondary dropdown-toggle btn-sales"
                                        data-toggle="dropdown">
                                        On this day
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Normal</a>
                                        <a class="dropdown-item active" href="#">Active</a>
                                        <a class="dropdown-item disabled" href="#">Disabled</a>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body chart-responsive">
                                <div class="chart" id="line-chart"
                                    style="height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                    <svg height="300" version="1.1" width="512.219" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                        style="overflow: hidden; position: relative; left: -0.5px;">
                                        <desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with
                                            Raphaël 2.3.0
                                        </desc>
                                        <defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs>
                                        <text x="49.546875" y="261" text-anchor="end" font-family="sans-serif"
                                            font-size="12px" stroke="none" fill="#888888"
                                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;"
                                            font-weight="normal">
                                            <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0
                                            </tspan>
                                        </text>
                                        <path fill="none" stroke="#aaaaaa" d="M62.046875,261H487.21900000000005"
                                            stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </path>
                                        <text x="49.546875" y="202" text-anchor="end" font-family="sans-serif"
                                            font-size="12px" stroke="none" fill="#888888"
                                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;"
                                            font-weight="normal">
                                            <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                                5,000</tspan>
                                        </text>
                                        <path fill="none" stroke="#aaaaaa" d="M62.046875,202H487.21900000000005"
                                            stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </path>
                                        <text x="49.546875" y="143" text-anchor="end" font-family="sans-serif"
                                            font-size="12px" stroke="none" fill="#888888"
                                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;"
                                            font-weight="normal">
                                            <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                                10,000</tspan>
                                        </text>
                                        <path fill="none" stroke="#aaaaaa" d="M62.046875,143H487.21900000000005"
                                            stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </path>
                                        <text x="49.546875" y="84" text-anchor="end" font-family="sans-serif"
                                            font-size="12px" stroke="none" fill="#888888"
                                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;"
                                            font-weight="normal">
                                            <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                                15,000</tspan>
                                        </text>
                                        <path fill="none" stroke="#aaaaaa" d="M62.046875,84H487.21900000000005"
                                            stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </path>
                                        <text x="49.546875" y="25" text-anchor="end" font-family="sans-serif"
                                            font-size="12px" stroke="none" fill="#888888"
                                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;"
                                            font-weight="normal">
                                            <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                                20,000</tspan>
                                        </text>
                                        <path fill="none" stroke="#aaaaaa" d="M62.046875,25H487.21900000000005"
                                            stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </path>
                                        <text x="409.2105056196841" y="273.5" text-anchor="middle" font-family="sans-serif"
                                            font-size="12px" stroke="none" fill="#888888"
                                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;"
                                            font-weight="normal" transform="matrix(1,0,0,1,0,7)">
                                            <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                                2013</tspan>
                                        </text>
                                        <text x="220.13031394289186" y="273.5" text-anchor="middle" font-family="sans-serif"
                                            font-size="12px" stroke="none" fill="#888888"
                                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;"
                                            font-weight="normal" transform="matrix(1,0,0,1,0,7)">
                                            <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                                2012</tspan>
                                        </text>
                                        <path fill="none" stroke="#3c8dbc"
                                            d="M62.046875,229.5412C73.92896354799514,229.2108,97.69314064398542,231.53245,109.57522919198055,228.2196C121.4573177399757,224.90675000000002,145.22149483596598,204.50514644808743,157.1035833839611,203.0384C168.856518795565,201.58759644808742,192.3623896187728,219.34895,204.11532503037668,216.5494C215.86826044198057,213.74984999999998,239.37413126518834,183.43358661202186,251.12706667679223,180.642C263.0091552247874,177.81973661202184,286.7733323207777,191.15875,298.6554208687728,194.094C310.53750941676793,197.02925,334.30168651275824,218.06921420765028,346.18377506075336,204.124C357.9367104723573,190.33036420765026,381.442581295565,91.84023618784529,393.1955167071689,83.1386C404.81929898238155,74.5325861878453,428.06686353280685,125.20556758241756,439.6906458080195,134.89339999999999C451.5727343560146,144.79651758241758,475.3369114520049,154.85015,487.21900000000005,161.50240000000002"
                                            stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </path>
                                        <circle cx="62.046875" cy="229.5412" r="4" fill="#3c8dbc" stroke="#ffffff"
                                            stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </circle>
                                        <circle cx="109.57522919198055" cy="228.2196" r="4" fill="#3c8dbc" stroke="#ffffff"
                                            stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </circle>
                                        <circle cx="157.1035833839611" cy="203.0384" r="4" fill="#3c8dbc" stroke="#ffffff"
                                            stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </circle>
                                        <circle cx="204.11532503037668" cy="216.5494" r="4" fill="#3c8dbc" stroke="#ffffff"
                                            stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </circle>
                                        <circle cx="251.12706667679223" cy="180.642" r="4" fill="#3c8dbc" stroke="#ffffff"
                                            stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </circle>
                                        <circle cx="298.6554208687728" cy="194.094" r="4" fill="#3c8dbc" stroke="#ffffff"
                                            stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </circle>
                                        <circle cx="346.18377506075336" cy="204.124" r="4" fill="#3c8dbc" stroke="#ffffff"
                                            stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </circle>
                                        <circle cx="393.1955167071689" cy="83.1386" r="4" fill="#3c8dbc" stroke="#ffffff"
                                            stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </circle>
                                        <circle cx="439.6906458080195" cy="134.89339999999999" r="4" fill="#3c8dbc"
                                            stroke="#ffffff" stroke-width="1"
                                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                        <circle cx="487.21900000000005" cy="161.50240000000002" r="4" fill="#3c8dbc"
                                            stroke="#ffffff" stroke-width="1"
                                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                    </svg>
                                    <div class="morris-hover morris-default-style"
                                        style="left: 19.0234px; top: 162px; display: none;">
                                        <div class="morris-hover-row-label">2011 Q1</div>
                                        <div class="morris-hover-point" style="color: #3c8dbc">
                                            Item 1: 2,666
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <!-- /.box-body -->
                            <div class="panel panel-default">
                                <div class="panel-body padding15">
                                    <strong><h2 class="md-title" align="center"><sup style="font-size: 20px">Last 7 Day Customer</sup></h2></strong>
                                    <div class="box-body chart-responsive">
                                            <div class="chart" id="chart" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-xs-4">
                        <div class="box box-info">
                            <div class="heading text-muted d-flex justify-content-between">
                                <h6 class="text-capitalize">weekly top items</h6>
                                <a href="#" class="text-capitalize view-more font-weight-bold">view more</a>
                            </div>
                            <div class="content text-dark d-flex justify-content-between">
                                <div class="d-text">
                                    <h6 class="text-title text-uppercase">News paper</h6>
                                    <p class="p-text text-secondary">088214896547</p>
                                </div>
                                <div class="d-button">
                                    <button type="button" class="bg-primary text-white d-button-text px-4">706</button>
                                </div>
                            </div>
                            <div class="content text-dark d-flex justify-content-between">
                                <div class="d-text">
                                    <h6 class="text-title text-uppercase">News paper</h6>
                                    <p class="p-text text-secondary">088214896547</p>
                                </div>
                                <div class="d-button">
                                    <button type="button" class="bg-primary text-white d-button-text px-4">706</button>
                                </div>
                            </div>
                            <div class="content text-dark d-flex justify-content-between">
                                <div class="d-text">
                                    <h6 class="text-title text-uppercase">News paper</h6>
                                    <p class="p-text text-secondary">088214896547</p>
                                </div>
                                <div class="d-button">
                                    <button type="button" class="bg-primary text-white d-button-text px-4">706</button>
                                </div>
                            </div>
                            <div class="content text-dark d-flex justify-content-between">
                                <div class="d-text">
                                    <h6 class="text-title text-uppercase">News paper</h6>
                                    <p class="p-text text-secondary">088214896547</p>
                                </div>
                                <div class="d-button">
                                    <button type="button" class="bg-primary text-white d-button-text px-4">706</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="table-data">
            <div class="container">
                <div class="row">
                    <div class="col-8">
                        {{-- <table data-toggle="table" data-classes="table table-hover table-condensed employeeview"
                            data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-pagination="true"
                            class=""> --}}
                        <table id="vendor" class="table table-hover promotionview" style="width: 100%;">
                            <thead>
                                <tr class="header-colors">
                                    <th class="col-xs-1 headername text-capitalize" data-field="timestamp">timestamp
                                    </th>
                                    <th class="col-xs-1 headername text-capitalize" data-field="transaction_id">transaction
                                        id
                                    </th>
                                    <th class="col-xs-6 headername text-capitalize" data-field="amount">amount
                                    </th>
                                    <th class="col-xs-6 headername text-capitalize" data-field="tender_type">tender type
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>Cash</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>EBT</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>Cash</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>Cash</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>Cash</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>Cash</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>EBT</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>Cash</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>Cash</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>EBT</td>
                                </tr>
                                <tr id="tr-id-2" class="tr-class-2">
                                    <td>9:30 AM</td>
                                    <td>#74615</td>
                                    <td>$200</td>
                                    <td>Cash</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('page-script')
{{-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
4 <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
{{-- <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script> --}}
{{-- <link href="/stylesheet/morris.css" rel="stylesheet" type="text/css"/> --}}
{{-- <link rel="stylesheet" href="{{ asset('asset/css/morris.css') }}"> --}}
{{-- <link rel="stylesheet" href="{{ asset('asset/js/morris.min.js') }}"> --}}
{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script> --}}
{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> --}}
{{-- <script src="/javascript/morriss/morris.min.js"></script> --}}

<script type="text/javascript">
    var table = $('#vendor').DataTable({
        "dom": 't<"bottom col-md-12 row"<"col-md-2"i><"col-md-3"l><"col-md-7"p>>',
        "searching":false,
        "ordering": false,

        "pageLength":10,
      });

      $("#vendor_paginate").addClass("pull-right");

    $(document).ready(function(){
      var temp_sevendaysales = '<?php echo json_encode($output['sevendaysales']); ?>';
      window.sevendaysales = $.parseJSON(temp_sevendaysales);

      var temp_sevendaysCustomer = '<?php echo json_encode($output['sevendaysCustomer']); ?>';
      window.sevendaysCustomer = $.parseJSON(temp_sevendaysCustomer);

      var temp_dailySummary = '<?php echo json_encode($output['dailySummary']); ?>';
      window.dailySummary = $.parseJSON(temp_dailySummary);

      var temp_topItem = '<?php echo json_encode($output['topItem']); ?>';
      window.topItem = $.parseJSON(temp_topItem);

      var temp_topCategory = '<?php echo json_encode($output['topCategory']); ?>';
      window.topCategory = $.parseJSON(temp_topCategory);

      var temp_customer = '<?php echo json_encode($output['customer']); ?>';
      window.customer = $.parseJSON(temp_customer);
    });
  </script>

    <script src="{{ asset('javascript/dashboardApi.js') }}"></script>

  <script type="text/javascript">
    $(window).load(function() {
      $("div#divLoading").removeClass('show');
    });

    $(document).ready(function(){
        setTimeout(function(){
            if(window.sevenDaySalesArea !== undefined){
                window.sevenDaySalesArea.redraw();
            }
            if(window.sevenDaysCustomerArea !== undefined){
                window.sevenDaysCustomerArea.redraw();
            }
            if(window.topFiveCategoryBar !== undefined){
                window.topFiveCategoryBar.redraw();
            }
            if(window.topFiveProductBar !== undefined){
                window.topFiveProductBar.redraw();
            }
            if(window.cutomerFlow !== undefined){
                window.cutomerFlow.redraw();
            }

        }, 50);
    });


  </script>

@endsection
