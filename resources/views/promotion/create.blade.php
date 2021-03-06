@extends('layout')

@section('main-content')

<link rel="stylesheet" href="{{ asset('asset/css/promotion.css') }}">


<nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="main_nav">
            <div class="menu">
                <span class="font-weight-bold"> PROMOTIONS</span>
            </div>
            <div class="nav-submenu">
                <a type="button" class="btn btn-dark headerwhite buttons_menu basic-button-small" href="#"> PRINT
                    LABEL </a>
                <a type="button" class="btn btn-gray headerblack  buttons_menu " href="{{route('promotion.create')}}"> ADD NEW
                </a>
                <a type="button" class="btn btn-danger buttonred buttons_menu basic-button-small" href="#"> DELETE
                </a>
            </div>
        </div> <!-- navbar-collapse.// -->
    </div>
</nav>

<div class="container-fluid section-content">
    <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold text-uppercase">
            promotion INFO
        </div>
        <div class="divider font-weight-bold"></div>
    </div>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <form>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputFirstname" class="p-2 float-left text-uppercase">Promotion NAME</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                    placeholder="PROMOTION NAME">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left text-uppercase">promotion type</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_type"
                                    placeholder="PROMOTION TYPE" name="p_type">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <label for="inputLastname" class="p-2 float-left text-uppercase">promotion category</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                <input type="text" class="form-control promo-fields" id="p_category"
                                    placeholder="PROMOTION CATEGORY" name="p_category">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold text-uppercase">
            category INFO
        </div>
        <div class="divider font-weight-bold"></div>
    </div>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <form>
                    <div class="form-group row">
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputFirstname" class="p-2 float-left text-uppercase">start date</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                    placeholder="START DATE">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <label for="inputLastname" class="p-2 float-left text-uppercase">end date</label>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                <input type="text" class="form-control promo-fields" id="p_type"
                                    placeholder="END DATE" name="p_type">
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="mytextdiv">
        <div class="mytexttitle font-weight-bold">
            DISCOUNT INFO
        </div>
        <div class="divider font-weight-bold"></div>
    </div>

    <form action="#" class="form-inline">
        <div class="container-fluid py-3">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <form>
                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputFirstname" class="p-2 float-left">PROMOTION BUY QTY</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" class="form-control promo-fields" id="p_name" name="p_name"
                                        placeholder="PROMOTION BUY QTY">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputLastname" class="p-2 float-left">PROMOTION SLAB PRICE</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" class="form-control promo-fields" id="p_type"
                                        placeholder="PROMOTION SLAB PRICE" name="p_type">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                    <label for="inputLastname" class="p-2 float-left">FOR REGD. CUSTOMERS</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                    <input type="text" class="form-control promo-fields" id="p_category"
                                        placeholder="FOR REGD. CUSTOMERS" name="p_category">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                    <label for="inputAddressLine1" class="p-2 float-left">PROMOTION ITEM TYPE</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                    <input type="text" class="form-control promo-fields" id="p_bqty"
                                        placeholder="PROMOTION ITEM TYPE" name="p_bqty">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                    <label for="inputAddressLine2" class="p-2 float-left">QUANTITY LIMIT</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">

                                    <input type="text" class="form-control promo-fields" id="p_sprice"
                                        placeholder="QUANTITY LIMIT" name="p_sprice">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputAddressLine2" class="p-2 float-left">ALLOW REGULAR PRICE</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" class="form-control promo-fields" id="f_gcustomers"
                                        placeholder="ALLOW REGULAR PRICE" name="f_gcustomers">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-4 col-sm-2 col-lg-4 p-form">
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <label for="inputAddressLine1" class="p-2 float-left">PROMOTION STATUS</label>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                    <input type="text" class="form-control promo-fields" id="p_itemtype"
                                        placeholder="PROMOTION STATUS" name="p_itemtype">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </form>

    {{--  <div class="promotion-text">
        <span id="promotion-texts">Choose items for Promotions</span><a type="button" class="btn btn-primary btn-sm"
            href="#">HIDE</a>
    </div>  --}}
</div>

<section class="section-content py-1">
    <div class="container-fluid">
        <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"
            data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
            data-pagination="true" data-click-to-select="true">
            <thead>
                <tr class="header-color">
                    <th data-field="state" data-checkbox="true"></th>
                    <th class="col-xs-1 headername" data-field="item_name">
                        ITEM NAME &nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="Search">
                        </div>
                    </th>
                    <th class="col-xs-1 headername" data-field="sku">
                        SKU &nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="Search">
                        </div>
                    </th>
                    <th class="col-xs-1 headername" data-field="price">
                        PRICE &nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="Search">
                        </div>
                    </th>
                    <th class="col-xs-1 headername" data-field="department">
                        DEPARTMENT &nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="Search">
                        </div>
                    </th>
                    <th class="col-xs-1 headername" data-field="category">
                        CATEGORY &nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="Search">
                        </div>
                    </th>
                    <th class="col-xs-1 headername" data-field="subcategory">
                        SUBCATEGORY &nbsp;&nbsp;&nbsp;<i class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="Search">
                        </div>
                    </th>
                    <th class="col-xs-1 headername" data-field="supplier">SUPPLIER &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
                            class="fa fa-filter" aria-hidden="true"></i>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control table-heading-fields" placeholder="Search">
                        </div>
                    </th>
                    <th class="col-xs-1 headername" data-field="promotion">PROMOTION
                        <!-- <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="Search">
                            </div> -->
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat is a food of indian which grows every where in india of south.</a></td>
                    <td>Good</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Wheat</td>
                    <td>Good</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Good</td>
                    <td>100 Bags</td>
                    <td>Active</td>
                    <td>Rice</td>
                    <td>Good</td>
                    <td>100 Bags</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Good</td>
                    <td>100 Bags</td>
                    <td>Active</td>
                    <td>Rice</td>
                    <td>Good</td>
                    <td>100 Bags</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Fine</td>
                    <td>10 Packs</td>
                    <td>Active</td>
                    <td>Maze</td>
                    <td>Fine</td>
                    <td>10 Packs</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime09</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime12</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime13</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<div class="container">
    <div class="center">
        <a type="button" class="btn btn-primary basic-button-large" href="#">ADD TO PROMOTION</a>
    </div>
</div>


<section class="section-content py-5">
    <div class="container-fluid">
        <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"
            data-row-style="rowColors" data-striped="true" data-sort-name="Quality" data-sort-order="desc"
            data-pagination="true" data-click-to-select="true">
            <thead>
                <tr class="header-color">
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1" colspan="2" style="text-align:center;">NEW PRICE</th>
                    <th class="col-xs-1" colspan="2" style="text-align:center;">PROMOTION</th>

                    <tr class="header-color">
                        <th data-field="state" data-checkbox="true"></th>
                        <th class="col-xs-1" data-field="item_name">ITEM NAME</th>
                        <th class="col-xs-1" data-field="sku">SKU </th>
                        <th class="col-xs-1" data-field="qty">QTY </th>
                        <th class="col-xs-1" data-field="cost">COST </th>
                        <th class="col-xs-1" data-field="price">PRICE </th>
                        <th class="col-xs-1" data-field="others">OTHERS </th>
                        <th class="col-xs-1" data-field="regd_cust">REGD.CUST </th>
                        <th class="col-xs-1" data-field="others">OTHERS </th>
                        <th class="col-xs-1" data-field="regd_cust">REGD.CUST </th>
                    </tr>
                </tr>
            </thead>
            <tbody>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Good</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Wheat</td>
                    <td>Good</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Good</td>
                    <td>100 Bags</td>
                    <td>Active</td>
                    <td>Rice</td>
                    <td>Good</td>
                    <td>100 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Good</td>
                    <td>100 Bags</td>
                    <td>Active</td>
                    <td>Rice</td>
                    <td>Good</td>
                    <td>100 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Fine</td>
                    <td>10 Packs</td>
                    <td>Active</td>
                    <td>Maze</td>
                    <td>Fine</td>
                    <td>10 Packs</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
                <tr id="tr-id-2" class="tr-class-2">
                    <td></td>
                    <td><a href="promotionedit.php">Wheat</a></td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Sugar</td>
                    <td>Prime</td>
                    <td>200 Bags</td>
                    <td>Active</td>
                    <td>Active</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<div class="container">
    <div class="center">
        <a type="button" class="btn btn-danger basic-button-large" href="#">REMOVE FROM
            PROMOTION</a>
    </div>
</div>

<br />
<br />
<br />
<div class="container">
    <div class="center">
        <a type="button" class="btn btn-primary basic-button-small" href="#">SAVE</a>
        <a type="button" class="btn btn-danger basic-button-small" href="{{route('promotion.index')}}"> CANCEL </a>
    </div>
</div>

@endsection
