<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<div id="content">
  <div class="container-fluid">
    <div class="" style="margin-top:2%;">
      <div class="text-center">
        <h3 class="panel-title" style="font-weight:bold;font-size:24px;"><?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-6 pull-left">
            
          </div>
          <div class="col-md-6 pull-right">
            <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
          </div>
        </div>
        <hr>
         
        <?php if(isset($reports) && count($reports['item_data']) > 0){ ?>
        <div class="col-md-12">
          <div class="table-responsive"> 
            <table class="table" style="border: 1px solid #ccc;">
                  
                <thead>
                  <tr>
                    <th colspan="7" class="text-center text-uppercase">
                        <b style="font-size: 16px;">
                            <?php echo $reports['item_data'][0]['vitemname']; ?> [QOH: CASE <?php echo $reports['item_data'][0]['IQTYONHAND']; ?> ]
                        </b>
                    </th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    $current_year = date('Y'); 
                    $previous_year = date("Y",strtotime("-1 year"));
                  ?>
                  <tr>
                    <!--<td colspan="1" style="background-color: #fff;"></td>-->
                    <td colspan="1" class="text-left" style="background-color: #fff;border-top: none;">
                      <b class="text-uppercase text-info" style="font-size: 14px;">
                      <?php echo $previous_year;?> YTD SOLD - 
                      <?php 
                        echo !empty($reports['year_arr_sold'][$previous_year]['total_sold']) ? $reports['year_arr_sold'][$previous_year]['total_sold'] : '0' ;
                      ?>
                    </b>
                      </td>
                    
                    <!-- Adjustment Details -->
                    <td colspan="3" class="text-left" style="background-color: #fff;border-top: none;">
                        <b class="text-uppercase text-info" style="font-size: 14px;">
                            <?php echo $previous_year;?> YTD ADJUSTMENT - 
                            <?php echo !empty($reports['year_arr_adjustment'][$previous_year]['total_adjustment']) ? $reports['year_arr_adjustment'][$previous_year]['total_adjustment'] : '0' ;?>
                        </b>
                    </td>
                      
                    <td colspan="3" class="text-left" style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                      <b class="text-uppercase text-info" style="font-size: 14px;">
                      <?php echo $previous_year; ?> YTD RECEIVE -  
                      <?php
                        echo !empty($reports['year_arr_receive'][$previous_year]['total_receive']) ? $reports['year_arr_receive'][$previous_year]['total_receive']: '0' ;
                      ?>
                      </b>
                      </td>
                  </tr>
                  <tr>
                    <!--<th colspan="1" style="background-color: #fff;border-top: none;"></td>-->
                    <td colspan="1" class="text-left" style="background-color: #fff;border-top: none;">
                      <b class="text-uppercase text-danger" style="font-size: 14px;">
                      <?php echo $current_year;?> YTD SOLD - 
                      <?php 
                        echo !empty($reports['year_arr_sold'][$current_year]['total_sold']) ? $reports['year_arr_sold'][$current_year]['total_sold'] : '0' ;
                      ?>
                    </b>
                      </td>
                      
                      <!-- Adjustment Details -->
                    <td colspan="3" class="text-left" style="background-color: #fff;border-top: none;">
                        <b class="text-uppercase text-danger" style="font-size: 14px;">
                            <?php echo $current_year;?> YTD ADJUSTMENT - 
                            <?php echo !empty($reports['year_arr_adjustment'][$current_year]['total_adjustment']) ? $reports['year_arr_adjustment'][$current_year]['total_adjustment'] : '0' ;?>
                        </b>
                    </td>
                      
                      
                    <td colspan="3" class="text-left" style="background-color: #fff;border-top: none;border-right: 2px solid #cdd0d4;">
                      <b class="text-uppercase text-danger" style="font-size: 14px;">
                      <?php echo $current_year; ?> YTD RECEIVE - 
                      <?php
                        echo !empty($reports['year_arr_receive'][$current_year]['total_receive']) ? $reports['year_arr_receive'][$current_year]['total_receive']: '0' ;
                      ?>
                      </b>
                      </td>
                  </tr>
                  <tr>
                      <td colspan="1" style="border-right: 1px solid #cdd0d4;"></td>
                      <td colspan="3" class="text-center" style="border-right: 1px solid #cdd0d4;">Previous Year</td>
                      <td colspan="3" class="text-center" style="border-right: 2px solid #cdd0d4;">Current Year</td>
                  </tr>
                  <?php for($i = 1; $i <= 12; ++$i){ ?>
                    <tr>
                      <td colspan="1" style="border-right: 1px solid #cdd0d4;">
                        <b><?php echo DateTime::createFromFormat('!m', $i)->format('F'); ?></b>
                      </td>
                      <td colspan="3" style="border-right: 1px solid #cdd0d4;">
                        <?php if(!empty($reports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']) || !empty($reports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){?>
                          (<?php echo $previous_year;?>)&nbsp;
                        <?php } ?>

                        <?php if(!empty($reports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold'])){ ?>

                          SOLD (<?php echo (int)$reports['month_year_arr_sold'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']; ?>)
                          

                        <?php } else { ?>
                          &nbsp;
                        <?php } ?>
                        
                        <!-- Adjustment Deatils -->
                        <?php if(!empty($reports['month_year_arr_adjustment'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                            ADJ. (<?php echo (int)$reports['month_year_arr_adjustment'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; ?>)
                        <?php } else { ?>
                            &nbsp;
                        <?php } ?>

                        <?php if(!empty($reports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){ ?>

                          &nbsp;
                          Receive (<?php echo (int)$reports['month_year_arr_receive'][$previous_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive']; ?>)

                        <?php } else { ?>
                          &nbsp;
                        <?php } ?>

                      </td>
                      
                      <td colspan="3" style="border-right: 1px solid #cdd0d4;">
                        <?php if(!empty($reports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']) || !empty($reports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){?>
                          (<?php echo $current_year;?>)&nbsp;
                        <?php } ?>

                        <?php if(!empty($reports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold'])){ ?>

                          SOLD (<?php echo (int)$reports['month_year_arr_sold'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_sold']; ?>)
                          
                          
                        <?php } ?>
                        
                        <!-- Adjustment Details -->
                        <?php if(!empty($reports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment'])){ ?>
                            &nbsp;
                            ADJ. (<?php echo (int)$reports['month_year_arr_adjustment'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_adjustment']; ?>)
                        <?php } else { ?>
                            &nbsp;
                        <?php } ?>
                        
                        <?php if(!empty($reports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive'])){ ?>

                          &nbsp;
                          Receive (<?php echo (int)$reports['month_year_arr_receive'][$current_year][str_pad($i,2,"0",STR_PAD_LEFT)]['total_receive']; ?>)

                        <?php } else { ?>
                          &nbsp;
                        <?php } ?>
                      </td>
                    </tr>
                  <?php } ?>
                  
                </tbody>
              </table>
          </div>
        </div>
        <?php } ?>
        
      </div>
    </div>
  </div>
</div>