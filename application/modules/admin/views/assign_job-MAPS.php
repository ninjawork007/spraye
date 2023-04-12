
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
<script>
   var global_r = '';
</script>


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
                          
                             <a href="<?= base_url('admin/manageJobs')?>" id="save" class="btn btn-primary"></i>Manage Scheduled Services</a>

                             <button disabled="disabled" id="multiple-delete-id" class="ml-5 btn btn-danger unassigned-services-element">Delete Services</button>
                             
                             
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

        <div class="panel panel-flat">
             <div class="panel-heading">
                   <h5 class="panel-title">
                     <div class="form-group">

                        <div class="row">
                          
                           <div class="col-md-6">
                        
                           </div>

                           <div class="col-md-6 toggle-btn">
                             <div style="float: right;"> 
                               <label>
                               Map view&nbsp;<input name="changeview" type="checkbox" class="switchery-primary" >
                                  Table view
                               </label>
                              </div>                             
                           </div>

                        </div>


                      </div>
                         
                   </h5>
              </div>
        </div> 


      <!-- <div class="panel-body">
         <h5 class="panel-title">Users Details</h5>
         </div>-->
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="col-lg-12 col-md-12 col-sm-12">
               <div class="panel panel-flat">
                  <div style="background: #fafafa;padding-top: 10px;padding-bottom: 20px;color: #333;padding-left: 12px;">
                     <span class="unassigned-services-element text-semibold" style="font-size:15px;">Unassigned Services</span>  
                     <span class="archived-services-element text-semibold hidden" style="font-size:15px;">Archived Services</span>  
                     <span class="unassigned-services-element">Highlighted jobs indicate this job has been skipped and needs to be rescheduled</span>            
                     
										  
					  <div class="unassigned-services-element" style="float: right;margin-right: 18px;">
						  <input placeholder="Total Sq Feet" id="totalSqFt" type="text" style="">
                        <button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">
                        Assign Technician</button>
                     </div>
                  </div>
                  <div class="panel-body"  style="padding: 20px 0px;">
                     <div class="row">
                        <div class="col-md-4" id="mapdiv" >
                            <div id="dvMap" style="height:800px;">map div area</div>
                        </div>

                        <div class="col-md-8" id="tablediv" >
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
                                        <th>Property Longitude</th>
                                        <th>Property Latitude</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Zip</th>
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
                        <input type="date"  name="job_assign_date" class="form-control  pickadate" id="jobAssignDate" placeholder="YYYYY-MM-DD">
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
               <input type="hidden" name="group_id_new" id="group_id_new" >
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

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>"></script> 

<script>
    $('input[name=changeview]').change(function () {
        var mode = $(this).prop('checked');
      //  alert(mode);
        if(mode==false) {
         //   console.log("False etay")
          $('#tablediv').css('display','block');
          $('#mapdiv').css('display','block');

         //  $("#mapdiv").removeClass('col-md-4');
         //  $("#mapdiv").addClass('col-md-12');
         
         //  $("#tablediv").removeClass('col-md-8');
          $("#tablediv").addClass('col-md-8');

        } else {
         //   console.log("Beya")
          
          $('#mapdiv').css('display','none');
          $('#tablediv').css('display','block');
          $("#tablediv").removeClass('col-md-8');
          $("#tablediv").addClass('col-md-12');

         
        }
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
      
      // $("input:checkbox[name=group_id]:checked").each(function(){
      //    group_id.push($(this).data('group_id_new'));
      // });

      var all_checked_boxes = $('input:checkbox[name=group_id]:checked');
      for (let i = 0; i < all_checked_boxes.length; i++) {
         var a_checked_box_val = all_checked_boxes[i].getAttribute('data-realvalue');
         group_id.push(a_checked_box_val);
      }



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

      // console.log(post_data);

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

      function getRowNum() {
         let e = new Error();
         e = e.stack.split("\n")[2].split(":");
         e.pop();
         return e.pop();
      }

    var MapMarkers = [];

    var map;
    var marker;
    var markers = [];
    var filteredMarkers = [];

    var tableData = [];
    var filteredData = [];

    var allChecked = false;

     // Setup - add a text input to each footer cell
   $('#unassigntbl tfoot td').each( function () {
       var title = $(this).text();
       if (title=='PRIORITY' || title=='SERVICE NAME' || title=='PROPERTY TYPE' || title=='SERVICE AREA' ) {
         $(this).html( '<input type="text" class="form-control dtatableInput" placeholder="'+title+'" />' );
       } else {
         $(this).addClass('noSpacingInput');
       }
   } );

    function LoadMap() {

      // reset sqtf calc
      var sqftTotal = 0;
      $('#totalSqFt').val(sqftTotal); 

        var mapOptions = {
            center: new google.maps.LatLng(41.881832, -87.623177),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        SetMarker(0);

        google.maps.event.addListener(map, 'bounds_changed' , function () {

            // reset sqtf calc
            var sqftTotal = 0;
            $('#totalSqFt').val(sqftTotal); 

            filteredData = [];
            for (let i=0;i<tableData.length;i++) { 
               if (map.getBounds().contains(new google.maps.LatLng(tableData[i].property_latitude, tableData[i].property_longitude))) {
                  filteredData.push(tableData[i]);
               }
            }

          table = $('#unassigntbl').DataTable({
             data: filteredData,
             destroy: true,
             deferRender:false, 
        	    columnDefs: [
				{"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}},
			 ],
		     select:"multi",
             columns: [
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
                   {"data": "property_longitude", "name":"Property Longitude", "orderable": true},
                   {"data": "property_latitude", "name":"Property Latitude", "orderable": true},
                   {"data": "property_state", "name":"State", "orderable": true},
                   {"data": "property_city", "name":"City", "orderable": true},
                   {"data": "property_zip", "name":"Zip", "orderable": true},
                   {"data": "action", "name":"Action", "orderable": false}
		          ],
                pageLength:100,
                order:[[1,"asc"]],
                paging: true,
                processing: true,
                dom: 'l<"toolbar">frtip',
                initComplete: function(){
                    var cb = document.getElementById('#select_all');
                    addCheckBoxEvent();
                    unCheckAll();

                     // apply filters
                     $("div.toolbar").html('');
                     $("div.toolbar").append('<span class="tmpspan" >Filter: </span>');
                     $("div.toolbar").append(global_r);

                     // clear filters
                     var all_filter_boxes = document.querySelectorAll('input.dtatableInput');
                     all_filter_boxes.forEach(e => {
                        e.value = '';
                     })

                     // clear select all box
                     $("#select_all")[0].checked = false;

                }
             });

            table.on( 'search.dt', function () {
                var filtered = table.rows( { filter : 'applied'} ).data();
                filterPins(filtered);
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
            });
        });
    }

    function addCheckBoxEvent() {
        $("input:checkbox.map").click(function() {

         position =  $(this).val();
         for (let i=0; i<filteredMarkers.length; i++) {
            if (position == filteredMarkers[i].index) {
               var data = filteredMarkers[i];
            }
         }
               //  let data = filteredMarkers[position];

			// console.log(JSON.stringify(data));
				// console.log('data.lat: '+data.lat); 
				// console.log('data.lng: '+data.lng); 

               //  console.log(MapMarkers);

                let mapMarker = MapMarkers[position];
                let myLatlng = new google.maps.LatLng(data.lat, data.lng);
                let title = data.title;

                //console.log(MapMarkers);
                if (mapMarker != null) {
                    mapMarker.setMap(null);
                }

                if(!$(this).is(":checked")){
                    
                        marker = new google.maps.Marker({
                        icon:'<?= base_url("assets/img/default.png") ?>',
                        position: myLatlng,
                        map: map,
                        title: title
                    });

                    MapMarkers[position] = marker;
                } else {
                    //var data = markers[position];
                    //var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                    marker = new google.maps.Marker({
                        icon:'<?= base_url("assets/img/till.png") ?>',
                        position: myLatlng,
                        map: map,
                        title: title
                    });
                    MapMarkers[position] = marker;
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
    }

    function filterPins(filtered) {
        addCheckBoxEvent();

      //   for (let i=0; i< MapMarkers.length; i++) {
      //       MapMarkers[i].setMap(null);
      //   }

         MapMarkers.forEach(item => {
            item.setMap(null);
         });

        MapMarkers = [];
        filteredMarkers = [];

            for (let i=0; i< markers.length; i++) {
                let flag = false;
                let data;

                    for (let j=0; j< filtered.length; j++) {
                        if (markers[i].lat == filtered[j].property_latitude && markers[i].lng == filtered[j].property_longitude) {
                            flag = true;
                            data = markers[i];
                        }
                    }

                var infoWindow = new google.maps.InfoWindow();

                if (flag == true) {
                    let myLatlng = new google.maps.LatLng(data.lat, data.lng);

                        marker = new google.maps.Marker({
                            icon:'<?= base_url("assets/img/default.png") ?>',
                            position: myLatlng,
                            map: map,
                            title: "a2"
                        });

                        filteredMarkers.push(data);
                        MapMarkers.push(marker);

                        (function (marker, data) {
                            marker.addListener('mouseover', function() {
                            // infowindow.open(map, this);
                            infoWindow.setContent(data.address);
                            infoWindow.open(map, marker);
                        });
                    })(marker, data);

                    (function (marker, data) {
                    // assuming you also want to hide the infowindow when user mouses-out
                    marker.addListener('mouseout', function() {
                        infoWindow.setContent('data.address');
                        infoWindow.close(map, marker);
                    });
                })(marker, data);
            }
        }
    }
    
    function SetMarker(position) {
        //Remove previous Marker.
        if (marker != null) {
            marker.setMap(null);
        }
        //Set Marker on Map.
        if(position){

        }else{
            if (markers.length > 0) {
                allunchecked();
            }
        }
    }

    $("#select_all").change(function(){  //"select all" change 

        var status = this.checked; // "select all" checked status
        if (status) {
            $('#allMessage').prop('disabled', false);
            $('#multiple-delete-id,#multiple-restore-id').prop('disabled', false);
            checkAll();
        }
        else
        {
            $('#allMessage').prop('disabled', true);
            $('#multiple-delete-id,#multiple-restore-id').prop('disabled', true);
            unCheckAll();
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


    function unCheckAll() {
        allchecked = false;
        var infoWindow = new google.maps.InfoWindow();

      //   for (let i=0; i< MapMarkers.length; i++) {
      //       MapMarkers[i].setMap(null);
      //       console.log(MapMarkers[i])
      //   }

         MapMarkers.forEach(item => {
            item.setMap(null);
         });

        MapMarkers = [];
        //filteredMarkers = markers;

        for (i = 0; i < filteredMarkers.length; i++) {
            var data = filteredMarkers[i]
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);


            var marker = new google.maps.Marker({
            icon:'<?= base_url("assets/img/default.png") ?>',
                    position: myLatlng,
                    map: map,
                    title: 'data.title2'
                });
            
            (function (marker, data) {
                google.maps.event.addListener(marker, "mouseover", function (e) {
                    infoWindow.setContent(data.address);
                    infoWindow.open(map, marker);
                });
            })(marker, data);

            (function (marker, data) {
                // assuming you also want to hide the infowindow when user mouses-out
                marker.addListener('mouseout', function() {
                infoWindow.setContent(data.address);
                infoWindow.close(map, marker);
                });
            })(marker, data);
            
            MapMarkers.push(marker);
        }

        // disable buttons that need checkboxes clicked
        $('#allMessage').prop('disabled', true);
        $('#multiple-delete-id').prop('disabled', true);

    }

    function allunchecked() {
        allchecked = false;

        var infoWindow = new google.maps.InfoWindow();
        var lat_lng = new Array();
        var latlngbounds = new google.maps.LatLngBounds();
        
      //   for (let i=0; i< MapMarkers.length; i++) {
      //       MapMarkers[i].setMap(null);
      //   }

         MapMarkers.forEach(item => {
            item.setMap(null);
         });

        MapMarkers = [];
        filteredMarkers = [];

        for (i = 0; i < markers.length; i++) {
            var data = markers[i]
            filteredMarkers.push(data);
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);

            var marker = new google.maps.Marker({
            icon:'<?= base_url("assets/img/default.png") ?>',
                    position: myLatlng,
                    map: map,
                    title: 'data.title2'
                });

            latlngbounds.extend(marker.position);
            
            (function (marker, data) {
                  google.maps.event.addListener(marker, "mouseover", function (e) {
                  //   console.log("Mouse over 900");
                    infoWindow.setContent(data.address);
                    infoWindow.open(map, marker);
                    //this functio to be removed
                  });
                  })(marker, data);

         

         (function (marker, data) {
            // assuming you also want to hide the infowindow when user mouses-out
            marker.addListener('mouseout', function() {
               // infowindow.close();
               // console.log("Mouse out 900");
               infoWindow.setContent(data.address);
               infoWindow.close(map, marker);
            });
           })(marker, data);
            
            
            MapMarkers.push(marker);
        }

        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
    }

    function checkAll() {
        allchecked = true;
        var infoWindow = new google.maps.InfoWindow();

      //   console.log(MapMarkers);

      //   for (let i=0; i< MapMarkers.length; i++) {
      //      MapMarkers[i].setMap(null);
      //   }

         MapMarkers.forEach(item => {
            item.setMap(null);
         });

        MapMarkers = [];
        //filteredMarkers = markers;

        for (i = 0; i < filteredMarkers.length; i++) {
            var data = filteredMarkers[i];
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);

            var marker = new google.maps.Marker({
                icon:'<?= base_url("assets/img/till.png") ?>',
                position: myLatlng,
                map: map,
                title: data.title
            });

            (function (marker, data) {
                google.maps.event.addListener(marker, "mouseover", function (e) {
                    infoWindow.setContent(data.address);
                    infoWindow.open(map, marker);
                });
            })(marker, data);

            (function (marker, data) {
                // assuming you also want to hide the infowindow when user mouses-out
                marker.addListener('mouseout', function() {
                infoWindow.setContent(data.address);
                infoWindow.close(map, marker);
                });
            })(marker, data);

            //marker.setMap(map);
             MapMarkers.push(marker);
        }
    }

    function allchecked(){
        allchecked = true;
        var infoWindow = new google.maps.InfoWindow();
        var lat_lng = new Array();
        var latlngbounds = new google.maps.LatLngBounds();

      //   for (let i=0; i< MapMarkers.length; i++) {
      //       MapMarkers[i].setMap(null);
      //   }
         MapMarkers.forEach(item => {
            item.setMap(null);
         });
        MapMarkers = [];
        filteredMarkers = [];

        for (i = 0; i < markers.length; i++) {
            var data = markers[i];
            filteredMarkers.push(data);
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            lat_lng.push(myLatlng);

            var marker = new google.maps.Marker({
                icon:'<?= base_url("assets/img/till.png") ?>',
                position: myLatlng,
                //map: map,
                title: data.title
            });

            latlngbounds.extend(marker.position);
            marker.setMap(map);
            MapMarkers.push(marker);
        }

        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
    }   

   // DataTable
   var table =  $('#unassigntbl').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		   "pageLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
		   "order":[[1,"asc"]],
		   "ajax":{
		     "url": "<?= base_url('admin/ajaxGetRouting/')?>",
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
                  {"data": "property_longitude", "name":"Property Longitude", "orderable": true},
                  {"data": "property_latitude", "name":"Property Latitude", "orderable": true},
                  {"data": "property_state", "name":"State", "orderable": true},
                  {"data": "property_city", "name":"City", "orderable": true},
                  {"data": "property_zip", "name":"Zip", "orderable": true},
                  {"data": "action", "name":"Action", "orderable": false}
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
         dom: 'l<"toolbar">frtip',
         initComplete: function(){
            tableData = table.data();

            for (let i=0; i< tableData.length; i++) {
                var marker = {
                    index: i,
                    name: tableData[i].property_name,
                    address: tableData[i].address + ", " + tableData[i].property_city + ", " + tableData[i].property_state + ", " + tableData[i].property_zip,
                    lat: tableData[i].property_latitude,
                    lng: tableData[i].property_longitude,
                }

                markers.push(marker);
            }

            LoadMap();

            $("div.toolbar")
               .html(''); 
            var r = $('#unassigntbl tfoot td');
            global_r = r;
            // console.log(global_r);
            $("div.toolbar")
                  .append('<span class="tmpspan" ></span>');
            $("div.toolbar")
                  .append(r);
            // // Connect the filter inputs to filter data
            // $('#priority_filter').on('input', function() { // PRIORITY
            //       var filter_input_val = this.querySelector('input').value;
            //       table.columns( 1 ).search( filter_input_val ).draw();
                 
            //       console.log("priority_filter")
            //       setTimeout(function() { 
            //             var filtered = table.data();
            //             filterPins(filtered);
            //        }, 1000);
            // });

            // // Connect the filter inputs to filter data
            // $('#service_name_filter').on('input', function() { // SERVICE NAME
            //     console.log("search name filter")
            //       var filter_input_val = this.querySelector('input').value;
            //       table.columns( 2 ).search( filter_input_val ).draw();

            //       setTimeout(function() { 
            //             var filtered = table.data();
            //             filterPins(filtered);
            //        }, 1000);
            // });

            // // Connect the filter inputs to filter data
            // $('#property_type_filter').on('input', function() { // PROPERTY TYPE
            //     console.log("property_type_filter")
            //       var filter_input_val = this.querySelector('input').value;
            //       table.columns( 9 ).search( filter_input_val ).draw();

            //       setTimeout(function() { 
            //             var filtered = table.data();
            //             filterPins(filtered);
            //        }, 1000);
            // });

            // // Connect the filter inputs to filter data
            // $('#service_area_filter').on('input', function() { // SERVICE AREA
            //     console.log("service_area_filter")
            //       var filter_input_val = this.querySelector('input').value;
            //       table.columns( 10 ).search( filter_input_val ).draw();

            //       setTimeout(function() { 
            //             var filtered = table.data();
            //             filterPins(filtered);
            //        }, 1000);
            // });

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

            // $("#unassigntbl_filter label").after('<button disabled="disabled" id="multiple-delete-id" class="ml-5 btn btn-danger unassigned-services-element">Delete Services</button>');

            addCheckBoxEvent();

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

/*
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
   */
   
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

<script language="javascript" type="text/javascript">
   

</script>
<!-- /////  for multiple delete  -->
<script type="text/javascript">
     $('#allMessage').click(function(){ //iterate all listed checkbox items    
     
       // display assign at specific time
       var numberOfChecked = $('input:checkbox[name=group_id]:checked').length;
         if (numberOfChecked==1) {
           $('.specificTimeDivision').html('<div class="row"><div class="col-sm-6"><label>Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-control styled" name="specific_time_check" value="1" id="changespecifictime" ></label><div id="specific_time_input" style="display:none;" ><div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" readonly name="specific_time" placeholder="Specific Time"  >        <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>');
           reassignCheckboxAnTimePicker();
         } else {
           $('.specificTimeDivision').html('');
         }

         // insert group id
         // var group_id_new = [];
         var group_id_new = '';
         var all_checked_boxes = $('input:checkbox[name=group_id]:checked');
         for (let i = 0; i < all_checked_boxes.length; i++) {
            var a_checked_box_val = all_checked_boxes[i].getAttribute('data-realvalue');
            // group_id_new.push($(this).val());

            if (i == 0) {
               group_id_new = a_checked_box_val;
            } else {
               group_id_new += ',' + a_checked_box_val;
            }
            
         }

         $("#group_id").val(group_id_new);
         $("#group_id_new").val(group_id_new);
   
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
