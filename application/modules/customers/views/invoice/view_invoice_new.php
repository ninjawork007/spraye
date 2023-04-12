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
<div class="content invoicessss">
   <div id="loading" >
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
   </div>
   <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
      <div class="row cx-dt">
         <div class="col-md-3 col-sm-3 col-12">
            <div class=" service-bols">
               <h3 class="ser-head">Total Unpaid </h3>
               <p class="text-warning ser-num "> $ <?php if(!empty($total)) {echo number_format($total['total_unpaid'],2); } ?></p>
            </div>
         </div>
         <div class="col-md-3 col-sm-3 col-12">
            <div class="service-bols">
               <h3 class="ser-head">Total Billed</h3>
               <p class=" ser-num text-success">$ <?php if(!empty($total)) {echo number_format($total['total_billed'],2); } ?></p>
            </div>
         </div>
         <div class="col-md-3 col-sm-3 col-12">
            <div class="service-bols">
               <h3 class="ser-head">Total Revenue</h3>
               <p class="text-success ser-num">$ <?php if(!empty($total)) {echo number_format($total['total_revenue'],2); } ?></p>
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
                     <th>Status</th>
                     <th>Date</th>
                  	 <th>Action</th>
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

<!-- /content area -->
<script type="text/javascript">
   $(document).ready(function(){
	   
	 var table =  $('#activeInvoices').DataTable({
		  // "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":20,
		   "order":[[1,"asc"]],
		   "ajax":{
		     "url": "<?= base_url('admin/Invoices/ajaxGetActive/')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }

		   },
		   	 "deferRender":false,
		    
		    
        	"columnDefs": [
				{"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}}
			],
		   
		   "select":"multi",
		   "columns": [
			   	  {"data": "checkbox", "checkboxes":true, "stateSave":true, "searchable":false, "orderable":false},
		          {"data": "invoice_id", "name":"Invoice", orderable: true },
			   	  {"data": "customer_id", "name":"Customer", orderable: true },
		          {"data": "email", "name":"Email", orderable: true },
			   	  {"data": "cost", "name":"Amount", orderable: true },
			   	  {"data": "balance_due", "name":"Balance Due", orderable: true },
			      {"data": "status", "name":"Status", "width":"20%",orderable: true, },
			   	  {"data": "invoice_date", "name":"Date", orderable: true },
			   	  {"data": "actions", "name":"Actions","class":"table-action"},
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
		   dom: 'l<"toolbar">frtip',
		   initComplete: function(){
     
           $("div.toolbar")
              .html('<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-status" onclick="filterSearch(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-status" onclick="filterSearch(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li><li class="filter-status" onclick="filterSearch(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li class="filter-status" onclick="filterSearch(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>  <li class="filter-status" onclick="filterSearch(3)" ><a href="#"><span class="status-mark bg-till position-left"></span> Partial</a></li>   </ul></div>&nbsp;&nbsp;<div class="btn-group">   <button disabled="disabled" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" id="bulk_status_change" >Change Status <span class="caret"></span></button>   <ul class="dropdown-menu dropdown-menu-right">      <li onclick="bulkStatusChange(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li>      <li onclick="bulkStatusChange(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>      <li onclick="bulkStatusChange(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li> </ul></div>&nbsp;&nbsp;<button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">Send By Email</button>&nbsp;&nbsp;<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>');           
        },
		   
	   });
	   
	  
	   
   });


function  filterSearch(status) {
	var table = $('#activeInvoices').DataTable();
  	table.columns(6).search(status).draw();
	
}
	
</script>
<script type="text/javascript">
   $(document).on("click",".changestatus", function () {
   
   // $('.changestatus').on('click', function() {
       var invoice_id = $(this).attr('invoice_id');
       var status = $(this).val();       
       var over_all_total = Number($(this).attr('over_all_total'));
       var partial_payment = Number($(this).attr('partial_payment'));

        if (status==3) {


           swal.mixin({
               input: 'text',
               confirmButtonText: 'Yes',
               cancelButtonText: 'No',
               showCancelButton: true,
               progressSteps: 1
             }).queue([
               {
                 title: 'Partial',
                 text: 'Type a partial payment',
                 inputValue : partial_payment,
                  inputValidator: (value) => {
  
                   return new Promise((resolve) => {

                    if ($.isNumeric( value)== false ) {

                      resolve('Please enter valid number');
                    } else if(value <= 0 ) {
                      resolve('Please enter a value greater than 0.');

                    } else if (value > over_all_total ) {
                      resolve('Value should be less than or equal to total cost value.');

                    } else {                      
                      resolve();
                    }
                  })
                }
               },
             ]).then((result) => {

               if (result.value) {

                  var partial_payment  = result.value[0];
                
                  changeStatusFunction(invoice_id,status,partial_payment)
      
               }
             })
   



        } else {
          changeStatusFunction(invoice_id,status)
        }
     
   });


   function changeStatusFunction(invoice_id,status,partial_payment='') {

      $("#loading").css("display","block");
       $.ajax({
           type: 'POST',
           url: '<?php echo base_url(); ?>admin/Invoices/changeStatus',
           data: {invoice_id: invoice_id, status: status,partial_payment : partial_payment},
           success: function (data)
           {
            $("#loading").css("display","none");

              location.reload();
          
           }
       });


   }
   
       
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
       $('#bulk_status_change').prop('disabled', false);
   
      }
      else
      {
        $('#allMessage').prop('disabled', true);
        $('#allPrint').prop('disabled', true);
        $('#deletebutton').prop('disabled', true);
        $('#bulk_status_change').prop('disabled', true);
        
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
       $('#bulk_status_change').prop('disabled', true);
      }
      else {
       $('#allMessage').prop('disabled', false);
       $('#allPrint').prop('disabled', false);
       $('#deletebutton').prop('disabled', false);
       $('#bulk_status_change').prop('disabled', false);
   
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
</script>
