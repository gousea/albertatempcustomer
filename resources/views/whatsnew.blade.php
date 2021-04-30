
<?php
error_reporting(E_ERROR | E_PARSE);
//echo $file_location = getcwd();
$version=$_GET["version"];

$file1="/home/albertapayments/albertapos/public_html/customer/storage/pos_version_info/VerInfo$version.txt";

$myfile = fopen($file1, "r") or die(" $version Version Not available");
$filesize=filesize($file1);
$lines=fread($myfile,$filesize);
$line= explode(PHP_EOL, $lines);

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>POS VERSION</title>
<base href="https://customer.albertapayments.com/" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<script src="view/javascript/common.js" type="text/javascript"></script>
<link rel="stylesheet" href="view/stylesheet/jquery-ui.css">
<script src="view/javascript/jquery/jquery-ui.js"></script>

<style>
.dropbtn {
    background-color: #ffffff;
    color: white;
    padding: 13px;
    font-size: 14px;
    border: none;
    cursor: pointer;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #ffffff;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    overflow-y: auto;
    height: 150px;
}

.dropdown-content span {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content span:hover {background-color: #ffffff;cursor: pointer;color:#fff;}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #ffffff;
}
#header{
    background-color:#03A9F4;
}
</style>

</head>
<body>
<div id="divLoading" class="show"></div>
<div id="container">
<header id="header" class="navbar navbar-static-top" style ="height:50px";>
  <div class="navbar-header">
      <?php $version=$_GET["version"];?>
        <a href="https://customer.albertapayments.com/index.php?route=common/dashboard" class="navbar-brand"><?php echo $line[0] ;?></a></div>    
  </header>
<!---  pos version-->

<div style="margin-left:70px;">

<!--<h1> pos versions <?php echo $version ?></h1>-->
<?php
//echo nl2br(file_get_contents( "$file1" ));

//echo $line;  die;
foreach($line as $key=>$ln){
         if($key==0){
             continue;
         }
         if(trim($ln)==='New Functionalities added' || trim($ln)==='Bugs Resolved'){
          echo "<h3>" ,$ln ,"</h3>"; 
          echo "<br>";
         }

         else{
          echo $ln ; 
          echo "<br>";
         }
          
 }
fclose($myfile);

        // $file=$file1;
        // $linecount = 0;
        // $handle = fopen($file, "r");
        
        // while(!feof($handle)){
        //   $line = fgets($handle);
        //   $lines= explode(' . ', $line);
        //   foreach($lines as $ln){
        //      //if($ln=="New Functionalities added"){
        //       echo $ln ; 
        //       echo "<br>";
          
        //     }
            
        //   $linecount++;
        // }
        
        // fclose($handle);
?>
</div>
















<script type="text/javascript">
$(document).ready(function(){   
    /*to top*/
        $(window).scroll(function(){
        if ($(this).scrollTop() > 200) {
            $('.scrollToTop').fadeIn();
        } else {
            $('.scrollToTop').fadeOut();
        }
    }); 
    //Click event to scroll to top
    $('.scrollToTop').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    }); 
});
</script>

<script type="text/javascript">
    $(document).on('click', '.change_store', function(event) {
        
        var store_id = $(this).attr('data-store-id');
        $('form#form_store_change #change_store_id').val(store_id);
        $('form#form_store_change').submit();

    });
</script>

<script>
function openNavStore() {
    document.getElementById("mySidenavStore").style.width = "250px";
    document.getElementById("mySidenavReports").style.width = "0";
}

function closeNavStore() {
    document.getElementById("mySidenavStore").style.width = "0";
}

$(document).on('click', '.di_store_name,.di_reports', function(event) {
    event.preventDefault();
    /* Act on the event */
});

function openNavReports() {
    document.getElementById("mySidenavReports").style.width = "250px";
    document.getElementById("mySidenavStore").style.width = "0";
}

function closeNavReports() {
    document.getElementById("mySidenavReports").style.width = "0";
}

$(document).on('click', '.di_store_name', function(event) {
    event.preventDefault();
    /* Act on the event */
});


$(window).scroll(function(){
    if ($(this).scrollTop() > 52) {
        $('.sidenav').css('top','0px');
    } else {
        $('.sidenav').css('top','45px');
    }
});
</script>

<script type="text/javascript">
    $(document).on('keyup', '#store_search', function(event) {
        event.preventDefault();
        $('p.change_store').hide();
        var txt = $(this).val();

        if(txt != ''){
          $('p.change_store').each(function(){
            if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
              $(this).show();
            }
          });
        }else{
          $('p.change_store').show();
        }
      });

    $(document).on('keyup', '#report_search', function(event) {
        event.preventDefault();
        $('a.report_name').hide();
        var txt = $(this).val();

        if(txt != ''){
          $('a.report_name').each(function(){
            if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
              $(this).show();
            }
          });
        }else{
          $('a.report_name').show();
        }
      });
</script>

<style type="text/css">
    #header .navbar-header a.navbar-brand{
        color: #fff;
        font-size: 20px;
        font-weight: bold;
    }
</style>

<div id="forgottenModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <!-- <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div> -->
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 text-center">
           <!-- <p style="font-size: 15px"><b>To Reset Your Password Call On :</b><a href="tel:+18885026650" style="font-size: 15px">&nbsp;&nbsp;1-888-502-6650</a></p>
          
          --></div>
        </div>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
    $(document).on('click', '#forgotten_link', function(event) {
        event.preventDefault();
        $('#forgottenModal').modal('show');
    });

    $(document).on('click', '.editable_all_selected', function(event) {
      event.preventDefault();
      $(this).select();
    });
</script>

<!-- rotating logo -->

<script type="text/javascript">

  $(document).on('click', '.breadcrumb li a, ul.pagination > li, #header > ul > li:eq(2),#header > ul > li:eq(3), #header > ul > li:eq(4), #mySidenavStore > div.side_content_div > div.side_inner_content_div > p:not(:eq(0)), #mySidenavReports > div.side_content_div > div.side_inner_content_div > p:not(:eq(0)), .edit_btn_rotate, .cancel_btn_rotate, .add_new_btn_rotate, .save_btn_rotate, .show_all_btn_rotate', function(event) {
    $("div#divLoading").addClass('show');
  });

  $(document).on('click', '#menu li a', function(event) {
    if (!$(this).hasClass("parent")) {
      $("div#divLoading").addClass('show');
    }
  });
</script>

<!-- rotating logo -->

<script type="text/javascript">
  $(document).on('paste', '.nordqty_class, .npackqty_class, input[name="iqtyonhand"], .ndebitqty_class, .ntransferqty_class', function(event) {
    event.preventDefault();
    
  });
</script>

<script type="text/javascript">
  function isValid(str) {
      return !/[~`!@#$%\^&*()+=\-\[\]\\';._,/{}|\\":<>\?]/g.test(str);
  }
</script>
<script type="text/javascript">
  $(window).load(function() {
    $("div#divLoading").removeClass('show');
  });
</script>