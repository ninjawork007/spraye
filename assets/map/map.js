     
    function geocodePosition(e) {
        geocoder.geocode({
            latLng: e.latLng
        }, function (responses) {
            if (responses && responses.length > 0) {
              
                $("input[name='latitude']").val(e.latLng.lat());
                $("input[name='longitude']").val(e.latLng.lng());
                $("#address").val(responses[0].formatted_address);
              
//                updateMarkerAddress(responses[0].formatted_address);
            } else {
                updateMarkerAddress('Cannot determine address at this location.');
            }
        });
    }


    function initAutocomplete(lat,lng) {
   //   alert('call');
    $('#saveaddress').prop('disabled', true);
         var latLng = new google.maps.LatLng(lat, lng);
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });


        var marker = new google.maps.Marker({
            position: latLng,
            title: 'Point A',
            map: map,
            draggable: true

        });
    
            
            function showResult(result) {
              var lat =  document.getElementById('latitude').value = result.geometry.location.lat();
              var lng =  document.getElementById('longitude').value = result.geometry.location.lng();
              initAutocomplete(lat,lng);
             $('#saveaddress').prop('disabled', false);


           
            }

            

        function getLatitudeLongitude(callback, address) {
            // If adress is not supplied, use default value 'Ferrol, Galicia, Spain'
            //address = address || 'Ferrol, Galicia, Spain';
            // Initialize the Geocoder
            geocoder = new google.maps.Geocoder();
            if (geocoder) {
                geocoder.geocode({
                    'address': address
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        callback(results[0]);
                    }
                });
            }
        }



    var address = document.getElementById('address').value;
    getLatitudeLongitude(showResult, address)

        marker.addListener('dragend', geocodePosition);
        marker.addListener('drag', geocodePosition);
        
      }
     // This example requires the Places library. Include the libraries=places
     // parameter when you first load the API. For example:
     // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

     function initMap() {
       var map = new google.maps.Map(document.getElementById('map'), {
         center: {lat: 31.046051, lng: 34.851612},
         zoom: 8
       });
       var card = document.getElementById('pac-card');
       var input = document.getElementById('address');
       var types = document.getElementById('type-selector');
       var strictBounds = document.getElementById('strict-bounds-selector');

       map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

       var autocomplete = new google.maps.places.Autocomplete(input);

       // Bind the map's bounds (viewport) property to the autocomplete object,
       // so that the autocomplete requests use the current map bounds for the
       // bounds option in the request.
       autocomplete.bindTo('bounds', map);

       var infowindow = new google.maps.InfoWindow();
       var infowindowContent = document.getElementById('infowindow-content');
       infowindow.setContent(infowindowContent);
       var marker = new google.maps.Marker({
         map: map,
         anchorPoint: new google.maps.Point(0, -29)
       });




       autocomplete.addListener('place_changed', function() {
         infowindow.close();
         marker.setVisible(false);
         var place = autocomplete.getPlace();
         if (!place.geometry) {
           // User entered the name of a Place that was not suggested and
           // pressed the Enter key, or the Place Details request failed.
           window.alert("No details available for input: '" + place.name + "'");
           return;
         }

         // If the place has a geometry, then present it on a map.
         if (place.geometry.viewport) {
           map.fitBounds(place.geometry.viewport);
         } else {
           map.setCenter(place.geometry.location);
           map.setZoom(17);  // Why 17? Because it looks good.
         }
         marker.setPosition(place.geometry.location);
         marker.setVisible(true);

         var address = '';
         if (place.address_components) {
           address = [
             (place.address_components[0] && place.address_components[0].short_name || ''),
             (place.address_components[1] && place.address_components[1].short_name || ''),
             (place.address_components[2] && place.address_components[2].short_name || '')
           ].join(' ');
         }

         infowindowContent.children['place-icon'].src = place.icon;
         infowindowContent.children['place-name'].textContent = place.name;
         infowindowContent.children['place-address'].textContent = address;
         infowindow.open(map, marker);
       });

       // Sets a listener on a radio button to change the filter type on Places
       // Autocomplete.
       function setupClickListener(id, types) {
         var radioButton = document.getElementById(id);
         radioButton.addEventListener('click', function() {
           autocomplete.setTypes(types);
         });
       }

       setupClickListener('changetype-all', []);
       setupClickListener('changetype-address', ['address']);
       setupClickListener('changetype-establishment', ['establishment']);
       setupClickListener('changetype-geocode', ['geocode']);

       document.getElementById('use-strict-bounds')
           .addEventListener('click', function() {
             console.log('Checkbox clicked! New state=' + this.checked);
             autocomplete.setOptions({strictBounds: this.checked});
           });

       marker.addListener('dragend', geocodePosition);
       marker.addListener('drag', geocodePosition);
     }
