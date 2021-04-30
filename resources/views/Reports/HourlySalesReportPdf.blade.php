<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "Hourly Sales Report" ?></h3>
    <hr style="height:1px; background-color:black;">
   
</div> 

<div class="panel-body">
        
        
        <div class="row">  
          <div class="col-md-6 pull-left">
            <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
          </div>
        </div>
        
        <div class="row">
          
          <div class="col-md-6 pull-left">
            <p><b>Store Address: </b><?php echo $store[0]->vaddress1 ?></p>
          </div>
          
        </div>
        
        <div class="row">
          
          <div class="col-md-6 pull-left">
            <p><b>Store Phone: </b><?php echo $store[0]->vphone1; ?></p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 pull-left">
             <p><b>Date: </b><?php echo isset($p_start_date) ? $dates_selected : date("m-d-Y"); ?></p>
          </div>
        </div>


               <div class="row">
                   <div class="col-md-12 table-responsive">
                      
                       <table class="table table-bordered table-striped table-hover">
                         <tr>
                            <td><b>Hourly Sales</b></td>
                            <td class="text-right"><b>Amount</b></td>
                            <td><b>Transactions</b></td>
                        </tr>
                        <?php foreach($report_hourly as $r) { ?>
                            <tr>
                                <td><?php echo isset($r['Hours']) ? $r['Hours']: 0; ?></td>
                                <td class='text-right'><?php echo "$",isset($r['Amount']) ? $r['Amount']: 0; ?></td>
                                <td><?php echo isset($r['Number']) ? $r['Number']: 0; ?></td>
                            </tr>
                        <?php } ?>
                   
                     
                   </div>
               </div>
        
       </tr>

   </tbody>
</table>

<style type="text/css">

         
   /*.table {            */
   /*     page-break-after: avoid;*/
   /*     page-break-inside: always;*/
        /*break-inside: avoid;*/
   /* }*/


tr.applyBorder{border:1pt solid;}

</style>