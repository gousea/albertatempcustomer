@extends('layouts.master')

@section('title')
  Taxes
@endsection

@section('main-content')

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      
      <!-- <h1>Taxes</h1> -->
      <ul class="breadcrumb">
        <li><a href="https://customer.albertapayments.com/index.php?route=common/dashboard&amp;token=82bT8kvibI19RHfZNJp4h2leEGdWZGsZ">Home</a></li>
        <li><a href="https://customer.albertapayments.com/index.php?route=administration/tax&amp;token=82bT8kvibI19RHfZNJp4h2leEGdWZGsZ">Taxes</a></li>
      </ul>
    </div>
  </div>


  <div class="container-fluid">
    @if (session()->has('message'))
          <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>      
    @endif
    
    @if (session()->has('error-message'))
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{session()->get('error-message')}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>      
    @endif
    
    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
          <i class="fa fa-exclamation-circle"></i>{{$error}}
          <button type="button" class="close" data-dismiss="alert">&times;</button><br/>
        @endforeach
        
      </div> 
    @endif
     
    <div class="panel panel-default">
      <div class="panel-heading head_title">
        <h3 class="panel-title"><i class="fa fa-list"></i> Tax</h3>
      </div>

      <div class="panel-body">
        <div class="row" style="padding-bottom: 15px;float: right;">
          <div class="col-md-12">
            <div class="">
              <a id="save_button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>   
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        
        <form action="" method="post" enctype="multipart/form-data" id="form-tax">
        @csrf
        <?php if(session()->get('hq_sid') == 1){ ?>
            <input type="hidden" id="stores_hq" name="stores_hq" value="">
        <?php } ?>
          <div class="table-responsive">
            <table id="table_tax" class="table table-bordered table-hover" style="width:50%;">
              <thead>
                <tr>
                 <?php if(isset($taxes[0]['Id'])) {?>    
                    <td style="" class="text-left">TAX1</td>
                  <?php }?>
                  
                  <?php if(isset($taxes[1]['Id'])) {?>  
                  <td class="text-left">TAX2</td>
                   <?php }?>

                  <?php if(isset($taxes[2]['Id'])) {?>

                  <td class="text-left">TAX3</td>
                  <?php }?>
                </tr>
              </thead>

              <tbody> 
            
                <tr>
                 <?php if(isset($taxes[0]['vtaxtype'])) {?>
                  
                  <td class="text-left">
                      <input type="hidden" name="Id1" value="<?php echo (isset($taxes[0]['Id'])?$taxes[0]['Id']: '');?>">
                      
                    <b>Name : </b>&nbsp;&nbsp;
                    <input type="text" class="editable tax_name" name="vtaxtype1" maxlength="50" id="vtaxtype1" value="<?php echo $taxes[0]['vtaxtype'];?>"/>
                    <input type="hidden" class="hiddentaxcode" name="vtaxcode" value="<?php echo (isset($taxes[0]['Id'])?$taxes[0]['vtaxcode']: '');?>">
                  </td>
                <?php }?>
                
               
                  
                  <?php if(isset($taxes[1]['vtaxtype'])) {?>
                  <td class="text-left">
                  <input type="hidden" name="Id2" value="<?php echo (isset($taxes[1]['Id'])?$taxes[1]['Id']:'');?>">
                  
                  <!--<input type="hidden" name="Id2" value="<?php echo ($taxes[1]['Id']);?>">-->
                  
                    <b>Name : </b>&nbsp;&nbsp;
                    <input type="text" class="editable tax_name" name="vtaxtype2" maxlength="50" id="vtaxtype2" value="<?php echo $taxes[1]['vtaxtype'];?>"/>
                    <input type="hidden" class="hiddentaxcode" name="vtaxcode" value="<?php echo (isset($taxes[1]['Id'])?$taxes[1]['vtaxcode']: '');?>">
                  </td>
                  <?php }?>
                  
                  <?php if(isset($taxes[2]['vtaxtype'])) {?>
                  
                  
                  <td class="text-left">
                  <input type="hidden" name="Id3" value="<?php echo (isset($taxes[2]['Id'])?$taxes[2]['Id']:'');?>">
                  <b>Name : </b>&nbsp;&nbsp;
                  <input type="text" class="editable tax_name" name="vtaxtype3" maxlength="50" id="vtaxtype3" value="<?php echo $taxes[2]['vtaxtype'];?>"/>
                  <input type="hidden" class="hiddentaxcode" name="vtaxcode" value="<?php echo (isset($taxes[3]['Id'])?$taxes[3]['vtaxcode']: '');?>">
                   </td>
                   
                  <?php }?>
              
                </tr>
                
                <tr>
                  <?php if(isset($taxes[0]['ntaxrate'])) {?>  
                  <td class="text-left"><b>Rate&nbsp;&nbsp; : </b>&nbsp;&nbsp;
                    <input type="hidden" class="hiddentaxname" name="hiddentaxname" value="<?php echo $taxes[0]['vtaxtype'];?>">
                    <input type="text" class="editable tax_rate" name="ntaxrate1" id="taxRate1" maxlength="7" max="100"  value="<?php echo $taxes[0]['ntaxrate'];?>"/>
                 </td>
                  <?php }?>
                  
                 <?php if(isset($taxes[1]['ntaxrate'])) {?>
                  <td class="text-left"><b>Rate&nbsp;&nbsp; : </b>&nbsp;&nbsp;
                    <input type="hidden" class="hiddentaxname" name="hiddentaxname" value="<?php echo $taxes[1]['vtaxtype'];?>">
                    <input type="text" class="editable tax_rate" name="ntaxrate2" id="taxRate2" maxlength="7" value="<?php echo $taxes[1]['ntaxrate'];?>"/>
                 </td>
                  <?php }?>
                 
                  <?php if(isset($taxes[2]['ntaxrate'])) {?>
                  <td class="text-left"><b>Rate&nbsp;&nbsp; : </b>&nbsp;&nbsp;
                    <input type="hidden" class="hiddentaxname" name="hiddentaxname" value="<?php echo $taxes[2]['vtaxtype'];?>">
                    <input type="text" class="editable tax_rate" name="ntaxrate3" id="taxRate3" maxlength="7" value="<?php echo $taxes[2]['ntaxrate'];?>"/></td>
                  <?php }?>

                </tr>
          </tbody>
              
            </table>
          </div>
        </form>
        
      </div>
    </div>
  </div>
</div>


<?php if(session()->get('hq_sid') == 1){ ?>
     <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Select the stores in which you want to update the Tax :</h4>
          </div>
        
          <div class="modal-body">
             <table class="table table-bordered">
                <thead id="table_green_header_tag">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="editSelectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead>
                <tbody id="tax_stores"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button id="Edit_btn_tax" class="btn btn-danger" data-dismiss="modal">Update</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
    
      </div>
    </div>
<?php } ?>


@endsection

@section('scripts')


<script src="/javascript/bootbox.min.js" defer=""></script>

<script type="text/javascript">
  $(document).on('click', '#save_button', function(event) {
    
    event.preventDefault();
    
    var edit_url = '/updatetax';
    edit_url = edit_url.replace(/&amp;/g, '&');
    
    var all_tax_name = true;
    
    $('.tax_name').each(function(){
      if($(this).val() == ''){
        // alert('Please Enter Name');
        bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: "Please Enter Name", 
          callback: function(){}
        });
        all_tax_name = false;
        return false;
      }else{
        all_tax_name = true;
      }
    });
    
    
    var numericReg = /^[0-9]*(?:\.\d{1,2})?$/;
    if(all_tax_name == true){
        var all_tax_rate = true;
        var taxes = [];
        var hiddentaxname = [];
        $('.hiddentaxname').each(function(){
            hiddentaxname.push($(this).val())
        });
        var hiddentaxcode = [];
        $('.hiddentaxcode').each(function(){
            hiddentaxcode.push($(this).val())
        });
        
        $('.tax_rate').each(function(){
            
            
            taxes.push($(this).val());
            if($(this).val() == ''){
                // alert('Please Enter Rate');
                bootbox.alert({ 
                    size: 'small',
                    title: "Attention", 
                    message: "Please Enter Rate", 
                    callback: function(){}
                });
                all_tax_rate = false;
                return false;
            }
            // else{
            //     if(numericReg.test($(this).val())){
            //         // alert('Please Enter Valid Rate');
            //         // bootbox.alert({ 
            //         //   size: 'small',
            //         //   title: "Attention", 
            //         //   message: "Please Enter Valid Rate", 
            //         //   callback: function(){}
            //         // });
            //         all_tax_rate = false;
            //         return false;
            //     }else{
            //         all_tax_rate = true;
            //     }
            // }
        });
    }else{
      all_tax_rate = false;
    }
    
    if(all_tax_rate == true){
        <?php if(session()->get('hq_sid') == 1){ ?>
            $.ajax({
                url : "<?php echo url('/duplicatetax'); ?>",
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                },
                data : JSON.stringify({tax_name:hiddentaxname, tax_rate: taxes, tax_code: hiddentaxcode}),
                type : 'POST',
                contentType: "application/json",
                dataType: 'json',
                success: function(result) {
                    var popup = '';
                    @foreach (session()->get('stores_hq') as $stores)
                        if(result.includes({{ $stores->id }})){
                            var data = '<tr>'+
                                            '<td>'+
                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                    '<input type="checkbox" class="checks check custom-control-input editstores" disabled id="hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                '</div>'+
                                            '</td>'+
                                            '<td class="checks_content" style="color:grey"><span>{{ $stores->name }} [{{ $stores->id }}] (Item does not exist)</span></td>'+
                                        '</tr>';
                                    $('#editSelectAllCheckbox').attr('disabled', true);
                              
                        } else {
                            var data = '<tr>'+
                                            '<td>'+
                                                '<div class="custom-control custom-checkbox" id="table_green_check">'+
                                                    '<input type="checkbox" class="checks check custom-control-input editstores"  id="else_hq_sid_{{ $stores->id }}" name="editstores" value="{{ $stores->id }}">'+
                                                '</div>'+
                                            '</td>'+
                                            '<td class="checks_content" ><span>{{ $stores->name }} [{{ $stores->id }}] </span></td>'+
                                        '</tr>';
                        }
                        popup = popup + data;
                    @endforeach
                    $('#tax_stores').html(popup);    
                }
            });
            $("#myModal").modal('show');
        <?php } else { ?>
            $('#form-tax').attr('action', edit_url);
            $('#form-tax').submit();
            $("div#divLoading").addClass('show');
        <?php } ?>
        
        
     
    }
  });
  
  var edit_stores = [];
    edit_stores.push("{{ session()->get('sid') }}");
    $('#editSelectAllCheckbox').click(function(){
        if($('#editSelectAllCheckbox').is(":checked")){
            $(".editstores").prop( "checked", true );
        }else{
            $( ".editstores" ).prop("checked", false );
        }
    });
    
    $('#Edit_btn_tax').click(function(){
        $.each($("input[name='editstores']:checked"), function(){            
            edit_stores.push($(this).val());
        });
        
        var edit_url = '/updatetax';
        edit_url = edit_url.replace(/&amp;/g, '&');
        
        $("#stores_hq").val(edit_stores);
        $('#form-tax').attr('action', edit_url);
        $('#form-tax').submit();
        $("div#divLoading").addClass('show');
    });
</script>

<script type="text/javascript">
  
  $(document).on('keypress keyup blur', 'input[name="ntaxrate1"], input[name="ntaxrate2"], input[name="ntaxrate3"]', function(event) {

    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))  {
      event.preventDefault();
    }
    
  }); 

  $(document).on('focusout', 'input[name="ntaxrate1"], input[name="ntaxrate2"], input[name="ntaxrate3"]', function(event) {
    event.preventDefault();

    if($(this).val() != ''){
      if($(this).val().indexOf('.') == -1){
        var new_val = $(this).val();
        $(this).val(new_val+'.0000');
      }else{
        var new_val = $(this).val();
        if(new_val.split('.')[1].length == 0){
          $(this).val(new_val+'0000');
        }
      }
    }
  });

  $(document).on("keyup", ".tax_rate", function(event) {

      let prevVal = this.value;
      
      //console.log(this.value);
      
      if(this.value.length > 7){
        $('#warningModal').show();
        $(this).val(this.value.substr(0, 7));
        
        
        
        event.preventDefault();
      }

      var val = parseInt(this.value);
      
      
      
      console.log(val);


    //   if(val > 100 || val < 0)
    //   {
    //       console.log(val)
          
          
    //       alert('Invalid Tax Rate.  Please Check..!');
    //      // this.value= '' ; 
    //       event.preventDefault();
          
    //   }
      
      if(val > 100 || val < 0)
      {
       
       
          alert('Invalid Tax Rate.  Please Check..!');
          this.value='';  
          
          event.preventDefault();
          
      }
      
      
  });



</script>

<script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>
@endsection