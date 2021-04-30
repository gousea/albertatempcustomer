<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "Credit Card Report" ?></h3>
    <hr style="height:1px; background-color:black;">
   
</div> 


<div class="panel-body" >
        
        <div class="row">
          <div class="col-md-6 pull-left">
            <p><b>From: </b><?php echo $start_date; ?> To <?php echo $end_date; ?></p>
          </div>
        </div>
        
       <div class="row">  
          <div class="col-md-6 pull-left">
            <p><b>Store Name: </b>{{ session()->get('storeName') }}</p>
          </div>
        </div>
        
        <div class="row">
          
          <div class="col-md-6 pull-left">
            <p><b>Store Address: </b><?php echo $Store_Adress; ?></p>
          </div>
        </div>
        
        <div class="row">
          
          <div class="col-md-6 pull-left">
            <p><b>Store Phone: </b><?php echo $Store_Phone; ?></p>
          </div>
        </div>  
        


               <div class="row">
                   <div class="col-md-12 table-responsive">
                      
                       <table class="table table-bordered table-striped table-hover">
                          <tr>
                               <th>Date</th>
                               <th>Time</th>
                               <th>LAST FOUR OF CC</th>
                               <th>APPROVAL CODE</th>
                               <th>AMOUNT</th>
                               <th>CARD TYPE</th>
                               
                           </tr>
                         
                           <?php 
                               
                               foreach($card_data as $v){ ?>
                           
                                       <tr>
                                           
                                           <td style="width:80px;"><?php echo isset($v->date) ? $v->date: ""; ?></td>
                                           <td><?php echo isset($v->time) ? $v->time: ""; ?></td>
                                           <td><?php echo isset($v->last_four_of_cc) ? $v->last_four_of_cc: 0.00; ?></td>
                                           
                                           <td><?php echo isset($v->approvalcode) ? $v->approvalcode: 0.00; ?></td>
                                           <td><?php echo isset($v->amount) ? $v->amount: 0.00; ?></td>
                                           <td><?php echo isset($v->vcardtype) ? $v->vcardtype: 0.00; ?></td>
                                       
                                       </tr>
                           <?php }?>
                   
                     
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
