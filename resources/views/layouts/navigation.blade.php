<nav class="navbar navbar-expand-lg navbar-dark bg-dark sub-navigation-bar">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav sub">
                @if (in_array('PER1001', session()->get('userPermsData')))
                    <li class="nav-item sub-nav"> <a class="nav-link sub text-uppercase"
                            href="{{ route('dashboard') }}">DASHBOARD </a> </li>
                @endif

                @if (in_array('PER1002', session()->get('userPermsData')))
                    <li class="nav-item sub-nav"><a class="nav-link sub text-uppercase" href="{{ route('vendors') }}">
                            VENDORS </a></li>
                @endif

                @if (in_array('PER1003', session()->get('userPermsData')))
                    <li class="nav-item sub-nav"><a class="nav-link sub text-uppercase"
                            href="{{ route('customers') }}"> CUSTOMERS </a></li>
                @endif

                @if (in_array('PER1004', session()->get('userPermsData')))
                    <li class="nav-item sub-nav dropdown">
                        <a class="nav-link dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown">
                            EMPLOYEES </a>
                        <ul class="dropdown-menu main-dropdown">
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('users') }}">
                                    EMPLOYEES</a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('timeclock') }}">
                                    TIME CLOCK </a></li>
                        </ul>
                    </li>
                @endif

                @if (in_array('PER1006', session()->get('userPermsData')))
                    <li class="nav-item sub-nav dropdown">
                        <a class="nav-link dropdown-toggle sub text-uppercase"
                            href="{{ url('/item/item_list/Active/DESC') }}" data-toggle="dropdown"> PRODUCTS
                        </a>
                        <ul class="dropdown-menu main-dropdown">
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#">Items</a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ url('/item/parent_child_list') }}">Parent Child</a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#">Edit Multiple Items</a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#">Quick Update of Items</a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#">Last Modified Items</a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Item Movement </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Item Audit </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('promotion') }}">
                                    Promotions </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Buy Down </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Item Group </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Quick Item </a></li>
                        </ul>
                    </li>
                @endif

                @if (in_array('PER1007', session()->get('userPermsData')))
                    <li class="nav-item sub-nav dropdown">
                        <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown">
                            INVENTORY </a>
                        <ul class="dropdown-menu main-dropdown">
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Receiving Order</a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Purchase Order </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Department </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Category </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Sub Category </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Unit </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Size </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Manufacturer </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Physical </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Waste </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('adjustment') }}"> Adjustment </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Transfer </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Location </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Reason </a></li>
                        </ul>
                    </li>
                @endif

                @if (in_array('PER1008', session()->get('userPermsData')))
                    <li class="nav-item sub-nav dropdown">
                        <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown">
                            ADMINISTRATION </a>
                        <ul class="dropdown-menu main-dropdown">
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Perform End of Day </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#">Item List Display</a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Store </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Paid Outs </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Aisle </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Shelf </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Shelving </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Tax </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Store Settings </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> POS Settings </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> FTP </a></li>
                        </ul>
                    </li>
                @endif

                @if (in_array('PER1009', session()->get('userPermsData')))
                    <li class="nav-item sub-nav dropdown">
                        <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown"> REPORTS
                        </a>
                        <ul class="dropdown-menu main-dropdown">
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> End of Day Report</a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('EodShift') }}"> End of Shift Report </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Credit Card Report </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Scan Data Report </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Sales Transaction Report
                                </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Tax Report </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Hourly Sales Report </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> RO History Report </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Inventory on Hand Report
                                </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Profit & Loss Report </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Paid Out Report </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Below Cost Report </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('salesreport') }}"> Sales Report </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Product Listing </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Employee Loss Prevention
                                    Report </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Item Summary Report </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Sales History Report </a>
                            </li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Sales Analytics Report
                                </a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Zero Movement Report </a>
                            </li>
                        </ul>
                    </li>
                @endif


                <li class="nav-item"><a class="nav-link sub text-uppercase" href="#"> TUTORIALS </a></li>
            </ul>
        </div> <!-- navbar-collapse.// -->
    </div>
</nav>
