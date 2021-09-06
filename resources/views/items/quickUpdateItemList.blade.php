@extends('layouts.layout')

@section('title')
  Quick Update of Item
@endsection

@section('main-content')
<form action="{{ route('quick_update_item') }}" method="post" id="form_item_search">
@csrf

    <input type="hidden" name="sku" value="">
    <input type="hidden" name="item" value="">
    <input type="hidden" name="unit" value="">
    <input type="hidden" name="department" value="">
    <input type="hidden" name="category" value="">
    <input type="hidden" name="unitcost" value="">
    <input type="hidden" name="unitprice" value="">
    <input type="hidden" name="tax1" value="">
    <input type="hidden" name="tax2" value="">
    <input type="hidden" name="qoh" value="">
                                
</form>
<form action="{{ route('quick_update_item_edit') }}" method="post" id="form_item_price_update">
    @csrf
    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue menu">
        <div class="container">
            <div class="row">
                <div class="collapse navbar-collapse" id="main_nav">
                    <div class="menu">
                        <span class="font-weight-bold text-uppercase"> Quick Update of Item</span>
                    </div>
                    <div class="nav-submenu">
                        <div style="display: inline-block;">
                            <button title="Update" type="button" class="btn btn-gray headerblack buttons_menu" id="update_button"><i
                                    class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                        </div>
                    </div>
                </div> <!-- navbar-collapse.// -->
            </div>
        </div>
    </nav>

    <div id="content" class="section-content menu">
        <div class="container-fluid" style="zoom:90%;">
            <div class="panel panel-default padding-left-right">
                
                <div class="panel-body row">
                    <span class="col-md-11" style="float:left;color:red">
                        <lable class="required control-label">NOTE: For filter, after giving inputs for filter click on the filter button</lable>
                    </span>
                    <input type="submit" class="btn btn-primary button-blue basic-button-small col-md-1" id="search_filter" form="form_item_search" value="FILTER" style="float:right;">
                </div>
                
                <div class="">
                    
                        <input type="hidden" name="search_item_type" value={{ $search_item_type }}>
                        @if(session()->get('hq_sid') == 1)
                            <input type="hidden" id="hidden_store_hq_val" name="stores_hq" value="">
                        @endif
                        <table id="vendor" class="table table-hover promotionview" style="width: 100%">
                            @if ($items)
                                <thead>
                                    <tr class="header-color">
                                        <th style="width: 1px;" class="text-center headername">
                                            <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"/></th>
                                        <th class="text-left headername text-uppercase">
                                            SKU
                                            <div class="form-group has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields" placeholder="SKU" id="sku" value="<?php echo $filter_data['sku'] ?>" style="width: 100px;">
                                            </div>
                                        </th>
                                        <th class="text-left headername text-uppercase">Item
                                            <div class="form-group has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields" placeholder="ITEM" id="item" value="<?php echo $filter_data['item'] ?>" style="width: 100px;">
                                            </div>
                                        </th>
                                        <th class="text-left headername text-uppercase">Unit
                                            <div class="form-group has-search">
                                                <select class='table-heading-fields' id="unit" style="width: 100px;">
                                                    <option value='all'>All</option>";
                                                        <?php 
                                                          foreach($units as $unit){
                                                        ?>  
                                                            <option value='<?=$unit['vunitcode']?>' <?php if($filter_data['unit'] == $unit['vunitcode']){ echo"selected"; } ?> ><?=$unit['vunitname'] ?></option>;
                                                        <?php } ?>
                                                </select>
                                                
                                            </div>
                                        </th>
                                        <th class="text-left headername text-uppercase">Department
                                            <div class="form-group has-search">
                                                <select class='table-heading-fields' id='department' style="width: 130px;">
                                                    <option value='all'>All</option>";
                                                        <?php 
                                                          foreach($departments as $department){
                                                        ?>  
                                                            <option value='<?=$department->vdepcode?>' <?php if($filter_data['department'] == $department->vdepcode){ echo"selected"; } ?> ><?=$department->vdepartmentname ?></option>;
                                                        <?php } ?>
                                                </select>
                                                
                                            </div>
                                        </th>
                                        <th class="text-left headername text-uppercase">Category
                                            <div class="form-group has-search">
                                                <select class='table-heading-fields' id="category" style="width: 115px;">
                                                    <option value='all'>All</option>
                                                    
                                                </select>
                                                
                                            </div>
                                        </th>
                                        <th class="text-left headername text-uppercase" id="th_cost_price">Unit Cost
                                            <div class="form-group has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields" placeholder="UNIT COST" id="unitcost" value="<?php echo $filter_data['unitcost'] ?>" style="width: 115px;">
                                            </div>
                                        </th>
                                      
                                        <th class="text-left headername text-uppercase">Unit Price
                                            <div class="form-group has-search">
                                                <span class="fa fa-search form-control-feedback"></span>
                                                <input type="text" class="form-control table-heading-fields" placeholder="UNIT PRICE" id="unitprice" value="<?php echo $filter_data['unitprice'] ?>" style="width: 115px;">
                                            </div>
                                        </th>
                                        <th class="text-left headername text-uppercase">Tax 1
                                            <div class="form-group has-search">
                                                
                                                <select class="table-heading-fields" id="tax1" style="width: 100px;">
                                                    <option value="all">Select</option>
                                                    <option value="Y" <?php if($filter_data['tax1'] == 'Y'){ echo"selected"; } ?>>YES</option>
                                                    <option value="N" <?php if($filter_data['tax1'] == 'N'){ echo"selected"; } ?>>NO</option>
                                                </select>
                                                
                                            </div>
                                        </th>
                                        <th class="text-left headername text-uppercase">Tax 2
                                            <div class="form-group has-search">
                                                <select class="table-heading-fields" id="tax2" style="width: 100px;">
                                                    <option value="all">Select</option>
                                                    <option value="Y" <?php if($filter_data['tax2'] == 'Y'){ echo"selected"; } ?>>YES</option>
                                                    <option value="N" <?php if($filter_data['tax2'] == 'N'){ echo"selected"; } ?>>NO</option>
                                                </select>
                                                
                                            </div>
                                        </th>
                                        <!--<th class="text-left headername text-uppercase">QOH-->
                                        <!--    <div class="form-group has-search">-->
                                        <!--        <span class="fa fa-search form-control-feedback"></span>-->
                                        <!--        <input type="text" class="form-control table-heading-fields" placeholder="QOH" value="<?php echo $filter_data['qoh'] ?>" id="qoh" style="width: 100px;">-->
                                        <!--    </div>-->
                                        <!--</th>-->
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($items as $i => $item)
                                        <tr>
                                            <td data-order={{ $item['iitemid'] }} class="text-center">
                                                <input type="checkbox" class="checkbox" name="selected[{{$i}}][iitemid]"
                                                    id="items[{{$i}}][select]" value={{ $item['iitemid'] }} />
                                                <input type="hidden" name="items[{{$i}}][iitemid]"
                                                    value={{ $item['iitemid'] }}>
                                            </td>

                                            <td class="text-left">
                                                <span>{{ $item['vbarcode'] }}</span>
                                            </td>

                                            <td class="text-left">
                                                <span>{{ $item['VITEMNAME'] }}</span>
                                            </td>

                                            <td class="text-left">
                                                <span>{{ $item['vunitname'] }}</span>
                                            </td>

                                            <td class="text-left">
                                                <select name="items[{{$i}}][vdepartmentname]" class="editable form-control" id="dept_code_{{$i}}"
                                                    onclick='document.getElementById("items[{{$i}}][select]").checked = true;'
                                                    @if($new_database !== false)
                                                    onchange='getCategories(this.options[this.selectedIndex].value,{{$i}})'
                                                    @endif style="width:100%;text-align:right;">
                                                    <option value="">--Select Department--</option>
                                                    @if(isset($departments) && count($departments) > 0)
                                                        @foreach($departments as $department)
                                                            @if(isset($item['vdepcode']) && $item['vdepcode'] == $department->vdepcode)
                                                                <option value={{ $department->vdepcode}} selected="selected">
                                                                    {{ $department->vdepartmentname }}</option>
                                                            @else
                                                                <option value={{ $department->vdepcode}}>
                                                                    {{ $department->vdepartmentname }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>

                                            <td class="text-left">
                                                <select id="categroy_{{ $i }}" name="items[{{$i}}][vcategoryname]"
                                                    class="editable form-control"
                                                    onclick='document.getElementById("items[{{$i}}][select]").checked = true;'
                                                    style="width:100%;text-align:right;">
                                                    <option value="">--Select Category--</option>
                                                    @if(count($categories) > 0)
                                                        @foreach($categories as $category)
                                                            @if(isset($item['vcategorycode']) && $item['vcategorycode'] == $category->vcategorycode)
                                                                <option value={{ $category->vcategorycode}} selected="selected">
                                                                    {{ $category->vcategoryname }}</option>
                                                            @else
                                                                <option value={{ $category->vcategorycode}}>
                                                                    {{ $category->vcategoryname }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>


                                            <td class="text-right td_cost_price" style="width:10%;">
                                                <input type="text" class="editable class_unitcost"
                                                    name="items[{{$i}}][nunitcost]" value={{  number_format($item['nunitcost'], 2) }}
                                                    onclick='document.getElementById("items[{{$i}}][select]").checked = true;'
                                                    style="width:100%;text-align:right;" />
                                            </td>

                                            <td class="text-right" style="width:10%;">
                                                <input type="text" class="editable class_unitprice"
                                                    name="items[{{$i}}][dunitprice]"
                                                    value={{ $item['dunitprice'] }}
                                                    onclick='document.getElementById("items[{{$i}}][select]").checked = true;'
                                                    style="width:100%;text-align:right;" />
                                            </td>

                                            <td class="text-left">
                                                <select name="items[{{$i}}][vtax1]" class="form-control"
                                                    onchange='document.getElementById("items[{{$i}}][select]").checked = true;'>
                                                    @if (isset($array_yes_no) && count($array_yes_no) > 0)
                                                        @foreach($array_yes_no as $k => $array_y_n)
                                                            @if ($k == $item['vtax1'])
                                                                <option value={{ $k}} selected="selected">{{ $array_y_n }}
                                                                </option>
                                                            @else
                                                                <option value={{ $k}}>{{ $array_y_n }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>

                                            <td class="text-left">
                                                <select name="items[{{$i}}][vtax2]" class="form-control"
                                                    onchange='document.getElementById("items[{{$i}}][select]").checked = true;'>
                                                    @if (isset($array_yes_no) && count($array_yes_no) > 0)
                                                        @foreach($array_yes_no as $k => $array_y_n)
                                                            @if ($k == $item['vtax2'])
                                                                <option value={{ $k}} selected="selected">{{ $array_y_n }}
                                                                </option>
                                                            @else
                                                                <option value={{ $k}}>{{ $array_y_n }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>

                                            <!--<td class="text-right" style="width:10%;">-->
                                            <!--    <input type="text" class="editable class_qoh"-->
                                            <!--        name="items[{{$i}}][iqtyonhand]"-->

                                            <!--        value="<?php echo $item['iqtyonhand'] ;?>"-->
                                            <!--        onclick='document.getElementById("items[{{$i}}][select]").checked = true;'-->
                                            <!--        style="width:100%;text-align:right;" readonly />-->
                                            <!--</td>-->
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">{{ $text_no_results }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                </div>
                {{ $items->appends(request()->except('page'))->links() }}
            </div>
            <br>
        </div>
    </div>

</form>

<?php if(session()->get('hq_sid') == 1){ ?>
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title">Select the stores in which you want to delete the vendor:
            <span style="color:blue">Note: The item which are present in child store only will get updated</span></h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
             <table class="table" style="width: 100%; border-collapse: separate; border-spacing:0 5px !important;">
                  <thead id="table_green_header_tag"  style="background-color: #286fb7!important;">
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox" id="table_green_check">
                                <input type="checkbox" class="" id="selectAllCheckbox" name="" value="" style="background: none !important;">
                            </div>
                        </th>
                        <th colspan="2" id="table_green_header">Select All</th>
                    </tr>
                </thead >
                <tbody>
                    @foreach (session()->get('stores_hq') as $stores)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox" id="table_green_check">
                                    <input type="checkbox" class="checks check stores" id="stores" name="stores" value="{{ $stores->id }}">
                                </div>
                            </td>
                            <td class="checks_content"><span>{{ $stores->name }} [{{ $stores->id }}]</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" id="save_btn" class="btn btn-primary" data-dismiss="modal">Update</button>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          </div>
        </div>
      </div>
    </div>
<?php } ?>


@endsection


@section('page-script')
<link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">


<style>
    #vendor_paginate{
        display: none;
    }
    .disabled, #item_ellipsis{
        pointer-events:none;
    }
    .table-heading-fields, .table-heading-fields::-webkit-input-placeholder {
    padding-left: 16px;
    }
    .panel-default nav{
        margin-bottom: 50px;
    }
    
    .padding-left-right {
        padding: 0 2% 0 2%;
    }
</style>
<script src="/javascript/bootbox.min.js" defer=""></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>-->
@foreach ($items as $i => $item)
    <script>
            $(document ).ready(function() {
                var category_url = "{{route('quick_update_get_categories_selected')}}";
                category_url = category_url.replace(/&amp;/g, '&');
                var dep = $('#dept_code_{{$i}}').val();
                var cat_code = "<?php echo $item['vcategorycode'] ?>";
                $.ajax({
                url : category_url,
                    headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token();  ?>'
                    },
                    data : {depcode:dep, catcode:cat_code},
                    type : 'POST',
                    success: function(data) {
                        $('#categroy_'+ <?php echo $i; ?>).empty().html(data)
                    }
                });
            });
        </script>
@endforeach

<script>
    $(document).ready(function(){
        var item_type = $("#item_type").val();
        $("#item_type_hidden").val(item_type);
    });
</script>

<script>
    
	$(document).on('change', 'input[name="show_cost_price"]', function (event) {
	    event.preventDefault();
	    if ($(this).is(":checked")) {
	        $('#th_cost_price, .td_cost_price').show();
	    } else {
	        $('#th_cost_price, .td_cost_price').hide();
	    }
	});

    
    $(document).on('click', '#update_button', function (event) {
        
        var count = 0;
        $('.checkbox:checked').each(function(i) {
            count = count + 1;
            
        });
        
        if (count <= 0) {
            bootbox.confirm({
                size: 'small',
                title: " ",
                message: "Not any Item is Selected",
                callback: function(result) {}
            });
            
            return false;
        }
        

        <?php if(session()->get('hq_sid') == 1) { ?>
            $("div#divLoading").removeClass('show');
            $("#myModal").modal('show');
        <?php } else { ?>
            $('form#form_item_price_update').submit();
        <?php } ?>
        

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

    $("#save_btn").click(function(){
        $.each($("input[name='stores']:checked"), function(){
            stores.push($(this).val());
        });
        $("#hidden_store_hq_val").val(stores);
        $('form#form_item_price_update').submit();
    });

    $(function() { $('input[name="automplete-product"]').focus(); });

    function getCategories(depcode,i)
    {
        var category_url = '<?php echo $get_categories_url; ?>';
        category_url = category_url.replace(/&amp;/g, '&');
        $.ajax({
            url : category_url,
            data : {depcode:depcode,_token: "{{ csrf_token() }}",},
            type : 'POST',
            success: function(data) {
                $('#categroy_'+i).empty().html(data)
            }
        });

    }

   $(document).on('change','.class_unitprice',function(){
        var $this = $(this);
        $this.val(parseFloat($this.val()).toFixed(4));
    })

    $(document).on('change','.class_unitcost',function(){
        var $this = $(this);
        $this.val(parseFloat($this.val()).toFixed(4));
    });
    
    $(document).on('submit', 'form#form_item_search', function(){
        event.preventDefault();
        
        let sku= $('#sku').val();
        let item= $('#item').val();
        let unit= $('#unit').val();
        let department= $('#department').val();
        let category= $('#category').val();
        let unitcost= $('#unitcost').val();
        let unitprice= $('#unitprice').val();
        let tax1= $('#tax1').val();
        let tax2= $('#tax2').val();
        let qoh= $('#qoh').val();
        
        $('input[name="sku"]').val(sku);
        $('input[name="item"]').val(item);
        $('input[name="unit"]').val(unit);
        $('input[name="department"]').val(department);
        $('input[name="category"]').val(category);
        $('input[name="unitcost"]').val(unitcost);
        $('input[name="unitprice"]').val(unitprice);
        $('input[name="tax1"]').val(tax1);
        $('input[name="tax2"]').val(tax2);
        $('input[name="qoh"]').val(qoh);
        
        $('#form_item_search')[0].submit();
        
    });
    
    $(document).on('change', '#department', function(){
        
        let depcode = $(this).val();
        var category_url = '<?php echo $get_categories_url; ?>';
        category_url = category_url.replace(/&amp;/g, '&');
        $.ajax({
            url : category_url,
            data : {depcode:depcode,_token: "{{ csrf_token() }}",},
            type : 'POST',
            success: function(data) {
                $('#category').empty().html(data)
            }
        }); 
    });
    
    $(document).on('input', '.table-heading-fields', function(){
        var self = this;
                
        if(self.value != ''){
            $(this).closest('div').find('.fa-search').hide();
            
        }else{
            $(this).closest('div').find('.fa-search').show();
        } 
    });

</script>
<style>
    .disable {
        pointer-events:none;
    }

</style>
@endsection
