<style type="text/css">
  #dvMap {
       height: 865px;
       margin-left: -20px;
       margin-top: -20px;
      }
   .datatable-footer, .datatable-header{
    padding: 0
   }   

   .panel-flat > .panel-heading {
      padding-top: 0;
      padding-bottom: 0;
   }
   .toolbar {
    float: left;
    padding-left: 5px;
}
#modal_add_service .row {
  margin-bottom: 5px;
}
.label-gray , .bg-gray  {
  background-color: #808080;
  background-color: #808080;
  border-color: #808080;
}

</style>

<div class="content">
    <div class="panel panel-flat">

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
                               Map view&nbsp;<input name="changeview" type="checkbox" class="switchery-primary" checked="checked">
                                  Table view
                               </label>
                              </div>                             
                           </div>

                        </div>


                      </div>
                         
                   </h5>
              </div>
          </div> 
      
  

      <div class="panel-body">
        
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; 
         ?></b>


       <div class="row">


        <div class="col-md-4" id="mapdiv" >
             <div id="dvMap"></div>
        </div>

    

        <div class="col-md-8" id="tablediv" >


                    <div  class="table-responsive table-spraye">
                       <table  class="table datatable-basic">    
                            <thead>  
                                <tr>
                                    <th><input type="checkbox"  id="select_all" <?php if (empty($prospects)) { echo 'disabled'; }  ?>    /></th>  
                                    <th >Property Name</th>                        
                                    <th>Address</th>
                                    <!-- <th>Address 2</th>
                                    <th>City, State, Zip</th>
                                    <th>Area</th>
                                    <th>Type</th>
                                    <th>Yard Square Feet</th>
                                    <th>Notes</th> -->
                                    <th>Program</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Source</th>
                                    <th>Action</th>
                                </tr>  
                            </thead>
                            <tbody>
                            <?php $name = "";
								if (!empty($prospects)) { foreach ($prospects as $key => $value) {

									if (!empty($value->customer_id)) {
										foreach ($value->customer_id as $value2) {
                                              $name =  $value2->first_name.' '.$value2->last_name;
                                             }
									}
                            

                             ?>

                                <tr>
                                    <td>
                                         <input type="checkbox" class="myCheckBox map" value='<?php echo $key?>' property_id="<?= $value->property_id ?>" name="selectcheckbox" data-propname="<?php echo $value->property_title." - ".$name; ?>" >
                                    </td>
                                    <td style="text-transform: capitalize;"><a href="<?=base_url("admin/editProperty/").$value->property_id ?>"><?= $value->property_title ?></a></td>
                                    
                                    <td><?= $value->property_address ?></td>
                                 
                                    <td><?php $data=array(); if (!empty($value->program_id)) {
                                                
                                             foreach ($value->program_id as $value2) {
                                              $data[] =  $value2->program_name;
                                             }
                                         echo   implode(' , ',$data);
                                         $data = '';

                                          } ?></td>
                                     <td><?php $data=array();  if (!empty($value->customer_id)) {
                                                
                                             foreach ($value->customer_id as $value2) {
                                              $data[] =  '<a href="'.base_url("admin/editCustomer/").$value2->customer_id.'" >'.$value2->first_name.' '.$value2->last_name.'</a>';
                                             }
                                         echo   implode(' , ',$data);
                                         $data = '';

                                          }  ?></td>

                                      <td>
                                        
                                         <?php if ($value->property_status==2) { ?>
                                          <span class="label label-gray">Prospect</span>
                                         
                                        <?php } else { ?>
                                         <span class="label label-danger">Non-Active</span>
                                        <?php } ?>
                                   
                                      </td>
                                      <td>
                                        <?php
                                          foreach($source_list as $source){
                                            if($source->source_id == $value->source){
                                              echo $source->source_name
                                              ?>
                                              <?php
                                            }
                                          }
                                        ?>
                                      </td>
                                    
                                    <td class="table-action">


                                    <ul style="list-style-type: none; padding-left: 0px;">
                                                
                                                <li style="display: inline; padding-right: 10px;">
                                      <a href="<?=base_url("admin/editProperty/").$value->property_id ?>" class="button-next"><i class="icon-pencil   position-center" style="color: #9a9797;"></i></a>
                                                </li>
                                                <li style="display: inline; padding-right: 10px;">
                                                    <a href="<?=base_url("admin/prospectDelete/").$value->property_id ?>" class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                                </li>

                                    </ul>
                                    </td>
                                </tr>
                            
                            <?php } } ?>

                            </tbody>
                        </table>
                     </div>

     
         
         
        </div>         
       </div>
      </div>
      </div>
</div>

  <!-- Primary modal -->
          <div id="modal_add_csv" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Add Property</h6>
                </div>

              <form  name="csvfileimport"   action="<?= base_url('admin/addPropertyCsv') ?>" method="post" enctype="multipart/form-data" >

              <div class="modal-body">
                    
               
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">                   
                          <label>Select csv file</label>
                          <input type="file"  name="csv_file">
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
          <!-- /primary modal -->


          <div id="modal_assign_program" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Assign Program</h6>
                </div>

              <form  name="assignProgram" action="<?= base_url('admin/assignProgramToProperies') ?>" method="post" enctype="multipart/form-data" >

              <div class="modal-body">
                    
               
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">                   
                          <label>Assign Program</label>

                            <select class="form-control" name="program_id" id= "selected_program_id">
                              <option value="">Select Any Program</option>
                            <?php if($programlist) {  foreach ($programlist as $value) { ?>
                                <option value="<?= $value->program_id ?>"><?= $value->program_name ?></option>
                            <?php } } ?>
                            </select>

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
<!---  Add Service Modal --->
		<div id="modal_add_service" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Add Service</h6>
                </div>

              <form  name="addService" method="post" enctype="multipart/form-data" >

              <div class="modal-body">


                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">                   
                          <label>Add Service</label>
                            <select class="form-control" name="job_id" id="selected_job_id" required>
                              <option value="">Select Any Service</option>
                            <?php if($servicelist) {  foreach ($servicelist as $value) { ?>
                                <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>
                            <?php } } ?>
                            </select>
                        </div>
						</div>
						<div class="row">
							<div class="col-sm-12">                   
                         	 <label>Pricing</label>
								<select class="form-control" name="program_price" id="add_service_program_price" required>
									<option value="">Select Any Pricing</option>
									<option value=1>One-Time Service Invoicing</option>
									<option value=2>Invoiced at Service Completion</option>
									<option value=3>Manual Billing</option>
								</select>
                        </div>
                      </div>
				  </div>
				  <div class="form-group">
						<div class="row" id="price_override_modal">
							
							<div class="col-sm-12" id="addServicePOLabel">                   
                   			<label><b>Price Override Per Service</b></label>
                        	</div>
						</div>
                    </div>
                        
                     <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" id="addServiceSubmit" class="btn btn-success">Save</button>
                     </div>
                   </div>
                </form>
              </div>
            </div>
          </div> 
<!-------------------------------------------->

<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>
<script type="text/javascript">
  $('.confirm_delete').click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');
   swal({
  title: 'Are you sure?',
  text: "You won't be able to recover this !",
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#009402',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes',
  cancelButtonText: 'No'
}).then((result) => {

  if (result.value) {
   window.location = url;
  }
})


});
</script>   

<?php 
$marker = array();
if (!empty($prospects)) {

  foreach ($prospects as $key => $value) {
      $marker[] = array(
                         "index"=>$key,
                         "name"=> $value->property_title,
                         "address"=> $value->property_address,
                         "lat"=> $value->property_latitude,
                         "lng"=> $value->property_longitude
                     ); 

  } 
}

 ?>


<script>

    $('input[name=changeview]').change(function () {
        var mode = $(this).prop('checked');
      //  alert(mode);
        if(mode==false) {
          $('#tablediv').css('display','none');
          $('#mapdiv').css('display','block');

          $("#mapdiv").removeClass('col-md-4');
          $("#mapdiv").addClass('col-md-12');

        } else {
          
          $('#mapdiv').css('display','none');
          $('#tablediv').css('display','block');
          $("#tablediv").removeClass('col-md-8');
          $("#tablediv").addClass('col-md-12');

         
        }
        // $.ajax({
        //     type: 'POST',
        //     url: 'http://125.99.173.44/daddy_pocket/admin/userUpdate',
        //     data: {mode: mode, user_id: user_id},
        //     success: function (data)
        //     {
        //         // alert(data);
        //     }
        // });

    });
</script>


<script type="text/javascript">
  
   $('.datatable-basic').DataTable({
       "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
       "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],


       language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
        
   
            dom: 'l<"toolbar">frtip',
            initComplete: function(){
     
           $("div.toolbar")
              .html('<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled ><i class=" icon-trash btn-del"></i> Delete</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-info" id="assignbutton" onclick="assignmultiple()" disabled > <i class=" icon-plus22"></i> Assign Program</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-info" id="addServiceButton" onclick="addSingleService()" disabled > <i class=" icon-plus22"></i> Add Standalone Service</button>');           
        }       
     });


</script>


<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBH7pmiDU016Cg76ffpkYQWcFQ4NaAC2VI&sensor=false"></script>


    <script type="text/javascript">
         
        <?php
        $objJSON = json_encode($marker);
         
       //  var_dump($objJSON);

        echo "var markers  = ". $objJSON . ";\n";
        ?>

        window.onload = function () {
        LoadMap();
        };
         
         
        // alert(JSON.stringify(markers));
        var map;
        var marker;
        var dftMarker = {
          address: "",
          index: 0,
          lat: "38.640857769638046",
          lng: "-90.20640064179844",
          name: "default"          
        };
        function LoadMap() {
        if( markers.length < 1 ) {
          markers.push( dftMarker );
        }
        var mapOptions = {

            center: new google.maps.LatLng( markers[0].lat,markers[0].lng ),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        SetMarker(0);
        };
        function SetMarker(position) {
        //Remove previous Marker.
        if (marker != null) {
            marker.setMap(null);
        }
        //Set Marker on Map.
        if(position){

        }else{

          allunchecked();

        }
    };


   $("input:checkbox.map").click(function() {
    position =  $(this).val();
   
     if(!$(this).is(":checked")){
   
        var data = markers[position];
        // alert(JSON.stringify(data));
        var myLatlng = new google.maps.LatLng(data.lat, data.lng);
        marker = new google.maps.Marker({
        icon:'<?= base_url("assets/img/default.png") ?>',
        position: myLatlng,
        map: map,
        title: data.name
        });
      } else {
             
        var data = markers[position];
        var myLatlng = new google.maps.LatLng(data.lat, data.lng);
        marker = new google.maps.Marker({
        icon:'<?= base_url("assets/img/till.png") ?>',
        position: myLatlng,
        map: map,
        title: data.name
       });
    }
 }); 


 
  $("#select_all").change(function(){  //"select all" change 
    var status = this.checked; // "select all" checked status
   if (status) {
    $('#deletebutton').prop('disabled', false);
    $('#assignbutton').prop('disabled', false);
	$('#addServiceButton').prop('disabled', false);

    allchecked();


   }
   else
   {
     $('#deletebutton').prop('disabled', true);
     $('#assignbutton').prop('disabled', true);

     allunchecked();


     
   }
    $('.myCheckBox').each(function(){ //iterate all listed checkbox items
        this.checked = status; //change ".checkbox" checked status

    });
});

  $('.myCheckBox').change(function(){ //".checkbox" change 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(this.checked == false){ //if this item is unchecked
        $("#select_all")[0].checked = false; //change "select all" checked status to false


    }
    
    //check "select all" if all checkbox items are checked
    if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){ 
        $("#select_all")[0].checked = true; //change "select all" checked status to true
        
         
    }
});


var checkBoxes = $('table .myCheckBox');
checkBoxes.change(function () {
    $('#deletebutton').prop('disabled', checkBoxes.filter(':checked').length < 1);
    $('#assignbutton').prop('disabled', checkBoxes.filter(':checked').length < 1);
	$('#addServiceButton').prop('disabled', checkBoxes.filter(':checked').length < 1);
});
checkBoxes.change();  



function allchecked() {

console.log("All checked");

                  var infoWindow = new google.maps.InfoWindow();
                  var lat_lng = new Array();
                  var latlngbounds = new google.maps.LatLngBounds();
                  for (i = 0; i < markers.length; i++) {
                  var data = markers[i]
                  var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                  lat_lng.push(myLatlng);
                  var marker = new google.maps.Marker({
                  icon:'<?= base_url("assets/img/till.png") ?>',
                  position: myLatlng,
                  map: map,
                  title: data.title
                  });



                  
                  console.log("Before attaching PA", map)

                  latlngbounds.extend(marker.position);
                  (function (marker, data) {
                  google.maps.event.addListener(marker, "click", function (e) {
                  infoWindow.setContent(data.address);
                  infoWindow.open(map, marker);
                  });
                  })(marker, data);

                  (function (marker, data) {
                    marker.addListener('mouseover', function() {
                      console.log("Mousein");
                      // infowindow.open(map, this);
                      infoWindow.setContent(data.address);
                      infoWindow.open(map, marker);
                  });
                  })(marker, data);

                  (function (marker, data) {
                    // assuming you also want to hide the infowindow when user mouses-out
                    marker.addListener('mouseout', function() {
                        // infowindow.close();
                        console.log("Mouse out");
                        infoWindow.setContent('data.address');
                        infoWindow.close(map, marker);
                    });
                  })(marker, data);
                  // console.log("After attaching even", marker)


            }
            map.setCenter(latlngbounds.getCenter());
            map.fitBounds(latlngbounds);

}
function allunchecked() {

                  var infoWindow = new google.maps.InfoWindow();
                  var lat_lng = new Array();
                  var latlngbounds = new google.maps.LatLngBounds();
                  for (i = 0; i < markers.length; i++) {
                  var data = markers[i];
                  var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                  lat_lng.push(myLatlng);
                  var marker = new google.maps.Marker({
                  icon:'<?= base_url("assets/img/default.png") ?>',
                  position: myLatlng,
                  map: map,
                  title: data.name
                  });
                  latlngbounds.extend(marker.position);
                  (function (marker, data) {
                  google.maps.event.addListener(marker, "click", function (e) {
                  infoWindow.setContent(data.address);
                  infoWindow.open(map, marker);
                  });
                  })(marker, data);
            }
            map.setCenter(latlngbounds.getCenter());
            map.fitBounds(latlngbounds);

}
 
function deletemultiple() {

     swal({
      title: 'Are you sure?',
      text: "You won't be able to recover this !",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#009402',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then((result) => {

      if (result.value) {

       var selectcheckbox = [];
       $("input:checkbox[name=selectcheckbox]:checked").each(function(){
             selectcheckbox.push($(this).attr('property_id'));
        }); 
       
     //  alert(selectcheckbox);

     
          $.ajax({
             type: "POST",
             url: "<?= base_url('admin/deletemultipleProspects') ?>",
             data: {prospects : selectcheckbox }
          }).done(function(data){
                  if (data==1) {
                    swal(
                       'Prospect !',
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


 function assignmultiple() {


    var selected = [];
   
        $("input:checkbox[name=selectcheckbox]:checked").each(function(){
             selected.push($(this).attr('property_id'));
        }); 
 
   if (selected.length > 0) {
      $('#modal_assign_program').modal('show');

    } else {
   
       swal({
             type: 'error',
             title: 'Oops...',
             text: 'Something went wrong!'
           })
    }
 }
$('#modal_add_service').on('hidden.bs.modal', function (e) {
  $('div#price_override_modal').not('div#addServicePOLabel').empty();
});		
		
 function addSingleService() {
    var selected = [];
	var property_names = [];
   
        $("input:checkbox[name=selectcheckbox]:checked").each(function(){
             selected.push($(this).attr('property_id'));
			 property_names.push($(this).data('propname'));
        }); 
	 
 
   if (selected.length > 0) {
	   $(selected).each(function(i){
		$('div#price_override_modal').append('<div class="col-sm-12"><label>'+property_names[i]+'</label><input type="number" class="form-control" min=0 name="add_job_price_override['+i+']" value="" placeholder="(Optional) Enter Price Override Here" data-propname="'+property_names[i]+'" data-propid="'+this+'"></div>');
		
	});
      $('#modal_add_service').modal('show');

    } else {
       swal({
             type: 'error',
             title: 'Oops...',
             text: 'Something went wrong!'
           })
    }
 }

$('form[name="addService"] button[type="submit"]').on('click', function(e){
	e.preventDefault();  
//// NEED TO FORMAT FOR MULTIPLE PROPERTIES ////
	var serviceId = $('#selected_job_id').val();
	var serviceName = $('#selected_job_id option:selected').text();
	var programPrice = $('select#add_service_program_price').val();
  $('#add_service_program_price').parent().children('.error').remove();
  $('#selected_job_id').parent().children('.error').remove();

  if (programPrice == '')
  {
      var error_label = '<label id="program-price-error" class="error" for="program_price">Please select a pricing method</label>';
      var el = $('#add_service_program_price').parent().append(error_label);
  }

  if (serviceId == '')
  {
      var error_label = '<label id="service-error" class="error" for="job_id">Please select a service</label>';
      var el = $('#selected_job_id').parent().append(error_label);
  }

  if (programPrice == '' || serviceId == '')
  {
      return;
  }
	var post = [];
	$('div#price_override_modal input').each(function(n){
		var propertyId = $(this).data('propid');
		var propertyName = $(this).data('propname');
		var programName = serviceName + "- Standalone";
		var priceOverride = $(this).val();
		
		
    if (priceOverride == '') {
      var price_override_set = 0;
    } else {
        var price_override_set = 1;
    }
		
		var property = {
			service_id:serviceId,
			service_name:serviceName,
			property_id: propertyId,
			property_name:propertyName,
			program_name: programName,
			price_override:priceOverride,
			is_price_override_set:price_override_set,
			program_price:programPrice
		};	
		
		post.push(property);
    // console.log(post);
	});
	//post = JSON.stringify(post);
	//post = JSON.parse(post);
	//console.log(post);
    $.ajax({

		  type: 'POST',
		  url: "<?=base_url('admin/job/addJobToProperty')?>",
		  data: {post},
		  dataType: "JSON",
		  success: function (data){
			console.log(data)

		  }

		 }).done(function(data){
			if (data.status=="success") {
          $('#modal_add_service').modal('hide');
          $('div#price_override_modal').empty();
          swal(
              'Success!',
              'Service Added Successfully',
              'success'
          )
      } else {
          swal({
              type: 'error',
              title: 'Oops...',
              text: 'Please select a service and a pricing method'
          })
      }
          });
	
});

 
 
    </script>
