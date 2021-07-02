@extends('layouts.layout')
@section('title')
Employee Loss Prevention Report
@endsection
@section('main-content')

<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
        <div class="container">
            <div class="collapse navbar-collapse" id="main_nav">
                <div class="menu">
                    <span class="font-weight-bold text-uppercase"> Employee Loss Prevention Report</span>
                </div>
                <div class="nav-submenu">
                     
                </div>
            </div> 
        </div>
    </nav>

  <div class="container">  
          <br> 
          <h6><span> EXPORT PRODUCT LIST</span></h6>
          <br>
          <form method="post" id="export_form">
            @csrf
            @method('post')
           
              <input type="button" id="csv_export_btn" name="export" value="EXPORT" class="btn btn-success rcorner header-color" style="width: 135px";>
             
            </div>
          </form>
  
  </div>


@endsection
@section('page-script')

<script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/reportline.css') }}">


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

//   $(document).on('submit', '#filter_form', function(event) {
    
//     if($('#report_by > option:selected').length == 0){
//       alert('Please Select Department');
//       return false;
//     }
    
//     if($('#report_by').val() == ''){
//       alert('Please Select Department');
//       return false;
//     }

//     if($('#start_date').val() == ''){
//       alert('Please Select Start Date');
//       return false;
//     }

//     if($('#end_date').val() == ''){
//       alert('Please Select End Date');
//       return false;
//     }

//     $("div#divLoading").addClass('show');
    
//   });

//   $(document).ready(function() {
//     $("#btnPrint").printPage();
//   });

//   $(window).load(function() {
//     $("div#divLoading").removeClass('show');
//   });

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