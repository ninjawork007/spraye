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
<div class="content invoicessss">
   <div id="loading" >
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
   </div>
   <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
      <div id="invoicetablediv">
         <div  class="table-responsive table-spraye">
            <table  class="table datatable-button-init-custom">
               <thead>
                  <tr>
                     <th><input type="checkbox" id="select_all" <?php if (empty($invoice_details)) { echo 'disabled'; }  ?>    /></th>
                     <th>Invoice</th>
                     <th>Customer Name</th>
                     <th>Email</th>
                     <th>Amount</th>
                     <th>Balance Due</th>                     
                     
                     <th>Date</th>
                     
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($invoice_details)) { 
                     foreach ($invoice_details as $value) { ?>      
                  <tr>
                     <td><input  name="group_id" type="checkbox"  value="<?=$value->invoice_id.':'.$value->customer_id ?>" invoice_id="<?=$value->invoice_id ?>" class="myCheckBox" /></td>
                     <td><?= $value->invoice_id; ?></td>
                     <td style="text-transform: capitalize;"><a href="<?= base_url("admin/editCustomer/").$value->customer_id ?>" style="color:#3379b7;"><?= $value->first_name.' '.$value->last_name ?></a></td>
                     <td><?= $value->email ?></td>                     
                      
                      <?php 
                        $total_tax_amount = getAllSalesTaxSumByInvoice($value->invoice_id)->total_tax_amount;
                        
                       ?>

                     <!--<td><?= '$ '.number_format($value->cost+$total_tax_amount,2) ?></td>-->
                     <td><?= '$ '.number_format($value->invoice_total_calculated_final_cost_minus_partial ,2) ?></td>
                     
                      <?php $due = $value->invoice_total_calculated_final_cost ; ?>
                     <td><?=  $due<=0 ? '$ 0.00' : '$ '.number_format($due,2)    ?></td>

                     
                     
                     <td><?= date('m-d-Y', strtotime($value->invoice_date)) ?></td>
                     
                  </tr>
                  <?php  }  } ?>
               </tbody>
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
$(document).ready(function() {
   $("div.toolbar")
              .html('<button type="submit"  class="btn btn-success" id="restorebutton" onclick="restoremultiple()" disabled >Restore</button>');
});   
</script>
<script language="javascript" type="text/javascript">
   $(document).on("change","#select_all", function () {
       var status = this.checked; // "select all" checked status
      if (status) {       
         $('#restorebutton').prop('disabled', false);
      }
      else
      {        
        $('#restorebutton').prop('disabled', true);
      }   
       $('input:checkbox').not(this).prop('checked', this.checked);
   });
   $(document).on("change",".myCheckBox", function () {
      if($('.myCheckBox').filter(':checked').length < 1) {
         $('#restorebutton').prop('disabled', true);
      }
      else {
         $('#restorebutton').prop('disabled', false);
      }   
       if(this.checked == false){ // if this item is unchecked
         $("#select_all")[0].checked = false;
      }      
      //check "select all" if all checkbox items are checked
      if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){ 
          $("#select_all")[0].checked = true; //change "select all" checked status to true
      }
   });
   function restoremultiple() {
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
               url: "<?= base_url('')  ?>admin/Invoices/restoremultipleInvoices",
               data: {invoices : selectcheckbox }
            }).done(function(data){
               $("#loading").css("display","none");    
               if (data==1) {
                  swal(
                     'Invoices !',
                     'Restored Successfully ',
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

   /*DECLINED ESTIMATE FUNCTIONS*/
   function tableintalArchived(argument){

       $('#total-archived-invoices').DataTable({
           "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?> ,
           "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
           buttons: [
               {
                   extend: 'colvis',
                   text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                   className: 'btn bg-indigo-400 btn-icon'
               }
           ],
           stateSave: true,
           columnDefs: [
               {
                   targets: -1,
                   visible: false
               }
           ],
       });
   }
</script>

