<style type="text/css">
	#activeInvoices_processing{
		top:85%!important;
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
   padding: 2px 2px;
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
   .label-refunded, .bg-refunded {
      background-color : #fd7e14;
      border-color : #fd7e14;
   }
   .toolbar {
   float: left;
   padding-left: 5px;
	margin-bottom: 5px;
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
	.dt-buttons {
		display: inline-block;
		margin: 0 10px 20px 10px;
	}
</style>

<?php $years = array_reverse(range(2020, strftime("%Y", time()) - 1)); ?>
<!-- Content area -->
<div class="content invoicessss">
   <div id="loading" >
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
   </div>
   <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
      <div class="row cx-dt">
         <div class="col-md-3 col-sm-3 col-12">
            <div class="service-bols">
               <h3 class="ser-head">Total Revenue (YTD)</h3>
               <p class="text-success ser-num">$ <?php if(!empty($total)) {echo number_format($total['total_revenue'],2); } ?>
              </p>
            </div>
         </div>
         <div class="col-md-3 col-sm-3 col-12">

         </div>
      </div>

      <div id="invoicetablediv">
         <div  class="table-responsive table-spraye">
            <table class="table" id="activeInvoices">
               <thead>
                  <tr>
                     <th><input type="checkbox" id="select_all"/></th>
                     <th>Invoice</th>
                     <th>Customer Name</th>
                     <th>Email</th>
                     <th>Amount</th>
                     <th>Balance Due</th>
                     <th>Payment Method</th>
                     <th>Payment Info</th>
                     <th>Sent Status</th>
					 <th>Payment Status</th>
                     <th>Invoice Date</th>
                     <th>Sent Date</th>
                     <th>Opened Date</th>
                     <th>Payment Date</th>
                     <th>Refund Date</th>
                  </tr>
               </thead>
            </table>
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
<!-- CHECK FOR INCOMING AGING REPORT PARAMS -->

<!-- Paid Modal
    Created By: Alvaro Munoz
    Objective: Multiple invoice payments.
    Called from pay button when some invoice or invoices are selected.

-->
<div id="modal_theme_multiple_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Pay Total Amount Due</h6>
            </div>
            <form id="add_paid_payment_form" action="" method="post" style="padding: 10px;" >
                <input type="hidden" class="form-control" name="selected_invoices" id="selectedInvoices" placeholder="" value="" >
                <input type="hidden" class="form-control" name="selected_amounts" id="selectedAmountsDue" placeholder="" value="" >
                <!-- Gets inserted on the button onclick function that calls for the modal (pay)-->
<!--                <div id="past_payments_"></div>-->

                <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Partial Payments Already Applied:</label>
                <input type="text" class="form-control" name="past_payments"  id="past_payments" placeholder="" value="" style="margin-bottom: 5px;" readonly >
                <div style="height: 10px;"></div>


                <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Total Amount Due:</label>
                <input type="text" class="form-control" name="partial_payment" id="partial_payment" value="" readonly>
                <input type="hidden" id="invoice_id" name="invoice_id" value="<?= $invoice_details->invoice_id ?>">
                <input type="hidden" id="payment_status" name="payment_status" value="2">
                <input type="hidden" id="total_due" name="total_due" value="0">
                <div style="height: 10px;"></div>
                <select class="bootstrap-select form-control" name="payment_method" id="paid_modal_select" style="border: 1px solid #12689b; margin-top: 5px;">
                    <option value="0">Select A Payment Method</option>
                    <option value="0">Cash</option>
                    <option value="1">Check</option>
                    <option value="2">Credit Card</option>
                    <option value="3">Other</option>
                </select>
                <div style="height: 10px;"></div>
                <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Payment Info:</label>
                <input type="text" class="form-control" name="payment_info" id="payment_info" placeholder="" value="">

                <div style="height: 20px;"></div>
                <button type="submit" class="btn btn-paid-status">Mark Paid</button>

            </form>
        </div>
    </div>
</div>

<!-- Modal csv date range and confirm downlad-->
<div id="modal_theme_csv_download" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Select a date range:</h6>
            </div>
            <form id="add_paid_payment_form" action="Invoices/downloadInvoiceCSV" method="post" style="padding: 10px;" >
                <input type="hidden" class="form-control" name="selected_invoices" id="selectedInvoices" placeholder="" value="" >
                <input type="hidden" class="form-control" name="selected_amounts" id="selectedAmountsDue" placeholder="" value="" >
                <!-- Gets inserted on the button onclick function that calls for the modal (pay)-->
                <!--                <div id="past_payments_"></div>-->

                <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Initial Date:</label>
                <input type="date" class="form-control" name="date_init"  id="date_init" placeholder="" value="" style="margin-bottom: 5px;"  >
                <div style="height: 10px;"></div>
                <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">End Date:</label>
                <input type="date" class="form-control" name="date_end"  id="date_end" placeholder="" value="" style="margin-bottom: 5px;"  >
                <div style="height: 10px;"></div>

                <button type="submit" class="btn btn-paid-status">Download</button>

            </form>
        </div>
    </div>
</div>

<?php if(isset($_GET['aging']) && $_GET['aging'] == 1){ ?>
<script type="text/javascript">
   $(document).ready(function(){
	 var aging = 1;
	 var table =  $('#activeInvoices').DataTable({
         "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":<?= $this ->session->userdata('compny_details')-> default_display_length?>,
		 	
		   "order":[[8,"desc"]],
		   "ajax":{
		     "url": "<?= base_url('admin/Invoices/ajaxGetActive/')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',aging:aging },

		   },
		   	 "deferRender":false,


        	"columnDefs": [
				{"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}},


			],

		   "select":"multi",
		   "columns": [
			   	  {"data": "checkbox", "checkboxes":true, "stateSave":true, "searchable":false, "orderable":false},
		          {"data": "invoice_id", "name":"Invoice", "searchable":true, "orderable": true },
			   	  {"data": "customer_id", "name":"Customer", "searchable":true, "orderable": true },
		          {"data": "email", "name":"Email", "searchable":true, "orderable": true },
			   	  {"data": "cost", "name":"Amount", "orderable": true },
			   	  {"data": "balance_due", "name":"Balance Due", "orderable": true },
                  {"data": "payment_method", "name":"Payment Method","searchable":true, "orderable": true },
                  {"data": "payment_info", "name":"Payment Info", "searchable":true,"orderable": true },
                  {"data": "status", "name":"Status", "width":"20%","orderable": true, },
			   	  {"data": "payment_status", "name":"Payment Status", "width":"20%","orderable": true, },
			   	  {"data": "invoice_date", "name":"Invoice Date", "orderable": true },
			   	  {"data": "sent_date", "name":"Sent Date", "orderable": true },
			   	  {"data": "opened_date", "name":"Opened Date", "orderable": true },
			      {"data": "payment_created", "name":"Payment Date", "orderable": true },
			      {"data": "refund_datetime", "name":"Refund Date", "orderable": true },
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_ records',
            
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
		   dom: '<"toolbar">fBlrtip',
		    buttons:[
				{
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon',
				columns: [1,2,3,4,5,6,7,8,9,10,11],


            },
				],
		   initComplete: function(){

           $("div.toolbar")
              .html('<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Sent <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-status" onclick="filterSearch(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-status" onclick="filterSearch(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li><li class="filter-status" onclick="filterSearch(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li class="filter-status" onclick="filterSearch(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Opened</a></li></ul></div>&nbsp;&nbsp;<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Payment <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-payment" onclick="filterPayment(5)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-payment" onclick="filterPayment(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unpaid</a></li><li class="filter-payment" onclick="filterPayment(1)"  ><a href="#"><span class="status-mark bg-till position-left"></span> Partial</a></li><li class="filter-payment" onclick="filterPayment(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>  <li class="filter-payment" onclick="filterPayment(3)" ><a href="#"><span class="status-mark bg-danger position-left"></span> Past Due</a></li> <li class="filter-payment" onclick="filterPayment(4)" ><a href="#"><span class="status-mark bg-refunded position-left"></span> Refunded </a></li>  </ul></div>&nbsp;&nbsp;<div class="btn-group">   <button disabled="disabled" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" id="bulk_status_change" >Change Sent Status <span class="caret"></span></button>   <ul class="dropdown-menu dropdown-menu-right">      <li onclick="bulkStatusChange(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li>      <li onclick="bulkStatusChange(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>      <li onclick="bulkStatusChange(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Opened</a></li> </ul></div>&nbsp;&nbsp;<button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">Send By Email</button>&nbsp;&nbsp;<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>');
        },

	   });



   });
</script>
<?php } else { ?>
<!-- /content area -->
<script type="text/javascript">
   $(document).ready(function(){

	 var table =  $('#activeInvoices').DataTable({
         "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
         "pageLength":20,

		   "order":[[10,"desc"]],
		   "ajax":{
		     "url": "<?= base_url('admin/Invoices/ajaxGetActive/')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,


        	"columnDefs": [
				{"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}},


			],

		   "select":"multi",
		   "columns": [
			   	  {"data": "checkbox", "checkboxes":true, "stateSave":true, "searchable":false, "orderable":false},
		          {"data": "invoice_id", "name":"Invoice", "searchable":true, "orderable": true },
			   	  {"data": "customer_id", "name":"Customer", "searchable":true, "orderable": true },
		          {"data": "email", "name":"Email", "searchable":true, "orderable": true },
			   	  {"data": "cost", "name":"Amount", "orderable": true },
			   	  {"data": "balance_due", "name":"Balance Due", "orderable": true },
                  {"data": "payment_method", "name":"Payment Method", "orderable": true },
                  {"data": "payment_info", "name":"Payment Info", "orderable": true },
			      {"data": "status", "name":"Status", "width":"20%","orderable": true, },
			   	  {"data": "payment_status", "name":"Payment Status", "width":"20%","orderable": true, },
			   	  {"data": "invoice_date", "name":"Invoice Date", "orderable": true },
			   	  {"data": "sent_date", "name":"Sent Date", "orderable": true },
			   	  {"data": "opened_date", "name":"Opened Date", "orderable": true },
			      {"data": "payment_created", "name":"Payment Date", "orderable": true },
			      {"data": "refund_datetime", "name":"Refund Date", "orderable": true }
	           ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show :</span> _MENU_ records',
            
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
		   dom: '<"toolbar">fBlrtip',
		    buttons:[
				{
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon',
				columns: [1,2,3,4,5,6,7,8,9,10,11],


            },
				],
		   initComplete: function(){

           $("div.toolbar")
              .html('<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Sent <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-status" onclick="filterSearch(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-status" onclick="filterSearch(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li><li class="filter-status" onclick="filterSearch(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li class="filter-status" onclick="filterSearch(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Opened</a></li></ul></div>&nbsp;&nbsp;<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Payment <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-payment" onclick="filterPayment(5)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-payment" onclick="filterPayment(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unpaid</a></li><li class="filter-payment" onclick="filterPayment(1)"  ><a href="#"><span class="status-mark bg-till position-left"></span> Partial</a></li><li class="filter-payment" onclick="filterPayment(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>  <li class="filter-payment" onclick="filterPayment(3)" ><a href="#"><span class="status-mark bg-danger position-left"></span> Past Due</a></li>  <li class="filter-payment" onclick="filterPayment(4)" ><a href="#"><span class="status-mark bg-refunded position-left"></span> Refunded </a></li> </ul></div>&nbsp;&nbsp;<div class="btn-group">   <button disabled="disabled" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" id="bulk_status_change" >Change Sent Status <span class="caret"></span></button>   <ul class="dropdown-menu dropdown-menu-right">      <li onclick="bulkStatusChange(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li>      <li onclick="bulkStatusChange(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>      <li onclick="bulkStatusChange(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Opened</a></li> </ul></div>&nbsp;&nbsp;<button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">Send By Email</button>&nbsp;&nbsp;<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-warning" id="latefeebutton" onclick="latefeemultiple()" disabled >Add Late Fee</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-success" id="paybutton" onclick="paymultiple()" disabled >Mark Paid</button>');
        },

	   });



   });
</script>
<?php } ?>
<!-- END CONDITION TO CHECK FOR INCOMING AGING REPORT PARAMS -->
<!-- <script type="text/javascript">
   $(function () {
      $.ajax({
         type: "GET",
         url: "<?php //echo base_url('admin/Invoices/ajaxGetTotalOfDueInvoices/'); ?>",
         // data: "data",
         // dataType: "dataType",
         success: function (response) {
            $('.ajax_load_total_unpaid').text(JSON.parse(response).due_amount_total);
            $('.total_billed_amount').text(JSON.parse(response).total_billed_amount);
         }
      });
   });
</script> -->
<script type="text/javascript">
function  filterSearch(status) {
	console.log("Sent Status: "+ status);
	var table = $('#activeInvoices').DataTable();
  	table.columns(8).search(status).draw();

}
function  filterPayment(status) {
	var table = $('#activeInvoices').DataTable();
  	table.columns(9).search(status).draw();
    

}

</script>
<script type="text/javascript">

   $(document).on("click",".changestatus", function () {

   // $('.changestatus').on('click', function() {
       var invoice_id = $(this).attr('invoice_id');
       var status = $(this).val();

       changeStatusFunction(invoice_id,status)


   });


   function changeStatusFunction(invoice_id,status) {

      $("#loading").css("display","block");
      console.log("Status is " + status);
       $.ajax({
           type: 'POST',
           url: '<?php echo base_url(); ?>admin/Invoices/changeStatus',
           data: {invoice_id: invoice_id, status: status},
           success: function (data)
           {
            console.log(data);
            $("#loading").css("display","none");

              location.reload();

           }
       });


   }
   $(document).on("click",".changepayment", function () {

   // $('.changestatus').on('click', function() {
       var invoice_id = $(this).attr('invoice_id');
       var payment_status = $(this).val();
       var total_due = Number($(this).attr('total_due'));
       var over_all_total = Number($(this).attr('over_all_total'));
       var partial_payment = Number($(this).attr('partial_payment'));
       var doc_query;

       console.log('Total Due is : ' + total_due);

        if (payment_status==1) {
			//if status is partial

         doc_query = "#modal_theme_primary_partial_payment_btn_" + invoice_id;
         console.log(doc_query);

         $(doc_query).click();

         //   swal.mixin({
         //       input: 'text',
         //       confirmButtonText: 'Yes',
         //       cancelButtonText: 'No',
         //       showCancelButton: true,
         //       progressSteps: 1
         //     }).queue([
         //       {
         //         title: 'Partial',
         //         text: 'Type a partial payment',
         //         inputValue : partial_payment,
         //          inputValidator: (value) => {

         //           return new Promise((resolve) => {

         //            if ($.isNumeric( value)== false ) {

         //              resolve('Please enter valid number');
         //            } else if(value <= 0 ) {
         //              resolve('Please enter a value greater than 0.');

         //            } else if (value > over_all_total ) {
         //              resolve('Value should be less than or equal to total cost value.');

         //            } else {
         //              resolve();
         //            }
         //          })
         //        }
         //       },
         //     ]).then((result) => {

         //       if (result.value) {

         //          var partial_payment  = result.value[0];

         //          changePaymentStatusFunction(invoice_id,payment_status,partial_payment)

         //       }
         //     })
        }
         else if (payment_status==2 && total_due > 0){
            //if status is paid
         console.log(total_due);
        doc_query = "#modal_theme_primary_paid_payment_btn_" + invoice_id;
        console.log(doc_query);

         $(doc_query).click();

        } 
         else if (payment_status==4){
            //if status is refunded
         console.log(total_due);
        doc_query = "#modal_theme_primary_refund_payment_btn_" + invoice_id;
        console.log(doc_query);

         $(doc_query).click();

        } else {
          changePaymentStatusFunction(invoice_id,payment_status)
        }

   });
	 function changePaymentStatusFunction(invoice_id,payment_status,partial_payment='') {

      $("#loading").css("display","block");
       $.ajax({
           type: 'POST',
           url: '<?php echo base_url(); ?>admin/Invoices/changePaymentStatus',
           data: {invoice_id: invoice_id, payment_status: payment_status,partial_payment : partial_payment},
           success: function (data)
           {
            $("#loading").css("display","none");

              location.reload();

           }
       });
   }

   $("#add_paid_payment_form").submit(function(e) {
       e.preventDefault();

       $("#modal_theme_multiple_payment").css("display", "none");
       $.ajax({
           type: "POST",
           url: '<?php echo base_url(); ?>admin/Invoices/changeMultiplePaymentStatus',
           data: $(this).serialize()
       }).done(function(data){
           $("#loading").css("display","none");
            console.log(data);
           if (data=="true") {
               swal(
                   "Full Payment",
                   "Added Successfully",
                   "success"
               ).then(function() {
                   location.reload();
               });

           } else {
               swal({
                   type: "error",
                   title: "Oops...",
                   text: "Something went wrong!"
               });
           }
       });


   })
</script>



<script>
   $(document).on("click",".email", function () {

        // $('.email').click(function(){

          var invoice_id = $(this).attr('id');
          var customer_id = $(this).attr('customer_id');


              swal.mixin({
               input: 'text',
               confirmButtonText: 'Send',
               showCancelButton: true,
               progressSteps: 1
             }).queue([
               {
                 title: 'Invoice Message',
                 text: 'Type a message to the customer below to be included with the invoice. Then click "Send" to email the invoice to the customer.'
               },
             ]).then((result) => {
               if (result.value) {
                var message  = result.value;

                  $("#loading").css("display","block");


                     $.ajax({
                     type: 'POST',
                     url: '<?php echo base_url(); ?>admin/Invoices/sendPdfMail',
                     data: {invoice_id: invoice_id, customer_id: customer_id,message : message},
                     success: function (data)
                     {

                        //alert(data);
                        $("#loading").css("display","none");
                           swal(
                              'Invoice !',
                              'Sent Successfully ',
                              'success'
                              ).then(function() {
                     location.reload();
                  });
                           }
                         });
               }
             })
         });

</script>
<script language="javascript" type="text/javascript">
   $(document).on("change","#select_all", function () {

     // $("#select_all").change(function(){  //"select all" change
       var status = this.checked; // "select all" checked status
      if (status) {
       $('#allMessage').prop('disabled', false);
       $('#allPrint').prop('disabled', false);
       $('#deletebutton').prop('disabled', false);
       $('#latefeebutton').prop('disabled', false);
       $('#paybutton').prop('disabled', false);
       $('#bulk_status_change').prop('disabled', false);
	   $('#bulk_payment_change').prop('disabled', false);

      }
      else
      {
        $('#allMessage').prop('disabled', true);
        $('#allPrint').prop('disabled', true);
        $('#deletebutton').prop('disabled', true);
        $('#latefeebutton').prop('disabled', true);
        $('#paybutton').prop('disabled', true);
        $('#bulk_status_change').prop('disabled', true);
		$('#bulk_payment_change').prop('disabled', true);

      }

       $('input:checkbox').not(this).prop('checked', this.checked);

       // $(document).on("each",'.myCheckBox',function(){ //iterate all listed checkbox items
       //     this.checked = status; //change ".checkbox" checked status

       // });
   });

   // $(document).on("change",".myCheckBox", function () {

   //   // $('.myCheckBox').change(function(){ //".checkbox" change
   //     //uncheck "select all", if one of the listed checkbox item is unchecked

   // });

</script>
<script type="text/javascript">
   // var checkBoxes = $('.myCheckBox');
   $(document).on("change",".myCheckBox", function () {

   // checkBoxes.change(function () {
   // alert(checkBoxes);
      if($('.myCheckBox').filter(':checked').length < 1) {
    //  alert("if");
       $('#allMessage').prop('disabled', true);
       $('#allPrint').prop('disabled', true);
       $('#deletebutton').prop('disabled', true);
       $('#latefeebutton').prop('disabled', true);
       $('#paybutton').prop('disabled', true);
       $('#bulk_status_change').prop('disabled', true);
	   $('#bulk_payment_change').prop('disabled', true);
      }
      else {
       $('#allMessage').prop('disabled', false);
       $('#allPrint').prop('disabled', false);
       $('#deletebutton').prop('disabled', false);
       $('#latefeebutton').prop('disabled', false);
       $('#paybutton').prop('disabled', false);
       $('#bulk_status_change').prop('disabled', false);
	   $('#bulk_payment_change').prop('disabled', false);

      //  alert('else');
      }

       if(this.checked == false){ //if this item is unchecked
          $("#select_all")[0].checked = false; //change "select all" checked status to false


      }

      //check "select all" if all checkbox items are checked
      if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
          $("#select_all")[0].checked = true; //change "select all" checked status to true


      }

      // $('#allMessage').prop('disabled', checkBoxes.filter(':checked').length < 1);
      // $('#allPrint').prop('disabled', checkBoxes.filter(':checked').length < 1);
   });

   //checkBoxes.change();

</script>
<script type="text/javascript">
   $(document).on("click","#allMessage", function () {


      // $('#allMessage').click(function(){

            swal.mixin({
             input: 'text',
             confirmButtonText: 'Send',
             showCancelButton: true,
             progressSteps: 1
           }).queue([
             {
               title: 'Invoice Message',
               text: 'Type a message to the customer below to be included with the invoice. Then click "Send" to email the invoice to the customer.'
             },
           ]).then((result) => {

             if (result.value) {
                 var message  = result.value;

                 var group_id_array = $("input:checkbox[name=group_id]:checked").map(function(){
                           return $(this).val();
                       }).get(); // <----

                $("#loading").css("display","block");
                        $.ajax({
                         type: 'POST',
                         url: '<?php echo base_url(); ?>admin/invoices/sendPdfMailToSelected',
                         data: {group_id_array,message : message},
                         success: function (data)
                         {
                          $("#loading").css("display","none");
                        //  alert(data);
                             swal(
                                  'Invoice !',
                                  'Sent Successfully ',
                                  'success'
                                  )
                                  .then(function() {
                            location.reload();
                                  });
                         }
                       });
             }
           })
       });

</script>
<script type="text/javascript">
   $(document).on("click","#allPrint", function () {


       var invoice_ids = $("input:checkbox[name=group_id]:checked").map(function(){
                         return $(this).attr('invoice_id');
        }).get(); // <----

     var href ="<?= base_url('admin/Invoices/printInvoice/') ?>"+invoice_ids;

       var win = window.open(href, '_blank');
        win.focus();

   });
</script>
<script type="text/javascript">
   function deletemultiple() {

        swal({
         title: 'Are you sure?',
         text: "",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#009402',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes',
         cancelButtonText: 'No'
       }).then((result) => {

         if (result.value) {

          var selectcheckbox = [];
          $("input:checkbox[name=group_id]:checked").each(function(){
                selectcheckbox.push($(this).attr('invoice_id'));
           });

              $("#loading").css("display","block");

             $.ajax({
                type: "POST",
                url: "<?= base_url('')  ?>admin/Invoices/deletemultipleInvoices",
                data: {invoices : selectcheckbox }
             }).done(function(data){
              $("#loading").css("display","none");

                     if (data==1) {
                       swal(
                          'Invoices !',
                          'Deleted Successfully ',
                          'success'
                      ).then(function() {
                        location.reload();
                      });


                     } else {
                       swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        })
                     }
             });
         }
       })
   }   

   function latefeemultiple() {

        swal({
         title: 'Are you sure?',
         text: "",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#009402',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes',
         cancelButtonText: 'No'
       }).then((result) => {

         if (result.value) {

          var selectcheckbox = [];
          $("input:checkbox[name=group_id]:checked").each(function(){
                selectcheckbox.push($(this).attr('invoice_id'));
           });

              $("#loading").css("display","block");

             $.ajax({
                type: "POST",
                url: "<?= base_url('')  ?>admin/invoices/addLateFeeInvoices",
                data: {invoices : selectcheckbox }
             }).done(function(data){
              $("#loading").css("display","none");

                     if (data==1) {
                       swal(
                          'Invoices !',
                          'Late Fee added successfully ',
                          'success'
                      ).then(function() {
                        location.reload();
                      });


                     } else {
                       swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        })
                     }
             });
         }
       })
   }

   function paymultiple() {
       var selectcheckbox = [];
       var selectamounts = [];
       var balance_due_total = 0;
       var past_payments_total = 0;
       $("input:checkbox[name=group_id]:checked").each(function(){
           selectcheckbox.push($(this).attr('invoice_id'));
           selectamounts.push($(this).attr('balance_due'));

           var balance_due = $(this).attr('balance_due');
           var past_payment = $(this).attr('past_payments');
           console.log("Past payments: "+past_payment)
           if ( $(this).attr('past_payments')) past_payment =0;
           balance_due_total +=  parseFloat(parseFloat(balance_due).toFixed(2));
           past_payments_total +=  parseFloat(parseFloat($(this).attr('past_payments')).toFixed(2));
            console.log("Total: "+past_payments_total)


       });

       var selectedInvoicesJson = JSON.stringify(selectcheckbox);
       var selectedAmountsJson = JSON.stringify(selectamounts);
       $('#selectedInvoices').val(selectedInvoicesJson);
       $('#total_due').val(balance_due_total);
       $('#partial_payment').val(parseFloat(parseFloat(balance_due_total).toFixed(2)));
       $('#past_payments').val(past_payments_total);
       $('#selectedAmountsDue').val(selectedAmountsJson);
       // Show modal
       $('#modal_theme_multiple_payment').modal('show');



   }

   function bulkStatusChange(status) {

    var invoice_ids = $("input:checkbox[name=group_id]:checked").map(function(){
                         return $(this).attr('invoice_id');
        }).get(); //



      $("#loading").css("display","block");
       $.ajax({
           type: 'POST',
           url: '<?php echo base_url(); ?>admin/Invoices/bulkChangeStatus',
           data: {invoice_ids: invoice_ids, status: status},
           success: function (data)
           {
            $("#loading").css("display","none");

              location.reload();

           }
       });
    }

   function productDetailsGet(job_id) {
         $('#productdetails').html('');
         $.getJSON( "<?= base_url() ?>"+"/admin/Invoices/GetProcuctDetails/"+job_id, function( data ) {

             $('#productdetails').html(data['result']);
             $('#modal_default').modal('toggle');

      });

    }

   function csv_download() {
       $('#modal_theme_csv_download').modal('show');
   }
  // Check to see is cc# is needed

  $(function() {

      $('#cc_number').hide(); 

      $('#refund_payment').change(function(){

          if($('#refund_payment').val() == '2') {

              $('#cc_number').show();

              $('#refund_cc').prop( "disabled", false ); 

              $('#refund_cc').addClass( "required" ); 

			  $('#refund_amount_input_full_full').prop( "disabled", true ); 

			  $('#refund_other').prop( "disabled", true ); 

			  $('#refund_amount_input_full_full').removeClass( "required"); 

			  $('#refund_other').removeClass( "required"); 

          } else {

              $('#cc_number').hide();

			  $('#refund_cc').prop( "disabled", true );

			  $('#refund_cc').removeClass( "required" ); 

          } 

      });

  });

  // Check to see is check# is needed

  $(function() {

      $('#check_number').hide(); 

      $('#refund_payment').change(function(){

          if($('#refund_payment').val() == '1') {

			$('#refund_cc').prop( "disabled", true );

			$('#check_number').show();

			$('#refund_amount_input_full_full').prop( "disabled", false ); 

			$('#refund_amount_input_full_full').addClass( "required" ); 

			$('#refund_other').prop( "disabled", true );

			$('#refund_cc').removeClass( "required"); 

			  $('#refund_other').removeClass( "required");  

          } else {

              $('#check_number').hide();

			  $('#refund_amount_input_full_full').prop( "disabled", true ); 

			  $('#refund_amount_input_full_full').removeClass( "required" ); 

          } 

      });

  });

  // Check to see notes on other 

  $(function() {

        $('#other').hide(); 

          $('#refund_payment').change(function(){

              if($('#refund_payment').val() == '3') {

				$('#refund_cc').prop( "disabled", true );

				$('#refund_amount_input_full_full').prop( "disabled", true );

				$('#other').show();

				$('#refund_other').prop( "disabled", false );

				$('#refund_other').addClass( "required" );

				$('#refund_amount_input_full_full').removeClass( "required"); 

			  $('#refund_cc').removeClass( "required");  

                } else {

                    $('#other').hide();

					$('#refund_other').prop( "disabled", true ); 

					$('#refund_other').removeClass( "required" ); 

                } 

              });

      });

</script>



<!-- start add credit modal -->
<div id="modal_batch_payment" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Add Batch Payments</h6>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?= base_url('inventory/Backend/Customers/AddBatchCredit') ?>">

         <div class="row">
            <div class="col-lg-3">Customer</div>
            <div class="col-lg-3">Amount</div>
            <div class="col-lg-2">Payment Type</div>
            <div class="col-lg-2">Check Number</div>
            <div class="col-lg-2"></div>
         </div>

         <div class="row" id="BatchRow1">
            <div class="col-lg-3" id="autocomplete-container-1">
               <input class="form-control CusInxBox" id="SearchCustomerBox-1" required spellcheck="true" name="customer_name[]">
               <ul class="dropdown-menu" id="itemSuggestions-1"></ul>
            </div>
            <div class="col-lg-3">
               <input class="form-control CusInxBoxAmount" required onchange="getAll()" onblur="getAll()" type="number" step="0.01" maxlength="100" size="100" spellcheck="true" name="BatchAmount[]">
            </div>
            <div class="col-lg-2">
               <select class="form-control" name="payment_type[]">
                  <option selected value="check">Check</option>
                  <option value="cash">Cash</option>
                  <option value="other">Other</option>
               </select>
            </div>
            <div class="col-lg-2">
               <input class="form-control" type="text" spellcheck="true" name="BatchReason[]">
            </div>
            <div class="col-lg-2">
               <button class="btn btn-danger mt-5 mb-5" onclick="RemoveBatchRow('BatchRow1')" type="button"> - Remove</button>
            </div>
         </div>
         <div id="LoadBathchRowNew"></div>

         <button onclick="AddMoreRowBatch()" class="btn btn-primary mt-5 mb-5" type="button"><i class="icon-plus22"></i> Add More</button>

         <h5>Total</h5>
         <div class="row">
            <div class="col-lg-3" id="ShowTotalNoCustomers">0</div>
            <div class="col-lg-3" id="ShowTotalNoAmount">0.0</div>
            <div class="col-lg-3"></div>
            <div class="col-lg-3"></div>
         </div>

          <div class="col">
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--end add credit modal -->



<div id="modal_add_csv" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Batch Payment</h6>
      </div>
      <form name="csvfileimport" action="<?= base_url('inventory/Backend/Customers/AddBatchCsv') ?>" method="post"
        enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Select csv file</label>
                <input type="file" name="csv_file">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="assignjob" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
   var counter = 1;
   function AddMoreRowBatch(){
      counter++;
      var HTML = "";
      HTML +='<div class="row" id="BatchRow'+counter+'">';
      HTML +='<div class="col-lg-3" id="autocomplete-container-'+counter+'">';
      HTML +='<ul class="dropdown-menu" id="itemSuggestions-'+counter+'"></ul>';
      HTML +='<input class="form-control CusInxBox" id="SearchCustomerBox-'+counter+'" required spellcheck="true" name="customer_name[]">';
      HTML +='</div>';
      HTML +='<div class="col-lg-3">';
      HTML +='<input class="form-control CusInxBoxAmount" onchange="getAll()" onblur="getAll()" required type="number" step="0.01" maxlength="100" size="100" spellcheck="true" name="BatchAmount[]">';
      HTML +='</div>';
      HTML +='<div class="col-lg-2">';
      HTML +='<select class="form-control" name="payment_type[]">';
      HTML +='<option selected value="check">Check</option>';
      HTML +='<option value="cash">Cash</option>';
      HTML +='<option value="other">Other</option>';
      HTML +='</select>';
      HTML +='</div>';
      HTML +='<div class="col-lg-2">';
      HTML +='<input class="form-control" type="text" spellcheck="true" name="BatchReason[]">';
      HTML +='</div>';
      HTML +='<div class="col-lg-2">';
      HTML +='<button class="btn btn-danger mt-5 mb-5" onclick=RemoveBatchRow("BatchRow'+counter+'") type="button"> - Remove</button>';
      HTML +='</div>';
      HTML +='</div>';

      $("#LoadBathchRowNew").append(HTML);

      $('#SearchCustomerBox-'+counter).on('focus', e => {
         $('#autocomplete-container-'+counter).addClass('open')
      })

      $('#SearchCustomerBox-'+counter).on('blur', e => {
         // Timeout so that the item clicked listener can fire
         setTimeout(() => {
            $('#autocomplete-container-'+counter).removeClass('open')
         }, 200)
      })

      // Listen for changes on the autocomplete input
      $('#SearchCustomerBox-'+counter).on('input', e => {
         autocomplete(e.target.value, counter);
         getAll();
      })

      $('ul#itemSuggestions-'+counter).on('click', 'li', e => {
         let id = $(e.currentTarget).data('item-id')
         $('#SearchCustomerBox-'+counter).val(id);
         getAll();
      })
   }

   function RemoveBatchRow(id){
      $("#"+id).remove();
      getAll();
   }


   // When focusing on the autocomplete, show list
   $('#SearchCustomerBox-1').on('focus', e => {
      $('#autocomplete-container-1').addClass('open')
   })

   $('#SearchCustomerBox-1').on('blur', e => {
      // Timeout so that the item clicked listener can fire
      setTimeout(() => {
         $('#autocomplete-container-1').removeClass('open')
      }, 200)
   })

   // Listen for changes on the autocomplete input
   $('#SearchCustomerBox-1').on('input', e => {
      autocomplete(e.target.value, 1);
      getAll();
   })

   $('ul#itemSuggestions-1').on('click', 'li', e => {
      let id = $(e.currentTarget).data('item-id')
      $('#SearchCustomerBox-1').val(id);
      getAll();
   })

   function autocomplete(search, counter) {
      var url = '<?= base_url('inventory/Backend/Customers/Search') ?>';
      var request_method = "GET";
      $.ajax({
         type: request_method,
         url: url,
         data: {search: search},
         dataType:'JSON', 
         success: function(response){
            $('ul#itemSuggestions-'+counter).empty()
            response.result.forEach(item => {
            let elem = `<li data-item-id="${item.customer_id}">`
               + `<span class="item-name">${item.first_name} ${item.last_name} - ${item.customer_id}</span>`
               + '</li>'

            $('ul#itemSuggestions-'+counter).append(elem)
            })
         }
      });
   }

   function getAll(){
      var totalCustomers = 0;
      var totalAmount = 0;
      $(".CusInxBox").each(function () {
         if($(this).val() != ""){
            totalCustomers += 1;
         }
      });

      $(".CusInxBoxAmount").each(function () {
         if($(this).val() != ""){
            totalAmount += parseFloat($(this).val());
         }
      });

      $("#ShowTotalNoCustomers").html(totalCustomers);
      $("#ShowTotalNoAmount").html(totalAmount);
   }

</script>