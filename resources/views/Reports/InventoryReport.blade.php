@extends('layouts.layout')
@section('title')
Inventory On Hand Report
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase">Inventory On Hand Report</span>
                </div>
                <div class="nav-submenu">
                       <?php if(isset($reports) && count($reports) > 0){ ?>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " href="#" id="csv_export_btn" > CSV
                            </a>
                             <a type="button" class="btn btn-gray headerblack  buttons_menu "  href="{{route('print_page_inv')}}}" id="btnPrint">PRINT
                            </a>
                            <a type="button" class="btn btn-gray headerblack  buttons_menu " id="pdf_export_btn" href="{{route('salesreportpdf_save_page')}}" > PDF
                            </a>
                        <?php } ?>
                </div>
            </div> 
        </div>
    </nav>

<section class="section-content py-6"> 
    <div class="container">
       
      
                <?php if(isset($reports_css) && count($reports_css) > 0 ) { ?>
                
                
                 <div class="row" style="padding-bottom: 10px;float: right;">
                            <div class="col-md-12">
                            
                                <a id="pdf_export_btn" href="" class="" style="margin-right:10px;">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                                </a>
                                <a  id="btnPrint" href="{{route('print_page_inv')}}"class="" style="margin-right:10px;">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                <a id="csv_export_btn" href="" class="" style="margin-right:10px;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV
                                </a>
                        
                            </div>
                        </div>
                        <br>
                <?php } ?>
              
                <h6><span>SEARCH PARAMETERS </span></h6>
                <br>
        
                <form method="get" id="filter_form"  action="{{ route('InventoryReportForm') }}">
                        @csrf
                        <div class="row">
                          
                                <div class="col-md-3" >  
                                    <div class="form-group ">
                                      
                                            <select name="vdepcode[]" class="sample-class"  id="dept_code"  multiple="true">
                                               <!--tion value="All">All</option>-->
                                                <?php if(isset($departments) && count($departments) > 0){?>
                                                    <?php foreach($departments as $department){ ?>
                                                    <option value="<?= $department['vdepcode'] ?>" <?php if(isset($vdepcode) && !empty($vdepcode) && in_array($department['vdepcode'], $vdepcode) ){ ?> selected <?php }?> ><?= $department['vdepartmentname']; ?></option>
                                                       
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        
                                    </div>
                                </div>
                            
                                <div class="col-md-3"  >
                                    <div class="form-group ">
                                        
                                        <div>
                                            <select name="vcategorycode[]"  class="sample-class1" multiple="true" id="category_code" style="width:100%;">
                                            <!--<option value="All">All</option>-->
                                                <?php if(isset($categories) && count($categories)){?>
                                                    <?php foreach($categories as $category){ ?>
                                                      <option value="<?= $category['vcategorycode'] ?>" <?php if(isset($catcode) && !empty($catcode) && in_array($category['vcategorycode'], $catcode) ){ ?> selected <?php }?> ><?= $category['vcategoryname']; ?></option>
                                                     
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3"  >
                                    <div class="form-group ">
                                       
                                        <div>
                                            <select name="subcat_id[]" class="sample-class2" id="subcat_id" multiple="true" >
                                                 <!--<option value="All">All</option>-->
                                                 <?php if(isset($subcategories) && count($subcategories) ){?>
                                                        <?php foreach($subcategories as $subcategory){ ?>
                                                         <option value="<?= $subcategory['subcat_id'] ?>" <?php if(isset($subcode) && !empty($subcode) && in_array($subcategory['subcat_id'], $subcode) ){ ?> selected <?php }?> ><?= $subcategory['subcat_name']; ?></option>
                                                         
                                                         <?php } ?>
                                                 <?php } ?>
                                                         
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            
                                <input type="hidden" readonly class="form-control" name="qoh_date" value="<?php echo isset($qoh_date) ? $qoh_date : date('m-d-Y'); ?>" id="qoh_date" placeholder="Date" autocomplete="off">
                                      
                                
                                   <div class="col-md-2"  >
                                    <div class="form-group">
                                        
                                      <input type="submit" class="btn btn-success align-bottom rcorner header-color form-control"  value="Generate">  
                                    </div>
                                    </div>
                            
                        </div>
                    
                     
                     
                     
                  </form>
                  <br>
                 <h6><span> INVENTORY ON HAND</span></h6>
               
                
                <?php if((isset($reports) && count($reports) > 0) || (isset($reportsN) && count($reportsN) > 0)  || (isset($reportsZero) && count($reportsZero) > 0) ){ ?>
                <br>
                
                <?php if(isset($no)){?>
                  <div class="col-md-12" >
                     <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
                  </div>
                  <div class="col-md-12">
                 <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
                  </div>
                  <div class="col-md-12">
                    <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
                  </div>
        
        
                  <div class="col-md-12">
                    <p style="font-size: 14px;"><b>Date: </b><?php echo $qoh_date; ?></p>
                  </div>
                  <div class="col-md-12">
                    <!-- <p style="font-size: 14px;"><span><b>Total Quantity On Hand - </b><?php echo $total_qoh;?></span></p> -->
                    <p style="font-size: 14px;"><span><b>Total Cost Value - </b>$ <?php echo number_format((float)$toal_value, 2) ;?></span></p>
                    <p style="font-size: 14px;"><span><b>Total Retail Value - </b>$ <?php echo number_format((float)$total_retail_value, 2) ;?></span></p>
                    <!--<p><span class="text-danger">*</span>Excludes Non-Inventory items and items with zero or negative QOH</p>-->
                  </div>
                <?php }?>
         
                    <table class="table table-striped table-condensed promotionview " 
                    style="border:none;width:100%;">
                      <thead class='header'>
                        <tr class="header-color  text-uppercase">
                    
                          <th style="width: 15%;">Department</th>
                          <th style="width: 20%;">Item</th>
                          <th style="width: 10%;" class="text-right">QOH</th>
                          <th style="width: 10%;" class="text-right">Cost Value</th>
                          <th style="width: 10%;" class="text-right">Total Cost Value</th>
                          <th style="width: 10%;" class="text-right">Retail Value</th>
                          <th style="width: 10%;" class="text-right">Total Retail Value</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                      
                          <tr class="header-color  text-uppercase">
                            <td></td>
                            <td class="text-right"><b>Grand Total</b></td>
                            <td class="text-right"><b><?php echo $total_qoh;?></b></td>
                            <td></td>
                            <td class="text-right"><b>$ <?php echo number_format((float)$toal_value, 2) ;?></b></td>
                            <td class="text-right"></td>
                            <td class="text-right"><b>$ <?php echo number_format((float)$total_retail_value, 2) ;?></b></td>
                          </tr>
                            <?php foreach ($reports as $key => $value){ ?>
                          <tr>
                        
                              <table class="table  table-condensed promotionview "style="margin-top: 3px;margin-bottom: 3px;">
                                <thead>
                                  <tr class="search_header th_color " style="cursor: pointer;" data-id="<?php echo $value['key_id']; ?>">
                                    <th class="text-uppercase header " style="width: 15%;cursor: pointer;">
                                      <?php echo $value['key_name']; ?>
                                    </th>
                                    <th style="width: 20%;">&nbsp;</th>
                                    <th style="width: 10%;" class="text-right"><?php echo $value['search_total_qoh']; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_cost_price'], 2) ; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_total_cost_price'], 2) ; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_retail_value'], 2) ; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_total_retail_value'], 2) ; ?></th>
                                  </tr>
                                </thead>
                                <tbody style="display: none;">
                            
                                </tbody>
                              </table>
                            
                          </tr>
                        <?php } ?>
                         
                      </tbody>
                    </table>
                  
                
                <!---
                negative
                -->
                
                <h4> <font color="red"> <b>Items with zero QOH</b></font></h4>
                
                
                 <div class="col-md-12">
                    <a id="csv_export_btnZero" href="<?php echo 'csv_exportZero'; ?>" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</a>
                    <a href="{{route('print_page_inv_Z')}}"id="btnPrintZero"  href="" class="pull-right" style="margin-right:10px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                    <a id="pdf_export_btnZero" href=""  data-dismiss="modal"  class="pull-right" style="margin-right:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                  </div>
                  <br>
                 <table class="table table-striped table-condensed promotionview " style="border:none;width:100%;">
                      <thead>
                        <tr style="border-top: 1px solid #ddd;" class="header-color  text-uppercase">
                        
                          <th style="width: 15%;">Department</th>
                          <th style="width: 20%;">Item</th>
                          <th style="width: 10%;" class="text-right">QOH</th>
                          <th style="width: 10%;" class="text-right">Cost Value</th>
                          <th style="width: 10%;" class="text-right">Total Cost Value</th>
                          <th style="width: 10%;" class="text-right">Retail Value</th>
                          <th style="width: 10%;" class="text-right">Total Retail Value</th>
                        </tr>
                      </thead>
                      <tbody>
                         <tr class="header-color  text-uppercase">
                            <td></td>
                            <td class="text-right"><b>Grand Total</b></td>
                            <td class="text-right"><b><?php echo $total_qohZero;?></b></td>
                            <td></td>
                            <td class="text-right"><b>$ <?php echo number_format((float)$toal_valueZero, 2) ;?></b></td>
                            <td class="text-right"></td>
                            <td class="text-right"><b>$ <?php echo number_format((float)$total_retail_valueZero, 2) ;?></b></td>
                          </tr>
                        <?php foreach ($reportsZero as $key => $value){ ?>
                        
                          <tr>
                            
                              <table class="table  table-condensed promotionview" style="margin-top: 3px;margin-bottom: 3px;">
                                <thead>
                                  <tr class="search_headerZ th_color" style="cursor: pointer;" data-id="<?php echo $value['key_id']; ?>">
                                    <th class="text-uppercase header" style="width: 15%;cursor: pointer;">
                                      <?php echo $value['key_name']; ?>
                                    </th>
                                    <th style="width: 20%;">&nbsp;</th>
                                    <th style="width: 10%;" class="text-right"><?php echo $value['search_total_qoh']; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_cost_price'], 2) ; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_total_cost_price'], 2) ; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_retail_value'], 2) ; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_total_retail_value'], 2) ; ?></th>
                                  </tr>
                                </thead>
                                <tbody style="display: none;">
                            
                                </tbody>
                              </table>
                            
                          </tr>
                        <?php } ?>
                         
                      </tbody>
                    </table>
                    
                    <h4> <font color="red"> <b>Items with negative QOH</b></font></h4>
                
                
                     <div class="col-md-12">
                        <a id="csv_export_btnN" href="<?php echo 'csv_exportN'; ?>" class="pull-right" style="margin-right:10px;"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</a>
                        <a href="{{route('print_page_inv_N')}}" id="btnPrintN" href=""  class="pull-right" style="margin-right:10px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                        <a id="pdf_export_btnN"  href=""  class="pull-right" style="margin-right:10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                      </div>
                      <br>
                      
                      <table class="table table-striped table-condensed promotionview " style="border:none;width:100%;">
                      <thead>
                        <tr style="border-top: 1px solid #ddd;"  class="header-color  text-uppercase">
                         
                          <th style="width: 15%;">Department</th>
                          <th style="width: 20%;">Item</th>
                          <th style="width: 10%;" class="text-right">QOH</th>
                          <th style="width: 10%;" class="text-right">Cost Value</th>
                          <th style="width: 10%;" class="text-right">Total Cost Value</th>
                          <th style="width: 10%;" class="text-right">Retail Value</th>
                          <th style="width: 10%;" class="text-right">Total Retail Value</th>
                        </tr>
                      </thead>
                      <tbody>
                         <tr class="header-color  text-uppercase">
                            <td></td>
                            <td class="text-right"><b>Grand Total</b></td>
                            <td class="text-right"><b><?php echo $total_qohN;?></b></td>
                            <td></td>
                            <td class="text-right"><b>$ <?php echo number_format((float)$toal_valueN, 2) ;?></b></td>
                            <td class="text-right"></td>
                            <td class="text-right"><b>$ <?php echo number_format((float)$total_retail_valueN, 2) ;?></b></td>
                          </tr>
                        <?php foreach ($reportsN as $key => $value){ ?>
                        
                          <tr>
                            
                              <table class="table  table-condensed promotionview " style="margin-top: 3px;margin-bottom: 3px;">
                                <thead>
                                  <tr class="search_headerN th_color" style="cursor: pointer;" data-id="<?php echo $value['key_id']; ?>">
                                    <th class="text-uppercase header" style="width: 15%;cursor: pointer;">
                                      <?php echo $value['key_name']; ?>
                                    </th>
                                    <th style="width: 20%;">&nbsp;</th>
                                    <th style="width: 10%;" class="text-right"><?php echo $value['search_total_qoh']; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_cost_price'], 2) ; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_total_cost_price'], 2) ; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_retail_value'], 2) ; ?></th>
                                    <th style="width: 10%;" class="text-right">$<?php echo number_format((float)$value['search_total_total_retail_value'], 2) ; ?></th>
                                  </tr>
                                </thead>
                                <tbody style="display: none;">
                            
                                </tbody>
                              </table>
                            
                          </tr>
                        <?php } ?>
                    
                      </tbody>
                    </table>
      
        
        <?php }else{ ?>
            <div class="row">
              <div class="col-md-12"><br><br>
                <div class="alert alert-info text-center">
                  <strong> Sorry no data found!</strong>
                </div>
              </div>
        </div>
        <?php } ?>
    </div>

</section>
@endsection   

@section('page-script')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/reportline.css') }}">

<script>  
$(document).ready(function() {
    
  $("#btnPrintN").printPage();
  $("#btnPrintZero").printPage();
  $("#btnPrint").printPage();
    var selectedDepts   = <?php echo json_encode($vdepcode?? ''); ?>;
    var selectedCat     = <?php echo json_encode($vcategorycode ??''); ?>;
    var selectedSubCat  = <?php echo json_encode($subcat_id ?? ''); ?>;
    
    // setTimeout(function(){ 
    //     if(selectedDepts != null && selectedDepts != ''){
    //         $('#dept_code').select2().val(selectedDepts).trigger("change")
    //     }
    //     else{
    //         $selectElement = $('#dept_code').select2({
    //         placeholder: "All",
    //         allowClear: true
    //         });
    //     }
    // },1000 );
    


    // setTimeout(function(){ 
    //     if(selectedCat != null && selectedCat != ''){
    //         $('#category_code').select2().val(selectedCat).trigger("change")
    //     }else{
    //         $selectElement2 = $('#category_code').select2({
    //         placeholder: "Please select  Category",
    //         allowClear: true
    //         });
    //     }
    // }, 2000);
    
    // setTimeout(function(){ 
    //     if(selectedSubCat != null && selectedSubCat != ''){
    //         $('#subcat_id').select2().val(selectedSubCat).trigger("change");
    //     }else{
    //         $selectElement2 = $('#subcat_id').select2({
    //         placeholder: "Please select  SubCategory",
    //         allowClear: true
    //         });
    //     }
    // }, 3000);
    
    //  $selectElement = $('select[name="vdepcode[]"]').select2({
    //          placeholder: "Please select Department",
    //      });
        
    //     $selectElement2 = $('select[name="vcategorycode[]"]').select2({
    //         placeholder: "Please select Category",
    //     });
        
    //     $selectElement3 = $('select[name="subcat_id[]"]').select2({
    //         placeholder: "Please select Sub Category",
       // });
    
           $selectElement = $('#dept_code').select2({
            placeholder: "Department",
            allowClear: true
            });
            
             $selectElement2 = $('#category_code').select2({
            placeholder: "Category",
            allowClear: true
            });
    
            $('.sample-class2').select2();
            $selectElement3 = $('#subcat_id').select2({
            placeholder: " SubCategory",
            allowClear: true
    
   
    
    
    
  }); 
  
  $("#qoh_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });
});    
 $("#dept_code").change(function(){
      
        // console.log($(this).text);
      var deptCode = $(this).val();
      console.log(deptCode);
        
        var input = {};
        input['dept_code'] = deptCode;
        
        // if(deptCode == ""){
        //     alert("please select atleast one department")
        // }
        if(deptCode != ""){
    //      console.log(deptCode);
    
            
            var url ='<?php echo route('InvCategory'); ?>';
                
                url = url.replace(/&amp;/g, '&');
    
    
    
            $.ajax({
                url : url,
                data : input,
                type : 'GET',
                contentType: "application/json",
                dataType: 'text json',
                success: function(response) {
            
                    // console.log(response);
                    if(response.length > 0){
                        // $("#span_dept_name").html($("#dept_code option:selected").text());
                        // $("#category_dept_name").val($("#dept_code option:selected").text());
                        // $("#category_dept_code").val(deptCode);
                        
                        // $('select[name="vcategorycode[]"]').select2().empty().select2({data: response});
                         $('select[name="vcategorycode[]"]').select2().empty().select2({
                            placeholder: "Please select Category",
                            allowClear: true,
                            data: response
                           
                       });
                       
                    }
            
                    setTimeout(function(){
                        $('#successAliasModal').modal('hide');
                    }, 3000);
                },
                error: function(xhr) { 
                    // if error occured
                    // console.log(xhr);
                    return false;
                }
            });
        }
         $("#category_code").change(function(){
        var cat_id = $(this).val();
        var input = {};
        input['cat_id'] = cat_id;
        if(cat_id != ""){    
            var url ='<?php echo route('InvSubCategory'); ?>';
                url = url.replace(/&amp;/g, '&');
            $.ajax({
                url : url,
                data : input,
                type : 'GET',
                contentType: "application/json",
                dataType: 'text json',
                success: function(response) {
                    if(response.length > 0){
                       $('select[name="subcat_id[]"]').select2().empty().select2({placeholder: "Select Sub Category", data: response});
                    }
                    setTimeout(function(){
                        $('#successAliasModal').modal('hide');
                    }, 3000);
                },
                error: function(xhr) {
                    return false;
                }
            });
        }
    });
    });
</script>  
<style>
    .select2-container--default .select2-selection--multiple{
    border-radius: 9px !important;
    height: 38px !important;
    width
  }
  .select2.select2-container.select2-container--default{
  width: 100% !important;
  }
 
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 38px !important;
  }
  
</style>

<script type="text/javascript">
  $(document).on('click', '.search_header', function(event) {
  event.preventDefault();
  $(this).toggleClass('expand');

  var current_header = $(this);

  var report_by = $('#report_by').val();
  var report_data = $('#report_data').val();
  var report_pull_id = $(this).attr('data-id');
  
  var data_post = {report_by:report_by,report_data:report_data,report_pull_id:report_pull_id};

 // data_post= JSON.stringify(data_post);

  var report_ajax_data_url = '<?php echo route('InvReport_Positive'); ?>';
  report_ajax_data_url = report_ajax_data_url.replace(/&amp;/g, '&');

  if($(this).hasClass('expand')){
    $.ajax({
      url : report_ajax_data_url,
      data : data_post,
      type : 'GET',
      contentType: "application/json",
     dataType: 'json',
      }).done(function(response){
        
        current_header.parent().parent().find('tbody').empty();
        var html = '';
        if(response){
          var total_qty = 0;
          var total_total_cost = 0;
          var total_total_value = 0;
          var total_total_retail_value = 0;

          html += ''
          $.each(response,function(i,v){

            var tot_value = parseInt(v.iqtyonhand) * parseFloat(v.cost).toFixed(2);
            var tot_ret_value = parseInt(v.iqtyonhand) * parseFloat(v.price/v.nsellunit).toFixed(2);

            total_total_retail_value = total_total_retail_value + tot_ret_value;

            total_qty = total_qty + parseInt(v.iqtyonhand);
            total_total_cost = total_total_cost + parseFloat(v.cost);
            
            total_total_value = total_total_value + tot_value;
          
            html += '<tr>';
            html += '<td style="width: 15%;">';
            html += v.vname;
            html += '</td>';
            html += '<td style="width: 20%;">';
            html += v.itemname;
            html += '</td>';
            html += '<td style="width: 10%;" class="text-right">';
            html += parseInt(v.iqtyonhand);
            html += '</td>';
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += parseFloat(v.cost).toFixed(2);
            html += '</td>';
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += tot_value.toFixed(2);
            html += '</td>';
            
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += parseFloat(v.price/v.nsellunit).toFixed(2);
            html += '</td>';
            
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += tot_ret_value.toFixed(2);
            html += '</td>';
            html += '</tr>';
          });

          html += '<tr class="header-color  text-uppercase">';
          html += '<td style="width: 10%;"></td>';
          html += '<td style="width: 20%;" class="text-right"><b>Total</b></td>';
          html += '<td style="width: 10%;" class="text-right"><b>'+ total_qty +'</b></td>';
          html += '<td style="width: 10%;"></td>';
          html += '<td style="width: 10%;" class="text-right"><b>$ '+total_total_value.toFixed(2) +'</b></td>';
          html += '<td style="width: 10%;" class="text-right"></td>';
          html += '<td style="width: 10%;" class="text-right"><b>$ '+total_total_retail_value.toFixed(2) +'</b></td>';
          html += 'tr>';

          current_header.parent().parent().find('tbody').append(html);
          current_header.parent().parent().find('tbody').show();
        }
    });
  }
  
  if(!$(this).hasClass('expand')){
    $(this).parent().parent().find('tbody').empty();
  }

});
</script>
<script type="text/javascript">
  $(document).on('click', '.search_headerN', function(event) {
  console.log("test");      
  event.preventDefault();
  $(this).toggleClass('expand');

  var current_header = $(this);

  var report_by = $('#report_by').val();
  var report_data = $('#report_data').val();
  var report_pull_id = $(this).attr('data-id');
  
  var data_post = {report_by:report_by,report_data:report_data,report_pull_id:report_pull_id};

  //data_post= JSON.stringify(data_post);

  var report_ajax_dataN_url = '<?php echo route('InvReport_N'); ?>';
  report_ajax_dataN_url = report_ajax_dataN_url.replace(/&amp;/g, '&');

  if($(this).hasClass('expand')){
    $.ajax({
      url : report_ajax_dataN_url,
      data : data_post,
      type : 'GET',
      contentType: "application/json",
      dataType: 'json',
      }).done(function(response){
        
        current_header.parent().parent().find('tbody').empty();
        var html = '';
        if(response){
          var total_qty = 0;
          var total_total_cost = 0;
          var total_total_value = 0;
          var total_total_retail_value = 0;

          html += ''
          $.each(response,function(i,v){

            var tot_value = parseInt(v.iqtyonhand) * parseFloat(v.cost).toFixed(2);
            var tot_ret_value = parseInt(v.iqtyonhand) * parseFloat(v.price/v.nsellunit).toFixed(2);

            total_total_retail_value = total_total_retail_value + tot_ret_value;

            total_qty = total_qty + parseInt(v.iqtyonhand);
            total_total_cost = total_total_cost + parseFloat(v.cost);
            
            total_total_value = total_total_value + tot_value;
          
            html += '<tr>';
            html += '<td style="width: 15%;">';
            html += v.vname;
            html += '</td>';
            html += '<td style="width: 20%;">';
            html += v.itemname;
            html += '</td>';
            html += '<td style="width: 10%;" class="text-right">';
            html += parseInt(v.iqtyonhand);
            html += '</td>';
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += parseFloat(v.cost).toFixed(2);
            html += '</td>';
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += tot_value.toFixed(2);
            html += '</td>';
            
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += parseFloat(v.price/v.nsellunit).toFixed(2);
            html += '</td>';
            
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += tot_ret_value.toFixed(2);
            html += '</td>';
            html += '</tr>';
          });

          html += '<tr class="header-color  text-uppercase">';
          html += '<td style="width: 10%;"></td>';
          html += '<td style="width: 20%;" class="text-right"><b>Total</b></td>';
          html += '<td style="width: 10%;" class="text-right"><b>'+ total_qty +'</b></td>';
          html += '<td style="width: 10%;"></td>';
          html += '<td style="width: 10%;" class="text-right"><b>$ '+total_total_value.toFixed(2) +'</b></td>';
          html += '<td style="width: 10%;" class="text-right"></td>';
          html += '<td style="width: 10%;" class="text-right"><b>$ '+total_total_retail_value.toFixed(2) +'</b></td>';
          html += 'tr>';

          current_header.parent().parent().find('tbody').append(html);
          current_header.parent().parent().find('tbody').show();
        }
    });
  }
  
  if(!$(this).hasClass('expand')){
    $(this).parent().parent().find('tbody').empty();
  }

});
</script>

<script type="text/javascript">
  $(document).on('click', '.search_headerZ', function(event) {
  console.log("test");      
  event.preventDefault();
  $(this).toggleClass('expand');

  var current_header = $(this);

  var report_by = $('#report_by').val();
  var report_data = $('#report_data').val();
  var report_pull_id = $(this).attr('data-id');
  
  var data_post = {report_by:report_by,report_data:report_data,report_pull_id:report_pull_id};

  //data_post= JSON.stringify(data_post);

  var report_ajax_dataZ_url ='<?php echo route('InvReport_zero'); ?>';
  report_ajax_dataZ_url = report_ajax_dataZ_url.replace(/&amp;/g, '&');

  if($(this).hasClass('expand')){
    $.ajax({
      url : report_ajax_dataZ_url,
      data : data_post,
      type : 'GET',
      contentType: "application/json",
      dataType: 'json',
      }).done(function(response){
        
        current_header.parent().parent().find('tbody').empty();
        var html = '';
        if(response){
          var total_qty = 0;
          var total_total_cost = 0;
          var total_total_value = 0;
          var total_total_retail_value = 0;

          html += ''
          $.each(response,function(i,v){

            var tot_value = parseInt(v.iqtyonhand) * parseFloat(v.cost).toFixed(2);
            var tot_ret_value = parseInt(v.iqtyonhand) * parseFloat(v.price/v.nsellunit).toFixed(2);

            total_total_retail_value = total_total_retail_value + tot_ret_value;

            total_qty = total_qty + parseInt(v.iqtyonhand);
            total_total_cost = total_total_cost + parseFloat(v.cost);
            
            total_total_value = total_total_value + tot_value;
          
            html += '<tr>';
            html += '<td style="width: 15%;">';
            html += v.vname;
            html += '</td>';
            html += '<td style="width: 20%;">';
            html += v.itemname;
            html += '</td>';
            html += '<td style="width: 10%;" class="text-right">';
            html += parseInt(v.iqtyonhand);
            html += '</td>';
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += parseFloat(v.cost).toFixed(2);
            html += '</td>';
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += tot_value.toFixed(2);
            html += '</td>';
            
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += parseFloat(v.price/v.nsellunit).toFixed(2);
            html += '</td>';
            
            html += '<td style="width: 10%;" class="text-right">';
            html +='$';
            html += tot_ret_value.toFixed(2);
            html += '</td>';
            html += '</tr>';
          });

          html += '<tr class="header-color  text-uppercase">';
          html += '<td style="width: 10%;"></td>';
          html += '<td style="width: 20%;" class="text-right"><b>Total</b></td>';
          html += '<td style="width: 10%;" class="text-right"><b>'+ total_qty +'</b></td>';
          html += '<td style="width: 10%;"></td>';
          html += '<td style="width: 10%;" class="text-right"><b>$ '+total_total_value.toFixed(2) +'</b></td>';
          html += '<td style="width: 10%;" class="text-right"></td>';
          html += '<td style="width: 10%;" class="text-right"><b>$ '+total_total_retail_value.toFixed(2) +'</b></td>';
          html += 'tr>';

          current_header.parent().parent().find('tbody').append(html);
          current_header.parent().parent().find('tbody').show();
        }
    });
  }
  
  if(!$(this).hasClass('expand')){
    $(this).parent().parent().find('tbody').empty();
  }

});
</script>

<script>

const saveData = (function () {
    const a = document.createElement("a");
    document.body.appendChild(a);
    a.style = "display: none";
    return function (data, fileName) {
        const blob = new Blob([data], {type: "octet/stream"}),
            url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = fileName;
        a.click();
        window.URL.revokeObjectURL(url);
    };
  }());


</script>
<script>  

    $(document).on("click", "#pdf_export_btn", function (event) {

        event.preventDefault();
       

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('InventoryReportpdf'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "InventoryReportpdf.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "InventoryReportpdf.pdf";

              // append the link to the document body

              document.body.appendChild(link);

              link.click();
               
            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();
        
    });
     $(document).on("click", "#csv_export_btn", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('InventoryReportcsv'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "InventoryReportcsv.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });
</script>    
<script>  

    $(document).on("click", "#pdf_export_btnZero", function (event) {

        event.preventDefault();
       

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('InventoryReportpdf_Z'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "InventoryReportpdf.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "InventoryReportpdf.pdf";

              // append the link to the document body

              document.body.appendChild(link);

              link.click();
               
            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();
        
    });
     $(document).on("click", "#csv_export_btnZero", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('InventoryReportcsv_Z'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "InventoryReportcsv.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });
</script>    
<script>  

    $(document).on("click", "#pdf_export_btnN", function (event) {

        event.preventDefault();
       

        $("div#divLoading").addClass('show');

        var pdf_export_url = '<?php echo route('InventoryReportpdf_N'); ?>';
      
        pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

        var req = new XMLHttpRequest();
        req.open("GET", pdf_export_url, true);
        req.responseType = "blob";
        req.onreadystatechange = function () {
          if (req.readyState === 4 && req.status === 200) {

            if (typeof window.navigator.msSaveBlob === 'function') {
              window.navigator.msSaveBlob(req.response, "InventoryReportpdf.pdf");
            } else {
              var blob = req.response;
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = "InventoryReportpdf.pdf";

              // append the link to the document body

              document.body.appendChild(link);

              link.click();
               
            }
          }
          $("div#divLoading").removeClass('show');
        };
        req.send();
        
    });
     $(document).on("click", "#csv_export_btnN", function (event) {

        event.preventDefault();

        $("div#divLoading").addClass('show');

          var csv_export_url = '<?php echo route('InventoryReportcsv_N'); ?>';
        
          csv_export_url = csv_export_url.replace(/&amp;/g, '&');

          $.ajax({
            url : csv_export_url,
            type : 'GET',
          }).done(function(response){
            
            const data = response,
            fileName = "InventoryReportcsv.csv";

            saveData(data, fileName);
            $("div#divLoading").removeClass('show');
            
          });
        
    });
</script>  

<style>
.rcorner {
  border-radius:9px;
}
.th_color{
    background-color: #474c53 !important;
    color: #fff;
    
  
}


[class^='select2'] {
  border-radius: 9px !important;
}
table, .promotionview {
    width: 100% !important;
    position: relative;
    left: 0%;
}
</style>

@endsection 