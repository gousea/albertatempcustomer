@extends('layouts.layout')

@section('title')
  Item Group
@endsection

@section('main-content')

<div id="content">

  <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold text-uppercase">Item Group</span>
            </div>
            <div class="nav-submenu">
                
                <button id="save_button_group" data-toggle="tooltip" title="Save" class="btn btn-gray headerblack  buttons_menu" ><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
                <a style="pointer-events:all;" href="/itemgroup" data-toggle="tooltip" title="Cancel" class="btn btn-danger buttonred buttons_menu basic-button-small text-uppercase cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
  </nav>
  
  <div class="container-fluid section-content">
    <div class="container-fluid">

      @if (session()->has('message'))
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> {{session()->get('message')}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>      
      @endif

      @if (session()->has('error'))
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{session()->get('error')}}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>      
      @endif

      <div id='errorDiv'>
      </div>
      @if ($errors->any())
        <div class="alert alert-danger">
          @foreach ($errors->all() as $error)
            <i class="fa fa-exclamation-circle"></i>{{$error}}
          @endforeach
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div> 
      @endif
      <div class="panel panel-default">
        
        <div class="panel-body">
          
          <form action="" method="post"  id="form-group_search_sku" class="form-horizontal">
            @csrf
            <div class="container section-content">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-group">Group Name</label>
                    <div class="col-sm-8">
                      <input type="text" maxlength="100" name="vitemgroupname" value="" placeholder="Group Name" class="form-control adjustment-fields" required="">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="input-template">Item Type</label>
                    <div class="col-sm-8">
                      <select name="vtype" id="vtype" class="form-control adjustment-fields">
                        <option value="">Please Select</option>
                        <option value="Product" selected="selected">Product</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
              <br><br>
            <div class="row">
              <div class="col-md-5">
                <form action="" method="post"  id="groupsearch" >
                @csrf
                
                  <div class="table-responsive">
                    <table class="table table-hover promotionview" style="padding:0px; margin:0px; left:3%;">
                      <thead>
                        <tr class="header-color">
                          <td style="width: 1px;" class="text-center">
                            <input type="checkbox" style="background: none;" onclick="$('input[name*=\'table1\']').prop('checked', this.checked)" name="table1">
                          </td>
                          <td style="width:130px;">
                            <input type="text" class="form-control search_c" id="itemsort1_search_sku" placeholder="SKU" style="border:none;" autocomplete="off">
                          </td>
                          <td style="width:242px;">
                            <input type="text" class="form-control search_c" id="itemsort1_search_item_name" placeholder="Name" style="border:none;">
                          </td>
                          <td class="text-right" style="width:100px; padding-top: 20px;">Price</td>
                        </tr>
                      </thead>
                    </table>
                    <div class="div-table-content">
                      <table class="table table-hover promotionview" id="itemsort1" style="table-layout: fixed; left:3%;">
                        <tbody id="searchData" style="display: block; height:400px; overflow-y : scroll;">
                            
                        </tbody>
                      </table>
                    </div>
                  </div>
                
                </form>
              </div>
              <div class="col-md-2 text-center" style="margin-top:12%;">
                
                <a class="btn btn-primary " style="cursor:pointer" id="add_item_btn"><i class="fa fa-arrow-right fa-3x"></i></a><br><br>
                <a class="btn btn-primary" style="cursor:pointer" id="remove_item_btn"><i class="fa fa-arrow-left fa-3x"></i></a>
              </div>

              <div class="col-md-5">
                  <form action="insertgroupname" method="post"  id="groupdetail" class="form-horizontal">
                  @csrf
                  
                    <div class="table-responsive">
                      <table class="table table-hover promotionview" style="padding:0px; margin:0px; left:3%;">
                        <thead>
                          <tr class="header-color">
                            <td style="width:1%;" class="text-center">
                              <input style="background: none;"  type="checkbox" onclick="$('input[name*=\'table2\']').prop('checked', this.checked)" name="table2">
                            </td>
                            <td style="width:242px;">
                              <input type="text" class="form-control itemsort2_search" placeholder="Name" style="border:none;">
                            </td>
                            <td class="text-right" style="width:100px; padding-top: 20px;">Sequence</td>
                            <td class="text-right" style="padding-top: 20px;">Price</td>
                          </tr>
                        </thead>
                      </table>
                      <div class="div-table-content" style="">
                        <table class="table table-hover promotionview" id="itemsort2" style="left:3%;">
                          <tbody id="resultData" style="display: block; height:400px; overflow-y : scroll;">
                          
                          </tbody>
                        </table>
                      </div>
                    </div>
                  
                  </form>
              </div>
            </div>
          
        </div>
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
            <h6 class="modal-title">Select the stores in which you want to Add New Item Group:
            <span style="color: blue;">Note: Please make sure all the items are exists in child store also</span></h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <table class="table" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                <thead id="table_green_header_tag" style="background-color: #286fb7!important;">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="selectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead >
                <tbody id="data_stores">
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check  stores" id="stores" name="stores" value="{{ $stores->id }}">
                                </div>
                            </td>
                            <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" id="save_btn" class="btn btn-success" data-dismiss="modal">Save</button>
          </div>
        </div>
      </div>
    </div>
    
<?php } ?>

@endsection

@section('page-script')

<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
<script src="/javascript/bootbox.min.js" defer=""></script>
<script>

  itemsSelected = [];

  //Item Filter
  $(document).on('submit', '#groupsearch', function (e) {
      e.preventDefault();
  });

  $(document).on('keyup', '.search_c', function (e) {
    e.preventDefault();

    var avArr = [];

    console.log(avArr);

    $("#itemsort2 tr").each(function () {
      var barcode = $(this).closest('tr').find('.vitemcode_c').val();
      avArr.push({
        barcode: $(this).find('.vbarcode_right').val()      
      });
    });

    var sku = $('#itemsort1_search_sku').val();
    var itemName = $('#itemsort1_search_item_name').val();

    var data = {};

    data['sku'] = sku;
    data['name'] = itemName;

    // data.push(sku: sku);
    // data['name'] = itemName;
    console.log(sku);        
    console.log(itemName);        
    console.log(data);
    if(sku.length == 0 && itemName == 0){
      return false;
    }

    
    if (typeof currentRequest === 'undefined') {
      currentRequest = null;
    }


    currentRequest = jQuery.ajax({
      type: 'POST',
      dataType: 'json',
      url: '/itemgroupsearch',
      headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},

      data: JSON.stringify({sku:sku, name:itemName ,barcodes: avArr}),
      //data: JSON.stringify(data),
      contentType: 'application/json; charset=utf-8',

      // data: data,
      beforeSend : function()    {

        console.log(currentRequest);           
        if(currentRequest != null) {
            currentRequest.abort();
        }
      },
      success: function (itemGroup) {

        let html = '';

        $.each(itemGroup.item, function(k, v){
          //console.log(v);
          html +='<tr>';

          html +='<td class="text-center" style="width:30px;">';
          html +='<input type="checkbox" name="table1[]" value="'+v.iitemid+'"  >';
          html +='<input type="hidden" name="checkbox_itemsort1['+v.iitemid+']" value="'+v.iitemid+'"  >';
          html +='</td>';

          html +='<td style="width:105px;">'+v.vitemcode;
          html +='<input type="hidden" class="vitemcode_c" id ="vitemcode['+v.vitemcode+']" value="'+v.vitemcode+'">';
          html +='</td>';

          html +='<td style="width:51%;">'+v.vitemname;
          html +='<input type="hidden" class="vitemname_c" id ="vitemname['+v.vitemname+']" value="'+v.vitemname+'">';
          html +='</td>';

          html +='<td class="text-right" style="width:70px;">'+v.dunitprice;
          html +='<input type="hidden" class="dunitprice_c"  value="'+v.dunitprice+'">';
          html +='</td>';

          html +='</tr>';
        });
        // console.log("1");

        $('#searchData').html(html);

        //console.log("2");
        $('#divLoading').removeClass('show');
              
      },
      done: function(){
        $('#divLoading').removeClass('show');
      }
    });
  });

  //
  
$(document).on('click', '#add_item_btn', function(event) {
  event.preventDefault();

  

  $("#itemsort1 input[type=checkbox]:checked").each(function () {

    var id =$(this).val();
    var barcode = $(this).closest('tr').find('.vitemcode_c').val();
    var itemname = $(this).closest('tr').find('.vitemname_c').val();
    var price = $(this).closest('tr').find('.dunitprice_c').val();
  
    itemsSelected.push({id: id});
    
    console.log(id);
    console.log(barcode);
    console.log(itemname);
    console.log(price);
    
   
    var right_items_html = '';
    
      right_items_html += '<tr>';

      right_items_html += '<td class="text-center" style="width:1%;">';
      right_items_html += '<input type="checkbox" class="checkbox_right" id="checkbox_itemsort2['+barcode+']" name="table2[]" value="'+id+'"/>';
      right_items_html += '<input type="hidden" class="checkbox_right" id="checkbox_itemsort2['+barcode+']" name="checkbox_itemsort2[]" value="'+id+'"/>';
      right_items_html += '<input type="hidden" id="checkbox_itemsort2[]" name="iitemgroupid">'
      right_items_html += '</td>';

      right_items_html += '<td style="width:242px;">'+itemname+'</td>';

      right_items_html += '<td>';
      right_items_html += '<input type="hidden" name="items[][vitemname]" value="'+itemname+'">';
      right_items_html += '<input type="hidden" class="vbarcode_right" name="vsku" value="'+barcode+'">';
      right_items_html += '</td>';

      right_items_html += '<td class="text-left" style="width:100px;">';
      right_items_html += '<input type="text" class="editable isequence_class " name="items['+barcode+'][isequence]"   style="width:50px;">';
      right_items_html += '</td>';
      
      right_items_html += '<td class="text-left">'+price+'</td>';
      right_items_html += '</tr>';
      window.index_item++;
    
    $('#itemsort2 tbody').append(right_items_html);

    $('#itemsort1 tbody').html('');
    
  });
});   

// Remove item

$(document).on('click', '#remove_item_btn', function(event) {
  event.preventDefault();

    $("#itemsort2 input[type=checkbox]:checked").each(function () {

      $(this).closest('tr').remove();
      // var id =$(this).val();
       var barcode = $(this).closest('tr').find('.vitemcode_c').val();
      // var itemname = $(this).closest('tr').find('.vitemname_c').val();
      // var price = $(this).closest('tr').find('.dunitprice_c').val();

      // itemsSelected.push({id: id});

      // console.log(id);
       console.log(barcode);
      // console.log(itemname);
      // console.log(price);
    });
  });

</script>

<script src="/javascript/bootbox.min.js" defer=""></script>


<script>

$(document).on('click','#save_button_group', function(e){
    e.preventDefault();
    
    var avArr = [];
    var grpName = $('input[name="vitemgroupname"]').val();
    $("#itemsort2 tr").each(function () {
        var id =$(this).val();
        var barcode = $(this).closest('tr').find('.vitemcode_c').val();
        // var itemname = $(this).closest('tr').find('.vitemname_c').val();
        // var price = $(this).closest('tr').find('.dunitprice_c').val();
        var sequence = $(this).closest('tr').find('.isequence_class').val();
        avArr.push({
            barcode: $(this).find('.vbarcode_right').val(),
            sequence:$(this).find('.isequence_class').val()      
        });
    });
    console.log(avArr);
    if(avArr.length < 1){
        bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: "You did not select anything", 
            callback: function(){location.reload(true);}
        });
        $("div#divLoading").removeClass('show');
        return false;
    }else{
        <?php if(session()->get('hq_sid') == 1){ ?>
            $('#myModal').modal('show');
        <?php } else { ?>
            $("div#divLoading").addClass('show');
            $.ajax({
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                url: '/savedata',
                contentType: 'application/json',
                data: JSON.stringify({groupname: grpName, barcodes: avArr}),
                success: function (e) { 
                  location.replace('/itemgroup');
                },
                
            }).fail(function (msg) {
                //console.log('FAIL');
                let mssg = '<div class="alert alert-danger">';
                //console.log(msg);
                let errors = msg.responseJSON;
                console.log(errors);
        
                $.each(errors, function(k, err){
                    console.log(err);
                    $.each(err, function(key, error){
                        console.log(error);
                        mssg += '<p><i class="fa fa-exclamation-circle"></i>'+error+"</p>";
                    });
                });
                mssg += '</div>';
                bootbox.alert({ 
                    size: 'small',
                    title: "  ", 
                    message: mssg, 
                    callback: function(){}
                });
                $("div#divLoading").removeClass('show');
                
            }).done(function (msg) {
                $("div#divLoading").removeClass('show');
            });
        <?php } ?>
    }
});

var stores = [];
stores.push("{{ session()->get('sid') }}");
$('#selectAllCheckbox').click(function(){
    if($('#selectAllCheckbox').is(":checked")){
        $(".stores").prop( "checked", true );
    }else{
        $( ".stores" ).prop("checked", false );
    }
});
       
        
$('#save_btn').click(function(){
    
    $.each($("input[name='stores']:checked"), function(){            
        stores.push($(this).val());
    });
    
    var avArr = [];
    var grpName = $('input[name="vitemgroupname"]').val();
    $("#itemsort2 tr").each(function () {
        var id =$(this).val();
        var barcode = $(this).closest('tr').find('.vitemcode_c').val();
        // var itemname = $(this).closest('tr').find('.vitemname_c').val();
        // var price = $(this).closest('tr').find('.dunitprice_c').val();
        var sequence = $(this).closest('tr').find('.isequence_class').val();
        avArr.push({
            barcode: $(this).find('.vbarcode_right').val(),
            sequence:$(this).find('.isequence_class').val()      
        });
    });
    
    $("div#divLoading").addClass('show');
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        url: '/savedata',
        contentType: 'application/json',
        data: JSON.stringify({groupname: grpName, barcodes: avArr, stores_hq:stores }) // access in body
    }).success(function (e) {
         location.replace('/itemgroup');
    }).fail(function (msg) {
        //console.log('FAIL');
        let mssg = '<div class="alert alert-danger">';
        //console.log(msg);
        let errors = msg.responseJSON;
        console.log(errors);

        $.each(errors, function(k, err){
            console.log(err);
            $.each(err, function(key, error){
                console.log(error);
                mssg += '<p><i class="fa fa-exclamation-circle"></i>'+error+"</p>";
            });
        });
        mssg += '</div>';
        bootbox.alert({ 
            size: 'small',
            title: "  ", 
            message: mssg, 
            callback: function(){}
        });
        $("div#divLoading").removeClass('show');

    }).done(function (msg) {
        // console.log('DONE');
        $("div#divLoading").removeClass('show');
    });
})

</script>


@endsection