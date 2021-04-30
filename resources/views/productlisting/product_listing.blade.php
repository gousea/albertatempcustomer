@extends('layouts.master')
@section('title') Product Listing Report @endsection
@section('main-content')

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!-- <h1><?php //echo $heading_title; ?></h1> -->
      <ul class="breadcrumb">
        <?php //foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php //echo $breadcrumb['href']; ?>"><?php //echo $breadcrumb['text']; ?></a></li>
        <?php //} ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Product Listing Report"; ?></h3>
      </div>
      <div class="panel-body">
        <div class="clearfix"></div>
        <div class="row">
          <form method="post" id="export_form">
            @csrf
            @method('post')
            <div class="col-md-12">
              <input type="button" id="csv_export_btn" name="export" value="Export" class="btn btn-success">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<script src="{{ asset('javascript/bootbox.min.js')}}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<style type="text/css">
  .table.table-bordered.table-striped.table-hover thead > tr {
    background-color: #2486c6;
    color: #fff;
  }

</style>

<script type="text/javascript">
  $(function(){
    $("#start_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });

    $("#end_date").datepicker({
      format: 'mm-dd-yyyy',
      todayHighlight: true,
      autoclose: true,
    });
  });

  $(document).ready(function() {
    // $('#report_by').select2({
    //   placeholder: "Please Select Department"
    // });
  });

  $(document).on('submit', '#filter_form', function(event) {
    
    if($('#report_by > option:selected').length == 0){
      alert('Please Select Department');
      return false;
    }
    
    if($('#report_by').val() == ''){
      alert('Please Select Department');
      return false;
    }

    if($('#start_date').val() == ''){
      alert('Please Select Start Date');
      return false;
    }

    if($('#end_date').val() == ''){
      alert('Please Select End Date');
      return false;
    }

    $("div#divLoading").addClass('show');
    
  });

  $(document).ready(function() {
    $("#btnPrint").printPage();
  });

  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });

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


  $(document).on("click", "#csv_export_btn", function (event) {
      
    var check_password_url = "{{route('productlisting_checkpassword')}}";
    check_password_url = check_password_url.replace(/&amp;/g, '&');
    
    event.preventDefault();
    
    var is_accessible = false;
    bootbox.prompt({
        title: "Please enter password!",
        inputType: 'password',
        callback: function (result) {
            
            $.ajax({
                    url : check_password_url,
                    type : 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data : {'password' : result},
                  }).done(function(password_result){
                    console.log(password_result+"asdfasdf");
                    if(password_result == "true")
                    {
                        $("div#divLoading").addClass('show');
                    
                          var csv_export_url = "{{route('productlisting')}}";
                        
                          csv_export_url = csv_export_url.replace(/&amp;/g, '&');
                    
                          $.ajax({
                            url : csv_export_url,
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            type : 'POST',
                          }).done(function(response){
                            
                            const data = response,
                            fileName = "product-list.csv";
                    
                            saveData(data, fileName);
                            $("div#divLoading").removeClass('show');
                            
                          });
                    }
                    else
                    {
                        bootbox.alert({ 
                            size: 'small',
                            title: "Attention", 
                            message: "Invalid Password!", 
                            callback: function(){}
                        });
                    }
            });
            
        }
    });
    
  });

</script>

@endsection