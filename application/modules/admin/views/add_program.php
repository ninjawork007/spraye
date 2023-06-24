<style type="text/css">
 #myTable > tbody > tr > td:hover{
    cursor:move;
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

  th , td {
  text-align: center;
  }
  .pre-scrollable {
   min-height: 0px;
  }
  @media (min-width: 769px){
.form-horizontal .control-label[class*=col-sm-] {
    padding-top: 0;
}}
</style>

 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

        <!-- Content area -->
        <div class="content form-pg">

          <!-- Form horizontal -->
          <div class="panel panel-flat">
         
             <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/programList') ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to All Programs</a>
                        </div>
                   </h5>
              </div>


            <div id="loading" > 
                <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
            </div>

              <br>
            
            <div class="panel-body">
              
        <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

              <form class="form-horizontal" action="<?= base_url('admin/addProgramData') ?>" method="post" id="add_program_form" name="addprogram" enctype="multipart/form-data" >
                <fieldset class="content-group">
                  
                  <div class="row">
                  <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Name</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" name="program_name" id="program_name" value="<?php echo set_value('program_name')?>" placeholder="Program Name">
                      <span style="color:red;"><?php echo form_error('program_name'); ?></span>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Pricing</label>
                    <div class="col-lg-10" style="padding-right: 15px;
    padding-left: 5px;">
        
                      
                        <select class="form-control" name="program_price" id="program_price" value="<?php echo set_value('program_price')?>"> 
                       
                        <option value="1">One-Time Program Invoicing</option>
                        <option value="2">Invoiced at Job Completion</option>
                        <option value="3">Manual Billing</option>
                     
                      </select>
                      <span style="color:red;"><?php echo form_error('program_price'); ?></span>
                    </div>
                    
                  </div>
                </div>
              </div>
                  
                <div class="row">
                   <div class="col-md-6 col-sm-6">
                       <div class="form-group" style="margin-bottom: 3px;">
                        <label class="control-label col-lg-2 col-md-2 col-sm-12 col-xs-12">Service List <span data-popup="tooltip-custom" title="Add Jobs to the Program in the order of application and priority." data-placement="top" >  <i class=" icon-info22 tooltip-icon"></i> </span></label>

                        <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
                          <select class="bootstrap-select form-control" name="program_job_tmp"  id="job_list" data-live-search="true">

                          <option value="" >Select any Service</option>
                            
                          <?php foreach ($joblist as $value): ?>
                              <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>  
                          <?php endforeach ?>
                          </select>
                          <span style="color:red;"><?php echo form_error('program_job'); ?></span>
                       </div>
                       <div class="col-lg-1 col-sm-2 col-xs-2 addbuttonmanage">
                          <div class="form-group">
                          <center>
                                         
                             <a href="#" data-toggle="modal" data-target="#modal_add_job"><i class="icon-add text-success" style="padding-top:6px;font-size:25px;" ></i></a>
                            
                          </center> 
                            </div>
                        </div>
                     
                    </div>
                </div>

                <input type="hidden" name="program_job" id="job_id_order_array" value="">

 
                 <div class="col-md-6">
                       <div class="form-group" style="margin-bottom: 3px;">
                        <label class="control-label col-lg-2 col-sm-12 col-xs-12">Property List</label>
                        <div class="multi-select-full col-lg-9 col-sm-10 col-xs-10">
                          <select   class="multiselect-select-all-filtering2 form-control" name="propertylistarray_tmp[]"  multiple="multiple"   id="property_list">
                          <?php if (!empty($propertylist)) { foreach($propertylist as $value){ if(isset($value->property_status) && $value->property_status != 0){ ?>
                              <option value="<?= $value->property_id ?>"><?= $value->property_title ?></option>  
                          <?php } } }?>
                          </select>
                         
                       </div>
                       <div class="col-lg-1 col-sm-2 col-xs-2 addbuttonmanage">
                          <div class="form-group">
                          <center>
                                         
                             <a href="#" data-toggle="modal" data-target="#modal_add_property"><i class="icon-add text-success" style="padding-top:6px;font-size:25px;" ></i></a>
                            
                          </center>
                            </div>
                        </div>
                     
                    </div>
                </div>

           </div>
        

           <div class="row">
            <div class="col-md-6">
                  <div class="prioritydivcontainer" style="display: none;">
                     <div  class="table-responsive  pre-scrollable">
                       <table  class="table table-bordered" id="myTable" >    
                            <thead>  
                                <tr>
                                    <th>Priority</th>                                                 
                                    <th>Service Name</th>                                                 
                                    <th>Remove</th>                                                 
                                </tr>             
                            </thead>
                            <tbody class="prioritytbody" >

                            </tbody>
                       </table>
                     </div>                    
                  </div>
               </div>

               <div class="col-md-6">
                   <textarea   name="propertylistarray" id="assign_property_ids2" style="display: none;" >[]</textarea>
 


                  <div class="property-price-over-ride-container" style="display: none;">
                     <div  class="table-responsive  pre-scrollable">
                       <table  class="table table-bordered">    
                            <thead>  
                                <tr>
                                          
                                    <th>Property Name</th>                                                 
                                    <th>Price Override Per Service</th>                                           
                                </tr>             
                            </thead>
                            <tbody class="priceoverridetbody" >

                            </tbody>
                       </table>
                     </div>                    
                  </div>
               </div>

               </div>
               <br>
                <div class="row" >  
                  <div class="col-md-6">
                     <div class="form-group">
                      <label class="control-label col-lg-2">Notes</label>
                      <div class="col-lg-10">
                        <textarea class="form-control" name="program_notes" value="<?php echo set_value('program_notes')?>" rows="5"></textarea>
                        <span style="color:red;"><?php echo form_error('program_notes'); ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-2">Days Between Services <span data-popup="tooltip-custom" title="Days between services for this program. Used to calculate service due date. Eg: 10 means service is due every 10 days. Scheduling window would be 5 - 15 days after the last program service." data-placement="top" >  <i class=" icon-info22 tooltip-icon"></i> </span></label>
                      <div class="col-lg-10">
                        <input type="number" name="program_schedule_window" class="form-control" placeholder="How many days would you like between services for this program?">
                        <span style="color:red;"><?php echo form_error('program_schedule_window'); ?></span>
                      </div>
                    </div>
                  </div>
                 </div>
                 
                
                </fieldset>

                <div class="text-right">
                  <button type="submit" id="submit-button" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>
              </form>
            </div>
          </div>
          <!-- /form horizontal -->

        </div>
        <!-- /content area -->    
    
    
     <!-- Primary modal  -->
          <div id="modal_add_job" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Add Service</h6>
                </div>

              <form  name="addjob" id="my_form"  action="<?= base_url('admin/job/addJobData') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax" >

                  <div class="modal-body">
                    
              <div class="form-group">
                <div class="row">
                          <div class="col-md-6 col-sm-6">                 
                             <label>Service Name</label>
                             <input type="text" class="form-control" name="job_name"  placeholder="Service Name">
                          </div>

                          <div class="col-md-6 col-sm-6">
                            <label>Price</label>
                            <div class="input-group">   
                                <input type="text" class="form-control" name="job_price" placeholder="Service Price">
                                <span class="input-group-btn">
                                    <span class="btn btn-success">per 1,000 sq ft.</span>
                                </span>
                           </div>
                         </div>                         
                    </div>
                </div>
                
                <div class="form-group">
                <div class="row">
                  <div class="col-md-6 col-sm-6">                   
                    <label>Service Description</label>
                    <textarea type="text" class="form-control" name="job_description" placeholder="Service Description"></textarea>
                  </div>
                  <div class="col-md-6 col-sm-6">              
                            <label>Service Note</label>
                            <textarea type="text" class="form-control" name="job_notes" placeholder="Service Notes"></textarea>
                  </div>


                </div>
                </div>
                            
            </div>
                     <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" id="assignjob" class="btn btn-success">Save</button>
                     </div>
                </form>
              </div>
            </div>
          </div> 
          <!-- /primary modal -->
 <div class="mydiv" style="display: none;">
            
    </div>
  
    


    <!-- Primary modal -->
          <div id="modal_add_property" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Add Property</h6>
                </div>

              <form  name="addproperty" id="my_form"  action="<?= base_url('admin/addPropertyDataJson') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax" >

                  <div class="modal-body">
                    
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6 col-sm-6">                 
                     <label>Property Name</label>
                     <input type="text" class="form-control" name="property_title" placeholder="Property Name">                      
                  </div>
                  <div class="col-md-6 col-sm-6">                   
                    <label>Address</label>
                  <input type="text" class="form-control" name="property_address" id="autocomplete" onFocus="geolocate()" placeholder="Address">
                  </div>
                </div>
                 <div id="map" ></div>
                   <input type="hidden" name="property_latitude" id="latitude" />
                   <input type="hidden" name="property_longitude" id="longitude" />
              </div>
 
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6"> 
                            <label>Address 2</label>
                              <input type="text" class="form-control" name="property_address_2" placeholder="Address 2">
                          </div>
                          <div class="col-md-6 col-sm-6">
                            <label>City</label>
                              <input type="text" class="form-control" name="property_city" placeholder="City" id="locality" >
                          </div>
                        </div>
                      </div>

                       <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6"> 
                            <label>State</label>
                              <select class="form-control" name="property_state" id="region" >
                                <option value="">Select State</option>
                              
                                 <option value="AL">Alabama</option>
                                    <option value="AK">Alaska</option>
                                    <option value="AZ">Arizona</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="CA">California</option>
                                    <option value="CO">Colorado</option>
                                    <option value="CT">Connecticut</option>
                                    <option value="DE">Delaware</option>
                                    <option value="DC">District Of Columbia</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="HI">Hawaii</option>
                                    <option value="ID">Idaho</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IN">Indiana</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">Massachusetts</option>
                                    <option value="MI">Michigan</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">Mississippi</option>
                                    <option value="MO">Missouri</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NV">Nevada</option>
                                    <option value="NH">New Hampshire</option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">North Carolina</option>
                                    <option value="ND">North Dakota</option>
                                    <option value="OH">Ohio</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="OR">Oregon</option>
                                    <option value="PA">Pennsylvania</option>
                                    <option value="RI">Rhode Island</option>
                                    <option value="SC">South Carolina</option>
                                    <option value="SD">South Dakota</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="TX">Texas</option>
                                    <option value="UT">Utah</option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WA">Washington</option>
                                    <option value="WV">West Virginia</option>
                                    <option value="WI">Wisconsin</option>
                                    <option value="WY">Wyoming</option>

                              </select>
                          </div>
                          <div class="col-md-6 col-sm-6">
                            <label>Zip Code</label>
                              <input type="text" class="form-control" name="property_zip" placeholder="Zip Code" id="postal-code" >
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6"> 
                            <label>Service Area</label>
                              <select class="form-control" name="property_area" value="<?php echo set_value('property_area')?>">
                                <option value="">Select Any Service Area</option>
                              <?php if (!empty($propertyarealist)) {
                                
                               foreach ($propertyarealist as $value){ ?>
                                  <option value="<?= $value->property_area_cat_id ?>"><?= $value->category_area_name ?></option>  
                              <?php } }?>
                              </select>
                          </div>
                          <div class="col-md-6 col-sm-6">
                            <div class="col-md-12 col-sm-12">
                              <label>Property Type</label>
                            </div>
                              <div class="col-md-6 col-sm-6">
                                  <label class="radio-inline">
                                       <input name="property_type" value="Commercial" type="radio" checked="checked" />Commercial
                                   </label>
                              </div>
                              <div class="col-md-6 col-sm-6">
                                   <label class="radio-inline">
                                        <input name="property_type" value="Residential" type="radio" />Residential
                                   </label>
                               </div>
                          </div>
                        </div>
                      </div>

                
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6"> 
                            <label>Yard Square Feet</label>
                             <input type="text" class="form-control" name="yard_square_feet" placeholder="Yard Square Feet">
                          </div>
                         <div class="col-md-6 col-sm-6">

                          <label class="">Assign Customer</label>
                              <div class="multi-select-full">
                                <select class="multiselect-select-all-filtering form-control" name="assign_customer[]" multiple="multiple" id="customer_list">
                                  
                                <?php if (!empty($customerlist)) {
                                
                                 foreach ($customerlist as $value) { ?>
                                    <option value="<?= $value->customer_id ?>"><?= $value->first_name ?> <?= $value->last_name ?></option>  
                                
                                <?php }} ?>
                                
                                </select>

                             </div>                           
                         </div> 
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="row">
                     
                          <div class="col-md-6 col-sm-6">
                            <label>Property Status</label>
                            <select class="form-control" name="property_status">
                                <option value="">Select Any Status</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Non-Active</option>
                              </select>                    
                          </div>
                           <div class="col-sm-6 col-md-6" style="display:<?= $setting_details->is_sales_tax==1 ? 'block' : 'none' ?> " > 
                              <label>Sales Tax Area</label>
                                 <div class="multi-select-full" >
                                   <select class="multiselect-select-all-filtering form-control" name="sale_tax_area_id[]" multiple="multiple" id="sales_tax" >
                                      <?php if (!empty($sales_tax_details)) { 
                                        foreach ($sales_tax_details as $key => $value) {
                                      ?>    
                                      <option value="<?= $value->sale_tax_area_id ?>"  ><?= $value->tax_name  ?>  </option>
                                      <?php  } } ?>
                                    </select>
                              </div>  
                                
                            </div>

                          

                        </div>
                      </div>
                      <div class="form-group">
                        <div class="row" >
                          <div class="col-md-12 col-sm-12"  >
                              <label>Property Info</label>
                              
                              <div style="border: 1px solid #12689b;" >
                              <textarea class="summernote_property" name="property_notes" > </textarea>
                                
                              </div>                             

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

          <!-- for map  -->



  




  <script src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>&libraries=places&callback=initAutocomplete"
        async defer></script>

 <script>
   // This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};

function initAutocomplete() {
  // Create the autocomplete object, restricting the search to geographical
  // location types.
  autocomplete = new google.maps.places.Autocomplete(
    /** @type {!HTMLInputElement} */
    (document.getElementById('autocomplete')), {
      types: ['geocode']
    });

  // When the user selects an address from the dropdown, populate the address
  // fields in the form.
  autocomplete.addListener('place_changed', function() {
    fillInAddress(autocomplete, "");
  });




}

function fillInAddress(autocomplete, unique) {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

    $('.mydiv').html(place.adr_address);
    return_locality = $('.locality').text();
    return_region = $('.region').text();
    return_postal_code = $('.postal-code').text();
    res = return_postal_code.split("-");
        
    $('#locality'+unique).val(return_locality);
    $('#region'+unique).val(return_region);
    $('#postal-code'+unique).val(res[0]);




  for (var component in componentForm) {
    if (!!document.getElementById(component + unique)) {
      document.getElementById(component + unique).value = '';
      document.getElementById(component + unique).disabled = false;
    }
  }

  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType] && document.getElementById(addressType + unique)) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(addressType + unique).value = val;

     //  alert(val);
    }
  }

  
}
google.maps.event.addDomListener(window, "load", initAutocomplete);

  function geolocate() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        var circle = new google.maps.Circle({
          center: geolocation,
          radius: position.coords.accuracy
        });

        //alert(position.coords.latitude);
        autocomplete.setBounds(circle.getBounds());
      });
    }
  }
</script>
    
          <!-- end  for map  -->

   


<script type="text/javascript">
   var selectedSortingValues = [];
   var selectedSortingTexts = [];
   var selectedValues = [];
   var selectedTexts = [];
   var optionValue = '';
   var optionText = '';
   $n = 1;

   $(document).on("change","#job_list",function() { 
         
    optionValue   = $(this).val();

    if (optionValue!='') {


     if ($.inArray(optionValue, selectedValues)!='-1') {
        
      } else {

        $('.prioritydivcontainer').css("display","block");

        optionText = $("#job_list option:selected").text();
       // alert(optionValue);
     //   alert(optionText);
      
        selectedValues.push(optionValue);

        selectedTexts.push(optionText);  

        var $row = $('<tr id="trid'+$n+'">'+
          '<td class="index" >'+$n+'</td>'+
          '<td>'+optionText+'</td>'+
          '<td class="removeclass" id="'+$n+'" optionValueRemove="'+optionValue+'" optionTextRemove="'+optionText+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></i></a></li></ul></td>'+
          '</tr>');    


        $('.prioritytbody:last').append($row);
        $n = $n+1;        
        $('#job_id_order_array').val(selectedValues);
      }
   } 
    
});
// REMOVE SELECTED ROW
  $(document).on("click",".removeclass",function() { 
    
    // alert(selectedValues);
    // alert(selectedTexts);

    var id = $(this).attr('id');
    var optionValueRemove = $(this).attr('optionValueRemove');
    var optionTextRemove = $(this).attr('optionTextRemove');
    
    selectedValues.splice($.inArray(optionValueRemove, selectedValues),1);
    selectedTexts.splice($.inArray(optionTextRemove, selectedTexts),1);

    $("#trid"+id).remove();

    $('#job_id_order_array').val(selectedValues);

    // alert(selectedValues);
    // alert(selectedTexts);
    rearrangetable();
  

  });

  


  function rearrangetable() {

    $('.prioritytbody').empty();
     $n = 1;
     $.each(selectedValues, function(i, item) {

       

          var $row = $('<tr id="trid'+$n+'">'+
          '<td class="index" >'+$n+'</td>'+
          '<td>'+selectedTexts[i]+'</td>'+
          '<td class="removeclass" id="'+$n+'" optionValueRemove="'+selectedValues[i]+'" optionTextRemove="'+selectedTexts[i]+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"></i></a></li></ul></td>'+
          '</tr>');    

        $('.prioritytbody:last').append($row);
  
    $n = $n+1;        

    });

  }





</script>


 <script type="text/javascript">
  var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
      $(this).width($originals.eq(index).width())
    });
    return $helper;
  },
    updateIndex = function(e, ui) {
    
      $('td.index', ui.item.parent()).each(function (i) {
        $(this).html(i+1);
      });

      selectedSortingValues = [];
      selectedSortingTexts = [];

      $('td.removeclass', ui.item.parent()).each(function (i) {
        console.log();
        // $(this).val(i + 1);
        selectedSortingValues.push($(this).attr('optionValueRemove'));
        selectedSortingTexts.push($(this).attr('optionTextRemove'));

      });

      selectedValues = selectedSortingValues;
      selectedTexts = selectedSortingTexts;
      $('#job_id_order_array').val(selectedValues);
     
    };

  $("#myTable tbody").sortable({
    helper: fixHelperModified,
    stop: updateIndex
  }).disableSelection();
  
    $("#myTable tbody").sortable({
    distance: 5,
    delay: 100, 
    opacity: 0.6,
    cursor: 'move',
    update: function() {}
    });


   </script>


<!-- for price override -->





<script type="text/javascript">

   var selectedValuesProperty = [];
   var selectedTextsProperty = [];
   var keyIds = [];
   var optionValueProperty = '';
   var optionTextProperty = '';
   $n2 = 1;
  
$(function() {

  reintlizeMultiselectpropertyPriceOver();

});

$('#add_program_form').on('submit',function(){
    var prog_name = $('#program_name').val();
    var prog_serv = $('#job_list').find('option:selected').val();
    var prog_prop = $('#property_list').find('option:selected').val();
    console.log(" Program: " + prog_name);
    console.log(" Service: " + prog_serv);
    console.log(" Property: " + prog_prop);
    if(prog_name != '' && prog_serv != '' && prog_prop != ''){
        $('#submit-button').attr('disabled', 'disabled');
    }
    
});


function reintlizeMultiselectpropertyPriceOver() {

      $(".multiselect-select-all-filtering2").multiselect('destroy');

        $('.multiselect-select-all-filtering2').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption : false,
        templates: {
            filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
        },

        onInitialized: function(select, container) {
       
           $(".styled, .multiselect-container input").uniform({ radioClass: 'checker'});
       
        },

        onSelectAll: function() {

            $.uniform.update();
        },
       
        onChange: function(option, checked, select) {


            if (checked) {

                optionValueProperty =  $(option).val();

                 if (optionValueProperty!='') {


               if ($.inArray(optionValueProperty, selectedValuesProperty)!='-1') {
                // alert('already');
                  
                } else {

                  $('.property-price-over-ride-container').css("display","block");

                  optionTextProperty = $(option).text();
                 // alert(optionValueProperty);
               //   alert(optionTextProperty);
                
                  selectedValuesProperty.push(optionValueProperty);
                  
                   keyIds.push({'property_id' : optionValueProperty, 'price_override' : 0,'is_price_override_set': null}); 


                  selectedTextsProperty.push(optionTextProperty);  

                  inputID =   'inpid'+$n2;
                  var $row = $('<tr id="tridproperty'+optionValueProperty+'">'+
                    '<td>'+optionTextProperty+'</td>'+
                    '<td> <input type="number" min="0" name="tmp'+$n2+'"  class="inpcl form-control" optval="'+optionValueProperty+'"  ></td>'+                    
                    '</tr>');    


                  $('.priceoverridetbody:last').append($row);
                  $n2 = $n2+1;        
                  // $('#assign_property_ids').val(selectedValuesProperty);
                        

                  $('#assign_property_ids2').val(JSON.stringify(keyIds));
                }
             } 



            } else {

                var id = $(option).val();
                var optionValuePropertyRemove = $(option).val();
                var optionTextPropertyRemove = $(option).text();
                
                selectedValuesProperty.splice($.inArray(optionValuePropertyRemove, selectedValuesProperty),1);

                selectedTextsProperty.splice($.inArray(optionTextPropertyRemove, selectedTextsProperty),1);

                keyIds = $.grep(keyIds, function(e){ 
                    return e.property_id != optionValuePropertyRemove; 
                 });

                $("#tridproperty"+id).remove();

                // $('#assign_property_ids').val(selectedValuesProperty);


                $('#assign_property_ids2').val(JSON.stringify(keyIds));

            }         
        }
    });   
}


//  $(document).on("change","#property_list",function() { 
         
//     optionValueProperty   = $(this).val();

//     if (optionValueProperty!='') {


//      if ($.inArray(optionValueProperty, selectedValuesProperty)!='-1') {
//       // alert('already');
        
//       } else {

//         $('.property-price-over-ride-container').css("display","block");

//         optionTextProperty = $("#property_list option:selected").text();
//        // alert(optionValueProperty);
//      //   alert(optionTextProperty);
      
//         selectedValuesProperty.push(optionValueProperty);
        
//          keyIds.push({'property_id' : optionValueProperty, 'price_override' : 0}); 


//         selectedTextsProperty.push(optionTextProperty);  

//         inputID =   'inpid'+$n2;
//         var $row = $('<tr id="tridproperty'+$n2+'">'+
//           '<td>'+optionTextProperty+'</td>'+
//           '<td> <input type="number" name="tmp'+$n2+'"  class="inpcl form-control" optval="'+optionValueProperty+'"  ></td>'+
//           '<td class="removeclassProperty" id="'+$n2+'" optionValuePropertyRemove="'+optionValueProperty+'" optionTextPropertyRemove="'+optionTextProperty+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"></i></a></li></ul></td>'+
//           '</tr>');    


//         $('.priceoverridetbody:last').append($row);
//         $n2 = $n2+1;        
//         // $('#assign_property_ids').val(selectedValuesProperty);
              

//         $('#assign_property_ids2').val(JSON.stringify(keyIds));
//       }
//    } 
    
// });


  // $(document).on("click",".removeclassProperty",function() { 
    
  //   // alert(selectedValuesProperty);
  //   // alert(selectedTextsProperty);

  //   var id = $(this).attr('id');
  //   var optionValuePropertyRemove = $(this).attr('optionValuePropertyRemove');
  //   var optionTextPropertyRemove = $(this).attr('optionTextPropertyRemove');
    
  //   selectedValuesProperty.splice($.inArray(optionValuePropertyRemove, selectedValuesProperty),1);

  //   selectedTextsProperty.splice($.inArray(optionTextPropertyRemove, selectedTextsProperty),1);

  //   keyIds = $.grep(keyIds, function(e){ 
  //       return e.property_id != optionValuePropertyRemove; 
  //    });

  //   $("#tridproperty"+id).remove();

  //   // $('#assign_property_ids').val(selectedValuesProperty);


  //   $('#assign_property_ids2').val(JSON.stringify(keyIds));

  // });

  // $(document).on("oninput",".prjoorincl",function(){

  //   alert('hhh');

  // });


  $(document).on("input",".inpcl",function() { 

      inputvalue  = $(this).val();
      property_id  = $(this).attr('optval');
        
        $.each( keyIds, function( key, value ) {
          
           if (property_id == value.property_id) {
            keyIds[key].price_override = inputvalue;            
            if(inputvalue != "") {
              keyIds[key].is_price_override_set = 1;
            } else {
              keyIds[key].is_price_override_set = null;
            }

           }
          // alert( key + ": " + value.property_id );
        }); 

    $('#assign_property_ids2').val(JSON.stringify(keyIds));


  }); 



</script>                    <!-- /content area -->
