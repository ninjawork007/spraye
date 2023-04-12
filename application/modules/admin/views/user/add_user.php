


        <!-- Content area -->
        <div class="content form-pg">

          <!-- Form horizontal -->
          <div class="panel panel-flat">
         <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/users') ?>"  id="save" class="btn btn-success" > <i class=" icon-arrow-left7"> </i> Back to All Users</a>
                        </div>
                   </h5>
              </div>

            <br>
            <div class="panel-body">              
        <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
              <form class="form-horizontal" action="<?= base_url('admin/users/addUserData') ?>" method="post" name="adduser" enctype="multipart/form-data" style="min-height: 396px;" >
                <fieldset class="content-group">                  
                 

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">First Name</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="user_first_name" placeholder="First Name">
                        </div>
                    </div>
                 </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Last Name</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" name="user_last_name" placeholder="Last Name">
                      </div>
                    </div>
                  </div>
                  
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group mg-bt">
                        <label class="control-label col-lg-3">Email</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="email" placeholder="Email">
                        </div>
                    </div>
                 </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group mg-bt">
                      <label class="control-label col-lg-3">Phone Number</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" name="phone" placeholder="Phone">
                        <span>Please do not use dashes</span>
                      </div>
                    </div>
                  </div>
                  
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Start Location</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="start_location" id="autocomplete" onFocus="geolocate()" placeholder="Start Location">
                        </div>
                    </div>
                 </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">End Location</label>
                      <div class="col-lg-9">
                        <input type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" name="end_location" placeholder="End Locations">
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
                    <div class="form-group" style="margin-bottom: 7px;">
                      <label class="control-label col-lg-3">Confirm Password</label>
                      <div class="col-lg-9">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                      </div>
                    </div>
                  </div>
                  
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Role</label>
                           <div class="col-lg-9">
                              <select class="form-control" name="role_id">
                                <option value="">Select Role</option>
                                <option value="2">Account Owner</option>
                                <option value="3">Account Admin</option>
                                <option value="4">Technician</option>
                              </select>
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

                <div class="text-right">
                  <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>

                </fieldset>

              </form>
            </div>
          </div>
          <!-- /form horizontal -->

        </div>
        <!-- /content area -->

        <script src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>&libraries=places&callback=initAutocomplete" async defer></script>
<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/counters/maxlength/maxlength.js"></script>

<script>
    // This example displays an address form, using the autocomplete feature
    // of the Google Places API to help users fill in the information.
    var placeSearch, autocomplete, autocomplete2;
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
