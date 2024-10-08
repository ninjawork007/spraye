
<style> 
   .togglebutton{
      font-size:13px;
   }
   .content {
   padding: 20px 20px 60px !important;
   }
   .test {
   padding-left: 12px !important;
   }
   .test1 {
   padding-left: 43px !important;
   }
   .fc-scroller {
   height: 500px! important;
   }
   .widgetmydiv header p a {
   color: #1f567c !important;
   }
   .widgetmydiv .widget-logo {
   display: none !important;
   }
   .widgetmydiv header p:last-child {
   font-size: 1.2em !important;
   }
   .alleditbtn {
   float: left;
   padding-left:5px; 
   }
   .tmpspan {
   float: left;
   margin: 8px 15px 8px 0;
   }
   table {
    border-collapse: inherit;
  }
</style>
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
   @media only screen and (max-width: 600px) {
   .calederview {
   display: none;
   }
   .togglebutton {
   display: none;
   }
   .sheduletable {
   display: block;
   }
   }
   @media only screen and (min-width: 600px) {
   .calederview {
   display: block;
   }
   .sheduletable {
   display: none;
   } 
   }
   @media only screen and (min-width: 1300px) {
   .table-responsive {
   height: 500px;
   }
   }
   @media only screen and (max-width: 1240px) {
      #multiple-delete-id {
         margin: 0;
      }
      .unassigned-services-element {
         float: unset !important;
         margin-top: 20px;
      }
      .toolbar {
         width: 90% !important; /* toolbar (filters) */
         padding-bottom: 20px;
         /* margin-left: -100px; */
         display: block;
         float: left !important;
      }
      #unassigntbl_length { /* show: */
         /* float: left; */
         display: block;
      }
      #unassigntbl_filter > label { /* search box */
         margin-bottom: 20px;
         margin-left: -10px;
      }
   }
   @media only screen and (max-width: 769px) {
      .toolbar {
         float: left !important;
      }
      .toolbar td {
         display: block;
      }
      #unassigntbl_length { /* show: */
         float: left !important;
      }
      #unassigntbl_filter > label { /* search box */
         float: left !important;
      }
      .form-group a {
         display: block;
         width: 200px;
         margin-bottom: 6px;
      }
      .form-group button {
         display: block;
         width: 200px;
      }
   }
   .btndivdelete {
   float: left;
   /*padding: 0px 2px 0px 14px;*/
   /*display: none;  */
   }
   .toolbar {
   float: right;
   width: 57%;
   }
   .toolbar td {
   padding-left: 4px;
   }
   #unassigntbl_filter input {
   width :150px !important;
   }
   tfoot {
   display: table-header-group;
   }
   td.fc-day.fc-past {
   background-color: #EEEEEE;
   }
   .label-row {
   margin-bottom:6px; 
   }
   .noSpacingInput {
   display: none;
   }
   .dtatableInput::placeholder {
   font-size: 10px;
   font-weight: 400;
   }

   .rescheduled_row {
    background: #b8d1f3 !important;
   }
</style>


<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/responsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/bootstrap-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/github.min.css">
<div class="content">
   <div class="">
      <div class="mymessage"></div>
      <b><?php if ($this->session->flashdata()): echo $this->session->flashdata('message');
         endif
         ?></b>
      <div id="loading" >
         <img id="loading-image" src="<?= base_url() ?>assets/loader.gif"  /> <!-- Loading Image -->
      </div>

      <div class="panel-heading"style="padding-left: 0px;">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin')?>" id="save" class="btn btn-success"><i class=" icon-arrow-left7"> </i> Back to Dashboard</a>
                          
                             <!-- <a href="<?= base_url('admin/manageJobs')?>" id="save" class="btn btn-primary"></i>Manage Scheduled Services</a> -->

                             <button disabled="disabled" id="multiple-delete-id" class="ml-5 btn btn-danger unassigned-services-element">Delete Property</button>
                             
                             
                             <button id="multiple-restore-id" disabled="disabled" class=" hidden btn btn-primary archived-services-element">Restore Services</button>
                             <!-- <div class="pull-right"> 
                                 <label class="togglebutton">
                                 Archived Services&nbsp;<input name="changeview" type="checkbox" class="switchery-primary"  checked="">
                                 Unassigned Services
                                 </label>
                     </div>                              -->
                        </div>
                   </h5>
              </div>
      <!-- <div class="panel-body">
         <h5 class="panel-title">Users Details</h5>
         </div>-->
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="col-lg-12 col-md-12 col-sm-12">
               <div class="panel panel-flat">
                  <div style="background: #fafafa;padding-top: 10px;padding-bottom: 20px;color: #333;padding-left: 12px;">
                     <span class="unassigned-services-element text-semibold" style="font-size:15px;">Prospected Properties</span>  
                     <span class="archived-services-element text-semibold hidden" style="font-size:15px;">Archived Services</span>  
                     <span class="unassigned-services-element">Highlighted jobs indicate this job has been skipped and needs to be rescheduled</span>            
                     
										  
					  <div class="unassigned-services-element" style="float: right;margin-right: 18px;">
						  <input placeholder="Total Sq Feet" id="totalSqFt" type="text" style="">
                        <button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">
                        Assign Technician</button>                  
                     </div>
                  </div>
                  <div class="panel-body"  style="padding: 20px 0px;">
                     
                     <div  class="table-responsive table-spraye dash-tbl" style="height: unset;">
                        <table  class="table" id="unassigntbl" >
                           <thead>
                              <tr>
                                 <th><input type="checkbox" id="select_all" /></th>
                                 <th>Priority</th>
                                 <th>Service Name</th>
                                 <th>Customer Name</th>
                                 <th>Property Name</th>
								         <th>Square Feet</th>
                                 <th>Last Service Date</th>
								         <th>Last Program Service Date</th>
                                 <th>Address</th>
                                 <th>Property Type</th>                                 
                                 <th>Service Area</th>
                                 <th>Program</th>
                                 <th>Note</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                 <td></td>
                                 <td id="priority_filter">PRIORITY</td>
                                 <td id="service_name_filter">SERVICE NAME</td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
								  <td></td>
								  <td></td>
                                 <td id="property_type_filter">PROPERTY TYPE</td>
                                 <td id="service_area_filter">SERVICE AREA</td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                              </tr>
                           </tfoot> 

                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Primary modal -->
<div id="modal_theme_primary" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Assign Service to Technician</h6>
         </div>
         <form action="<?= base_url('admin/tecnicianJobAssign') ?>" name= "tecnicianjobassign" method="post" >
            <div class="modal-body">
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6 col-md-6">
                        <label>Select Technician</label>
                        <div class="multi-select-full">
                           <select class="form-control" name="technician_id" id="technician_id" >
                              <option value="" >Select Any Technician</option>
                              
                              <?php 
                                 if (!empty($tecnician_details)) {
                                  foreach ($tecnician_details as $value) {
                                  echo '<option value="'.$value->user_id.'" >'.$value->user_first_name.' '.$value->user_last_name.'</option>';  
                                  }
                                 }
                                 
                                 
                                 ?> 
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-6 col-md-6" id="assigModalDate" >
                        <label>Select Date</label>
                        <input type="date"  name="job_assign_date" class="form-control  pickadate" id="jobAssignDate" placeholder="MM-DD-YYYY">
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-7 col-md-7">
                        <div class="row label-row" >
                           <div class="col-sm-12 col-md-12">
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview"  class="primary-assign styled" checked="checked" value="1">
                              Existing route
                              </label>
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview" class="primary-assign styled" value="2" >
                              Create a new route
                              </label>    
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-12 col-md-12">
                              <select name="route_select" class="form-control" id="route_select" >
                              </select>
                              <input type="text" name="route_input" class="form-control" placeholder="Route Name" id="route_input" style="display: none;" >
                              <div class="route_error" >
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-5 col-md-5">
                        <label>Service Notes</label>
                        <input type="text" name="job_assign_notes" placeholder="Service Assign Notes" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="specificTimeDivision form-group">
               </div>
               <input type="hidden" name="group_id" id="group_id" >
               <div class="modal-footer">
                  <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Save</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- /primary modal -->
<!--begin edit assign job  -->

<script language="javascript" type="text/javascript">
   $("#select_all").change(function(){  //"select all" change 

      var status = this.checked; // "select all" checked status

      if (status) {
      $('#allMessage').prop('disabled', false);
      $('#multiple-delete-id,#multiple-restore-id').prop('disabled', false);
      }
      else
      {
         $('#allMessage').prop('disabled', true);
         $('#multiple-delete-id,#multiple-restore-id').prop('disabled', true);
         
      }
	   
	   var sqftTotal = 0;
      $('.myCheckBox').each(function(){ //iterate all listed checkbox items
            this.checked = status; //change ".checkbox" checked status
            if ($(this).is(':checked')) {
               // console.log( $(this).parent().parent().find('td').eq(5).html() );
               sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(5).html());
            }
      });
	   
	   $('#totalSqFt').val(sqftTotal); 
	   
   });

</script>

<script type="text/javascript">
   $(document).on("change","table .myCheckBox", function() {
      if($(".table .myCheckBox").filter(':checked') .length < 1) {
         $('#allMessage').prop('disabled', true);
         $('#multiple-delete-id,#multiple-restore-id').prop('disabled', true);
      } else {
         $('#allMessage').prop('disabled', false);   
         $('#multiple-delete-id,#multiple-restore-id').prop('disabled', false);
      }            
   });  
   
</script>

<script type="text/javascript">
   $(document).on("click", "#multiple-delete-id,#multiple-restore-id", function(e) {
      var group_id = [];
      var button_id = this.id;
      var url = "";
      
      $("input:checkbox[name=group_id]:checked").each(function(){
         group_id.push($(this).val());
      });
      var post_data =  {};
      var success_message = "";
      post_data.group_id = group_id;
      if(button_id == "multiple-delete-id") {
         post_data.action = 'delete';
         success_message = "Deleted Successfully";
      } else {
         post_data.action = 'restore';
         success_message = "Restored Successfully";
      }
      console.log(post_data);
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
               $("#loading").css("display","block");   
               $.ajax({
               type: "POST",
               url: "<?= base_url('admin/deleteRestoreMultiUnassignedJobs') ?>",
               data: post_data,
               dataType: 'json'
            }).done(function(data){
               $("#loading").css("display","none");
               if (data.status==200) {
                  swal(
                     'Unassigned Service(s) !',
                     success_message,
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
   });
   $(document).on("click",".confirm_delete_unassign_job,.confirm_restore_unassign_job", function (e) {
      e.preventDefault();
      var action = "";
      var success_message = "";
      if($(this).hasClass('confirm_delete_unassign_job')) {
         action = "delete";
         success_message = "Deleted Successfully";
      } else {
         action = "restore";
         success_message = "Restored Successfully";
      }     
      var group_id = $(this).attr('grd_ids');
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
                   $("#loading").css("display","block");   
                   $.ajax({
                    type: "POST",
                    url: "<?= base_url('admin/deleteRestoreUnassignedJob') ?>",
                    data: {group_id : group_id, action : action },
                    dataType: 'json'
                 }).done(function(data){
                     
                     $("#loading").css("display","none");
   
                    if (data.status==200) {
   
                           swal(
                              'Unassigned Service !',
                              success_message,
                              'success'
                          ).then(function() {
                           location.reload();
                          })
                            
   
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
   
   
       });
</script>   

<style>
   #unassigntbl_processing {
      margin-top: 40px;
      background-color: white;
      background: white;
   }
</style>

<script>
 
   
    $(document).ready(function() {
      
   
      // Setup - add a text input to each footer cell
   $('#unassigntbl tfoot td').each( function () {
       var title = $(this).text();
       if (title=='PRIORITY' || title=='SERVICE NAME' || title=='PROPERTY TYPE' || title=='SERVICE AREA' ) {
         $(this).html( '<input type="text" class="form-control dtatableInput" placeholder="'+title+'" />' );
       } else {
         $(this).addClass('noSpacingInput');
       }
   } );
   
   // DataTable
   var table =  $('#unassigntbl').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		   "pageLength":<?= $this ->session->userdata('compny_details')-> default_display_length?>,
		   "order":[[1,"asc"]],
		   "ajax":{
		     "url": "<?= base_url('admin/ajaxGetProspects/')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
		   },
		   "deferRender":false, 
        	"columnDefs": [
				{"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}},
			],
		   "select":"multi",
		   "columns": [
			   	  {"data": "checkbox", "checkboxes":true, "stateSave":true, "searchable":false, "orderable":false},
		          {"data": "priority", "name":"Priority", "orderable": true, "searchable": true },
			   	  {"data": "job_name", "name":"Service Name", "searchable":true, "orderable": true },
		          {"data": "customer_name", "name":"Customer Name", "searchable":true, "orderable": true },
			   	  {"data": "property_name", "name":"Property Name", "searchable":true, "orderable": true },
			   	  {"data": "square_feet", "name":"Square Feet", "orderable": true },
                 {"data": "last_service_date", "name":"Last Service Date", "orderable": true },
                 {"data": "last_program_service_date", "name":"Last Program Service Date", "orderable": true },
                 {"data": "address", "name":"Address", "searchable":true, "orderable": true },
                 {"data": "property_type", "name":"Property Type", "orderable": true },
                 {"data": "category_area_name", "name":"Service Area", "orderable": true },
                 {"data": "program", "name":"Program", "orderable": true },
                 {"data": "reschedule_message", "name":"Note", "orderable": true},
                 {"data": "action", "name":"Action", "orderable": false}
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
         dom: 'l<"toolbar">frtip',
         initComplete: function(){

            $("div.toolbar")
               .html(''); 
            var r = $('#unassigntbl tfoot td');
            $("div.toolbar")
                  .append('<span class="tmpspan" >Filter: </span>');
            $("div.toolbar")
                  .append(r);

            // $("#unassigntbl_filter label").after('<button disabled="disabled" id="multiple-delete-id" class="ml-5 btn btn-danger unassigned-services-element">Delete Services</button>');

            // Connect the filter inputs to filter data
            $('#priority_filter').on('input', function() { // PRIORITY
                  var filter_input_val = this.querySelector('input').value;
                  table.columns( 1 ).search( filter_input_val ).draw();
            });

            // Connect the filter inputs to filter data
            $('#service_name_filter').on('input', function() { // SERVICE NAME
                  var filter_input_val = this.querySelector('input').value;
                  table.columns( 2 ).search( filter_input_val ).draw();
            });

            // Connect the filter inputs to filter data
            $('#property_type_filter').on('input', function() { // PROPERTY TYPE
                  var filter_input_val = this.querySelector('input').value;
                  table.columns( 9 ).search( filter_input_val ).draw();
            });

            // Connect the filter inputs to filter data
            $('#service_area_filter').on('input', function() { // SERVICE AREA
                  var filter_input_val = this.querySelector('input').value;
                  table.columns( 10 ).search( filter_input_val ).draw();
            });

            // BLUE ROWS for rescheduled on page load
            $('.myCheckBox').each(function() {
                  var row_job_mode = $(this).data('row-job-mode');
                  if (row_job_mode == 2) {
                     $(this).parent().parent().addClass('rescheduled_row');
                  }
            });

            // CALCULATE total square feet
            $('.myCheckBox').change(function(){
                  
                  var sqftTotal = 0;
                  $('#unassigntbl tbody input:checked').each(function() {
                     sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(5).html());
                  });
                  $('#totalSqFt').val(sqftTotal); 
               
                  //uncheck "select all", if one of the listed checkbox item is unchecked
                  if(this.checked == false){ //if this item is unchecked
                        $("#select_all")[0].checked = false; //change "select all" checked status to false
                  }
                  
                  //check "select all" if all checkbox items are checked
                  if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){ 
                        $("#select_all")[0].checked = true; //change "select all" checked status to true
                  }
            });

            // FIRE EVERYTIME AFTER TABLE HAS RENDERED
            table.on( 'draw', function () {

                  // BLUE ROWS for rescheduled on ajax table refresh
                  $('.myCheckBox').each(function() {
                        var row_job_mode = $(this).data('row-job-mode');
                        if (row_job_mode == 2) {
                           $(this).parent().parent().addClass('rescheduled_row');
                        }
                  });

                  // CALCULATE total square feet on ajax table refresh
                  $('.myCheckBox').change(function(){
                        
                        var sqftTotal = 0;
                        $('#unassigntbl tbody input:checked').each(function() {
                           sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(5).html());
                        });
                        $('#totalSqFt').val(sqftTotal); 
                     
                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        if(this.checked == false){ //if this item is unchecked
                              $("#select_all")[0].checked = false; //change "select all" checked status to false
                        }
                        
                        //check "select all" if all checkbox items are checked
                        if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){ 
                              $("#select_all")[0].checked = true; //change "select all" checked status to true
                        }
                  });
            } );

         },
		    buttons:[
				{
               extend: 'colvis',
               text: '<i class="icon-grid3"></i> <span class="caret"></span>',
               className: 'btn bg-indigo-400 btn-icon',
				   columns: [1,2,3,4,5,6,7],
				},
				],
	   });
   });

   $('input[name=changeview]').click(function () {
      $("#loading").css("display","block");
      var url = '';
      var sectionType = "";            
      if($(this).prop("checked") == true){
         $('.archived-services-element').addClass('hidden');
         $('.unassigned-services-element').removeClass('hidden');
         url = "<?= base_url('admin/assignJobs?ajax-call=true') ?>";
         sectionType = "unassigned_services";
      }
      else if($(this).prop("checked") == false){
         $('.archived-services-element').removeClass('hidden');
         $('.unassigned-services-element').addClass('hidden');
         url = "<?= base_url('admin/archivedJobs') ?>";
         sectionType = "archived_services";
      }
      table.clear().draw();
      $.ajax({
         type: "GET",
         url: url,    
         dataType: 'json'
      }).done(function(data){
         $("#loading").css("display","none");
         if (data.status==200) {
            console.log(data.records);
            if(data.records.length > 0) {
               $.each(data.records, function(i,datarecord){
                  var rowNode = [];
                  rowNode.push("<input  name='group_id' type='checkbox'  value='"+datarecord.customer_id+":"+datarecord.job_id+":"+datarecord.program_id+":"+datarecord.property_id+"' class='myCheckBox' />");
                  rowNode.push(datarecord.priority);
                  rowNode.push(datarecord.job_name);
                  rowNode.push(datarecord.first_name+' '+datarecord.last_name);
                  rowNode.push(datarecord.property_title);
                  rowNode.push(datarecord.job_completed_date);
                  rowNode.push(datarecord.property_address);
                  var property_type = "";
                  switch (datarecord.property_type) {
                     case 'Commercial':
                        property_type = 'Commercial';
                        break;
                     case 'Residential':
                        property_type = 'Residential';
                        break;
                     default:
                     property_type = 'Commercial';
                     break;
                  }               
                  rowNode.push(property_type);
                  rowNode.push(datarecord.category_area_name);
                  rowNode.push(datarecord.program_name);
                  rowNode.push(datarecord.reschedule_message);
                  if(sectionType == "unassigned_services") {
                     rowNode.push("<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='confirm_delete_unassign_job button-next' grd_ids='"+datarecord.customer_id+":"+datarecord.job_id+":"+datarecord.program_id+":"+datarecord.property_id+"'><i class='icon-trash position-center' style='color: #9a9797;'></i></a></li></ul>");
                  } else {
                     rowNode.push("<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='confirm_restore_unassign_job button-next' grd_ids='"+datarecord.customer_id+":"+datarecord.job_id+":"+datarecord.program_id+":"+datarecord.property_id+"'><i class='icon-undo position-center' style='color: #9a9797;'></i></a></li></ul>");
                  }               
                  table.row.add(rowNode).draw();
               });
            } else {
               $('.dataTables_empty').html("You do not have any archived services.");
            }  
            
         } else {
            swal({
               type: 'error',
               title: 'Oops...',
               text: 'Something went wrong!'
            })
         }
      });
   });   
   ; 
   
   
   
   
   
   
    $('.primary-assign').click(function () {
   
           if($(this).val() == 1){
             $('#route_input').css('display','none');
             $('#route_select').css('display','block');
           }
   
           else if($(this).val() == 2){
             $('#route_input').css('display','block');
             $('#route_select').css('display','none');
           }   
   
   });
   
   
   $(document).on("click","#changespecifictime", function (e) {
        if($(this).prop("checked") == true){
               $('#specific_time_input').css('display','block');           
            }
           else if($(this).prop("checked") == false){
             $('#specific_time_input').css('display','none');
           }   
   
   }); 

   
</script>     
<!-- /////  for multiple delete  -->
<script type="text/javascript">
  
	
   
     $('#allMessage').click(function(){ //iterate all listed checkbox items    
     
       var numberOfChecked = $('input:checkbox[name=group_id]:checked').length;
         if (numberOfChecked==1) {
           $('.specificTimeDivision').html('<div class="row"><div class="col-sm-6"><label>Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-control styled" name="specific_time_check" value="1" id="changespecifictime" ></label><div id="specific_time_input" style="display:none;" ><div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" readonly name="specific_time" placeholder="Specific Time"  >        <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>');
   
           reassignCheckboxAnTimePicker();
   
   
         } else {
           $('.specificTimeDivision').html('');
   
         }
   
     });
   
   
   
   
   
   
   // for assign job 
   
   $( "#technician_id" ).change(function() {
        technician_id = $(this).val();
      jobAssignDate =$('#jobAssignDate').val();
      route_select_id = 'route_select';
      routeMange(technician_id,jobAssignDate,route_select_id);
   });
   
     $( "#jobAssignDate" ).change(function() {
   
        $("#technician_id").trigger("change");
   });
   
   
   
   
   
   
   //  for multiple edit assign job  
      
   
   
   
   
   function routeMange(technician_id,jobAssignDate,route_select_id,selected_id='') {
      
      $('#'+route_select_id).html('');
      if (technician_id!='' && jobAssignDate!='' ){
   
           $.ajax({
              type: "POST",
              url: "<?= base_url('admin/getTexhnicianRoute') ?>",
              data: {technician_id : technician_id , job_assign_date : jobAssignDate },
              dataType : "json",
           }).done(function(data){
   
           if (data.length===0) {
             $('#'+route_select_id).append('<option value="">No route found</option>');
           } else {
           $.each(data, function( index, value ){  
   
                if (value.route_id==selected_id) {
                   selected = 'selected';
                  }else {
           selected = '';            
                  }
   
   
                 $('#'+route_select_id).append('<option value="'+value.route_id+'" '+selected+' >'+value.route_name+'</option>');
               });
           } 
           });
      }
    
   }
   
</script>
<!-- /////  -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
   $('.clockpicker').clockpicker();
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/highlight.min.js"></script>
<script type="text/javascript">
   hljs.configure({tabReplace: '    '});
   hljs.initHighlightingOnLoad();
</script>
