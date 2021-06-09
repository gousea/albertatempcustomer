@extends('layouts.layout')
@section('title', 'Item Adjustment')
@section('main-content')

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">

<div id="content">
    
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <h6><span class="font-weight-bold text-uppercase"> <?php echo $text_list; ?></span></h6>
                </div>
                <div class="nav-submenu">
                  <a type="button" href="<?php echo $add; ?>" title="<?php echo $button_add; ?>" class="btn btn-gray headerblack  buttons_menu add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
              </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
    
    <section class="section-content py-6">
        
        <div class="container">
          <?php if ($error_warning) { ?>
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <?php } ?>
          <?php if ($success) { ?>
          <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <?php } ?>
          <div class="panel panel-default">
            
            <div class="panel-body">
      
              
              <div class="clearfix"></div>
              
              <form action="" method="post" enctype="multipart/form-data" id="form-transfer-list">
                
                <div class="table-responsive col-xl-12 col-md-12">
                  <table id="transfer_listing" class="table table-hover" data-toggle="table" data-classes="table table-hover table-condensed promotionview"
                  data-row-style="rowColors" data-striped="true" data-click-to-select="true">
                  <?php if ($transfers) { ?>
                    <thead>
                      <tr class="header-color">
                        <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                        <th class="text-left">Invoice#
                          <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="SEARCH" id="adjustment_no">
                          </div>
                        </th>
                        <th class="text-left">Store Name
                          <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="SEARCH" id="adjustment_no">
                          </div>
                        </th>
                        <th class="text-left">Vendor Name
                          <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="SEARCH" id="adjustment_no">
                          </div>
                        </th>
                        <th class="text-left">Date Invoice
                          <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="SEARCH" id="adjustment_no">
                          </div>
                        </th>
                        <th class="text-left">Type
                          <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="SEARCH" id="adjustment_no">
                          </div>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <?php foreach ($transfers as $key => $transfer) { ?>
                      <tr id="adjustment_reason-row<?php echo $key; ?>">
                        <td data-order="<?php echo $transfer['iid']; ?>" class="text-center">
                          <input type="checkbox" name="selected[]" id="transfers[<?php echo $key; ?>][select]"  value="<?php echo $transfer['iid']; ?>" />
                        </td>
                        
                        <td class="text-left">
                          <span><?php echo $transfer['vinvnum']; ?></span>
                        </td>
      
                        <td class="text-left">
                          <span><?php echo $transfer['vstorename']; ?></span>
                        </td>
      
                        <td class="text-left">
                          <span><?php echo $transfer['vcompanyname']; ?></span>
                        </td>
      
                        <td class="text-left">
                          <span><?php echo date("m-d-Y", strtotime($transfer['dinvoicedate'])) ; ?></span>
                        </td>
      
                        <td class="text-left">
                          <span>Warehouse to Store</span>
                        </td>
      
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td colspan="7" class="text-center"><?php echo $text_no_results;?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </form>
              <?php if ($transfers) { ?>
              <div class="row">
                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                {{$transfers->links()}}
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
        
    </section>
</div>

@endsection

@section('page-script')
<script type="text/javascript">
    $(document).ready(function($) {
      $("div#divLoading").addClass('show');
    });
  
    $(window).on('load',function() {
      $("div#divLoading").removeClass('show');
    });
  </script>
@endsection