<nav class="navbar navbar-expand-lg navbar-dark bg-dark sub-navigation-bar">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="container">
        <div class="row">
            <div class="collapse navbar-collapse" id="main_nav">
                <ul class="navbar-nav sub">
                    @if (in_array('PER1001', session()->get('userPermsData')))
                        <li class="nav-item sub-nav"> <a class="nav-link sub text-uppercase"
                                href="{{ route('dashboard') }}">DASHBOARD </a> </li>
                    @endif

                    @if (in_array('PER1002', session()->get('userPermsData')))
                        <li class="nav-item sub-nav"><a class="nav-link sub text-uppercase" href="{{ route('vendors') }}">
                                VENDOR </a></li>
                    @endif

                    @if (in_array('PER1003', session()->get('userPermsData')))
                        <li class="nav-item sub-nav"><a class="nav-link sub text-uppercase"
                                href="{{ route('customers') }}"> CUSTOMER </a></li>
                    @endif

                    @if (in_array('PER1004', session()->get('userPermsData')))
                        <li class="nav-item sub-nav dropdown">
                            <a class="nav-link dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown">
                                EMPLOYEE</a>
                            <ul class="dropdown-menu main-dropdown">
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('users') }}">
                                        EMPLOYEE</a></li>
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
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ url('/item/item_list/Active/DESC') }}">Items</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ url('/item/parent_child_list') }}">Parent Child</a></li>
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
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ url('buydown') }}"> Buy Down </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('itemgroup') }}"> Item Group </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Quick Item </a></li>
                            </ul>
                        </li>
                    @endif

                    @if (in_array('PER1007', session()->get('userPermsData')))
                        <li class="nav-item sub-nav dropdown">
                            <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown">
                                INVENTORY </a>
                            <ul class="dropdown-menu main-dropdown">
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('ReceivingOrder') }}"> Receiving Order</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('PurchaseOrder') }}"> Purchase Order </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Department </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Category </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Sub Category </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Unit </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Size </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Manufacturer </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ url('inventory/physicalInventroy') }}"> Physical </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Waste </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('adjustment') }}"> Adjustment </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('transfer') }}">
                                        Transfer </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Location </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('reason') }}"> Reason </a></li>
                            </ul>
                        </li>
                    @endif

                    @if (in_array('PER1008', session()->get('userPermsData')))
                        <li class="nav-item sub-nav dropdown">
                            <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown">
                                ADMINISTRATION </a>
                            <ul class="dropdown-menu main-dropdown">
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('end_of_day') }}"> End of Day </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('unit') }}"> Unit
                                    </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('department') }}">Department</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('category') }}">Category</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('subcategory') }}">Sub Category</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('manufacturer') }}"> Manufacturer </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('aisle') }}">
                                        Aisle </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('shelf') }}">
                                        Shelf </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('shelving') }}">
                                        Shelving </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('size') }}"> Size
                                    </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('tax') }}"> Tax
                                    </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('paidout') }}">
                                        Paid Out </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('store_setting') }}">Store Setting</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('ageverify') }}">Age Verification</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('eodsetting') }}">Manual Sales Entry</a></li>
                            </ul>
                        </li>
                    @endif

                    @if (in_array('PER1009', session()->get('userPermsData')))
                        <li class="nav-item sub-nav dropdown">
                            <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown"> REPORTS
                            </a>
                            <ul class="dropdown-menu main-dropdown">
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('EodReport') }}">
                                        End of Day Report</a>
                                </li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('EodShift') }}">
                                        End of Shift Report </a>
                                </li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('CardReport') }}"> Credit Card Report </a>
                                </li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Scan Data Report </a>
                                </li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('SalesTransaction') }}"> Sales Transaction Report
                                    </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('TaxReport') }}"> Tax Report </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Hourly Sales Report </a>
                                </li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('POReport') }}"> RO History Report </a>
                                </li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('InventoryReport') }}"> Inventory on Hand Report
                                    </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Profit & Loss Report </a>
                                </li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('PaidoutReport') }}"> Paid Out Report </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Below Cost Report </a>
                                </li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ route('salesreport') }}"> Sales Report </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Product Listing </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Employee Loss Prevention
                                        Report </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('itemsummary') }}"> Item Summary Report </a>
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


                    <li class="nav-item sub-nav dropdown">
                        <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown"> SETTINGS
                        </a>
                        <ul class="dropdown-menu main-dropdown">
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('itemlistdisplay') }}">Item List Display</a></li> 
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('end_of_shift_printing') }}"> POS Settings</a></li>
                            <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('ftpsetting') }}">FTP</a></li>
                        </ul>
                    </li>

                    <!-- <li class="nav-item"><a class="nav-link sub text-uppercase" href="#"> TUTORIALS </a></li> -->
                </ul>
            </div> <!-- navbar-collapse.// -->
        </div>
    </div>
</nav>
