<nav id="column-left">
    <div id="profile">
        <div>
          <img src="{{ asset('image/user-icon-profile.png') }}" class="img-circle" width="50" height="50" />
        </div>
        <div style="margin-top: 10px;">
          <h4>{{Auth::user()->fname . ' ' . Auth::user()->lname}}</h4>
        </div>
      </div>
    <ul id="menu" style="background-color:#636466;">
        @if (in_array('PER1001', session()->get('userPermsData')))
            <li id="dashboard"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard fa-fw"></i> <span>Dashboard</span></a></li>
        @endif
        
        
        
        @if (in_array('PER1002', session()->get('userPermsData')))
        <li><a href="{{ route('vendors') }}"><i class="fa fa-building fa-fw"></i> <span>Vendor</span></a></li>
        @endif   
        
        @if(session()->get('hq_sid') != 1)
            @if (in_array('PER1003', session()->get('userPermsData')))
            <li><a href="{{ route('customers') }}"><i class="fa fa-child fa-fw"></i> <span>Customer</span></a></li>
            @endif
    
            @if (in_array('PER1004', session()->get('userPermsData')))
                <li>
                    <a href="{{ route('users') }}"><i class="fa fa-user fa-fw"></i> <span>Users</span></a>
                </li>
            @endif
            
            @if (in_array('PER1005', session()->get('userPermsData')))
                <li><a href="{{ route('store.edit') }}"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo 'Store';  ?></span></a></li>
            @endif 
        @endif

       @if (in_array('PER1006', session()->get('userPermsData')))
            <li><a class="parent active"><i class="fa fa-gift fa-fw"></i> <span>Item</span></a>
                <ul>
                    <li><a href="{{ url('/item/item_list/Active/DESC') }}">Item</a></li>
                    <li><a href="{{ url('/item/quick_item_list') }}">Quick Item</a></li>
                    <li><a href="{{ url('/item/parent_child_list') }}">Parent Child</a></li>
                    <li><a href="{{ route('itemgroup') }}"> Item Group </a></li>
                    <li><a href="{{ route('quick_update_item') }}">Quick Update of Item</a></li>
                    <li><a href="{{ url('/item/edit_multiple_item') }}">Edit Multiple Item</a></li>
                    <!--<li style="cursor: no-drop !important; text-decoration: line-through; pointer-events: none;" class="disabled"><a href="{{ route('item_audit') }}">Item Audit</a></li>-->
                    @if(session()->get('hq_sid') != 1)
                        <li  ><a href="{{ route('ItemAuditList') }}" >Item Audit </a></li>
                        <li><a href="{{ url('item/ItemMovement') }}">Item Movement</a></li>
                    @endif
                    <!--<li><a href="{{ url('promotion') }}">Promotion</a></li>-->
                    
                    @if(session()->get('version') == 320)
                        <li><a href="{{ url(session()->get('version').'/promotion')  }}">Promotion</a></li>
                    @else 
                        <li><a href="{{ url('/promotion')  }}">Promotion</a></li>
                    @endif
                    
                    
                    <li><a href="{{ url('buydown') }}">Buy Down</a></li>
                </ul>
            </li>
        @endif
        
        @if(session()->get('hq_sid') != 1)  
            @if (in_array('PER1007', session()->get('userPermsData')))
                <li>
                    <a class="parent active"><i class="fa fa-sitemap fa-fw"></i> <span> Inventory </span></a>
                    <ul>
                        <li><a href="{{ route('adjustment') }}">Adjustment</a></li>
                        <li><a href="{{ route('transfer') }}">Transfer</a></li>
                        
                        <li><a href="{{ route('inventory.physicalInventroy') }}"> Physical </a></li>
                        
                        <li><a href="{{ route('reason') }}"> Reason </a></li>
                        <li><a href="{{ route('PurchaseOrder') }}"> Purchase Order </a></li>
                        <li><a href="{{ route('ReceivingOrder') }}"> Receiving Order </a></li>
                    </ul>
                </li>
            @endif
        @endif
        
        @if (in_array('PER1008', session()->get('userPermsData')))
            <li>
                <a class="parent active"><i class="fa fa-tags fa-fw"></i> <span> Administration </span></a>
    
                <ul>
                    @if(session()->get('hq_sid') != 1)  
                        <li><a href="{{ route('end_of_day') }}"> End of Day </a></li> 
                    @endif
                    <li><a href="{{ route('unit') }}"> Unit </a></li>
                    <li><a href="{{ route('department') }}">Department</a></li>
                    <li><a href="{{ route('category') }}">Category</a></li>   
                    <li><a href="{{ route('subcategory') }}">Sub Category</a></li>
                    <li><a href="{{ route('manufacturer') }}"> Manufacturer </a></li>
                    @if(session()->get('hq_sid') != 1)
                        <li><a href="{{ route('aisle') }}"> Aisle </a></li>
                        <li><a href="{{ route('shelf') }}"> Shelf </a></li>
                        <li><a href="{{ route('shelving') }}"> Shelving </a></li>
                    @endif
                    <li><a href="{{ route('size') }}"> Size </a></li>
                    <li><a href="{{ route('tax') }}"> Tax </a></li> 
                    @if(session()->get('hq_sid') != 1)
                        <li><a href="{{ route('paidout') }}"> Paid Out </a></li>
                        <li><a href="{{ route('store_setting') }}">Store Setting</a></li>
                    @endif
                    <li><a href="{{ route('ageverify') }}">Age Verification</a></li>
                    
                    <?php $user_role = Auth::user()->user_role; ?>
                    @if($user_role === 'SuperAdmin' || $user_role === 'StoreAdmin')
                      <li><a href="{{ route('eodsetting') }}">Manual Sales Entry</a></li>
                    @endif
                </ul>
            </li>
         @endif        
        
        @if(session()->get('hq_sid') != 1)
            @if (in_array('PER1009', session()->get('userPermsData')))
            <li>
                <a class="parent"><i class="fa fa-bars fa-fw"></i>&nbsp;&nbsp;<span>Reports</span></a>
                <ul>
                    <li><a href="{{ route('EodReport') }}">End of Day</a></li>
                    <li><a href="{{ route('EodShift') }}">End of Shift Report</a></li>
                    <li><a href="{{ route('CardReport') }}">Credit Card Report</a></li>
                    <li><a href="{{ route('scan_data_report') }}">Scan Data Report</a></li>
                    
                    <li><a href="{{ route('SalesTransaction') }}">Sales Transaction</a></li>
                    <li><a href="{{ route('TaxReport') }}">Tax Report</a></li>
                    <li><a href="{{ route('HourlySalesReport') }}"><?php echo "Hourly Sales" ;?></a></li>
                    <li><a href="{{ route('POReport') }}">RO History</a></li>
                    <li><a href="{{ route('InventoryReport') }}">Inventory On Hand</a></li>
                    <li><a href="{{ route('ProfitReport') }}">Profit & Loss Report</a></li>
                    <li><a href="{{ route('PaidoutReport') }}">Paid Out Report</a></li>
                    <li><a href="{{ route('BelowCostReport') }}">Below Cost</a></li>
                    <li><a href="{{ route('salesreport') }}">Sales Report</a></li>
                    <li><a href="{{ route('productlisting') }}">Product Listing</a></li>
                    <li><a href="{{ route('employeereport') }}">Employee Loss Prevention Report</a></li>
                    <li><a href="{{ route('itemsummary') }}">Item Summary</a></li>
                    <li><a href="{{ route('saleshistoryreport') }}">Sales History Report</a></li>
                    <li><a href="{{ route('salesanalyticsreport') }}">Sales Analytics Report</a></li>
                    <li><a href="{{ route('zeromovementreport') }}"><?php echo "Zero Movement"; ?></a></li>
                </ul>
            </li>
            @endif
        @endif
        
        @if(session()->get('hq_sid') != 1)
            @if (in_array('PER1010', session()->get('userPermsData')))
            <li><a class="parent active"><i class="fa fa-cog fa-fw"></i> <span>Settings</span></a>
                <ul>
                  <li><a href="{{ route('itemlistdisplay') }}">Item List Display</a></li>      
                  <li><a href="{{ route('end_of_shift_printing') }}">POS Settings</a></li>
                  <li><a href="{{ route('ftpsetting') }}">FTP</a></li>
                </ul>
            </li>
            @endif
        @endif
        
        @if(session()->get('hq_sid') != 1)
            @if (in_array('PER1012', session()->get('userPermsData')))
            {{-- <li><a class="parent active"><i class="fa fa-circle-thin"></i> <span>General</span></a>
              <ul>
                <li><a href="{{route('upc_conversion')}}">UPC Conversion</a></li>   
                <li><a href="{{route('download')}}">Download</a></li>
              </ul>
            </li> --}}
            @endif
        @endif
        
    </ul>
</nav>

