<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPRAYE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Global stylesheets -->    
    <!-- <link href="<?= base_url('assets/admin') ?>/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css"> -->
    <link href="<?= base_url('assets') ?>/daily_invoice/css/daily_invoice.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/admin') ?>/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/admin') ?>/assets/css/core.css" rel="stylesheet" type="text/css">
    <!-- <link href="<?= base_url('assets/admin') ?>/assets/css/components.css" rel="stylesheet" type="text/css"> -->
    <!-- <link href="<?= base_url('assets/admin') ?>/assets/css/colors.css" rel="stylesheet" type="text/css"> -->
    <!-- /global stylesheets -->
    <!-- Core JS files --> 
   <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>    
    <!-- Add tokenizer.js file to head -->
   <script language="javascript" src="<?= BASYS_URL ?>tokenizer/tokenizer.js"></script>   
   <script src="<?= base_url() ?>/assets/popup/js/sweetalert2.all.js"></script>
   <!-- /theme JS files -->
   <style type="text/css">      
.login_new{
    background: #01669a;
    color: #fff;
    margin-bottom: 0px !important;
    border: 0 !important;
    border-radius: 0PX !important;
}
.error {
color: rgb(221, 51, 51) !important;
margin-bottom: 0px;
}
.login-container .page-container {
padding-top: 25px !important;
position: static;
}
a {
    font-weight: bold;
}
 #loading {
   width: 100%;
   height: 100%;
   top: 0;
   left: 0;
   position: fixed;
   display: none;
   opacity: 0.7;
   background-color: #fff;
   z-index: 9999;
   text-align: center;
   }
   #loading-image {
   position: absolute;
   left: 50%;
   top: 50%;
   width: 10%;
   z-index: 100;
   }
.text-center.no-margin {
  display: block;
}
.panel.panel-body.login-form {
  border: 1px solid #ccc !important;
}
</style>
</head>
<body>
<style type="text/css">
   #loading {
   width: 100%;
   height: 100%;
   top: 0;
   left: 0;
   position: fixed;
   display: none;
   opacity: 0.7;
   background-color: #fff;
   z-index: 99;
   text-align: center;
   }  
   #loading-image {
   position: absolute;
   left: 50%;
   top: 50%;
   width: 10%;
   z-index: 100;
   }
   .btn-group {
   margin-left: -4px !important;
   margin-top: -1px !important;
   }
   .dropdown-menu {
   min-width: 80px !important;
   }
   .myspan {
   width: 55px;
   }
   .label-warning, .bg-warning {
   background-color :#A9A9A9;
   background-color: #A9A9AA;
   border-color: #A9A9A9;
   }
   .toolbar {
   float: left;
   padding-left: 5px;
   }
   .dataTables_filter {
   /*text-align: center !important;*/
   margin-left: 60px !important;
   }
   #invoicetablediv{
   padding-top: 20px;
   }
   .Invoices .dataTables_filter input {
    margin-left: 11px !important;
    margin-top: 8px !important;
    margin-bottom: 5px !important;
}
.tablemodal > tbody > tr > td, .tablemodal > tbody > tr > th, .tablemodal > tfoot > tr > td, .tablemodal > tfoot > tr > th, .tablemodal > thead > tr > td, .tablemodal > thead > tr > th {
  border-top: 1px solid #ddd;
}
.label-till , .bg-till  {
    background-color: #36c9c9;
    background-color: #36c9c9;
    border-color: #36c9c9;
}
#mytbl {
    border: 1px solid 
    #6eb1fd;
    border-radius: 4px;
}
</style>
<!-- Content area -->
<div id="daily_invoice_list" class="content invoicessss">   
   <div class="panel-body">   
   <div class="login-form"><center>                       
      <?php 
         if (!empty($setting_details->company_logo)) { ?>
            <img height="50"  src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$setting_details->company_logo ?>">
         <?php } else { ?>
               <img src="<?= base_url() ?>/assets/admin/image/Spraye_Logo_Web_White.png "  alt="">
         <?php  }  ?></center></div>
   <span class=" page-name">Invoice Details</span>
      <div id="invoicetablediv">          
      <div class="row">
         <div class="col-sm-12">
            <strong>Customer Name:</strong>
             <?php echo $invoice_details[0]->first_name.' '.$invoice_details[0]->last_name  ?>
         </div>
         <div class="col-sm-12">
            <strong>Customer Email:</strong>
            <?php echo $invoice_details[0]->email  ?>
         </div>
         <div class="col-sm-12">
            <strong>Date:</strong>
            <?php echo date('m-d-Y',strtotime($invoice_details[0]->invoice_date))  ?>
         </div>
      </div>
         <div  class="table-responsive table-spraye">
            <table  class="table datatable-button-init-custom">
               <thead>
                  <tr>                     
                     <th>Invoice</th>
                     <th>Property</th>
                     <th>Program</th>
                     <th>Job</th>
                     <th>Amount</th>                     
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($invoice_details)) { 
                     $total_amount = 0;
                     foreach ($invoice_details as $value) {?>      
                  <tr>                     
                     <td><?= $value->invoice_id; ?></td>
                     <td><?php echo $value->property_title ?></td>
                     <td><?php echo $value->program_name ?></td>
                     <td><?php echo $value->job_name ?></td>
                      <?php 
                        $total_tax_amount = getAllSalesTaxSumByInvoice($value->invoice_id)->total_tax_amount;                        
                        // $amount = $value->cost+$total_tax_amount-$value->partial_payment;
                        $amount = $value->total_amount_minus_partial-$value->partial_payment;
                        $total_amount = $total_amount + $amount;
                       ?>
                     <td><?= '$ '.number_format($amount,2) ?></td>
                  </tr>
                  <?php  } ?>
                   <tr>
                        <td colspan="4"></td>
                        <td  class="text-left">
                           <strong>Total Amount:</strong> <?php echo '$ '.number_format($total_amount,2) ?>
                        </td>
                   </tr>     
               
               
              <?php } ?>
               </tbody>
            </table>
         </div>
         <div class="row">                        
            <div class="make-payment-btn-container" >
               <a href="<?= base_url("welcome/dailyPayment/").$hashstring?>" id="btn-login" class="btn btn-block" style="background-color: #47a447;color: #fff" >Make Payment</a>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- /form horizontal -->
<div id="modal_default" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title" style="float: left;">Product Details</h5>
         </div>
         <div class="modal-body" id="productdetails">
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<!-- /content area -->
<script type="text/javascript">
   function  filterSearch(status) {     
     
     $.ajax({
           type: "GET",
           url: "<?= base_url('admin/Invoices/getAllInvoiceBySearch/')?>"+status,
     }).done(function(data){
       $('#invoicetablediv').html(data);
         $('.datatable-button-init-custom').DataTable({
         language: {
            search: '<span></span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
         },
         dom: 'l<"toolbar">frtip',
         initComplete: function(){     
            $("div.toolbar")
              .html('<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li onclick="filterSearch(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li onclick="filterSearch(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li><li onclick="filterSearch(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li onclick="filterSearch(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>  <li onclick="filterSearch(3)" ><a href="#"><span class="status-mark bg-till position-left"></span> Partial</a></li>   </ul></div>&nbsp;&nbsp;<div class="btn-group">   <button disabled="disabled" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" id="bulk_status_change" >Change Status <span class="caret"></span></button>   <ul class="dropdown-menu dropdown-menu-right">      <li onclick="bulkStatusChange(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li>      <li onclick="bulkStatusChange(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>      <li onclick="bulkStatusChange(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li> </ul></div>&nbsp;&nbsp;<button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">Send By Email</button>&nbsp;&nbsp;<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>');           
        }       
     });   
       $('#allMessage').prop('disabled', true);
       $('#allPrint').prop('disabled', true);       
     });
   }
</script>


</body>