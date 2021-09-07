<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">




<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
</head>

<p style="text-align:left;margin-top:2%;margin-left:2%;"><img src="{{ public_path('image/logoreport.jpg') }}" alt="Pos logo" width="120" height="50"></p>
<div style="text-align: center; margin:0 auto;">
    <h3 class="panel-title" style="font-weight:bold;font-size:24px;margin-top:-6%;"><?php echo "Tax Report" ?></h3>
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
        <hr>
<?php if(isset($reports) && count($reports) > 0){ ?>
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <b><p><p style="float: left;">Non-Taxable Sales </p>
            <span style="float: right;"><?php echo "$", number_format((float)$reports['NONTAX'], 2) ; ?></span></p></b>
          </div>
        </div><br>
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <p><p style="float: left;">Taxable Sales (Tax1)</p>
            <span style="float: right;"><?php echo "$",number_format((float)$reports['Tax1Sales'], 2) ; ?></span></p>
          </div>
        </div><br>
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <p><p style="float: left;">Taxable Sales (Tax2)</p>
            <span style="float: right;"><?php echo "$", number_format((float)$reports['Tax2Sales'], 2) ; ?></span></p>
          </div>
        </div><br>
        
        <?php if(isset($reports['Tax3Sales'])){?>
        
         <div class="row">
          <div class="col-md-6 col-sm-6">
            <p><p style="float: left;">Taxable Sales (Tax3)</p>
            <span style="float: right;"><?php echo "$", number_format((float)$reports['Tax3Sales'], 2) ; ?></span></p>
          </div>
        </div>
        <?php }?>
        <div class="row">
          <div class="col-md-6 col-sm-6">
           <b> <p><p style="float: left;">Total Taxable Sales </p>
          <span style="float: right;"><?php echo "$",number_format((float)$reports['Tax1Sales'] + (float)$reports['Tax2Sales'] + (float)$reports['Tax3Sales'], 2) ; ?></span></p></b>
          </div>
        </div><br>
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <hr style="border-top: 2px solid #ccc;">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <p><p style="float: left;"><b>Net Sales</p>
            <span style="float: right;"><?php echo "$",$netsale =$reports['Tax1Sales']+$reports['Tax2Sales']+$reports['Tax3Sales']+$reports['NONTAX']; ?></span></p></b>
          </div>
        </div><br><br>
    
        <div class="row">
          <div class="col-md-6 col-sm-6">
           <p style="float: left;">Tax 1</p>
         
             <span style="float: right;"><?php echo "$",number_format((float)$reports['tax1'], 2) ; ?></span></p>
          </div>
        </div>
         <div class="row">
          <div class="col-md-6 col-sm-6">
            <p><p style="float: left;">Tax 2</p>
           

           <span style="float: right;"><?php echo "$",number_format((float)$reports['tax2'], 2); ?></span></p> 
            
          </div>
        </div>
        <?php if(isset($reports['tax3'])){?>
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <p><p style="float: left;">Tax 3</p>
           
           
           <span style="float: right;"><?php echo "$",number_format((float)$reports['tax3'], 2); ?></span></p> 
            
          </div>
        </div>
        <?php }?>
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <p style="float: left;"><b>Total Tax</p>
            
            
            <span style="float: right;"><?php echo "$",$totaltax= number_format((float)$reports['TAX'], 2) ; ?></span></p>
            
            </b>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <hr style="border-top: 2px solid #ccc;">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-6">
               <p style="float: left;"><b>Gross Sales:</p>
           <span style="float: right;"><?php echo "$",number_format((float)($reports['NNETTOTAL']), 2); ?></span></b></p>
          </div>
        </div>
        <br><br>
        <br><br>
        <?php }else{ ?>
          <?php if(isset($p_start_date)){ ?>
            <div class="row">
              <div class="col-md-12"><br><br>
                <div class="alert alert-info text-center">
                  <strong>Sorry no data found!</strong>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>