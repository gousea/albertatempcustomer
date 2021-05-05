@extends('layouts.layout')

@section('title')
    Dashboard Layout
@stop


{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"> --}}
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"> --}}
{{-- <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"> --}}
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet"
    type="text/css" /> --}}

@section('main-content')
    {{-- {{ dd($users) }} --}}
    <div id="content">
        <div class="container-fluid">
            <div class="panel panel-default">

                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <h2>CUSTOMIZABLE MENU</h2> --}}
                            <br>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#home">FOOTER LINKS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#menu1">DASHBOARD SORTING</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home" class="container tab-pane active"><br>
                                    <form name="myForm" action="{{ route('dashboardlayout') }}" method="POST"
                                        enctype="multipart/form-data" id="form-user" class="form-horizontal">
                                        @csrf
                                        {{-- <div class="row">
                                                <div class="col-md-12 bg-light text-right">
                                                    <button type="button" class="btn btn-primary">Cancel</button>
                                                    <button type="button" class="btn btn-warning ml-2">Save</button>
                                                </div>
                                            </div> --}}


                                        <div class="form-group control_system pull-right col-md-1">
                                            <input type="submit" id="Submit" value="Save" class="btn btn-primary"
                                                onclick="checkout()">
                                        </div>

                                        <div class="clearfix"></div>


                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Menu List</th>
                                                    <th scope="col">Menu List</th>
                                                    <th scope="col">Menu List</th>
                                                    <th scope="col">Menu List</th>
                                                    <th scope="col">Menu List</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck1" name="menu[]" value="dashboard">
                                                            <label class="custom-control-label"
                                                                for="customCheck1">DASHBOARD</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck2" name="menu[]" value="vendors">
                                                            <label class="custom-control-label"
                                                                for="customCheck2">VENDORS</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck3" name="menu[]" value="customers">
                                                            <label class="custom-control-label"
                                                                for="customCheck3">CUSTOMERS</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck4" name="menu[]" value="employees">
                                                            <label class="custom-control-label"
                                                                for="customCheck4">EMPLOYEES</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck5" name="menu[]" value="time_clock">
                                                            <label class="custom-control-label" for="customCheck5">TIME
                                                                CLOCK</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck6" name="menu[]" value="edit_multiple_items">
                                                            <label class="custom-control-label" for="customCheck6">Edit
                                                                Multiple
                                                                Items</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck7" name="menu[]"
                                                                value="quick_update_of_items">
                                                            <label class="custom-control-label" for="customCheck7">Quick
                                                                Update of
                                                                Items</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck8" name="menu[]" value="last_modified_items">
                                                            <label class="custom-control-label" for="customCheck8">Last
                                                                Modified
                                                                Items</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck9" name="menu[]" value="item_movement">
                                                            <label class="custom-control-label" for="customCheck9">Item
                                                                Movement</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck10" name="menu[]" value="item_audit">
                                                            <label class="custom-control-label" for="customCheck10">Item
                                                                Audit</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck11" name="menu[]" value="promotions">
                                                            <label class="custom-control-label"
                                                                for="customCheck11">Promotions</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck12" name="menu[]" value="buy_down">
                                                            <label class="custom-control-label" for="customCheck12">Buy
                                                                Down</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck13" name="menu[]" value="item_group">
                                                            <label class="custom-control-label" for="customCheck13">Item
                                                                Group</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck14" name="menu[]" value="quick_item">
                                                            <label class="custom-control-label" for="customCheck14">Quick
                                                                Item</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck15" name="menu[]" value="receiving_order">
                                                            <label class="custom-control-label"
                                                                for="customCheck15">Receiving
                                                                Order</label>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck16" name="menu[]" value="purchase_order">
                                                            <label class="custom-control-label" for="customCheck16">Purchase
                                                                Order</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck17" name="menu[]" value="department">
                                                            <label class="custom-control-label"
                                                                for="customCheck17">Department</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck18" name="menu[]" value="category">
                                                            <label class="custom-control-label"
                                                                for="customCheck18">Category</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck19" name="menu[]" value="sub_category">
                                                            <label class="custom-control-label" for="customCheck19">Sub
                                                                Category</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck20" name="menu[]" value="unit">
                                                            <label class="custom-control-label"
                                                                for="customCheck20">Unit</label>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck21" name="menu[]" value="size">
                                                            <label class="custom-control-label"
                                                                for="customCheck21">Size</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck22" name="menu[]" value="manufacturer">
                                                            <label class="custom-control-label"
                                                                for="customCheck22">Manufacturer</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck23" name="menu[]" value="physical">
                                                            <label class="custom-control-label"
                                                                for="customCheck23">Physical</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck24" name="menu[]" value="waste">
                                                            <label class="custom-control-label"
                                                                for="customCheck24">Waste</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck25" name="menu[]" value="adjustment">
                                                            <label class="custom-control-label"
                                                                for="customCheck25">Adjustment</label>
                                                        </div>
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck26" name="menu[]" value="transfer">
                                                            <label class="custom-control-label"
                                                                for="customCheck26">Transfer</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck27" name="menu[]" value="location">
                                                            <label class="custom-control-label"
                                                                for="customCheck27">Location</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck28" name="menu[]" value="reason">
                                                            <label class="custom-control-label"
                                                                for="customCheck28">Reason</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck29" name="menu[]" value="perform_end_of_day">
                                                            <label class="custom-control-label" for="customCheck29">Perform
                                                                End of
                                                                Day</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck30" name="menu[]" value="item_list_display">
                                                            <label class="custom-control-label" for="customCheck30">Item
                                                                List
                                                                Display</label>
                                                        </div>
                                                    </td>

                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck31" name="menu[]" value="store">
                                                            <label class="custom-control-label"
                                                                for="customCheck31">Store</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck32" name="menu[]" value="paid_outs">
                                                            <label class="custom-control-label" for="customCheck32">Paid
                                                                Outs</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck33" name="menu[]" value="aisle">
                                                            <label class="custom-control-label"
                                                                for="customCheck33">Aisle</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck34" name="menu[]" value="shelf">
                                                            <label class="custom-control-label"
                                                                for="customCheck34">Shelf</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck35" name="menu[]" value="shelving">
                                                            <label class="custom-control-label"
                                                                for="customCheck35">Shelving</label>
                                                        </div>
                                                    </td>

                                                </tr>



                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck36" name="menu[]" value="tax">
                                                            <label class="custom-control-label"
                                                                for="customCheck36">Tax</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck37" name="menu[]" value="store_settings">
                                                            <label class="custom-control-label" for="customCheck37">Store
                                                                Settings</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck38" name="menu[]" value="pos_settings">
                                                            <label class="custom-control-label" for="customCheck38">POS
                                                                Settings</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck39" name="menu[]" value="ftp">
                                                            <label class="custom-control-label"
                                                                for="customCheck39">FTP</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck40" name="menu[]" value="end_of_day_report">
                                                            <label class="custom-control-label" for="customCheck40">End
                                                                of
                                                                Day
                                                                Report</label>
                                                        </div>
                                                    </td>

                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck41" name="menu[]"
                                                                value="end_of_shift_report">
                                                            <label class="custom-control-label" for="customCheck41">End
                                                                of
                                                                Shift
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck42" name="menu[]" value="credit_card_report">
                                                            <label class="custom-control-label" for="customCheck42">Credit
                                                                Card
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck43" name="menu[]" value="scan_data_report">
                                                            <label class="custom-control-label" for="customCheck43">Scan
                                                                Data
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck44" name="menu[]"
                                                                value="sales_transaction_report">
                                                            <label class="custom-control-label" for="customCheck44">Sales
                                                                Transaction
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck45" name="menu[]" value="tax_report">
                                                            <label class="custom-control-label" for="customCheck45">Tax
                                                                Report</label>
                                                        </div>
                                                    </td>

                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck46" name="menu[]"
                                                                value="hourly_sales_report">
                                                            <label class="custom-control-label" for="customCheck46">Hourly
                                                                Sales
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck47" name="menu[]" value="ro_history_report">
                                                            <label class="custom-control-label" for="customCheck47">RO
                                                                History
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck48" name="menu[]"
                                                                value="inventory_on_hand_report">
                                                            <label class="custom-control-label"
                                                                for="customCheck48">Inventory on Hand
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck49" name="menu[]" value="profit_loss_report">
                                                            <label class="custom-control-label" for="customCheck49">Profit &
                                                                Loss
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck50" name="menu[]" value="paid_out_report">
                                                            <label class="custom-control-label" for="customCheck50">Paid
                                                                Out
                                                                Report</label>
                                                        </div>
                                                    </td>

                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck51" name="menu[]" value="below_cost_report">
                                                            <label class="custom-control-label" for="customCheck51">Below
                                                                Cost
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck52" name="menu[]" value="sales_report">
                                                            <label class="custom-control-label" for="customCheck52">Sales
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck53" name="menu[]" value="product_listing">
                                                            <label class="custom-control-label" for="customCheck53">Product
                                                                Listing</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck54" name="menu[]"
                                                                value="employee_loss_prevention_report">
                                                            <label class="custom-control-label" for="customCheck54">Employee
                                                                Loss
                                                                Prevention Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck55" name="menu[]"
                                                                value="item_summary_report">
                                                            <label class="custom-control-label" for="customCheck55">Item
                                                                Summary
                                                                Report</label>
                                                        </div>
                                                    </td>

                                                </tr>


                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck56" name="menu[]"
                                                                value="sales_history_report">
                                                            <label class="custom-control-label" for="customCheck56">Sales
                                                                History
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck57" name="menu[]"
                                                                value="sales_analytics_report">
                                                            <label class="custom-control-label" for="customCheck57">Sales
                                                                Analytics
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck58" name="menu[]"
                                                                value="zero_movement_report">
                                                            <label class="custom-control-label" for="customCheck58">Zero
                                                                Movement
                                                                Report</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck59" name="menu[]" value="tutorials">
                                                            <label class="custom-control-label"
                                                                for="customCheck59">TUTORIALS</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            {{-- <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                            <label class="custom-control-label" for="customCheck1">Inventory</label> --}}
                                                        </div>
                                                    </td>

                                                </tr>

                                            </tbody>



                                        </table>


                                    </form>
                                </div>
                                <div id="menu1" class="container tab-pane fade"><br>
                                    <h3>Dashboard Sorting</h3>
                                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                        aliquip ex ea commodo consequat.</p>
                                </div>
                            </div>



                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
@endsection

@section('page-script')
    {{-- <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('.mydatatable').DataTable();
            // alert("Testing now");
        });

        function checkout() {
            var checkBoxes = document.getElementsByClassName('custom-control-input');
            // console.log(checkBoxes);

            var nbChecked = 0;
            for (var i = 0; i < checkBoxes.length; i++) {
                if (checkBoxes[i].checked) {
                    nbChecked++;
                };
            };
            if (nbChecked > 3) {
                alert('You cannot select more than 3 books');
                return false;
            } else if (nbChecked == 0) {
                alert('Please, check at least one checkbox!');
                return false;
            } else {
                //Do what you need for form submission, if needed...
            }

            // const checkBoxes = document.querySelectorAll('.custom-control-input:checked');

            // if (checkBoxes.length > 3) {
            //     alert('You cannot select more than 3 books');
            //     return false;
            // }
        }

    </script>
    <script type="text/javascript">
        $(window).load(function() {
            $("div#divLoading").removeClass('show');
        });

    </script>
    <script>
        function myFunction() {
            $("form-users").sumbit();
        }


        $(document).on('keyup', '#automplete-product', function(event) {
            event.preventDefault();

            $('#users tbody tr').hide();
            var txt = $(this).val().toUpperCase();
            var td1, td2, td3, td4, td5;

            if (txt != '') {
                $('#users tbody tr').each(function() {

                    td1 = $(this).find("td")[1];
                    td2 = $(this).find("td")[2];
                    td3 = $(this).find("td")[3];
                    td4 = $(this).find("td")[4];
                    td5 = $(this).find("td")[5];

                    if (td1 || td2 || td3 || td4 || td5) {
                        if (td1.innerHTML.toUpperCase().indexOf(txt) > -1 || td2.innerHTML.toUpperCase()
                            .indexOf(txt) > -1 || td3.innerHTML.toUpperCase().indexOf(txt) > -1 || td4
                            .innerHTML.toUpperCase().indexOf(txt) > -1 || td5.innerHTML.toUpperCase()
                            .indexOf(txt) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });
            } else {
                $('#users tbody tr').show();
            }
        });


        $(function() {
            $('input[name="automplete-product"]').focus();
        });

    </script>


    <div class="modal fade" id="successModal" role="dialog">
        <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success text-center">
                        <p id="success_msg"></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger text-center">
                        <p id="error_msg"></p>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                </div>
            </div>

        </div>
    </div>
    <style>
        .disabled {
            pointer-events: none; //This makes it not clickable

        }

    </style>

@endsection
