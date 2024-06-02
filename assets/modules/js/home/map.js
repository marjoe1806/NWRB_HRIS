$(document).ready(function(){         
    $('.li_side').removeClass('active');
    $('.map_side').addClass('active');
    $('#searchlatlng').click();
    $(document).on('keydown', '.latitude , .longitude', function (e) {
       var key = e.keyCode;
        // alphabet
        if(key >= 65 && key <= 90){
            return false;
        }
        // dash
        if(key == 189){
            return true;
        }
        // special characters
        if(key >= 186 && key <= 222){
            return false;
        }
        // numpad numbers
        if(key >= 96 && key <= 105){
            return true;
        }
        // shift + numbers
        if((e.shiftKey &&  key >= 48) || (e.shiftKey &&  key >= 105)){
            return false;
        }
        // double qoute
        if(e.shiftKey && key == 222){
            return false;
        }
        // copy paste
        if(e.ctrlKey && key == 86){
            return false;
        }
        // numbers
        if(key >= 48 && key <= 57){
            // e.preventDefault();
            return true;
        }
    });
});

function initMap() {
    var map = new google.maps.Map(document.getElementById('mymap'), {
        center: {lat: 14.599512, lng: 120.984222},
        zoom: 18,
        mapTypeId: 'roadmap'
    });

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        // Clear out the old markers.
        markers.forEach(function(marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });

    $(document).on('click', '.searchlatlng', function(){
        var lat = $('.latitude').val();
        var lng = $('.longitude').val();

        var panPoint = new google.maps.LatLng(lat, lng);
        map.panTo(panPoint);

        for(i=0; i<markers.length; i++){
            markers[i].setMap(null);
        }

        markers.push(new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: map
        }));

        $.ajax({
            url: window.location.href+"/get_address",
            data:  {
                lat: lat,
                lng, lng
            },
            type: 'POST',
            cache: false,
        }).done(function(data){
            $(".mytbody").empty();
            if(data != 'empty'){
                json = JSON.parse(data);
                if(json.results != "" || json.results != null){
                    for (var i = 0; i < json.results.length; i++) {
                        $(".mytbody").append(
                            '<tr>'+
                                '<td>'+json.results[i].formatted_address+'</td>'+
                                '<td class="td-actions text-right">'+
                                    '<a rel="tooltip" title="Get specific LatLng" class="btn btn-success btn-circle waves-effect waves-circle waves-float get_latlng" data-address="'+json.results[i].formatted_address+'">'+
                                        '<i class="material-icons">search</i>'+
                                    '</a>'+
                                '</td>'+
                            '</tr>'
                        );
                    }


                    console.log()
                }
            }else{
                $(".mytbody").empty();
            }
            $('[rel="tooltip"]').tooltip();
        })

    });

    $(document).on('click', '.get_latlng', function(){
        $.ajax({
            url:  window.location.href+"/get_latlng",
            data:  {
                address: $(this).attr('data-address'),
            },
            type: 'POST',
            cache: false,
        }).done(function(data){
            if(data != 'empty'){
                json = JSON.parse(data);
                if(json.results != "" || json.results != null){
                    setTimeout(function(){ 
                        $('#myModal .modal-dialog').attr('class','modal-dialog modal-sm');
                        $('#myModal .modal-title').html("<strong>Message</strong>");
                        $('#myModal .modal-body').html(
                            '<div class="row">'+
                                '<div class="col-md-12">'+
                                    '<strong>Address: '+json.results[0].formatted_address+'</strong>'+
                                '</div>'+
                                '<div class="col-md-12">'+
                                    '<strong>Latitude: '+json.results[0].geometry.location.lat+'</strong>'+
                                '</div>'+
                                '<div class="col-md-12">'+
                                   '<strong>Longitude: '+json.results[0].geometry.location.lng+'</strong>'+
                                '</div>'+
                            '</div>'
                            );
                        $('#myModal .modal-footer').html();
                        $('#myModal').modal('show');  
                    }, 100);
                }
            }
        })

    });

    var geocoder = new google.maps.Geocoder();
    google.maps.event.addListener(map, "click", function (e) {
        var latLng = e.latLng;

        var latitude =  latLng.lat();
        var longitude =  latLng.lng();

        var panPoint = new google.maps.LatLng(latitude, longitude);
        map.panTo(panPoint);

        for(i=0; i<markers.length; i++){
            markers[i].setMap(null);
        }

        markers.push(new google.maps.Marker({
            position: new google.maps.LatLng(latitude, longitude),
            map: map
        }));

        $('.latitude').val(latitude);
        $('.longitude').val(longitude);

        geocoder.geocode({
            'latLng': e.latLng
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                $(".mytbody").empty();
                if(results != "" || results != null){
                    for (var i = 0; i < results.length; i++) {
                        $(".mytbody").append(
                            '<tr>'+
                                '<td>'+results[i].formatted_address+'</td>'+
                                '<td class="td-actions text-right">'+
                                    '<a rel="tooltip" title="Get specific LatLng" class="btn btn-success btn-circle waves-effect waves-circle waves-float get_latlng" data-address="'+results[i].formatted_address+'">'+
                                        '<i class="material-icons">search</i>'+
                                    '</a>'+
                                '</td>'+
                            '</tr>'
                        );
                    }
                }
            }else{
                $(".mytbody").empty();
            }
            $('[rel="tooltip"]').tooltip();
          });

    });
}