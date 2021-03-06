@extends('layouts.master')

@section('title')
  Quick Update of Item
@endsection

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
            <h3 class="panel-title"><i class="fa fa-list"></i> Quick Update of Item</h3>
		</div>
		
        <div class="panel-body">
            <div class="row" style="padding-bottom: 9px;float: right;">
                <div class="col-md-12">
                    <div style="display: inline-block;">
                        <input type="checkbox" name="show_cost_price" value="show_cost_price"
                            style="border: 1px solid #A9A9A9;">&nbsp;&nbsp;<span>Show Unit Cost</span>
                    </div>

                    <div style="display: inline-block;">
                        <select class="form-control" name="item_type" id="item_type">
                             <option value="All" selected="selected"> All </option>
                            @if(isset($item_types))
                                @foreach($item_types as $item_type)
                                    @if(isset($search_item_type) && $search_item_type == $item_type)
                                        <option value="{{ $item_type }}" selected="selected"> {{ $item_type }} </option>
                                    @else
                                        <option value="{{ $item_type }}">{{ $item_type }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div style="display: inline-block;">
                        <button title="Update" class="btn btn-primary" id="update_button"><i
                                class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="{{ route('quick_update_item') }}" method="post" id="form_item_search">
                    @csrf
                    <div class="row">
                        <div class="col-md-2" style="width:12%;">
                            <input type="radio" id="radio_category" name="search_radio" value="category" checked>&nbsp;&nbsp;<span
                                style="margin-top:3px;position:absolute;">Category</span>
                        </div>
                        <div class="col-md-2" style="width:12%;">
                            <input type="radio" id="radio_department" name="search_radio" value="department">&nbsp;&nbsp;<span
                                style="margin-top:3px;position:absolute;">Department</span>
                        </div>
                        <div class="col-md-2" style="width:12%;">
                            <input type="radio" id="radio_item_group" name="search_radio" value="item_group">&nbsp;&nbsp;<span
                                style="margin-top:3px;position:absolute;">Item Group</span>
                        </div>
                        <div class="col-md-2" style="width:12%;">
                            <input type="radio" id="radio_search" name="search_radio" value="search">&nbsp;&nbsp;<span
                                style="margin-top:3px;position:absolute;">Search</span>
                        </div>
                    </div><br><br>
                    <div class="row">
                        <div class="col-md-4" id="div_search_vcategorycode">
                            <span style="display:inline-block;width:20%;"><b>Category</b></span>&nbsp;&nbsp;
                            <select
                                name="search_vcategorycode" id="search_vcategorycode" class="form-control"
                                style="display:inline-block;width:70%;">
                                 <option value="" >Select Category</option>
                                @if(isset($categories) && count($categories) > 0)
                                    @foreach($categories as $category)
                                    <option value={{ $category->vcategorycode }}
                                        {{  (isset($search_vcategorycode) && $search_vcategorycode == $category->vcategorycode ) ? 'selected'  : '' }} >
                                        {{ $category->vcategoryname }} </option>
                                    @endforeach>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4" id="div_search_vdepcode">
                            <span style="display:inline-block;width:25%;"><b>Department</b></span>&nbsp;&nbsp;
                            <select
                                name="search_vdepcode" id="search_vdepcode" class="form-control"
                                style="display:inline-block;width:60%;">
                                <option value="" >Select Department</option>
                                @if(isset($departments) && count($departments) > 0)
                                    @foreach($departments as $department)
                                        <option value={{ $department->vdepcode }}
                                            {{ (isset($search_vdepcode) && $search_vdepcode == $department->vdepcode )  ? 'selected'  : '' }}>
                                            {{ $department->vdepartmentname }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4" id="div_search_vitem_group">
                            <span style="display:inline-block;width:25%;"><b>Item Group</b></span>&nbsp;&nbsp;
                            <select
                                name="search_vitem_group_id" id="search_vitem_group_id" class="form-control"
                                style="display:inline-block;width:60%;">
                                <option value="" >Select Item Group</option>
                                @if( isset($itemGroups) && count($itemGroups) > 0 )
                                    @foreach( $itemGroups as $itemGroup ){
                                    <option value= {{ $itemGroup->iitemgroupid }}
                                        {{ (isset($search_vitem_group_id) && ($search_vitem_group_id == $itemGroup->iitemgroupid) ) ? 'selected'  : ''  }}>
                                        {{ $itemGroup->vitemgroupname }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4" id="div_search_box">
                            <span style="display:inline-block;width:25%;"><b>Search Item</b></span>&nbsp;&nbsp;
                            <input
                                name="search_item" id="search_item"
                                class="form-control"
                                style="display:inline-block;width:60%;" value="{{ isset($search_item) ? $search_item : '' }}">
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" name="search_item_type" id="item_type_hidden" value="">
                            <input type="submit"  name="search_filter" value="Filter" class="btn btn-info">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <br>

        <div class="table-responsive">
            <form action="{{ route('quick_update_item_edit') }}" method="post" id="form_item_price_update">
                @csrf
                <input type="hidden" name="search_item_type" value={{ $search_item_type }}>
                <table id="item" class="table table-bordered table-hover">
                    @if ($items)
						<thead>
							<tr>
								<th style="width: 1px;" class="text-center"><input type="checkbox"
										onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
								<th class="text-left text-uppercase">SKU</th>
								<th class="text-left text-uppercase">Item</th>
								<th class="text-left text-uppercase">Unit</th>
								<th class="text-left text-uppercase">Department</th>
								<th class="text-left text-uppercase">Category</th>
								<th class="text-right text-uppercase" id="th_cost_price">Unit Cost</th>
								<th class="text-right text-uppercase" style="width:10%;">Unit Price</th>
								<th class="text-left text-uppercase">Tax 1</th>
								<th class="text-left text-uppercase">Tax 2</th>
								<th class="text-left text-uppercase">QOH</th>
							</tr>
						</thead>
						
						<tbody>
							@foreach ($items as $i => $item)
								<tr>
									<td data-order={{ $item['iitemid'] }} class="text-center">
										<input type="checkbox" name="selected[{{$i}}][iitemid]"
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
											onclick='document.getElementById("items[{{$i}}][select]").setAttribute("checked","checked");'
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
											onclick='document.getElementById("items[{{$i}}][select]").setAttribute("checked","checked");'
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
											name="items[{{$i}}][nunitcost]" value={{ $item['nunitcost'] }}
											onclick='document.getElementById("items[{{$i}}][select]").setAttribute("checked","checked");'
											style="width:100%;text-align:right;" />
									</td>

									<td class="text-right" style="width:10%;">
										<input type="text" class="editable class_unitprice"
											name="items[{{$i}}][dunitprice]"
											value={{ $item['dunitprice'] }}
											onclick='document.getElementById("items[{{$i}}][select]").setAttribute("checked","checked");'
											style="width:100%;text-align:right;" />
									</td>

									<td class="text-left">
										<select name="items[{{$i}}][vtax1]" class="form-control"
											onchange='document.getElementById("items[{{$i}}][select]").setAttribute("checked","checked");'>
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
											onchange='document.getElementById("items[{{$i}}][select]").setAttribute("checked","checked");'>
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
									
									<td class="text-right" style="width:10%;">
										<input type="text" class="editable class_qoh"
											name="items[{{$i}}][iqtyonhand]"
										
											value="<?php echo $item['iqtyonhand'] ;?>" 
											onclick='document.getElementById("items[{{$i}}][select]").setAttribute("checked","checked");'
											style="width:100%;text-align:right;" readonly />
									</td>
								</tr>
							@endforeach
                        @else
							<tr>
								<td colspan="7" class="text-center">{{ $text_no_results }}</td>
							</tr>
                        @endif
                    </tbody>
                </table>
            </form>
		</div>

        {{$items->links()}}
    </div>
    </div>
</div>
@endsection


@section('scripts')

<style>
    .disabled, #item_ellipsis{
        pointer-events:none;
    }
</style>
<script src="/javascript/bootbox.min.js" defer=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
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
    $(document).ready(function(){
        $('#div_search_vcategorycode').hide();
        $('#div_search_vdepcode').hide();
        $('#div_search_box').hide();
        $('#div_search_vitem_group').hide();
		$('#th_cost_price, .td_cost_price').hide();
		
        <?php if(isset($search_radio) && $search_radio == 'category'){ ?>
            $('#div_search_vcategorycode').show();
            $("input[name=search_radio][value='category']").prop('checked', true);
            <?php if(isset($search_find) && !empty($search_find)){ ?>
                var search_find = '{{ $search_find }}';
                $('#search_vcategorycode').val(search_find);
            <?php } ?>
        <?php }else if(isset($search_radio) && $search_radio == 'department'){ ?>
            $('#div_search_vdepcode').show();
            $("input[name=search_radio][value='department']").prop('checked', true);
            <?php if(isset($search_find) && !empty($search_find)){ ?>
                var search_find = '{{ $search_find }}';
                $('#search_vdepcode').val(search_find);
            <?php } ?>
        <?php }else if(isset($search_radio) && $search_radio == 'item_group'){ ?>
            $('#div_search_vitem_group').show();
            $("input[name=search_radio][value='item_group']").prop('checked', true);
            <?php if(isset($search_find) && !empty($search_find)){ ?>
                var search_find = '{{ $search_find }}';
                $('#search_vitem_group_id').val(search_find);
            <?php } ?>
        <?php }else if(isset($search_radio) && $search_radio == 'search'){ ?>
            $('#div_search_box').show();
            $("input[name=search_radio][value='search']").prop('checked', true);
            <?php if(isset($search_find) && !empty($search_find)){ ?>
                var search_find = '{{ $search_find }}';
                $('#search_item').val(search_find);
            <?php } ?>
        <?php } ?>

    });
	
	$(document).on('change', 'input[name="show_cost_price"]', function (event) {
	    event.preventDefault();
	    if ($(this).is(":checked")) {
	        $('#th_cost_price, .td_cost_price').show();
	    } else {
	        $('#th_cost_price, .td_cost_price').hide();
	    }
	});

    $(document).on('change', 'input:radio[name="search_radio"]', function(event) {
        event.preventDefault();
        if ($(this).is(':checked') && $(this).val() == 'category') {
            $('#div_search_vcategorycode').show();
            $('#div_search_vdepcode').hide();
            $('#div_search_box').hide();
            $('#div_search_vitem_group').hide();
        }else if ($(this).is(':checked') && $(this).val() == 'department') {
            $('#div_search_vdepcode').show();
            $('#div_search_vcategorycode').hide();
            $('#div_search_box').hide();
            $('#div_search_vitem_group').hide();
        }else if ($(this).is(':checked') && $(this).val() == 'item_group') {
            $('#div_search_vitem_group').show();
            $('#div_search_vdepcode').hide();
            $('#div_search_vcategorycode').hide();
            $('#div_search_box').hide();
        }else if ($(this).is(':checked') && $(this).val() == 'search') {
            $('#div_search_box').show();
            $('#div_search_vdepcode').hide();
            $('#div_search_vcategorycode').hide();
            $('#div_search_vitem_group').hide();
        }else {
            $('#div_search_vcategorycode').show();
            $('#div_search_vdepcode').hide();
            $('#div_search_box').hide();
            $('#div_search_vitem_group').hide();
        }
	}); 


	$(document).on('change', 'select[name="item_type"]', function (event) {
        var url = window.location.hash.split('?');
        var search_vcategorycode = $('#search_vcategorycode').val();
        var search_vdepcode = $('#search_vdepcode').val();
        var search_vitem_group_id = $('#search_vitem_group_id').val();
        var search_item = $('#search_item').val();
        
        if (search_vcategorycode != "" ) {
             var search_radio = "category";
        }else if (search_vdepcode != "" ) {
             var search_radio = "department";
        }else if ( search_vitem_group_id != "") {
            var search_radio = "item_group";
        }else if (search_item != "") {
             var search_radio = "search";
        }else {
            var search_radio = ""; 
        }
    
        // var search_radio = $('input[name="search_radio"]').val();
        if(url.length  > 1){
            url = url + '&search_item_type=' + $(this).val();
            url = url + '&search_radio=' + search_radio;
            url = url + '&search_vcategorycode=' + search_vcategorycode;
            url = url + '&search_vdepcode=' + search_vdepcode;
            url = url + '&search_vitem_group_id=' + search_vitem_group_id;
            url = url + '&search_item=' + search_item;
            
        }
        else{
            url = url + '?search_item_type=' + $(this).val();
            url = url + '&search_radio=' + search_radio;
            url = url + '&search_vcategorycode=' + search_vcategorycode;
            url = url + '&search_vdepcode=' + search_vdepcode;
            url = url + '&search_vitem_group_id=' + search_vitem_group_id;
            url = url + '&search_item=' + search_item;
        }
	    var current_url = '<?php echo $current_url;?>';
	    current_url = current_url + url;
	    window.location.href = url;
	});

    $(document).on('click', '.page-link', function (event) {
        // var selected_option = '<?php echo $search_radio;?>';
        var selected_option = $('input:radio[name="search_radio"]:checked').val();
        var selected_option_value = '';
        if (selected_option == 'category') {
            selected_option_value = $('#search_vcategorycode').val();
        } else if (selected_option == 'department') {
            selected_option_value = $('#search_vdepcode').val();
        } else if (selected_option == 'item_group') {
            selected_option_value = $('#search_vitem_group_id').val();
        } else if (selected_option == 'search') {
            selected_option_value = $('#search_item').val();
        }
       
        var quickupdate_search_item_type = '<?php echo $quickupdate_search_item_type ?>'
        var set_selected_option_session = '<?php echo $set_selected_option_session; ?>';
        set_selected_option_session = set_selected_option_session.replace(/&amp;/g, '&');
        console.log(selected_option);
        $("div#divLoading").addClass('show');
        $.ajax({
            url: set_selected_option_session,
            data: {
                _token: "{{ csrf_token() }}",
                selected_option         : selected_option,
                selected_option_value   : selected_option_value,
                quickupdate_search_item_type: quickupdate_search_item_type,
            },
            type: 'POST',
            success: function (data) {
                console.log(data);
            },
        });
        
    });
    
    $(document).on('click', '#update_button', function (event) {
        // var selected_option = '<?php echo $search_radio;?>';
        var selected_option = $('input:radio[name="search_radio"]:checked').val();
        var selected_option_value = '';
        if (selected_option == 'category') {
            selected_option_value = $('#search_vcategorycode').val();
        } else if (selected_option == 'department') {
            selected_option_value = $('#search_vdepcode').val();
        } else if (selected_option == 'item_group') {
            selected_option_value = $('#search_vitem_group_id').val();
        } else if (selected_option == 'search') {
            selected_option_value = $('#search_item').val();
        }
       
        var quickupdate_search_item_type = '<?php echo $quickupdate_search_item_type ?>'
        var set_selected_option_session = '<?php echo $set_selected_option_session; ?>';
        set_selected_option_session = set_selected_option_session.replace(/&amp;/g, '&');
        console.log(selected_option);
        $("div#divLoading").addClass('show');
        $.ajax({
            url: set_selected_option_session,
            data: {
                _token: "{{ csrf_token() }}",
                selected_option         : selected_option,
                selected_option_value   : selected_option_value,
                quickupdate_search_item_type: quickupdate_search_item_type,
            },
            type: 'POST',
            success: function (data) {
                setTimeout( function() {
                    $('form#form_item_price_update').submit();
                }, 1000);
                
            },
        });
        
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
    })
  
</script>
<style>
    .disable {
        //This makes it not clickable
        pointer-events:none; 
        
       
    }
    
</style>
@endsection