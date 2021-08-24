<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "Promotion Report" ?></h3>
    <hr style="height:1px; background-color:black;">
   
</div> 

<div class="panel-body">
        
        <div class="row">
          <div class="col-md-6 pull-left">
            <p><b>From: </b><?php echo $p_start_date; ?> To <?php echo $p_end_date; ?></p>
          </div>
        </div>
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

     <table class="table table-bordered table-striped table-hover">
                   
                   
                           <tr>
                            <th>DATE</th>
                            <th>SKU</th>
                            <th>ITEM NAME</th>
                            <th>PRICE</th>
                            <th>DISCOUNTED AMOUNT</th>
                            <th>DISCOUNTED PRICE</th>
                            <th>QTY</th>
                            <th>TRANSACTION #</th>
                           </tr>
                           
                           <?php $total=0;?>
                            <?php foreach($promo_data as $v){
                                $total= $total+$v->DISCOUNTED_AMOUNT; 
                             
                                } ?>
                             <tr>
                                    
                                <th> GRAND TOTAL</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><?php echo "$",number_format($total,2); ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                </tr>
                         <?php 
                            
                                $count = 0; 
                                foreach($promo_data as $v){?>
                                
                                    <tr>
                                            
                                           <td><?php echo isset($v->TDATE) ? $v->TDATE: ""; ?></td>
                                            
                                       
                                           <td><?php echo isset($v->SKU) ? $v->SKU: 0.00; ?></td>
                                            <td><?php echo isset($v->ITEMNAME) ? $v->ITEMNAME: ""; ?></td>
                                            <td><?php echo "$",isset($v->PRICE) ? $v->PRICE: 0.00; ?></td>
                                            <td><?php echo "$",isset($v->DISCOUNTED_AMOUNT) ? $v->DISCOUNTED_AMOUNT: 0.00; ?></td>
                                            
                                            <td><?php echo isset($v->DISCOUNTED_PRICE) ? $v->DISCOUNTED_PRICE: 0.00; ?></td>
                                            <td><?php echo isset($v->QTY) ? $v->QTY : 0; ?></td>
                                            
                                            <td><?php echo isset($v->TRANSACTION_NO) ? $v->TRANSACTION_NO: 0; ?></td>
                                    </tr>
                                <?php } ?>
                    
                         
                   
                   
                   
                       </table>
          

<style type="text/css">

/*table {            */
/*   page-break-after: always;*/
/*   page-break-inside: avoid;*/
/*   break-inside: avoid;*/
/*}*/

tr.applyBorder{border:1pt solid;}

</style>