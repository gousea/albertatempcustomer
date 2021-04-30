@extends('layouts.master')
@section('title', 'Item Adjustment')
@section('main-content')
<div id="content">
    <div class="page-header">
      <div class="container-fluid">
        
        <!-- <h1><?php echo $heading_title; ?></h1> -->
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div class="container-fluid">
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
        <div class="panel-heading head_title">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
        </div>
        <div class="panel-body">
  
          <div class="row" style="padding-bottom: 15px;float: right;">
            <div class="col-md-12">
              <div class="">
                <a href="<?php echo $add; ?>" title="<?php echo $button_add; ?>" class="btn btn-primary add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>   
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          
          <form action="" method="post" enctype="multipart/form-data" id="form-transfer-list">
            
            <div class="table-responsive">
              <table id="transfer_listing" class="table table-bordered table-hover" style="">
              <?php if ($transfers) { ?>
                <thead>
                  <tr>
                    <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                    <th class="text-left">Invoice#</th>
                    <th class="text-left">Store Name</th>
                    <th class="text-left">Vendor Name</th>
                    <th class="text-left">Date Invoice</th>
                    <th class="text-left">Type</th>
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
  </div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function($) {
      $("div#divLoading").addClass('show');
    });
  
    $(window).load(function() {
      $("div#divLoading").removeClass('show');
    });
  </script>
@endsection