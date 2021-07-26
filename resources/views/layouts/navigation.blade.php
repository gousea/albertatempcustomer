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

                    @if(session()->get('hq_sid') != 1)  
                        @if (in_array('PER1003', session()->get('userPermsData')))
                            <li class="nav-item sub-nav"><a class="nav-link sub text-uppercase"
                                    href="{{ route('customers') }}"> CUSTOMER </a></li>
                        @endif
                    @endif

                    @if(session()->get('hq_sid') != 1) 
                        @if (in_array('PER1004', session()->get('userPermsData')))
                            <li class="nav-item sub-nav dropdown">
                                <a class="nav-link dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown">
                                    EMPLOYEE</a>
                                <ul class="dropdown-menu main-dropdown">
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('users') }}">
                                            EMPLOYEE</a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('time_clock') }}">
                                            TIME CLOCK </a></li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if (in_array('PER1006', session()->get('userPermsData')))
                        <li class="nav-item sub-nav dropdown">
                            <a class="nav-link dropdown-toggle sub text-uppercase"
                                href="{{ url('/item/item_list/Active/DESC') }}" data-toggle="dropdown"> PRODUCTS
                            </a>
                            <ul class="dropdown-menu main-dropdown">
                                @if(session()->get('version') == 320)

                                    <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ url(session()->get('version').'/item/item_list/Active/DESC') }}">Items</a></li>
                                @else
                                    <li><a class="dropdown-item sub-dropdown text-uppercase"
                                        href="{{ url('/item/item_list/Active/DESC') }}">Items</a></li>
                                @endif
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ url('/item/edit_multiple_item') }}">Edit Multiple Items</a>
                                </li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('quick_update_item') }}">Quick Update of Items</a>
                                </li>

                                @if(session()->get('hq_sid') != 1) 
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="#">Last Modified Items</a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{url('/item/ItemMovement') }}"> Item Movement </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{route('ItemAuditList') }}"> Item Audit </a></li>
                                @endif
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('promotion') }}">Promotions </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ url('buydown') }}"> Buy Down </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('itemgroup') }}"> Item Group </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ url('/item/quick_item_list') }}"> Quick Item </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase"
                                    href="{{ url('/item/parent_child_list') }}">Parent Child</a></li>
                            </ul>
                        </li>
                    @endif

                    @if (in_array('PER1007', session()->get('userPermsData')))
                        <li class="nav-item sub-nav dropdown">
                            <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown">
                                INVENTORY </a>
                            <ul class="dropdown-menu main-dropdown">
                                @if(session()->get('hq_sid') != 1) 
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('ReceivingOrder') }}"> Receiving Order</a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('PurchaseOrder') }}"> Purchase Order </a></li>
                                @endif
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('department') }}"> Department </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('category') }}"> Category </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('subcategory') }}"> Sub Category </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('unit') }}"> Unit </a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('size') }}"> Size</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('manufacturer') }}"> Manufacturer </a></li>
                                @if(session()->get('hq_sid') != 1) 
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ url('inventory/physicalInventroy') }}"> Physical </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Waste </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('adjustment') }}"> Adjustment </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('transfer') }}">Transfer </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="#"> Location </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('reason') }}"> Reason </a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (in_array('PER1008', session()->get('userPermsData')))
                        <li class="nav-item sub-nav dropdown">
                            <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown">
                                ADMINISTRATION </a>
                            <ul class="dropdown-menu main-dropdown">
                                @if(session()->get('hq_sid') != 1) 
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('end_of_day') }}"> End of Day </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('itemlistdisplay') }}">Item List Display</a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('paidout') }}"> Paid Out </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('aisle') }}"> Aisle </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('shelf') }}">Shelf </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('shelving') }}">Shelving </a></li>
                                @endif    
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('tax') }}"> Tax</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('ageverify') }}">Age Verification</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('store_setting') }}">Store Setting</a></li>

                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('eodsetting') }}">Manual Sales Entry</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(session()->get('hq_sid') != 1) 
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
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('scan_data_report') }}"> Scan Data Report </a>
                                    </li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('SalesTransaction') }}"> Sales Transaction Report
                                        </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('TaxReport') }}"> Tax Report </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('HourlySalesReport') }}"> Hourly Sales Report </a>
                                    </li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('POReport') }}"> RO History Report </a>
                                    </li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('InventoryReport') }}"> Inventory on Hand Report
                                        </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{route('ProfitReport') }}"> Profit & Loss Report </a>
                                    </li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('PaidoutReport') }}"> Paid Out Report </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('BelowCostReport') }}"> Below Cost Report </a>
                                    </li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase"
                                            href="{{ route('salesreport') }}"> Sales Report </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('productlisting') }}"> Product Listing </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('employeereport') }}"> Employee Loss Prevention
                                            Report </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('itemsummary') }}"> Item Summary Report </a>
                                    </li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('saleshistoryreport') }}"> Sales History Report </a>
                                    </li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('salesanalyticsreport') }}"> Sales Analytics Report
                                        </a></li>
                                    <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('zeromovementreport') }}"> Zero Movement Report </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif
                    @if(session()->get('hq_sid') != 1) 
                        @if (in_array('PER1010', session()->get('userPermsData')))
                        <li class="nav-item sub-nav dropdown">
                            <a class="nav-link  dropdown-toggle sub text-uppercase" href="#" data-toggle="dropdown"> SETTINGS
                            </a>
                            <ul class="dropdown-menu main-dropdown">
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('itemlistdisplay') }}">Item List Display</a></li>  
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('end_of_shift_printing') }}"> POS Settings</a></li>
                                <li><a class="dropdown-item sub-dropdown text-uppercase" href="{{ route('ftpsetting') }}">FTP</a></li>
                            </ul>
                        </li>
                        @endif
                    @endif
                    {{-- <li class="nav-item"><a class="nav-link sub text-uppercase" href="#"> TUTORIALS </a></li> --}}
                </ul>
            </div> <!-- navbar-collapse.// -->
        </div>
    </div>
</nav>
