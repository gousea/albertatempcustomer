@extends('layouts.master')

@section('title')
  Item Group
@endsection

@section('main-content')
<div id="content">
    
  <div class="page-header">
    <div class="container-fluid">
      <!-- <h1>Item Group</h1> -->
      
    </div>
  </div>

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
      <div class="panel-heading head_title">
        <h3 class="panel-title">Item Group</h3>
      </div>
      <div class="panel-body">
        <div class="row" style="padding-bottom: 9px;float: right;">
          <div class="col-md-12">
            <div class="">
              <button id="save_button_group" title="Save" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</button>
              <a href="/itemgroup" data-toggle="tooltip" title="Cancel" class="btn btn-default cancel_btn_rotate"><i class="fa fa-reply"></i>&nbsp;&nbsp;Cancel</a>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>

        <ul class="nav nav-tabs responsive" id="myTab">
          <!-- <li class="active"><a href="#item_tab" data-toggle="tab" style="background-color: #f05a28;color: #fff !important;pointer-events:none;">General</a></li> -->
          <!--<li><a class="add_new_btn_rotate" href="https://customer.albertapayments.com/index.php?route=administration/group_slab_pricing&amp;token=lITyzK27IaERMkBz8AUBfX9hrH2neA01&amp;iitemgroupid=111" style="color: #fff !important;background-color: #03A9F4;">Group Slab Pricing</a></li>-->
        </ul>

        <form action="updategroupdetail" method="post"  id="form-group" class="form-horizontal">
          @csrf
          <input type="hidden" name="iitemgroupid" value="{{$groupname->iitemgroupid}}">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-group">Group Name</label>
                  <div class="col-sm-8">
                    <input type="text" maxlength="100" name="vitemgroupname" value="{{$groupname->vitemgroupname}}" placeholder="Group Name" class="form-control" required="">
                    <input type="hidden" maxlength="100" name="iitemgroupid" value="{{$groupname->iitemgroupid}}" >
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-template">Item Type</label>
                  <div class="col-sm-8">
                    <select name="vtype" id="vtype" class="form-control">
                      <option value="">Please Select</option>
                      <option value="Product" selected="selected">Product</option>
                    </select>
                  </div>
                </div>
            </div>
          </div>

          <br><br><br>
          <div class="row">

          <form action="" method="post"  id="groupsearch" >
            @csrf
            <div class="col-md-5">
              <div class="table-responsive">
                <table class="table table-bordered table-hover" style="padding:0px; margin:0px;" name="table1" id='leftItemSelectTable'>
                  <thead>
                    <tr>
                      <td style="width: 1px;" class="text-center">
                        <input type="checkbox"  id="leftSelectall" checked>
                      </td>
                      <td style="width:130px;">
                        <input type="text" class="form-control search_c" id="itemsort1_search_sku" placeholder="SKU" style="border:none;" autocomplete="off">
                      </td>
                      <td style="width:242px;">
                        <input type="text" class="form-control search_c" id="itemsort1_search_item_name" placeholder="Name" style="border:none;">
                      </td>
                      <td class="text-right" style="width:100px;">Price</td>
                    </tr>
                  </thead>
                </table>
                <div class="div-table-content">
                  <table class="table table-bordered table-hover" id="itemsort1" style="table-layout: fixed;">
                    <tbody id="searchData">
                        
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            </form>

            <div class="col-md-2 text-center" style="margin-top:12%;">

            <form action="insertgroupname" method="post"  id="groupdetail" class="form-horizontal">
              @csrf

              <a class="btn btn-primary" style="cursor:pointer" id="add_item_btn"><i class="fa fa-arrow-right fa-3x"></i></a><br><br>
              <a class="btn btn-primary" style="cursor:pointer" id="remove_item_btn"><i class="fa fa-arrow-left fa-3x"></i></a>
              </div>
            <div class="col-md-5">
              <div class="table-responsive">
                <table class="table table-bordered table-hover" style="padding:0px; margin:0px;" name="table2">
                  <thead>
                    <tr>
                      <td style="width:1%" class="text-center">
                       <input type="checkbox" onclick="$('input[name*=\'table2\']').prop('checked', this.checked)">
                      </td>
                      <td style="width:242px;">
                        <input type="text" class="form-control itemsort2_search" placeholder="Name" style="border:none;">
                      </td>
                      <td class="text-right" style="width:100px;">Sequence</td>
                      <td class="text-right">Price</td>
                    </tr>
                  </thead>
                </table>
                <div class="div-table-content" style="">
                  <table class="table table-bordered table-hover" id="itemsort2">
                    <tbody>
                    @foreach($groupdetail as $gpd)
                      <tr>
                        <td class="text-center" style="width:1%;">
                          <input type="checkbox" name="checkbox_itemsort2[] " value="{{$gpd->vbarcode}}" onclick="$('input[name*=\'table2\']').prop('checked', this.checked)" >
                        
                        </td>

                        <td style="width:242px;">{{$gpd->vitemname}}
                          <input type="hidden" name="items[{{$gpd->vitemname}}][vitemname]" value="{{$gpd->vitemname}}">
                          <input type="hidden" class="vbarcode_right" name="items[{{$gpd->vbarcode}}][vsku]" value="{{$gpd->vbarcode}}">
                        </td>

                        <td class="text-right" style="width:100px;"> 
                          <input type="text" class="editable isequence_class" name="items[{{$gpd->vbarcode}}][isequence]" value="{{$gpd->isequence}}" id="items[{{$gpd->vbarcode}}][isequence]" style="width:50px;text-align:right;">
                        </td> 

                        <td class="text-right">{{$gpd->dunitprice}}</td>
                        
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  
</div>
@endsection

@section('scripts')

<script>

  itemsSelected = [];

  $(document).on('click', '#leftSelectall', function(e){
    e.preventDefault();


    // $('#searchData .leftCheckbox').prop('checked', this.checked);
    
  });

  //Item Filter
  $(document).on('submit', '#groupsearch', function (e) {
    e.preventDefault();
  });

  
  $(document).on('keyup', '.search_c', function (e) {
    e.preventDefault();

    var avArr = [];

    //console.log(avArr);

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
    //console.log(sku);        
    //console.log(itemName);        
    //console.log(data);
    if(sku.length == 0 && itemName == 0){
      return false;
    }

    // if (currentRequest !== undefined) {
    //   currentRequest.abort();
    // }


    if (typeof currentRequest === 'undefined') {
      currentRequest = null;
    }


    currentRequest = jQuery.ajax({
      type: 'POST',
      dataType: 'json',
      url: '/itemgroupsearch',
      headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},

     // data: JSON.stringify(data),

      data: JSON.stringify({sku:sku, name:itemName ,barcodes: avArr}),
      
      contentType: 'application/json; charset=utf-8',

      // data: data,
      beforeSend : function()    {

        //console.log(currentRequest);           
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
          html +='<input type="checkbox" class="leftCheckbox" name="checkbox_itemsort1['+v.iitemid+'] table1[] "  value="'+v.iitemid+'">';
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

  $(document).on('click', '#add_item_btn', function(event) {
  event.preventDefault();

    $("#itemsort1 input[type=checkbox]:checked").each(function () {

      var id =$(this).val();
      var barcode = $(this).closest('tr').find('.vitemcode_c').val();
      var itemname = $(this).closest('tr').find('.vitemname_c').val();
      var price = $(this).closest('tr').find('.dunitprice_c').val();

      itemsSelected.push({id: id});

    //   console.log(id);
    //   console.log(barcode);
    //   console.log(itemname);
    //   console.log(price);


      var right_items_html = '';

        right_items_html += '<tr>';

        right_items_html += '<td class="text-center" style="width:1%;">';
        right_items_html += '<input type="checkbox" class="checkbox_right" id="checkbox_itemsort2['+barcode+']  table2[]" name="checkbox_itemsort2[]" value="'+id+'"/>';
        right_items_html += '<input type="hidden" id="checkbox_itemsort2[]" name="iitemgroupid">'
        right_items_html += '</td>';

        right_items_html += '<td>'+itemname;
        right_items_html += '<input type="hidden" name="items[][vitemname]" value="'+itemname+'">';
        right_items_html += '<input type="hidden" class="vbarcode_right" name="vsku" value="'+barcode+'">';
        right_items_html += '</td>';

        right_items_html += '<td class="text-right" style="width:100px;">';
        right_items_html += '<input type="text" class="editable isequence_class" name="items['+barcode+'][isequence]" value="0" id="items['+barcode+'][isequence]" style="width:50px;text-align:right;">';
                        
        right_items_html += '</td>';
      
        right_items_html += '<td class="text-right">'+price+'</td>';
        right_items_html += '</tr>';
        window.index_item++;

      $('#itemsort2 tbody').append(right_items_html);

      $('#itemsort1 tbody').html('');

    });
    
  });

  // remove
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

    $("div#divLoading").addClass('show');

    var avArr = [];

    var grpid = $('input[name = "iitemgroupid"]').val();
    var grpName = $('input[name="vitemgroupname"]').val();

    //console.log(avArr);

    $("#itemsort2 tr").each(function () {
      var barcode = $(this).closest('tr').find('.vitemcode_c').val();
      var sequence = $(this).closest('tr').find('.isequence_class').val();
      
      avArr.push({
        barcode: $(this).find('.vbarcode_right').val(),
        sequence: $(this).find('.isequence_class').val()      
      });
    });

//    console.log(avArr);
    if(avArr.length < 1){

      bootbox.alert({ 
        size: 'small',
        title: "Attention", 
        message: "You did not select anything", 
        callback: function(){location.reload(true);}
      });

      $("div#divLoading").removeClass('show');
      return false;

    }

    $.ajax({
    type: 'POST',
    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
    url: '/updatedata',
    contentType: 'application/json',
    data: JSON.stringify({grpid: grpid, grpname:grpName, barcodes: avArr}) // access in body
    }).success(function (e) {
    //console.log('SUCCESS');
    // console.log(e);

    location.replace('/itemgroup');

    //  location.reload();
    }).fail(function (msg) {
      
      //console.log('FAIL');
      let mssg = '<div class="alert alert-danger">';
      
      //console.log(msg);
      let errors = msg.responseJSON;
      //console.log(errors);

      $.each(errors, function(k, err){
      //   console.log(err);
      $.each(err, function(key, error){
          // console.log(error);
        mssg += '<p><i class="fa fa-exclamation-circle"></i>'+error+"</p>";
      });

    });

    mssg += '</div>';
      
      bootbox.alert({ 
          size: 'small',
          title: "Attention", 
          message: mssg, 
          callback: function(){
            // location.reload(true);
          }
        });
        
      $("div#divLoading").removeClass('show');
  
    }).done(function (msg) {
      // console.log('DONE');
      $("div#divLoading").removeClass('show');
  
    });


  });


</script>

@endsection