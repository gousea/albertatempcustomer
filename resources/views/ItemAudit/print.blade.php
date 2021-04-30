<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style type="text/css">
   @page { size: landscape; }
   @page :left {
      margin-left: 1px;
      margin-right: 1px;
   }

   @page :right {
      margin-left: 1px;
      margin-right: 1px;
   }
 </style>
<div id="content">
  <div class="container-fluid">
    <div class="" style="margin-top:2%;">
      <div class="text-center">
        <h3 class="panel-title" style="font-weight:bold;font-size:24px;"><?php echo "Item Audit"; ?></h3>
      </div>
      <div class="panel-body">
        
       

        <?php if(isset($list) ){ ?>
        <div class="row">
          <div class="col-md-12">
          <br>
            <table class="table table-bordered" style="border:none;font-size:1vw;">
              <thead>
                <tr style="border-top: 1px solid #ddd;">
                 
                  <th >SKU</th>
                  <th >Item Name</th>
                  <th >Modification</th>
                  <th class="text-right">Before</th>
                  <th class="text-right">Current</th>
                  <th class="text-right">Location</th>
                  <th class="text-right">Date</th>
                  <th >Time</th>
                  <th >User</th>
                 
                </tr>
              </thead>
              <tbody>
                  
                  <?php foreach ($list as $k => $v){ ?>
                  <?php if ($v['beforem']!=$v['afterm']){ ?>
                     <tr>
                    
                    <td><?php echo $v['vbarcode'];?></td>
                   
                    <td><?php echo $v['vitemname'];?></td>
                    <td><?php echo $mtype;?></td>
                     <?php if (is_numeric($v['beforem'])) { ?>
                              <td><?php echo number_format($v['beforem'],2);?></td>
                              <td><?php echo number_format($v['afterm'],2);?></td>
                     <?php }  else { ?>
                         <td><?php echo $v['beforem'];?></td>
                        <td><?php echo $v['afterm'];?></td>
                   <?php  } ?>
                     
                        
                    
                    <td><?php echo  "Web";?></td>
                     <?php $date = date('m-d-Y',strtotime($v['historydatetime']));
                     $time = date('H:i:s',strtotime($v['historydatetime']));?>
                    <td><?php echo $date ;?></td>
                    <td><?php echo $time;?></td>
                    <td><?php echo $v['userid'];?></td>
                    </tr>
                  <?php } ?>
                  <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
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
</div>