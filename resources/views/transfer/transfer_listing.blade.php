@extends('layouts.layout')
@section('title', 'Item Adjustment')
@section('main-content')

<div id="content">
    
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> <?php echo $text_list; ?></span>
                </div>
                <div class="nav-submenu">
                  <a type="button" href="<?php echo $add; ?>" title="<?php echo $button_add; ?>" class="btn btn-gray headerblack  buttons_menu add_new_btn_rotate"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
              </div>
            </div> <!-- navbar-collapse.// -->
        </div>
    </nav>
    
    <section class="section-content py-6">
        
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
            
            <div class="panel-body">
      
              
              <div class="clearfix"></div>
              
              <form action="" method="post" enctype="multipart/form-data" id="form-transfer-list">
                
                <div class="table-responsive">
                  <table id="transfer_listing" class="table table-bordered table-hover" style="">
                  <?php if ($transfers) { ?>
                    <thead>
                      <tr class="header-color">
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
        
    </section>
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