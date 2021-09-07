@extends('layouts.layout')

@section('title')
    Dashboard
@stop

@section('main-content')

    <link rel="stylesheet" href="{{ asset('asset/css/dashboard.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

    <style>
        .lessContent {
            display: none;
        }

        .moreContent {
            display: none;
        }
        #print{
            color: -webkit-link;
            cursor: pointer;
            text-decoration: underline;
        }

    </style>
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Dashboard</span>
                </div>
                <div class="nav-submenu">
                    <a type="button" class="btn btn-gray headerblack  buttons_menu text-uppercase"
                        href="{{ route('dashboardlayout') }}"> Edit layout
                    </a>
                </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
    <div class="container section-content">
        <div class="row">
            <div class="col-lg-8 col-md-12 col-xs-8">
                <div class="heading text-muted">
                    <h6>Store Statistics</h6>
                </div>
                <div class="grid-container">
                    <div class="box grid-item1">
                        <div class="icons">
                            {{-- <i class="fa fa-eye fa-3x text-muted transperent-icons" aria-hidden="true"></i> --}}
                            <img src="{{ asset('image/outline_today_black_24dp.png') }}" style="opacity: 0.3" />
                        </div>
                        <div class="text-numbers">
                            {{-- <p class="buttons_menu a font-weight-bold">$2,193.00</p> --}}
                            <h6>$ {{ $output['sales']['today'] }}</h6>
                            <p class="buttons_menu c text-muted font-weight-bold">Today's Sales</p>
                        </div>
                    </div>
                    <div class="box grid-item2">
                        <div class="icons">
                            {{-- <i class="fa fa-cutlery fa-3x text-muted transperent-icons" aria-hidden="true"></i> --}}
                            <img src="{{ asset('image/outline_date_range_black_24dp.png') }}" style="opacity: 0.3" />
                        </div>
                        <div class="text-numbers">
                            <p class="buttons_menu a font-weight-bold">$ {{ $output['sales']['week'] }}</p>
                            <p class="buttons_menu c text-muted font-weight-bold">Weekly sales</p>
                        </div>
                    </div>
                    <div class="box grid-item3">
                        <div class="icons">
                            <img src="{{ asset('image/outline_schedule_black_24dp.png') }}" style="opacity: 0.3" />
                            {{-- <i class="fa fa-clock-o fa-3x text-muted transperent-icons" aria-hidden="true"></i> --}}
                        </div>
                        <div class="text-numbers">
                            <p class="buttons_menu a font-weight-bold">{{ $output['customers']['today'] }}</p>
                            <p class="buttons_menu c text-muted font-weight-bold">Today's Customers</p>
                        </div>
                    </div>
                    <div class="box grid-item4">
                        <div class="icons">
                            {{-- <i class="fa fa-users fa-3x text-muted transperent-icons" aria-hidden="true"></i> --}}
                            <img src="{{ asset('image/outline_groups_black_24dp.png') }}" style="opacity: 0.3" />
                        </div>
                        <div class="text-numbers">
                            <p class="buttons_menu a font-weight-bold">{{ $output['customers']['week'] }}</p>
                            <p class="buttons_menu c text-muted font-weight-bold">Weekly Customers</p>
                        </div>
                    </div>
                    <div class="box grid-item5">
                        <div class="icons">
                            {{-- <i class="fa fa-eye fa-3x text-muted transperent-icons" aria-hidden="true"></i> --}}
                            {{-- <i class="fa fa-th-large fa-3x text-muted transperent-icons" aria-hidden="true"></i> --}}
                            <img src="{{ asset('image/outline_add_business_black_24dp.png') }}" style="opacity: 0.3" />
                        </div>
                        <div class="text-numbers">
                            <p class="buttons_menu a font-weight-bold">{{ $output['total_item']->totalitem }}</p>
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
            <div class="col-lg-4 col-md-12 col-xs-4">
                <div class="heading text-muted">
                    <h6>News & Updates</h6>
                </div>
                <div class="content text-muted">
                    <?php foreach($output['news_update'] as $newsupdate) {?>
                    <p><?php echo $newsupdate->news_text; ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container section-content">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-xs-8 ">
                <div class="box box-info">

                    <!-- /.box-body -->
                    <div class="panel panel-default">
                        <div class="panel-body padding15">
                            <strong>
                                <h2 class="md-title" align="center"><sup style="font-size: 20px">Last 7 Day
                                        Customer</sup></h2>
                            </strong>
                            <div class="box-body chart-responsive">
                                <div class="chart" id="chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-xs-4">
                <div class="box box-info">
                    <div class="d-flex justify-content-center">
                        <h6 class="text-uppercase" style="color:#000">Weekly Top Items</h6>
                    </div>
                    <div class="disp-cont lessContent" id="lessId">
                        <?php if(empty($output['topItem'])){ ?>
                        <span class="text-justify text-uppercase">No data available </span>
                        <?php }else{ ?>
                        <?php  if(isset($output['topItem']->data) ){  ?>
                        <?php if(isset($output['topItem']) && count($output['topItem']->data) != 0){   ?>
                        <?php for ($i=0; $i < 5; $i++) { ?>
                        <div class="content text-dark d-flex justify-content-between" id="five">
                            <?php if(isset($output['topItem'][$i]->Item)) { ?>
                            <div class="d-text">
                                <?php
                                $url = url()->current();
                                $url_data = explode('/dashboard', $url);
                                $urllink = $url_data[0];
                                $product_id = $output['topItem'][$i]->itemid;
                                $itemlink = '/item/edit/' . $product_id;
                                $target_url = $url_data[0] . $itemlink;
                                ?>
                                <a href="<?php echo $target_url; ?>">
                                    <h6 class="text-uppercase"><?php echo $output['topItem'][$i]->Item; ?></h6>
                                </a>
                                <a href="<?php echo $target_url; ?>">
                                    <p class="text-uppercase" style="font-size:13px;"><?php echo $output['topItem'][$i]->sku; ?></p>
                                </a>
                                <!-- <p class="p-text text-secondary"></p> -->
                            </div>
                            <div class="d-button">
                                <a href="<?php echo $target_url; ?>" class="bg-primary text-white d-button-text px-4"
                                    style="padding-top: 5px; padding-bottom: 5px;"><?php echo floatval($output['topItem'][$i]->Quantity); ?></a>
                            </div>
                            <?php } else {?>
                            <span class="text-justify text-uppercase">No data available </span>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <?php }else{ ?>
                        <div>
                            <p class="text-justify" style="text-align: center !important; font-size: 16px;">No Data
                                Found!!!</p>
                        </div>
                        <?php } ?>
                        <?php } else { ?>
                        <?php if(isset($output['topItem']) ){  ?>
                        <?php for ($i=0; $i < 5; $i++) { ?>
                        <div class="content text-dark d-flex justify-content-between" id="five">
                            <?php if(isset($output['topItem'][$i]->Item)) { ?>
                            <div class="d-text">
                                <?php
                                $url = url()->current();
                                $url_data = explode('/dashboard', $url);
                                $urllink = $url_data[0];
                                $product_id = $output['topItem'][$i]->itemid;
                                $itemlink = '/item/edit/' . $product_id;
                                $target_url = $url_data[0] . $itemlink;
                                ?>
                                <a href="<?php echo $target_url; ?>">
                                    <h6 class="text-uppercase"><?php echo $output['topItem'][$i]->Item; ?></h6>
                                </a>
                                <a href="<?php echo $target_url; ?>">
                                    <p class="text-uppercase" style="font-size:13px;"><?php echo $output['topItem'][$i]->sku; ?></p>
                                </a>
                                <!-- <p class="p-text text-secondary"></p> -->
                            </div>
                            <div class="d-button">
                                <a href="<?php echo $target_url; ?>" class="bg-primary text-white d-button-text px-4"
                                    style="padding-top: 5px; padding-bottom: 5px;"><?php echo floatval($output['topItem'][$i]->Quantity); ?></a>
                            </div>
                            <?php } else {?>
                            <span class="text-justify text-uppercase">No data available </span>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <?php }else{ ?>
                        <div>
                            <p class="text-justify" style="text-align: center !important; font-size: 16px;">No Data
                                Found!!!</p>
                        </div>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                    </div>




                    <div class="more-cont moreContent" id="moreId">
                        <?php if(isset($output['topItem'])){ ?>
                        <?php foreach($output['topItem'] as $topitem){ ?>
                        <div class="content text-dark d-flex justify-content-between">
                            <?php if(isset($topitem->Item)) { ?>
                            <div class="d-text">
                                <?php
                                $url = url()->current();
                                $url_data = explode('/dashboard', $url);
                                $urllink = $url_data[0];
                                $product_id = $topitem->itemid;
                                $itemlink = '/item/edit/' . $product_id;
                                $target_url = $url_data[0] . $itemlink;
                                ?>
                                <a href="<?php echo $target_url; ?>">
                                    <h6 class="text-uppercase"><?php echo $topitem->Item; ?></h6>
                                </a>
                                <a href="<?php echo $target_url; ?>">
                                    <p class="text-uppercase" style="font-size:13px;"><?php echo $topitem->sku; ?></p>
                                </a>
                            </div>
                            <div class="d-button">
                                <a href="<?php echo $target_url; ?>" class="bg-primary text-white d-button-text px-4"
                                    style="padding-top: 5px; padding-bottom: 5px;"><?php echo floatval($topitem->Quantity); ?></a>
                            </div>
                            <?php } else {?>
                            <span class="text-justify text-uppercase">No data available </span>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <?php }else{ ?>
                        <div>
                            <p class="text-justify text-uppercase">No Data available!!!</p>
                        </div>
                        <?php } ?>
                    </div>


                    <?php if(isset($topitem->Item)) { ?>
                    <button type="button" class="bg-primary text-white d-button-text px-4" id="moreBtn"
                        style="padding-left: 250px;">More</button>
                    <button type="button" class="bg-primary text-white d-button-text px-4" id="lessBtn"
                        style="padding-left: 250px;">Less</button>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-8" style="bottom: 180px;">
            <div class="table-responsive">
                <div class="heading text-muted">
                    <h6 class="text-uppercase">Latest Transactions</h6>
                </div>
                <table id="vendor" class="table table-hover promotionview" style="width: 100%">
                    <thead>
                        <tr class="header-color">
                            <th class="col-xs-1 headername text-uppercase" style="width: 300px;">timestamp</th>
                            <th class="col-xs-1 headername text-uppercase" style="width: 300px;">transaction id</th>
                            <th class="col-xs-6 headername text-uppercase" style="width: 300px;">amount</th>
                            <th class="col-xs-6 headername text-uppercase" style="width: 300px;">tender type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($output['trn_sales_data'] as $sales_data)
                            <tr id="customer-row">
                                <td><span>{{ $sales_data->sales_timestamp }}</span></td>
                                <td><a class="print-sales" id="print" data-id="<?php echo $sales_data->isalesid;?>">{{ $sales_data->transaction_id }} </a></td>
                                <!-- <td><span>{{ $sales_data->transaction_id }}</span></td> -->
                                <td><span>{{ $sales_data->sales_amount }}</span></td>
                                <td><span>{{ $sales_data->tender_type }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="view_salesdetail_model" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">        
        <div class="modal-body" id="printme">          
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btnPrint" data-dismiss="modal" >Print</button>
      </div>
      </div>
    </div>
  </div>

    

@endsection

@section('page-script')

    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>


    
<script type="text/javascript">



$(document).on('click', '.print-sales', function(event) {
  event.preventDefault();
  var reportdata_url = '<?php echo route('dashboardsalevalue'); ?>';
  reportdata_url  = reportdata_url.replace(/&amp;/g, '&');

  var salesid =$(this).attr("data-id");

  $("div#divLoading").addClass('show');

  $.ajax({
      
      url     : reportdata_url,
      data    : {salesid:salesid},
      type    : 'GET',
      
  }).done(function(response){
      var  response = $.parseJSON(response); //decode the response array
    
      if(response.code == 1 ){
          $("div#divLoading").removeClass('show');
          $('.modal-body').html(response.data);
          $('#view_salesdetail_model').modal('show');
      
      }else if(response.code == 0){
          alert('Something Went Wrong!!!');
          $("div#divLoading").removeClass('show');
          return false;
    }		
  });

});
</script>

    <script type="text/javascript">
        var table = $('#vendor').DataTable({
            // "dom": 't<"bottom col-md-12 row"<"col-md-2"i><"col-md-3"l><"col-md-7"p>>',
            "dom": 't<"bottom col-md-12 row"<"col-md-2"i><"col-md-3"l><"col-md-7"p>>',
            "searching": false,
            "destroy": true,
            "ordering": false,
            "pageLength": 10
        });

        $("#vendor_paginate").addClass("pull-right");

        $(document).ready(function() {
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
        // $(window).load(function() {
        //     $("div#divLoading").removeClass('show');
        // });
        $(document).ready(function() {
            setTimeout(function() {
                if (window.sevenDaySalesArea !== undefined) {
                    window.sevenDaySalesArea.redraw();
                }
                if (window.sevenDaysCustomerArea !== undefined) {
                    window.sevenDaysCustomerArea.redraw();
                }
                if (window.topFiveCategoryBar !== undefined) {
                    window.topFiveCategoryBar.redraw();
                }
                if (window.topFiveProductBar !== undefined) {
                    window.topFiveProductBar.redraw();
                }
                if (window.cutomerFlow !== undefined) {
                    window.cutomerFlow.redraw();
                }
            }, 50);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#lessId').removeClass('lessContent');
            $('#moreId').addClass('moreContent');
            $('#lessBtn').hide();
            $('#moreBtn').click(function() {
                $('#lessId').addClass('lessContent');
                $('#moreId').removeClass('moreContent');
                $('#moreBtn').hide();
                $('#lessBtn').show();
            });

            $('#lessBtn').click(function() {
                $('#lessId').removeClass('lessContent');
                $('#moreId').addClass('moreContent');
                $('#lessBtn').hide();
                $('#moreBtn').show();
            });


            // $('.more').click(function(e) {
            //     e.preventDefault();
            //     $(this).text(function(i, t) {
            //         if(t == 'More'){
            //             $('#lessId').removeClass('lessContent');
            //             $('#moreId').addClass('moreContent');
            //         } else if(t == 'Less'){
            //             $('#lessId').addClass('lessContent');
            //             $('#moreId').removeClass('moreContent');
            //         }
            //         return t == 'Less' ? 'More' : 'Less';
            //     }).prev('.more-cont').slideToggle()
            // });
        });
    </script>
@endsection
