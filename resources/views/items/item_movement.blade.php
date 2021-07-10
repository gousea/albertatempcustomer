@extends('layouts.layout')

@section('title', 'Item Movement')

@section('main-content')

    <nav class="navbar navbar-expand-lg sub_menu_navbar navbar-dark bg-primary headermenublue menu">
        <div class="container">
            <div class="row">
                <div class="collapse navbar-collapse" id="main_nav">
                    <div class="menu">
                        <span class="font-weight-bold"> Item Movement</span>
                    </div>
                    <div class="nav-submenu">
                    </div>
                </div> <!-- navbar-collapse.// -->
            </div>
        </div>
    </nav>

    <?php error_reporting(0); ?>
    <div id="content">
        <br>
        <div class="container">
            <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo
                $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>

            <?php if ($message) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $message; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>


            <?php if (isset($reports) && count($reports['item_data']) > 0) { ?>
            <div class="row" style="padding-bottom: 15px;float: right;">
                <div class="col-md-12">
                    <a href="<?php echo $data['print_page']; ?>" id="btnPrint"
                        class="pull-right" style="margin-right:10px;"><i class="fa fa-print" aria-hidden="true"></i>
                        Print</a>
                    <a href="#" id="pdf_export_btn" class="pull-right" style="margin-right:10px; cursor: pointer;"><i
                            class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12">
                    <h6><span>SEARCH PARAMETERS </span></h6>
                </div>
                <br>
                <form method="POST" id="filter_form" action="{{ url('item/ItemMovement') }}" class="form-inline">
                    @csrf
                    <div class="form-group mx-sm-2 mb-2">
                        <input type="text" class="form-control ui-autocomplete-input" name="report_by"
                            value="<?php echo isset($data['report_by']) ? $data['report_by'] : ''; ?>"
                            id="automplete-product" placeholder="Search Item..." required>
                            <input type="hidden" name="search_iitemid" id="search_iitemid"
                                value="<?php echo isset($data['search_iitemid']) ? $data['search_iitemid'] : ''; ?>">
                            <input type="hidden" name="search_vbarcode" id="search_vbarcode"
                                value="<?php echo isset($data['search_vbarcode']) ? $data['search_vbarcode'] : ''; ?>">
                            </select>
                    </div>
                    <div class="form-group mx-sm-4 mb-2">
                        {{-- <input type="submit" class="btn btn-success rcorner header-color" value="Generate"> --}}
                        <input type="submit" class="btn btn-success rcorner header-color" value="Generate">
                    </div>
                </form>
            </div>
            <?php if (isset($reports) && count($reports['item_data']) > 0) { ?>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        {{-- <table data-toggle="table" data-classes="table table-hover table-condensed promotionview"
                                data-row-style="rowColors" data-striped="true" style="width: 100%;margin-bottom: 0px;"> --}}
                        <table id="vendor" class="table table-hover promotionview dataTable no-footer" style="width: 100%;" role="grid">
                            <thead>

                                <?php if (isset($parentreports) && !empty($parentreports)) { ?>
                                    <tr class="headermenublue">
                                        <th colspan="1"></th>
                                        <th colspan="5" style="text-align: center; padding-left: 300px; text-transform: uppercase;"><?php echo $reports['item_data'][0]['vitemname']; ?>
                                                [QOH: UNITS <?php echo $parentreports[0]['item_data'][0]['QOH'] %
                                                $parentreports[0]['item_data'][0]['npack']; ?> ]
                                        </th>
                                        <th colspan="1"></th>
                                    </tr>
                                    <?php } else { ?>
                                    <tr class="headermenublue">
                                        <th colspan="1"></th>
                                        <th colspan="5" style="text-align: center; padding-left: 300px; text-transform: uppercase;"><?php echo $reports['item_data'][0]['vitemname']; ?>
                                                [QOH: CASE <?php echo $reports['item_data'][0]['IQTYONHAND']; ?> ]
                                        </th>
                                        <th colspan="1"></th>
                                    </tr>
                                <?php } ?>
                            </thead>
                            <tbody>
                                <?php
                                $current_year = date('Y');
                                $previous_year = date('Y', strtotime('-1 year'));
                                ?>
                                <tr>
                                    <td colspan="1" style="background-color: #fff;border-top: none;" class="th_color"></td>
                                    <td colspan="2" class="th_color" style="background-color:#fff; border-top: none;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $previous_year; ?> YTD SOLD
                                            <?php echo
                                            !empty($reports['year_arr_sold'][$previous_year]['total_sold']) ? (int)
                                            $reports['year_arr_sold'][$previous_year]['total_sold'] : '0'; ?>

                                            <?php
                                            $value1 =
                                            !empty($reports['year_arr_adjustment'][$previous_year]['total_adjustment']) ?
                                            $reports['year_arr_adjustment'][$previous_year]['total_adjustment'] : '0';
                                            /*adjustment */
                                            $value2 = !empty($reports['year_arr_oqoh'][$previous_year]['total_oqoh']) ?
                                            $reports['year_arr_oqoh'][$previous_year]['total_oqoh'] : '0'; /*Opening Qoh
                                            Web*/
                                            $value3 = !empty($reports['year_arr_qoh'][$previous_year]['total_qoh']) ?
                                            $reports['year_arr_qoh'][$previous_year]['total_qoh'] : '0'; /*Quick Update
                                            web*/
                                            $value4 = !empty($reports['year_arr_inv'][$previous_year]['total_inv']) ?
                                            $reports['year_arr_inv'][$previous_year]['total_inv'] : '0'; /* Parent update*/
                                            $value6 = !empty($reports['year_arr_cqoh'][$previous_year]['total_cqoh']) ?
                                            $reports['year_arr_cqoh'][$previous_year]['total_cqoh'] : '0'; /*Child Upadte*/
                                            $value7 = !empty($reports['year_arr_phqoh'][$previous_year]['total_phqoh']) ?
                                            $reports['year_arr_phqoh'][$previous_year]['total_phqoh'] : '0'; /*Phone update
                                            Qoh update by sku api*/
                                            $value8 = !empty($reports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh'])
                                            ? $reports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh'] : '0'; /*Opening
                                            Qoh by phone */
                                            $value9 =
                                            !empty($reports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment'])
                                            ? $reports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment'] :
                                            '0';
                                            $totaladjpreviousyr = $value1 + $value2 + $value3 + $value4 + $value6 + $value7
                                            + $value8 + $value9;
                                            ?>
                                        </b>
                                    </td>


                                    <td colspan="2" class="th_color" style="background-color:#fff; border-top: none;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $previous_year; ?> YTD ADJUSTMENT
                                            <?php echo $totaladjpreviousyr; ?>
                                            <?php //echo
                                            !empty($reports['year_arr_adjustment'][$previous_year]['total_adjustment']) ?
                                            $reports['year_arr_adjustment'][$previous_year]['total_adjustment'] : '0'; ?>
                                        </b>
                                    </td>

                                    <td colspan="2" class="th_color"
                                        style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $previous_year; ?> YTD RECEIVE
                                            <?php echo
                                            !empty($reports['year_arr_receive'][$previous_year]['total_receive']) ?
                                            $reports['year_arr_receive'][$previous_year]['total_receive'] : '0'; ?>
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" style="background-color: #fff;border-top: none;" class="th_color"></td>
                                    <td colspan="2" class="th_color" style="background-color:#fff; border-top: none;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $current_year; ?> YTD SOLD
                                            <?php echo
                                            !empty($reports['year_arr_sold'][$current_year]['total_sold']) ? (int)
                                            $reports['year_arr_sold'][$current_year]['total_sold'] : '0'; ?>
                                        </b>
                                    </td>


                                    <td colspan="2" class="th_color" style="background-color:#fff; border-top: none;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $current_year; ?> YTD ADJUSTMENT
                                            <!--  Old code
                                    <?php
                    //echo !empty($reports['year_arr_adjustment'][$current_year]['total_adjustment']) ? $reports['year_arr_adjustment'][$current_year]['total_adjustment'] : '0' ;
                    ?>
                                -->


                                            <?php
                                            $value1 =
                                            !empty($reports['year_arr_adjustment'][$current_year]['total_adjustment']) ?
                                            $reports['year_arr_adjustment'][$current_year]['total_adjustment'] : '0';
                                            /*adjustment */
                                            $value2 = !empty($reports['year_arr_oqoh'][$current_year]['total_oqoh']) ?
                                            $reports['year_arr_oqoh'][$current_year]['total_oqoh'] : '0'; /*Opening Qoh
                                            Web*/
                                            $value3 = !empty($reports['year_arr_qoh'][$current_year]['total_qoh']) ?
                                            $reports['year_arr_qoh'][$current_year]['total_qoh'] : '0'; /*Quick Update web*/
                                            $value4 = !empty($reports['year_arr_inv'][$current_year]['total_inv']) ?
                                            $reports['year_arr_inv'][$current_year]['total_inv'] : '0';
                                            $value6 = !empty($reports['year_arr_cqoh'][$current_year]['total_cqoh']) ?
                                            $reports['year_arr_cqoh'][$current_year]['total_cqoh'] : '0'; /*Child Upadte*/
                                            $value5 = !empty($reports['year_arr_pqoh'][$current_year]['total_pqoh']) ?
                                            $reports['year_arr_pqoh'][$current_year]['total_pqoh'] : '0'; /* Parent update*/
                                            $value7 = !empty($reports['year_arr_phqoh'][$current_year]['total_phqoh']) ?
                                            $reports['year_arr_phqoh'][$current_year]['total_phqoh'] : '0'; /*Phone update
                                            Qoh update by sku api*/
                                            $value8 = !empty($reports['year_arr_ophoqoh'][$current_year]['total_ophoqoh']) ?
                                            $reports['year_arr_ophoqoh'][$current_year]['total_ophoqoh'] : '0'; /*Opening
                                            Qoh by phone */
                                            $value9 =
                                            !empty($reports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment'])
                                            ? $reports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment'] : '0';
                                            $TotalAdjustment = $value1 + $value2 + $value3 + $value4 + $value6 + $value7 +
                                            $value8 + $value5 + $value9;
                                            ?>

                                            <?php echo $TotalAdjustment; ?>
                                        </b>
                                    </td>


                                    <td colspan="2" class="th_color" style="background-color:#fff; border-top: none;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $current_year; ?> YTD RECEIVE
                                            <?php echo
                                            !empty($reports['year_arr_receive'][$current_year]['total_receive']) ?
                                            $reports['year_arr_receive'][$current_year]['total_receive'] : '0'; ?>
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center" style="border-right: 1px solid #cdd0d4;">MONTH</th>
                                    <th colspan="3" class="text-center" style="border-right: 1px solid #cdd0d4;">PREVIOUS YEAR</th>
                                    <th colspan="3" class="text-center" style="border-right: 2px solid #cdd0d4;">CURRENT YEAR</th>

                                </tr>

                                <?php for ($i = 1; $i <= 12; ++$i) { ?> <tr>
                                    <td colspan="2" style="border-right: 1px solid #cdd0d4;">
                                        <b><?php echo DateTime::createFromFormat('!m', $i)->format('F'); ?></b>
                                    </td>
                                    <td colspan="3" style="border-right: 1px solid #cdd0d4;">
                                        <?php if
                                        (!empty($reports['month_year_arr_sold'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']) ||
                                        !empty($reports['month_year_arr_receive'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>
                                        (<?php echo $previous_year; ?>)&nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_sold'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold'])) { ?>

                                        SOLD (<?php echo (int)
                                        $reports['month_year_arr_sold'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']; ?>)


                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <!-- Adjustment Deatils -->
                                        <?php if
                                        (!empty($reports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['ptotal_adjustment'])) { ?>
                                        Phy Adj. (<?php echo (int)
                                        $reports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <!-- qoh Deatils
                                <?php if (!empty($reports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh'])) { ?>
                                    QU Adj (<?php echo (int) $reports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>-->

                                        <?php if
                                        (!empty($reports['month_year_arr_receive'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>

                                        &nbsp;
                                        Receive (<?php echo (int)
                                        $reports['month_year_arr_receive'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive']; ?>)

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_oqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh'])) { ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)
                                        $reports['month_year_arr_oqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_adjustment'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_adjustment'])) { ?>
                                        &nbsp;
                                        <?php
                                        $adjustvalue = (int)
                                        $reports['month_year_arr_adjustment'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_adjustment'];
                                        $adjvaluereset += $adjustvalue;
                                        ?>

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $quickupdatevalue = (int) $reports['month_year_arr_qoh'][$previous_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['total_qoh'];
                                        $adjvaluereset += $quickupdatevalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_inv'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'])) { ?>
                                        &nbsp;
                                        <?php
                                        $invresetvalue = (int) $reports['month_year_arr_inv'][$previous_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['total_inv'];
                                        $adjvaluereset += $invresetvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>



                                        <?php if
                                        (!empty($reports['month_year_arr_pqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $pqohvalue = (int) $reports['month_year_arr_pqoh'][$previous_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['total_pqoh'];
                                        $adjvaluereset += $pqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>
                                        <!---->


                                        <!------>
                                        <?php if
                                        (!empty($reports['month_year_arr_cqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $cqohvalue = (int) $reports['month_year_arr_cqoh'][$previous_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['total_cqoh'];
                                        $adjvaluereset += $cqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if ($adjvaluereset != 0) { ?>
                                        Adj. (<?php
                                        echo $adjvaluereset;
                                        $adjvaluereset = 0;
                                        ?>)

                                        <?php } else { ?>

                                        <?php $adjvaluereset = 0; ?>

                                        <?php } ?>
                                        <?php if
                                        (!empty($reports['month_year_arr_phqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh'])) { ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)
                                        $reports['month_year_arr_phqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_ophqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh'])) { ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)
                                        $reports['month_year_arr_ophqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>


                                    </td>

                                    <td colspan="3"
                                        style="border-right: 1px solid #cdd0d4;border-right: 2px solid #cdd0d4;">
                                        <?php if
                                        (!empty($reports['month_year_arr_sold'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']) ||
                                        !empty($reports['month_year_arr_receive'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>
                                        <!-- (<?php
                                                //echo $current_year;
                                                ?>)&nbsp;-->
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_sold'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold'])) { ?>

                                        SOLD (<?php echo (int)
                                        $reports['month_year_arr_sold'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']; ?>)


                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_receive'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>

                                        &nbsp;
                                        Receive (<?php echo (int)
                                        $reports['month_year_arr_receive'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive']; ?>)

                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_were'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_were'])) { ?>

                                        &nbsp;
                                        WareHouse (<?php echo (int)
                                        $reports['month_year_arr_were'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_were']; ?>)

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>


                                        <?php if
                                        (!empty($reports['month_year_arr_oqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh'])) { ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)
                                        $reports['month_year_arr_oqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_adjustment_phy'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['ptotal_adjustment'])) { ?>
                                        Phy Adj. (<?php echo (int)
                                        $reports['month_year_arr_adjustment_phy'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <!-- Adjustment Details -->


                                        <?php if
                                        (!empty($reports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_adjustment'])) { ?>
                                        &nbsp;
                                        <?php
                                        $adjustvalue = (int)
                                        $reports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_adjustment'];
                                        $adjvaluereset += $adjustvalue;
                                        ?>

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $quickupdatevalue = (int) $reports['month_year_arr_qoh'][$current_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['total_qoh'];
                                        $adjvaluereset += $quickupdatevalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'])) { ?>
                                        &nbsp;
                                        <?php
                                        $invresetvalue = (int) $reports['month_year_arr_inv'][$current_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['total_inv'];
                                        $adjvaluereset += $invresetvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>



                                        <?php if
                                        (!empty($reports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                        &nbsp;
                                        <?php $pqohvalue = (int)
                                        $reports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'];
                                        //$adjvaluereset += $pqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>
                                        <!---->


                                        <!------>
                                        <?php if
                                        (!empty($reports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                        &nbsp;
                                        <?php $cqohvalue = (int)
                                        $reports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'];
                                        // $adjvaluereset += $cqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if ($adjvaluereset != 0) { ?>
                                        Adj. (<?php
                                        echo $adjvaluereset;
                                        $adjvaluereset = 0;
                                        ?>)

                                        <?php } else { ?>

                                        <?php $adjvaluereset = 0; ?>
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                        &nbsp;
                                        Parent Adj. (<?php echo (int)
                                        $reports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if (isset($parentreports) && !empty($parentreports)) { ?>
                                        <?php if
                                        (!empty($reports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                        &nbsp;
                                        Tfr to Parent
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php } else { ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                        &nbsp;
                                        Child Adj. (<?php echo (int)
                                        $reports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php }} ?>


                                        <?php if
                                        (!empty($reports['month_year_arr_phqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh'])) { ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)
                                        $reports['month_year_arr_phqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_ophqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh'])) { ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)
                                        $reports['month_year_arr_ophqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($reports['month_year_arr_posqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_posqoh'])) { ?>
                                        &nbsp;
                                        Opening Qoh POS. (<?php echo (int)
                                        $reports['month_year_arr_posqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_posqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>
                                        <!-- adjustment detail end ------>


                                        <!-- old code start

                                <?php if (!empty($reports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_adjustment'])) { ?>
                                    &nbsp;
                                    P Adj. (<?php echo (int) $reports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_adjustment']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                 <?php if (!empty($reports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh'])) { ?>
                                    &nbsp;
                                    QU Adj (<?php echo (int) $reports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                <?php if (!empty($reports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_inv'])) { ?>
                                    &nbsp;
                                   IR Adj (<?php echo (int) $reports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_inv']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>


                                <?php if (!empty($reports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                    &nbsp;
                                    Child Update QoH(<?php echo (int) $reports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_pqoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                <?php if (!empty($reports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                    &nbsp;
                                    Parent Update QoH(<?php echo (int) $reports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_cqoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>


                                 old code end -->

                                    </td>
                                    </tr>
                                    <?php } ?>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <br>
            <br>
            <?php } ?>
            <!-- new parent and child relationship // Hanamant B --->
            <?php foreach ($childreports as $childreports) { ?>
            <?php if (isset($childreports) && count($childreports['item_data']) > 0) { ?>
            {{-- <h3>Child</h3> --}}
            <div class="col-md-12">
                <h6><span>CHILD </span></h6>
            </div>
            <br>
            <?php } ?>

            <?php if (isset($childreports) && count($childreports['item_data']) > 0) { ?>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover promotionview dataTable no-footer">
                            <thead>
                                <tr class="headermenublue">
                                    <th colspan="1"></th>
                                    <th colspan="5" class="text-center text-uppercase" style="text-align: center; padding-left: 98px; text-transform: uppercase;">
                                        <?php echo $childreports['item_data'][0]['vitemname']; ?> [QOH: CASE <?php echo
                                            $childreports['item_data'][0]['IQTYONHAND']; ?> ]</th>
                                    <th colspan="1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $current_year = date('Y');
                                $previous_year = date('Y', strtotime('-1 year'));
                                ?>
                                <tr>
                                    <td colspan="2" style="background-color: #fff;border-top: none;" class="th_color"></td>
                                    <td colspan="2" class="th_color" style="background-color: #fff;border-top: none;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $previous_year; ?> YTD SOLD
                                            <?php echo
                                            !empty($childreports['year_arr_sold'][$previous_year]['total_sold']) ? (int)
                                            $childreports['year_arr_sold'][$previous_year]['total_sold'] : '0'; ?>

                                            <?php
                                            $value1 =
                                            !empty($childreports['year_arr_adjustment'][$previous_year]['total_adjustment'])
                                            ? $childreports['year_arr_adjustment'][$previous_year]['total_adjustment'] :
                                            '0'; /*adjustment */
                                            $value2 = !empty($childreports['year_arr_oqoh'][$previous_year]['total_oqoh']) ?
                                            $childreports['year_arr_oqoh'][$previous_year]['total_oqoh'] : '0'; /*Opening
                                            Qoh Web*/
                                            $value3 = !empty($childreports['year_arr_qoh'][$previous_year]['total_qoh']) ?
                                            $childreports['year_arr_qoh'][$previous_year]['total_qoh'] : '0'; /*Quick Update
                                            web*/
                                            $value4 = !empty($childreports['year_arr_inv'][$previous_year]['total_inv']) ?
                                            $childreports['year_arr_inv'][$previous_year]['total_inv'] : '0'; /* Parent
                                            update*/
                                            $value6 = !empty($childreports['year_arr_cqoh'][$previous_year]['total_cqoh']) ?
                                            $childreports['year_arr_cqoh'][$previous_year]['total_cqoh'] : '0'; /*Child
                                            Upadte*/
                                            $value7 = !empty($childreports['year_arr_phqoh'][$previous_year]['total_phqoh'])
                                            ? $childreports['year_arr_phqoh'][$previous_year]['total_phqoh'] : '0'; /*Phone
                                            update Qoh update by sku api*/
                                            $value8 =
                                            !empty($childreports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh']) ?
                                            $childreports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh'] : '0';
                                            /*Opening Qoh by phone */
                                            $value9 =
                                            !empty($childreports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment'])
                                            ? $childreports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment']
                                            : '0';
                                            $totaladjpreviousyr = $value1 + $value2 + $value3 + $value4 + $value6 + $value7
                                            + $value8 + $value9;
                                            ?>




                                        </b>
                                    </td>


                                    <td colspan="2" class="th_color" style="background-color: #fff;border-top: none;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $previous_year; ?> YTD ADJUSTMENT
                                            <?php echo $totaladjpreviousyr; ?>
                                            <?php //echo
                                            !empty($childreports['year_arr_adjustment'][$previous_year]['total_adjustment'])
                                            ? $childreports['year_arr_adjustment'][$previous_year]['total_adjustment'] :
                                            '0'; ?>
                                        </b>
                                    </td>

                                    <td colspan="2" class="th_color"
                                        style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $previous_year; ?> YTD RECEIVE
                                            <?php echo
                                            !empty($childreports['year_arr_receive'][$previous_year]['total_receive']) ?
                                            $childreports['year_arr_receive'][$previous_year]['total_receive'] : '0'; ?>
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" style="background-color: #fff;border-top: none;" class="th_color"></td>
                                    <td colspan="2" class="th_color" style="background-color: #fff;border-top: none;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $current_year; ?> YTD SOLD
                                            <?php echo
                                            !empty($childreports['year_arr_sold'][$current_year]['total_sold']) ? (int)
                                            $childreports['year_arr_sold'][$current_year]['total_sold'] : '0'; ?>
                                        </b>
                                    </td>


                                    <td colspan="2" class="th_color" style="background-color: #fff;border-top: none;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $current_year; ?> YTD ADJUSTMENT
                                            <!--  Old code
                                    <?php
                    //echo !empty($childreports['year_arr_adjustment'][$current_year]['total_adjustment']) ? $childreports['year_arr_adjustment'][$current_year]['total_adjustment'] : '0' ;
                    ?>
                                -->


                                            <?php
                                            $value1 =
                                            !empty($childreports['year_arr_adjustment'][$current_year]['total_adjustment'])
                                            ? $childreports['year_arr_adjustment'][$current_year]['total_adjustment'] : '0';
                                            /*adjustment */
                                            $value2 = !empty($childreports['year_arr_oqoh'][$current_year]['total_oqoh']) ?
                                            $childreports['year_arr_oqoh'][$current_year]['total_oqoh'] : '0'; /*Opening Qoh
                                            Web*/
                                            $value3 = !empty($childreports['year_arr_qoh'][$current_year]['total_qoh']) ?
                                            $childreports['year_arr_qoh'][$current_year]['total_qoh'] : '0'; /*Quick Update
                                            web*/
                                            $value4 = !empty($childreports['year_arr_inv'][$current_year]['total_inv']) ?
                                            $childreports['year_arr_inv'][$current_year]['total_inv'] : '0';
                                            $value6 = !empty($childreports['year_arr_cqoh'][$current_year]['total_cqoh']) ?
                                            $childreports['year_arr_cqoh'][$current_year]['total_cqoh'] : '0'; /*Child
                                            Upadte*/
                                            $value5 = !empty($childreports['year_arr_pqoh'][$current_year]['total_pqoh']) ?
                                            $childreports['year_arr_pqoh'][$current_year]['total_pqoh'] : '0'; /* Parent
                                            update*/
                                            $value7 = !empty($childreports['year_arr_phqoh'][$current_year]['total_phqoh'])
                                            ? $childreports['year_arr_phqoh'][$current_year]['total_phqoh'] : '0'; /*Phone
                                            update Qoh update by sku api*/
                                            $value8 =
                                            !empty($childreports['year_arr_ophoqoh'][$current_year]['total_ophoqoh']) ?
                                            $childreports['year_arr_ophoqoh'][$current_year]['total_ophoqoh'] : '0';
                                            /*Opening Qoh by phone */
                                            $value9 =
                                            !empty($childreports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment'])
                                            ? $childreports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment'] :
                                            '0';
                                            $TotalAdjustment = $value1 + $value2 + $value3 + $value4 + $value6 + $value7 +
                                            $value8 + $value5 + $value9;
                                            ?>

                                            <?php echo $TotalAdjustment; ?>
                                        </b>
                                    </td>


                                    <td colspan="2" class="th_color"
                                        style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                        <b class="text-uppercase" style="font-size: 14px;">
                                            <?php echo $current_year; ?> YTD RECEIVE
                                            <?php echo
                                            !empty($childreports['year_arr_receive'][$current_year]['total_receive']) ?
                                            $childreports['year_arr_receive'][$current_year]['total_receive'] : '0'; ?>
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center" style="border-right: 1px solid #cdd0d4;">MONTH</th>
                                    <th colspan="3" class="text-center" style="border-right: 1px solid #cdd0d4;">PREVIOUS YEAR</th>
                                    <th colspan="3" class="text-center" style="border-right: 2px solid #cdd0d4;">CURRENT YEAR</th>

                                </tr>

                                <?php for ($i = 1; $i <= 12; ++$i) { ?> <tr>
                                    <td colspan="2" style="border-right: 1px solid #cdd0d4;">
                                        <b><?php echo DateTime::createFromFormat('!m', $i)->format('F'); ?></b>
                                    </td>
                                    <td colspan="3" style="border-right: 1px solid #cdd0d4;">
                                        <?php if
                                        (!empty($childreports['month_year_arr_sold'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']) ||
                                        !empty($childreports['month_year_arr_receive'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>
                                        (<?php echo $previous_year; ?>)&nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_sold'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold'])) { ?>

                                        SOLD (<?php echo (int)
                                        $childreports['month_year_arr_sold'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']; ?>)


                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <!-- Adjustment Deatils -->
                                        <?php if
                                        (!empty($childreports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['ptotal_adjustment'])) { ?>
                                        Phy Adj. (<?php echo (int)
                                        $childreports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <!-- qoh Deatils
                                <?php if (!empty($childreports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh'])) { ?>
                                    QU Adj (<?php echo (int) $childreports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>-->

                                        <?php if
                                        (!empty($childreports['month_year_arr_receive'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>

                                        &nbsp;
                                        Receive (<?php echo (int)
                                        $childreports['month_year_arr_receive'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive']; ?>)

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_oqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh'])) { ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)
                                        $childreports['month_year_arr_oqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_adjustment'][$previous_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['total_adjustment'])) { ?>
                                        &nbsp;
                                        <?php
                                        $adjustvalue = (int)
                                        $childreports['month_year_arr_adjustment'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_adjustment'];
                                        $adjvaluereset += $adjustvalue;
                                        ?>

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $quickupdatevalue = (int)
                                        $childreports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'];
                                        $adjvaluereset += $quickupdatevalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_inv'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'])) { ?>
                                        &nbsp;
                                        <?php
                                        $invresetvalue = (int)
                                        $childreports['month_year_arr_inv'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'];
                                        $adjvaluereset += $invresetvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>



                                        <?php if
                                        (!empty($childreports['month_year_arr_pqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $pqohvalue = (int) $childreports['month_year_arr_pqoh'][$previous_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['total_pqoh'];
                                        $adjvaluereset += $pqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>
                                        <!---->


                                        <!------>
                                        <?php if
                                        (!empty($childreports['month_year_arr_cqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $cqohvalue = (int) $childreports['month_year_arr_cqoh'][$previous_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['total_cqoh'];
                                        $adjvaluereset += $cqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if ($adjvaluereset != 0) { ?>
                                        Adj. (<?php
                                        echo $adjvaluereset;
                                        $adjvaluereset = 0;
                                        ?>)

                                        <?php } else { ?>

                                        <?php $adjvaluereset = 0; ?>

                                        <?php } ?>
                                        <?php if
                                        (!empty($childreports['month_year_arr_phqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh'])) { ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)
                                        $childreports['month_year_arr_phqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_ophqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh'])) { ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)
                                        $childreports['month_year_arr_ophqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>


                                    </td>

                                    <td colspan="3"
                                        style="border-right: 1px solid #cdd0d4;border-right: 2px solid #cdd0d4;">
                                        <?php if
                                        (!empty($childreports['month_year_arr_sold'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']) ||
                                        !empty($childreports['month_year_arr_receive'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>
                                        <!-- (<?php
                                                //echo $current_year;
                                                ?>)&nbsp;-->
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_sold'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold'])) { ?>

                                        SOLD (<?php echo (int)
                                        $childreports['month_year_arr_sold'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']; ?>)


                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_receive'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>

                                        &nbsp;
                                        Receive (<?php echo (int)
                                        $childreports['month_year_arr_receive'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive']; ?>)

                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_were'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_were'])) { ?>

                                        &nbsp;
                                        WareHouse (<?php echo (int)
                                        $childreports['month_year_arr_were'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_were']; ?>)

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>


                                        <?php if
                                        (!empty($childreports['month_year_arr_oqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh'])) { ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)
                                        $childreports['month_year_arr_oqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_adjustment_phy'][$current_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['ptotal_adjustment'])) { ?>
                                        Phy Adj. (<?php echo (int)
                                        $childreports['month_year_arr_adjustment_phy'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <!-- Adjustment Details -->


                                        <?php if
                                        (!empty($childreports['month_year_arr_adjustment'][$current_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['total_adjustment'])) { ?>
                                        &nbsp;
                                        <?php
                                        $adjustvalue = (int)
                                        $childreports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_adjustment'];
                                        $adjvaluereset += $adjustvalue;
                                        ?>

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $quickupdatevalue = (int)
                                        $childreports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'];
                                        $adjvaluereset += $quickupdatevalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'])) { ?>
                                        &nbsp;
                                        <?php
                                        $invresetvalue = (int)
                                        $childreports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'];
                                        $adjvaluereset += $invresetvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>



                                        <?php if
                                        (!empty($childreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                        &nbsp;
                                        <?php $pqohvalue = (int)
                                        $childreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'];
                                        //$adjvaluereset += $pqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>
                                        <!---->


                                        <!------>
                                        <?php if
                                        (!empty($childreports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                        &nbsp;
                                        <?php $cqohvalue = (int)
                                        $childreports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'];
                                        // $adjvaluereset += $cqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if ($adjvaluereset != 0) { ?>
                                        Adj. (<?php
                                        echo $adjvaluereset;
                                        $adjvaluereset = 0;
                                        ?>)

                                        <?php } else { ?>

                                        <?php $adjvaluereset = 0; ?>
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                        &nbsp;
                                        Parent Adj. (<?php echo (int)
                                        $childreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>



                                        <?php if
                                        (!empty($childreports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                        &nbsp;
                                        Tfr to Parent
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_phqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh'])) { ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)
                                        $childreports['month_year_arr_phqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($childreports['month_year_arr_ophqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh'])) { ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)
                                        $childreports['month_year_arr_ophqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>
                                        <!-- adjustment detail end ------>


                                        <!-- old code start

                                <?php if (!empty($childreports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_adjustment'])) { ?>
                                    &nbsp;
                                    P Adj. (<?php echo (int) $childreports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_adjustment']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                 <?php if (!empty($childreports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh'])) { ?>
                                    &nbsp;
                                    QU Adj (<?php echo (int) $childreports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                <?php if (!empty($childreports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_inv'])) { ?>
                                    &nbsp;
                                   IR Adj (<?php echo (int) $childreports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_inv']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>


                                <?php if (!empty($childreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                    &nbsp;
                                    Child Update QoH(<?php echo (int) $childreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_pqoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                <?php if (!empty($childreports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                    &nbsp;
                                    Parent Update QoH(<?php echo (int) $childreports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_cqoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>


                                 old code end -->

                                    </td>
                                    </tr>
                                    <?php } ?>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <br>
            <br>
            <?php } ?>


            <?php } ?>

            <!-- child End-- parent start -->

            <?php foreach ($parentreports as $parentreports) { ?>
            <?php if (isset($parentreports) && count($parentreports['item_data']) > 0) { ?>
            <h3>Parent</h3>
            <?php } ?>

            <?php if (isset($parentreports) && count($parentreports['item_data']) > 0) { ?>
            <br><br>
            <div class="row">
                <div class="col-md-10">
                    <div class="table-responsive">
                        <table class="table" style="border: 1px solid #ccc;">
                            <thead>
                                <tr>
                                    <th colspan="7" class="text-center text-uppercase"><b style="font-size: 16px;"><?php echo $parentreports['item_data'][0]['vitemname']; ?> [QOH: CASE <?php echo
                                            $parentreports['item_data'][0]['IQTYONHAND']; ?> ]</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $current_year = date('Y');
                                $previous_year = date('Y', strtotime('-1 year'));
                                ?>
                                <tr>
                                    <td colspan="2" style="background-color: #fff;"></td>
                                    <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                        <b class="text-uppercase text-info" style="font-size: 14px;">
                                            <?php echo $previous_year; ?> YTD SOLD
                                            <?php echo
                                            !empty($parentreports['year_arr_sold'][$previous_year]['total_sold']) ? (int)
                                            $parentreports['year_arr_sold'][$previous_year]['total_sold'] : '0'; ?>

                                            <?php
                                            $value1 =
                                            !empty($parentreports['year_arr_adjustment'][$previous_year]['total_adjustment'])
                                            ? $parentreports['year_arr_adjustment'][$previous_year]['total_adjustment'] :
                                            '0'; /*adjustment */
                                            $value2 = !empty($parentreports['year_arr_oqoh'][$previous_year]['total_oqoh'])
                                            ? $parentreports['year_arr_oqoh'][$previous_year]['total_oqoh'] : '0'; /*Opening
                                            Qoh Web*/
                                            $value3 = !empty($parentreports['year_arr_qoh'][$previous_year]['total_qoh']) ?
                                            $parentreports['year_arr_qoh'][$previous_year]['total_qoh'] : '0'; /*Quick
                                            Update web*/
                                            $value4 = !empty($parentreports['year_arr_inv'][$previous_year]['total_inv']) ?
                                            $parentreports['year_arr_inv'][$previous_year]['total_inv'] : '0'; /* Parent
                                            update*/
                                            $value6 = !empty($parentreports['year_arr_cqoh'][$previous_year]['total_cqoh'])
                                            ? $parentreports['year_arr_cqoh'][$previous_year]['total_cqoh'] : '0'; /*Child
                                            Upadte*/
                                            $value7 =
                                            !empty($parentreports['year_arr_phqoh'][$previous_year]['total_phqoh']) ?
                                            $parentreports['year_arr_phqoh'][$previous_year]['total_phqoh'] : '0'; /*Phone
                                            update Qoh update by sku api*/
                                            $value8 =
                                            !empty($parentreports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh']) ?
                                            $parentreports['year_arr_ophoqoh'][$previous_year]['total_ophoqoh'] : '0';
                                            /*Opening Qoh by phone */
                                            $value9 =
                                            !empty($parentreports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment'])
                                            ? $parentreports['year_arr_adjustment_phy'][$previous_year]['ptotal_adjustment']
                                            : '0';
                                            $totaladjpreviousyr = $value1 + $value2 + $value3 + $value4 + $value6 + $value7
                                            + $value8 + $value9;
                                            ?>




                                        </b>
                                    </td>


                                    <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                        <b class="text-uppercase text-info" style="font-size: 14px;">
                                            <?php echo $previous_year; ?> YTD ADJUSTMENT
                                            <?php echo $totaladjpreviousyr; ?>
                                            <?php //echo
                                            !empty($parentreports['year_arr_adjustment'][$previous_year]['total_adjustment'])
                                            ? $parentreports['year_arr_adjustment'][$previous_year]['total_adjustment'] :
                                            '0'; ?>
                                        </b>
                                    </td>

                                    <td colspan="2" class="text-left"
                                        style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                        <b class="text-uppercase text-info" style="font-size: 14px;">
                                            <?php echo $previous_year; ?> YTD RECEIVE
                                            <?php echo
                                            !empty($parentreports['year_arr_receive'][$previous_year]['total_receive']) ?
                                            $parentreports['year_arr_receive'][$previous_year]['total_receive'] : '0'; ?>
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" style="background-color: #fff;border-top: none;">
                                        </td>
                                    <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                        <b class="text-uppercase text-danger" style="font-size: 14px;">
                                            <?php echo $current_year; ?> YTD SOLD
                                            <?php echo
                                            !empty($parentreports['year_arr_sold'][$current_year]['total_sold']) ? (int)
                                            $parentreports['year_arr_sold'][$current_year]['total_sold'] : '0'; ?>
                                        </b>
                                    </td>


                                    <td colspan="2" class="text-left" style="background-color: #fff;border-top: none;">
                                        <b class="text-uppercase text-danger" style="font-size: 14px;">
                                            <?php echo $current_year; ?> YTD ADJUSTMENT
                                            <!--  Old code
                                    <?php
                    //echo !empty($parentreports['year_arr_adjustment'][$current_year]['total_adjustment']) ? $parentreports['year_arr_adjustment'][$current_year]['total_adjustment'] : '0' ;
                    ?>
                                -->


                                            <?php
                                            $value1 =
                                            !empty($parentreports['year_arr_adjustment'][$current_year]['total_adjustment'])
                                            ? $parentreports['year_arr_adjustment'][$current_year]['total_adjustment'] :
                                            '0'; /*adjustment */
                                            $value2 = !empty($parentreports['year_arr_oqoh'][$current_year]['total_oqoh']) ?
                                            $parentreports['year_arr_oqoh'][$current_year]['total_oqoh'] : '0'; /*Opening
                                            Qoh Web*/
                                            $value3 = !empty($parentreports['year_arr_qoh'][$current_year]['total_qoh']) ?
                                            $parentreports['year_arr_qoh'][$current_year]['total_qoh'] : '0'; /*Quick Update
                                            web*/
                                            $value4 = !empty($parentreports['year_arr_inv'][$current_year]['total_inv']) ?
                                            $parentreports['year_arr_inv'][$current_year]['total_inv'] : '0';
                                            $value6 = !empty($parentreports['year_arr_cqoh'][$current_year]['total_cqoh']) ?
                                            $parentreports['year_arr_cqoh'][$current_year]['total_cqoh'] : '0'; /*Child
                                            Upadte*/
                                            $value5 = !empty($parentreports['year_arr_pqoh'][$current_year]['total_pqoh']) ?
                                            $parentreports['year_arr_pqoh'][$current_year]['total_pqoh'] : '0'; /* Parent
                                            update*/
                                            $value7 = !empty($parentreports['year_arr_phqoh'][$current_year]['total_phqoh'])
                                            ? $parentreports['year_arr_phqoh'][$current_year]['total_phqoh'] : '0'; /*Phone
                                            update Qoh update by sku api*/
                                            $value8 =
                                            !empty($parentreports['year_arr_ophoqoh'][$current_year]['total_ophoqoh']) ?
                                            $parentreports['year_arr_ophoqoh'][$current_year]['total_ophoqoh'] : '0';
                                            /*Opening Qoh by phone */
                                            $value9 =
                                            !empty($parentreports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment'])
                                            ? $parentreports['year_arr_adjustment_phy'][$current_year]['ptotal_adjustment']
                                            : '0';
                                            $TotalAdjustment = $value1 + $value2 + $value3 + $value4 + $value6 + $value7 +
                                            $value8 + $value5 + $value9;
                                            ?>

                                            <?php echo $TotalAdjustment; ?>
                                        </b>
                                    </td>


                                    <td colspan="2" class="text-left"
                                        style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                                        <b class="text-uppercase text-danger" style="font-size: 14px;">
                                            <?php echo $current_year; ?> YTD RECEIVE
                                            <?php echo
                                            !empty($parentreports['year_arr_receive'][$current_year]['total_receive']) ?
                                            $parentreports['year_arr_receive'][$current_year]['total_receive'] : '0'; ?>
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" style="border-right: 1px solid #cdd0d4;"></th>
                                    <th colspan="3" class="text-center" style="border-right: 1px solid #cdd0d4;">Previous
                                        Year</th>
                                    <th colspan="3" class="text-center" style="border-right: 2px solid #cdd0d4;">Current
                                        Year</th>

                                </tr>

                                <?php for ($i = 1; $i <= 12; ++$i) { ?> <tr>
                                    <td colspan="2" style="border-right: 1px solid #cdd0d4;">
                                        <b><?php echo DateTime::createFromFormat('!m', $i)->format('F'); ?></b>
                                    </td>
                                    <td colspan="3" style="border-right: 1px solid #cdd0d4;">
                                        <?php if
                                        (!empty($parentreports['month_year_arr_sold'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']) ||
                                        !empty($parentreports['month_year_arr_receive'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>
                                        (<?php echo $previous_year; ?>)&nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_sold'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold'])) { ?>

                                        SOLD (<?php echo (int)
                                        $parentreports['month_year_arr_sold'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']; ?>)


                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <!-- Adjustment Deatils -->
                                        <?php if
                                        (!empty($parentreports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['ptotal_adjustment'])) { ?>
                                        Phy Adj. (<?php echo (int)
                                        $parentreports['month_year_arr_adjustment_phy'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <!-- qoh Deatils
                                <?php if (!empty($parentreports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh'])) { ?>
                                    QU Adj (<?php echo (int) $parentreports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>-->

                                        <?php if
                                        (!empty($parentreports['month_year_arr_receive'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>

                                        &nbsp;
                                        Receive (<?php echo (int)
                                        $parentreports['month_year_arr_receive'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive']; ?>)

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_oqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh'])) { ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)
                                        $parentreports['month_year_arr_oqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_adjustment'][$previous_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['total_adjustment'])) { ?>
                                        &nbsp;
                                        <?php
                                        $adjustvalue = (int)
                                        $parentreports['month_year_arr_adjustment'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_adjustment'];
                                        $adjvaluereset += $adjustvalue;
                                        ?>

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $quickupdatevalue = (int)
                                        $parentreports['month_year_arr_qoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'];
                                        $adjvaluereset += $quickupdatevalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_inv'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'])) { ?>
                                        &nbsp;
                                        <?php
                                        $invresetvalue = (int)
                                        $parentreports['month_year_arr_inv'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'];
                                        $adjvaluereset += $invresetvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>



                                        <?php if
                                        (!empty($parentreports['month_year_arr_pqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $pqohvalue = (int) $parentreports['month_year_arr_pqoh'][$previous_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['total_pqoh'];
                                        $adjvaluereset += $pqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>
                                        <!---->


                                        <!------>
                                        <?php if
                                        (!empty($parentreports['month_year_arr_cqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $cqohvalue = (int) $parentreports['month_year_arr_cqoh'][$previous_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['total_cqoh'];
                                        $adjvaluereset += $cqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if ($adjvaluereset != 0) { ?>
                                        Adj. (<?php
                                        echo $adjvaluereset;
                                        $adjvaluereset = 0;
                                        ?>)

                                        <?php } else { ?>

                                        <?php $adjvaluereset = 0; ?>

                                        <?php } ?>
                                        <?php if
                                        (!empty($parentreports['month_year_arr_phqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh'])) { ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)
                                        $parentreports['month_year_arr_phqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_ophqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh'])) { ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)
                                        $parentreports['month_year_arr_ophqoh'][$previous_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>


                                    </td>

                                    <td colspan="3"
                                        style="border-right: 1px solid #cdd0d4;border-right: 2px solid #cdd0d4;">
                                        <?php if
                                        (!empty($parentreports['month_year_arr_sold'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']) ||
                                        !empty($parentreports['month_year_arr_receive'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>
                                        <!-- (<?php
                                                //echo $current_year;
                                                ?>)&nbsp;-->
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_sold'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold'])) { ?>

                                        SOLD (<?php echo (int)
                                        $parentreports['month_year_arr_sold'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_sold']; ?>)


                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_receive'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive'])) { ?>

                                        &nbsp;
                                        Receive (<?php echo (int)
                                        $parentreports['month_year_arr_receive'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_receive']; ?>)

                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_were'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_were'])) { ?>

                                        &nbsp;
                                        WareHouse (<?php echo (int)
                                        $parentreports['month_year_arr_were'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_were']; ?>)

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>


                                        <?php if
                                        (!empty($parentreports['month_year_arr_oqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh'])) { ?>
                                        &nbsp;
                                        Opening QoH(<?php echo (int)
                                        $parentreports['month_year_arr_oqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_oqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_adjustment_phy'][$current_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['ptotal_adjustment'])) { ?>
                                        Phy Adj. (<?php echo (int)
                                        $parentreports['month_year_arr_adjustment_phy'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['ptotal_adjustment']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <!-- Adjustment Details -->


                                        <?php if
                                        (!empty($parentreports['month_year_arr_adjustment'][$current_year][str_pad($i, 2,
                                        '0', STR_PAD_LEFT)]['total_adjustment'])) { ?>
                                        &nbsp;
                                        <?php
                                        $adjustvalue = (int)
                                        $parentreports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_adjustment'];
                                        $adjvaluereset += $adjustvalue;
                                        ?>

                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $quickupdatevalue = (int)
                                        $parentreports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_qoh'];
                                        $adjvaluereset += $quickupdatevalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'])) { ?>
                                        &nbsp;
                                        <?php
                                        $invresetvalue = (int)
                                        $parentreports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_inv'];
                                        $adjvaluereset += $invresetvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>



                                        <?php if
                                        (!empty($parentreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                        &nbsp;
                                        <?php $pqohvalue = (int)
                                        $parentreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'];
                                        //$adjvaluereset += $pqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>
                                        <!---->


                                        <!------>
                                        <?php if
                                        (!empty($parentreports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                        &nbsp;
                                        <?php
                                        $cqohvalue = (int) $parentreports['month_year_arr_cqoh'][$current_year][str_pad($i,
                                        2, '0', STR_PAD_LEFT)]['total_cqoh'];
                                        $adjvaluereset += $cqohvalue;
                                        ?>
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if ($adjvaluereset != 0) { ?>
                                        Adj. (<?php
                                        echo $adjvaluereset;
                                        $adjvaluereset = 0;
                                        ?>)

                                        <?php } else { ?>

                                        <?php $adjvaluereset = 0; ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                        &nbsp;
                                        Parent Adj. (<?php echo (int)
                                        $parentreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_pqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php } ?>
                                        <?php if
                                        (!empty($parentreports['month_year_arr_phqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh'])) { ?>
                                        &nbsp;
                                        Phone Adj. (<?php echo (int)
                                        $parentreports['month_year_arr_phqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_phqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>

                                        <?php if
                                        (!empty($parentreports['month_year_arr_ophqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh'])) { ?>
                                        &nbsp;
                                        Opening Qoh Phone. (<?php echo (int)
                                        $parentreports['month_year_arr_ophqoh'][$current_year][str_pad($i, 2, '0',
                                        STR_PAD_LEFT)]['total_ophqoh']; ?>)
                                        <?php } else { ?>
                                        &nbsp;
                                        <?php } ?>
                                        <!-- adjustment detail end ------>


                                        <!-- old code start

                                <?php if (!empty($parentreports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_adjustment'])) { ?>
                                    &nbsp;
                                    P Adj. (<?php echo (int) $parentreports['month_year_arr_adjustment'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_adjustment']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                 <?php if (!empty($parentreports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh'])) { ?>
                                    &nbsp;
                                    QU Adj (<?php echo (int) $parentreports['month_year_arr_qoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_qoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                <?php if (!empty($parentreports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_inv'])) { ?>
                                    &nbsp;
                                   IR Adj (<?php echo (int) $parentreports['month_year_arr_inv'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_inv']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>


                                <?php if (!empty($parentreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_pqoh'])) { ?>
                                    &nbsp;
                                    Child Update QoH(<?php echo (int) $parentreports['month_year_arr_pqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_pqoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                <?php if (!empty($parentreports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_cqoh'])) { ?>
                                    &nbsp;
                                    Parent Update QoH(<?php echo (int) $parentreports['month_year_arr_cqoh'][$current_year][str_pad($i, 2, '0', STR_PAD_LEFT)]['total_cqoh']; ?>)
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>


                                 old code end -->

                                    </td>
                                    </tr>
                                    <?php } ?>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <br>
            <br>
            <?php } ?>


            <?php } ?>
            <!---parent end --->

            <?php if (isset($reports) && count($reports['item_data']) > 0) { ?>
            <div class="row">
                <div class="col-md-12" id="item_movement_by_date_selection">
                    <div class="col-md-12">
                        <h6><span>Item Movement By Date</span></h6>
                    </div>
                    <br>
                    {{-- <h3 class="text-danger"></h3> --}}
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="start_date" value="{{ old('start_date') }}" autocomplete="off"
                                        placeholder="Start Date" id="start_date" class="form-control" readonly/>
                            {{-- <input type="text" class="form-control" name="start_date" id="start_date"
                                placeholder="Start Date" readonly> --}}
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="end_date" id="end_date"
                                placeholder="End Date" readonly>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="search_by_item">
                                <option value="sold">Sold</option>
                                <option value="receive">Receive</option>
                                <option value="adjustment">Adjustment</option>
                                <option value="openingqoh">Opening Qoh WEB</option>
                                <option value="openingpos">Opening Qoh POS</option>
                                <option value="phoneadjustment">Phone Adjustment</option>
                                <option value="openingqohphone">Opening QoH Phone</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="button" class="btn btn-success rcorner header-color item_movement_btn" value="GENERATE">
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="item_movement_by_date_selection_table"
                            style="display: none;">
                            <thead>
                                <tr class="th_color">
                                    <th id="first_th">Print Receipt</th>
                                    <th>Action</th>
                                    <th>Date</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Pack Qty</th>
                                    <th class="text-right">Size</th>
                                    <th class="text-right">Price</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php } ?>


        </div>
    </div>

@endsection


@section('page-script')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="{{ asset('javascript/jquery.printPage.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/adjustment.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" defer></script>
    <script src="{{ asset('javascript/bootbox.min.js') }}" defer></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <style>
        .ui-menu-item-wrapper:hover{
            color: rgb(238, 136, 41);
            border: rgb(235, 121, 29) 1px solid;
            text-transform: uppercase;
        }
    .autoc {
        /* position:absolute; */
        /* cursor:default;
        z-index:1001 !important */
    }
    </style>

    <script>
         $(function(){
            $("#start_date").datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                widgetPositioning:{
                    horizontal: 'auto',
                    vertical: 'bottom'
                }
            });
        });
        $(function(){
            $("#end_date").datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                widgetPositioning:{
                    horizontal: 'auto',
                    vertical: 'bottom'
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).on('submit', '#filter_form', function(event) {

            if ($('#report_by').val() == '') {
                // alert('Please Select Department');
                bootbox.alert({
                    size: 'small',
                    title: "",
                    message: "Please Select Item",
                    callback: function() {}
                });
                return false;
            }

            if ($('input[name="start_date"]').val() != '' && $('input[name="end_date"]').val() != '') {

                var d1 = Date.parse($('input[name="start_date"]').val());
                var d2 = Date.parse($('input[name="end_date"]').val());

                if (d1 > d2) {
                    bootbox.alert({
                        size: 'small',
                        title: "",
                        message: "Start date must be less then end date!",
                        callback: function() {}
                    });
                    return false;
                }
            }

            // $("div#divLoading").addClass('show');

        });
    </script>

    <script>
        $(document).ready(function() {
            $("#btnPrint").printPage();
        });
    </script>

    <script type="text/javascript">
        const saveData = (function() {
            const a = document.createElement("a");
            document.body.appendChild(a);
            a.style = "display: none";
            return function(data, fileName) {
                const blob = new Blob([data], {
                        type: "octet/stream"
                    }),
                    url = window.URL.createObjectURL(blob);
                a.href = url;
                a.download = fileName;
                a.click();
                window.URL.revokeObjectURL(url);
            };
        }());

        $(document).on("click", "#csv_export_btn", function(event) {

            event.preventDefault();

            // $("div#divLoading").addClass('show');

            var csv_export_url = '<?php echo $csv_export; ?>';

            csv_export_url = csv_export_url.replace(/&amp;/g, '&');

            $.ajax({
                url: csv_export_url,
                type: 'GET',
            }).done(function(response) {

                const data = response,
                    fileName = "item-movement-report.csv";

                saveData(data, fileName);
                // $("div#divLoading").removeClass('show');

            });

        });

        $(document).on("click", "#pdf_export_btn", function(event) {

            event.preventDefault();

            // $("div#divLoading").addClass('show');

            var pdf_export_url = '<?php echo $data['pdf_save_page']; ?>';

            pdf_export_url = pdf_export_url.replace(/&amp;/g, '&');

            var req = new XMLHttpRequest();
            req.open("GET", pdf_export_url, true);
            req.responseType = "blob";
            req.onreadystatechange = function() {
                if (req.readyState === 4 && req.status === 200) {

                    if (typeof window.navigator.msSaveBlob === 'function') {
                        window.navigator.msSaveBlob(req.response, "Item-Movement-Report.pdf");
                    } else {
                        var blob = req.response;
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "Item-Movement-Report.pdf";

                        // append the link to the document body

                        document.body.appendChild(link);

                        link.click();
                    }
                }
                // $("div#divLoading").removeClass('show');
            };
            req.send();

        });
    </script>


    <script type="text/javascript">
        $(document).on('change', 'input[name="start_date"],input[name="end_date"]', function(event) {
            event.preventDefault();

            if ($('input[name="start_date"]').val() != '' && $('input[name="end_date"]').val() != '') {

                var d1 = Date.parse($('input[name="start_date"]').val());
                var d2 = Date.parse($('input[name="end_date"]').val());

                if (d1 > d2) {
                    bootbox.alert({
                        size: 'small',
                        title: "",
                        message: "Start date must be less then end date!",
                        callback: function() {}
                    });
                    return false;
                }
            }
        });
    </script>

    <script>
        $(function() {

            var url = '<?php echo $data['searchitem']; ?>';
            //console.log(url);
            url = url.replace(/&amp;/g, '&');

            $("#automplete-product").autocomplete({
                minLength: 2,
                source: function(req, add) {
                    $.getJSON(url, req, function(data) {
                        window.suggestions = [];
                        $.each(data, function(i, val) {
                            suggestions.push({
                                label: val.vitemname,
                                value: val.vitemname,
                                vbarcode: val.vbarcode,
                                id: val.iitemid
                            });
                        });
                        add(window.suggestions);
                    });
                },
                select: function(e, ui) {
                    $('#search_iitemid').val(ui.item.id);
                    $('#search_vbarcode').val(ui.item.vbarcode);
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).on('click', '.item_movement_btn', function(event) {
            event.preventDefault();

            var vbarcode = $('#search_vbarcode').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var data_by = $('select[name="search_by_item"]').val();

            if ($('#start_date').val() == '') {
                // alert('Please Select Start Date');
                bootbox.alert({
                    size: 'small',
                    title: "",
                    message: "Please Select Start Date",
                    callback: function() {}
                });
                return false;
            }

            if ($('#end_date').val() == '') {
                // alert('Please Select End Date');
                bootbox.alert({
                    size: 'small',
                    title: "",
                    message: "Please Select End Date",
                    callback: function() {}
                });
                return false;
            }

            if ($('input[name="start_date"]').val() != '' && $('input[name="end_date"]').val() != '') {

                var d1 = Date.parse($('input[name="start_date"]').val());
                var d2 = Date.parse($('input[name="end_date"]').val());

                if (d1 > d2) {
                    bootbox.alert({
                        size: 'small',
                        title: "",
                        message: "Start date must be less then end date!",
                        callback: function() {}
                    });
                    return false;
                }
            }

            var item_movement_data_url = '<?php echo $data['item_movement_data']; ?>';

            item_movement_data_url = item_movement_data_url.replace(/&amp;/g, '&');

            item_movement_data_url = item_movement_data_url + '?vbarcode=' + vbarcode + '&start_date=' +
                start_date + '&end_date=' + end_date + '&data_by=' + data_by;

            //  alert(item_movement_data_url);

            if (data_by == 'receive') {
                $('#item_movement_by_date_selection_table > thead > tr').html(
                    '<th>Action</th><th>Date</th> <th class="text-right">Qty</th><th class="text-right">Pack Qty</th><th class="text-right">Size</th><th class="text-right">Price</th><th class="text-right">PO Number</th><th>Before QOH</th><th>After QOH</th>'
                );
            } else if (data_by == 'sold') {
                $('#item_movement_by_date_selection_table > thead > tr').html(
                    '<th id="first_th">Print Receipt</th><th>Action</th><th>Date</th><th class="text-right">Qty</th><th class="text-right">Pack Qty</th><th class="text-right">Size</th><th class="text-right">Price</th><th>Before QOH</th><th>After QOH</th>'
                );
            } else if (data_by == 'adjustment') {
                $('#item_movement_by_date_selection_table > thead > tr').html(
                    '<th>Action</th><th>Date</th><th>Before QOH</th><th>Qty</th> <th>After QOH</th> <th class="text-right">Ref Number</th>'
                );
            } else if (data_by == 'adjustment') {
                $('#item_movement_by_date_selection_table > thead > tr').html(
                    '<th>Action</th><th>Date</th><th>Before QOH</th><th>Qty</th> <th>After QOH</th> <th class="text-right">Ref Number</th>'
                );
            } else if (data_by == 'openingpos') {
                $('#item_movement_by_date_selection_table > thead > tr').html(
                    '<th>Action</th><th>Date</th><th>Before QOH</th><th>Qty</th> <th>After QOH</th> <th class="text-right">Ref Number</th>'
                );
            } else if (data_by == 'phoneadjustment') {
                $('#item_movement_by_date_selection_table > thead > tr').html(
                    '<th>Action</th><th>Date</th><th>Before QOH</th><th>Qty</th> <th>After QOH</th><th class="text-right">Ref Number</th>'
                );
            } else if (data_by == 'openingqohphone') {
                $('#item_movement_by_date_selection_table > thead > tr').html(
                    '<th>Action</th><th>Date</th><th>Qty</th><th class="text-right">Ref Number</th>');
            }


            $.getJSON(item_movement_data_url, function(result) {

                // $("div#divLoading").addClass('show');

                var html = '';
                $('#item_movement_by_date_selection_table > tbody').empty();

                if (result.length) {
                    var total_qty = total_amount = 0;
                    $.each(result, function(i, v) {
                        html += '<tr>';
                        if (data_by == 'sold') {
                            html += '<td><button data-idettrnid="' + v.idettrnid +
                                '" data-isalesid="' + v.isalesid +
                                '" class="btn btn-info print-sales" style="background-color:#286fb7; color:#fff;"><i class="fa fa-print"></i> Print</button></td>';
                        }
                        html += '<td>';
                        if (data_by == 'sold') {
                            html += 'Sales';
                        } else if (data_by == 'receive') {
                            html += 'Receive';
                        } else if (data_by == 'openingqoh') {
                            html += 'Opening Qoh';
                        } else if (data_by == 'adjustment') {
                            if (v.vtype == 'Conv Case') {
                                html += 'Conv. Case to Unit';
                            } else {
                                html += v.vtype;
                            }

                        } else if (data_by == 'phoneadjustment') {
                            html += 'Phone Adjustment';

                        } else if (data_by == 'openingqohphone') {
                            html += 'Opening QoH Phone';

                        } else if (data_by == 'openingpos') {
                            html += 'Opening Qoh WEB';

                        }


                        html += '</td>';
                        html += '<td>';
                        html += v.ddate;
                        html += '</td>';
                        if (data_by == "adjustment" || data_by == "phoneadjustment" || data_by ==
                            "openingpos") {
                            html += '<td>';
                            html += parseInt(v.beforeQOH);
                            html += '</td>';
                        }

                        html += '<td class="text-right">';
                        html += parseInt(v.items_count);
                        total_qty += parseInt(v.items_count);
                        html += '</td>';
                        if (data_by == "adjustment" || data_by == "phoneadjustment" || data_by ==
                            "openingpos") {
                            html += '<td>';
                            html += parseInt(v.afterQOH);
                            html += '</td>';
                        }
                        if (data_by != "adjustment" && data_by != "qohupdate" && data_by !=
                            "invreset" && data_by != "openingqoh" && data_by != "perentchild" &&
                            data_by != "childparent" && data_by != "phoneadjustment" && data_by !=
                            "openingqohphone" && data_by != "openingpos" && data_by != "receive") {
                            html += '<td class="text-right">';
                            html += parseInt(v.npack);
                            html += '</td>';
                            html += '<td class="text-right">';
                            html += v.size;
                            html += '</td>';
                            html += '<td class="text-right">';
                            html += parseFloat(v.total_price).toFixed(2);
                            total_amount += parseFloat(v.total_price).toFixed(2);;
                            html += '</td>';
                            html += '<td class="text-right">';
                            html += v.before_sold_qoh;
                            html += '</td>';
                            html += '<td class="text-right">';
                            html += v.after_sold_qoh;
                            html += '</td>';
                        }
                        if (data_by == 'receive') {
                            html += '<td class="text-right">';
                            html += parseInt(v.npack);
                            html += '</td>';
                            html += '<td class="text-right">';
                            html += v.size;
                            html += '</td>';
                            html += '<td class="text-right">';
                            html += parseFloat(v.total_price).toFixed(2);
                            total_amount += parseFloat(v.total_price).toFixed(2);;
                            html += '</td>';
                            html += '<td class="text-right">';
                            html += v.ponumber;
                            html += '</td>';
                            html += '<td class="text-right">';
                            html += v.before_rece_qoh;
                            html += '</td>';
                            html += '<td class="text-right">';
                            html += v.after_rece_qoh;
                            html += '</td>';
                        }
                        if (data_by == 'adjustment') {
                            html += '<td class="text-right">';
                            html += v.vrefnumber;
                            html += '</td>';
                        }

                        if (data_by == 'openingqoh') {
                            html += '<td class="text-right">';
                            html += v.vrefnumber;
                            html += '</td>';
                        }

                        if (data_by == 'phoneadjustment') {
                            html += '<td class="text-right">';
                            html += v.vrefnumber;
                            html += '</td>';
                        }
                        if (data_by == 'openingqohphone') {
                            html += '<td class="text-right">';
                            html += v.vrefnumber;
                            html += '</td>';
                        }
                        if (data_by == 'openingpos') {
                            html += '<td class="text-right">';
                            html += v.vrefnumber;

                            html += '</td>';
                        }

                        html += '</tr>';
                    });
                    if (data_by == 'sold') {
                        html += '<tr><th colspan="3" class="text-right">Total</th><th class="text-right">' +
                            total_qty + '</th><th></th><th></th><th class="text-right">' + parseFloat(
                                total_amount).toFixed(2) + '</th></tr>';
                    } else if (data_by == 'receive') {
                        html += '<tr><th colspan="2" class="text-right">Total</th><th class="text-right">' +
                            total_qty + '</th><th></th><th></th><th class="text-right">' + parseFloat(
                                total_amount).toFixed(2) + '</th><th></th><th></th><th></th></tr>';
                    } else if (data_by == 'adjustment') {
                        html += '<tr><th colspan="3" class="text-right">Total</th><th class="text-right">' +
                            total_qty + '</th><th></th><th></th></tr>';
                    }
                    console.log(html);
                    $('#item_movement_by_date_selection_table > tbody').append(html);

                } else {

                    $('#item_movement_by_date_selection_table > tbody').append(
                        '<tr><td class="text-center" colspan="9">Sorry no data found!</td> </tr>');
                }
                $('#item_movement_by_date_selection').show();
                $('#item_movement_by_date_selection_table').show();

                // $("div#divLoading").removeClass('show');

            });

        });

        $(document).on('change', 'select[name="search_by_item"]', function(event) {
            event.preventDefault();

            if ($(this).val() == 'sold') {
                $('#item_movement_by_date_selection_table').hide();
                $('#item_movement_by_date_selection_table #first_th').show();
            } else {
                $('#item_movement_by_date_selection_table').hide();

                $('#item_movement_by_date_selection_table #first_th').hide();
            }
        });
    </script>

    <script type="text/javascript">
        $(document).on('click', '.print-sales', function(event) {
            event.preventDefault();

            var isalesid = $(this).attr("data-isalesid");
            var idettrnid = $(this).attr("data-idettrnid");

            var item_movement_print_data_url = '<?php echo $data['item_movement_print_data']; ?>';

            item_movement_print_data_url = item_movement_print_data_url.replace(/&amp;/g, '&');

            item_movement_print_data_url = item_movement_print_data_url + "?isalesid=" + isalesid + "&idettrnid=" +
                idettrnid;

            // $("div#divLoading").addClass('show');

            $.ajax({
                url: item_movement_print_data_url,
                type: 'GET',
            }).done(function(response) {

                var response = $.parseJSON(response); //decode the response array

                if (response.code == 1) {
                    // $("div#divLoading").removeClass('show');
                    $('.modal-body').html(response.data);
                    $('#view_salesdetail_model').modal('show');

                } else if (response.code == 0) {
                    alert('Something Went Wrong!!!');
                    // $("div#divLoading").removeClass('show');
                    return false;
                }
            });

        });
    </script>

    <div class="modal fade" id="view_salesdetail_model" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body" id="printme">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="itemBtnPrint">Print</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var printpage_url = '<?php echo $data['printpage']; ?>';
            printpage_url = printpage_url.replace(/&amp;/g, '&');

            $("#itemBtnPrint").printPage({
                url: printpage_url,
            });
        });
    </script>


<style>
    .th_color{
        background-color: #474c53 !important;
        color: #fff;
    }
    table, .promotionview {
        width: 100% !important;
        position: relative;
        left: 0%;

    }
    .table .table {
        background-color: #f8f9fa;
    }

    .th_white_color{
        background-color: #fff;
        border-top: 3px solid ##cccccc;
    }
    h6 {
    width: 100%;
    text-align: left;
    border-bottom: 2px solid;
    line-height: 0.1em;
    margin: 10px 0 20px;
    color:#286fb7;
    }

    h6 span {
        background:#f8f9fa!important;
        padding:0 10px;
        color:#286fb7;
    }
    .rcorner {
    border-radius:9px;
    }

</style>


@endsection
