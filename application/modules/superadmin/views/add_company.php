       <!-- Content area -->
        <div class="content">
          <!-- Form horizontal -->
          <div class="panel panel-flat">
             <div class="panel-heading">
                       <h5 class="panel-title">
                            <div class="form-group">
                              <a href="<?= base_url('superadmin') ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to All Companies</a>
                            </div>
                       </h5>
              </div>

            <br>
            
            <div class="panel-body">    
              <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>           
              <form class="form-horizontal" action="<?= base_url('superadmin/addCompanyData') ?>" method="post" name="addcompany" enctype="multipart/form-data">
                <fieldset class="content-group"> 
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Company Name</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="company_name" placeholder="Company Name">
                        </div>
                    </div>
                 </div>                   
                 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Company Address</label>
                        <div class="col-lg-9">
                           <input type="text" id="autocomplete" onFocus="geolocate()" class="form-control" name="company_address" placeholder="Company Address">
                        </div>
                    </div>
                 </div> 
			         	</div>

                <div class="row">
                  
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Company Email</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="company_email" placeholder="Company Email">
                        </div>
                    </div>
                 </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Web Address</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="web_address" placeholder="Web Address">
                        </div>
                    </div>
                 </div>                    
                
                </div>

                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label col-lg-3">Currency</label>
                        <div class="col-lg-9">
                          <select class="form-control" name="company_currency" id="company_currency" required>
                            <option value="">Select Currency</option>
                            <option value="USD">USD - United States Dollars</option>
                            <option value="CAD">CAD - Canadian Dollars</option>
                          </select>
                          <small id="companyCurrencyHelp" class="form-text text-muted">Currency company will charge customers.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                </fieldset>

                  <fieldset class="content-group">
                      <legend class="text-bold">Maps and Routes</legend>
                        <div class="row">
                          
                          <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Start Location</label>
                                <div class="col-lg-9">
                                   <input type="text" class="form-control" name="start_location" id="autocomplete2" onFocus="geolocate()" placeholder="Start Locations" value="" >
                                </div>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="control-label col-lg-3">End Location</label>
                              <div class="col-lg-9">
                                <input type="text" id="autocomplete3" onFocus="geolocate()" class="form-control" name="end_location"  placeholder="End Locations" value="">
                              </div>
                            </div>
                         </div>                  
                        </div>                    
                </fieldset>

                <fieldset class="content-group">
                      <legend class="text-bold">User Details</legend>
                      <div class="row">
                        
                        <div class="col-md-6">
                          <div class="form-group">
                              <label class="control-label col-lg-3">Name</label>
                              <div class="col-lg-9">

                                <div class="row">
                                  <div class="col-md-6">
    
                                     <input type="text" class="form-control" name="user_first_name"  placeholder="User First Name" >
                                    
                                  </div>
                                  <div class="col-md-6">
    
                                     <input type="text" class="form-control" name="user_last_name"  placeholder="User Last Name" >
                                    
                                  </div>
                                </div>


                              </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                              <label class="control-label col-lg-3">Applicator Number</label>
                              <div class="col-lg-9">
                                  
                                  <input type="text" class="form-control" name="applicator_number" placeholder="Applicator Number">


                              </div>
                          </div>
                        </div>                  

                      </div>  
                      <div class="row">
                       
                       <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label col-lg-3">Email</label>
                            <div class="col-lg-9">
                              <input type="email" class="form-control" name="email" placeholder="Email" value="">
                            </div>
                          </div>
                       </div>

                       <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label col-lg-3">Phone</label>
                            <div class="col-lg-9">
                              <input type="text" class="form-control" name="phone" placeholder="User Phone Number" value="">
                              <br>
                              <span>Please do not use dashes</span>
                            </div>
                          </div>
                       </div>

                      </div>
                      
                     <div class="row">
                      
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Password</label>
                            <div class="col-lg-9">
                               <input type="password" id="password" class="form-control" name="password" placeholder="Password">
                            </div>
                        </div>
                     </div> 
                      
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Confirm Password</label>
                          <div class="col-lg-9">
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                          </div>
                        </div>
                      </div>    

                                    
                    </div> 
                </fieldset>  
                <div class="row"> 
                    <div class="text-center col-md-12 col-lg-12 col-sm-12">
                      <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </div>
                

              

              </form>
            </div>
          </div>
          <!-- /form horizontal -->

        </div>
        <!-- /content area -->

           

  <script src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>&libraries=places&callback=initAutocomplete"
        async defer></script>

 <script>
   // This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete, autocomplete2, autocomplete3;
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

  autocomplete2 = new google.maps.places.Autocomplete(
    /** @type {!HTMLInputElement} */
    (document.getElementById('autocomplete2')), {
      types: ['geocode']
    });
  autocomplete2.addListener('place_changed', function() {
    fillInAddress(autocomplete2, "2");
  });


  autocomplete3 = new google.maps.places.Autocomplete(
    /** @type {!HTMLInputElement} */
    (document.getElementById('autocomplete3')), {
      types: ['geocode']
    });
  autocomplete3.addListener('place_changed', function() {
    fillInAddress(autocomplete3, "3");
  });


  



}

function fillInAddress(autocomplete, unique) {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

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

      alert(val);
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
  
