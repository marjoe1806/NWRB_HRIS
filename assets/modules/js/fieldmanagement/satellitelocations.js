var circleData = [];
var pageKey = "view";
$(function(){
    var page = "";
    base_url = commons.base_url;
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        maxDate: new Date(),
        time: false
    });
    $(document).on('show.bs.modal','#myModal', function () {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            maxDate: new Date(),
            time: false
        });
        $.AdminBSB.input.activate();
        $.AdminBSB.select.activate();
    })
    $(document).on('click','#printSatelliteLocations',function(e){
        e.preventDefault();
        PrintElem("servicerecords-container");
    })
    $(document).on('click','#btnReset',function(e){
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: "Are you sure you want to reset form?",
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function () {
                        //Code here
                        $('form#updateSatelliteLocations').attr("action",commons.baseurl+"fieldmanagement/SatelliteLocations/addSatelliteLocations");
                        $('form#updateSatelliteLocations').attr("id","addSatelliteLocations");
                        $('form #id').val("");
                        $('#btnUpdate').attr("disabled","disabled");
                        $('#btnSave').removeAttr("disabled");
                        $("form")[0].reset();
                        pageKey = "view"
                        runMaps();
                        $.alert({
                            title:'<label class="text-success">Success</label>',
                            content:'Successfully reset.'
                        });
                    }

                },
                cancel: function () {
                
                }
            }
        });
        
    })
    $(document).on('click','#btnUpdate',function(e){
        e.preventDefault()
        $('form#addSatelliteLocations').attr("action",commons.baseurl+"fieldmanagement/SatelliteLocations/updateSatelliteLocations");
        $('form#addSatelliteLocations').attr("id","updateSatelliteLocations");
        $('#btnUpdate').attr("disabled","disabled");
        $('#btnSave').removeAttr("disabled");
        $('#btnReset').prop("disabled", true);
        $('#btnReset').attr("disabled","disabled");
        pageKey = "update";
        runMaps();
    })
    //Confirms
    $(document).on('click','.activateSatelliteLocations,.deactivateSatelliteLocations',function(e){
        e.preventDefault();
        me = $(this);
        url = me.attr('href');
        var id = me.attr('data-id');
        content = 'Are you sure you want to proceed?';
        if(me.hasClass('activateSatelliteLocations')){
            content = 'Are you sure you want to activate selected Satellite Location?';
        }
        else if(me.hasClass('deactivateSubSatelliteLocations')){
            content = 'Are you sure you want to deactivate selected sub Satellite Location?';
        }
        data = {id: id};
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: content,
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function () {
                        //Code here
                        $.confirm({
                            content: function () {
                                var self = this;
                                return $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: {id:id},
                                    dataType: "json",
                                    success: function(result){
                                        if(result.Code == "0"){
                                            if(result.hasOwnProperty("key")){
                                                switch(result.key){
                                                    case 'activateSatelliteLocations':
                                                    case 'deactivateSatelliteLocations':
                                                        self.setContent(result.Message);
                                                        self.setTitle('<label class="text-success">Success</label>');
                                                        
                                                        break;
                                                }
                                            }  
                                        }
                                        else{
                                            self.setContent(result.Message);
                                            self.setTitle('<label class="text-danger">Failed</label>');
                                        } 
                                    },
                                    error: function(result){
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
                            }
                        });
                    }

                },
                cancel: function () {
                }
            }
        });
    })
    //Ajax non-forms
    $(document).on('click','#loadSatelliteLocationsForm',function(e){
        e.preventDefault();
        me = $(this)
        id = me.attr('data-id');
        url = me.attr('href');  
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id},
            dataType: "json",
            success: function(result){
                page = me.attr('id');
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'loadSatelliteLocations':
                            page="";
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-md');
                            $('#myModal .modal-title').html('Load Satellite Locations');
                            $('#myModal .modal-body').html(result.form);
                            $.ajax({
                                type: "POST",
                                url: commons.baseurl+"fieldmanagement/SatelliteLocations/getSatelliteLocations",
                                dataType: "json",
                                success: function(result){
                                    //console.log(result);
                                    select =    '<select class="satellite_location form-control" name="satellite_location" id="satellite_location" data-live-search="true">'
                                                    '<option value="" selected></option>'
                                                '</select>'
                                    $(".satellite_location_select").html(select);
                                    if(result.Code == "0"){
                                        $.each(result.Data.details,function(i,v){
                                            $("#satellite_location").append('<option value="'+v.id+'">'+v.name+'</option>');
                                        })
                                    }
                                    $('#myModal').modal('show');
                                }
                            });
                            break;
                    }
                    $("#"+result.key).validate({
                        rules:
                        {
                            ".required":
                            {
                                required: true
                            },
                            ".email":
                            {
                                required: true,
                                email: true
                            }
                        },
                        highlight: function (input) {
                            $(input).parents('.form-line').addClass('error');
                        },
                        unhighlight: function (input) {
                            $(input).parents('.form-line').removeClass('error');
                        },
                        errorPlacement: function (error, element) {
                            $(element).parents('.form-group').append(error);
                        }
                    });
                }
            },
            error: function(result){
                $.alert({
                    title:'<label class="text-danger">Failed</label>',
                    content:'There was an error in the connection. Please contact the administrator for updates.'
                });
            }
        });
    })
    //Ajax Forms
    $(document).on('submit','#addSatelliteLocations,#updateSatelliteLocations,#loadSatelliteLocations',function(e){
        e.preventDefault();
        var form = $(this)
        content = "Are you sure you want to proceed?";
        if(form.attr('id') == "addSatelliteLocations"){
            content = "Are you sure you want to add Satellite Location?";
        }
        if(form.attr('id') == "updateSatelliteLocations"){
            content = "Are you sure you want to update Satellite Location?";
        }
        if(form.attr('id') == "loadSatelliteLocations"){
            content = "Are you sure you want to load Satellite Location?";
        }
        url = form.attr('action');
        $.confirm({
            title: '<label class="text-warning">Confirm!</label>',
            content: content,
            type: 'orange',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    action: function () {
                        //Code here
                        $.confirm({
                            content: function () {
                                var self = this;
                                return $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: form.serialize(),
                                    dataType: "json",
                                    success: function(result){
                                        if(result.hasOwnProperty("key")){
                                            if(result.Code == "0"){
                                                if(result.hasOwnProperty("key")){
                                                    switch(result.key){
                                                        case 'addSatelliteLocations':
                                                            console.log(result);
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('form#addSatelliteLocations').attr("action",commons.baseurl+"fieldmanagement/SatelliteLocations/updateSatelliteLocations");
                                                            $('form#addSatelliteLocations').attr("id","updateSatelliteLocations");
                                                            $('form #id').val(result.Data.id);
                                                            $('#btnSave').attr("disabled","disabled");
                                                            $('#btnReset').removeAttr("disabled");
                                                            $('#btnUpdate').removeAttr("disabled");
                                                        case 'updateSatelliteLocations':
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#btnSave').attr("disabled","disabled");
                                                            $('#btnReset').removeAttr("disabled");
                                                            $('#btnUpdate').removeAttr("disabled");
                                                            break;
                                                        case 'loadSatelliteLocations':
                                                            $('#btnSave').attr("disabled","disabled");
                                                            $('#btnReset').attr("disabled","disabled");
                                                            $('#btnReset').prop("disabled", true);
                                                            $('#btnUpdate').removeAttr("disabled");
                                                            circleData = result.Data.details;
                                                            pageKey = "view"
                                                            runMaps();
                                                            self.setContent(result.Message);
                                                            self.setTitle('<label class="text-success">Success</label>');
                                                            $('#myModal').modal('hide');
                                                            break;
                                                    }
                                                }  
                                            }
                                            else{
                                                self.setContent(result.Message);
                                                self.setTitle('<label class="text-danger">Failed</label>');
                                            }
                                        }
                                    },
                                    error: function(result){
                                        self.setContent("There was an error in the connection. Please contact the administrator for updates.");
                                        self.setTitle('<label class="text-danger">Failed</label>');
                                    }
                                });
                            }
                        });
                    }

                },
                cancel: function () {
                }
            }
        });
    })
    function PrintElem(elem)
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        mywindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head>');
        mywindow.document.write('</head><body >');
        mywindow.document.write( document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        return true;
    }
    //Google Map Function
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
    // console.log(commons.baseurl)
    
})
var runMaps = function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            //alert('hello')
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;
            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: latitude,
                    lng: longitude
                },
                zoom: 18
            });
            var shape = [];
            var all_overlays = [];
            var selectedShape;
            var drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.MARKER,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        //google.maps.drawing.OverlayType.MARKER,
                        google.maps.drawing.OverlayType.CIRCLE
                    ]
                },
                circleOptions: {
                    fillColor: '#ffff00',
                    fillOpacity: 0.2,
                    strokeWeight: 3,
                    clickable: false,
                    editable: true,
                    zIndex: 1
                },
                polygonOptions: {
                    clickable: true,
                    draggable: true,
                    editable: true,
                    fillColor: '#ffff00',
                    fillOpacity: 1,

                },
                rectangleOptions: {
                    clickable: true,
                    draggable: true,
                    editable: true,
                    fillColor: '#ffff00',
                    fillOpacity: 0.5,
                }
            });

            function clearSelection() {
                if (selectedShape) {
                    selectedShape.setEditable(false);
                    selectedShape = null;
                }
            }

            function setSelection(shape) {
                clearSelection();
                selectedShape = shape;
                shape.setEditable(true);
                // google.maps.event.addListener(selectedShape.getPath(), 'insert_at', getPolygonCoords(shape));
                // google.maps.event.addListener(selectedShape.getPath(), 'set_at', getPolygonCoords(shape));
            }

            function deleteSelectedShape() {
                if (selectedShape) {
                    selectedShape.setMap(null);
                    drawingManager.setOptions({
                       drawingControl: true
                    });
                }

                shape = []
            }

            function deleteAllShape() {
                for (var i = 0; i < all_overlays.length; i++) {
                    all_overlays[i].overlay.setMap(null);
                }
                all_overlays = [];
                shape = []
                circle.setMap(null);
            }

            function CenterControl(controlDiv, map) {

                // Set CSS for the control border.
                var controlUI = document.createElement('div');
                controlUI.style.backgroundColor = '#fff';
                controlUI.style.border = '2px solid #fff';
                controlUI.style.borderRadius = '3px';
                controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
                controlUI.style.cursor = 'pointer';
                controlUI.style.marginBottom = '22px';
                controlUI.style.textAlign = 'center';
                controlUI.title = 'Select to delete the shape';
                controlDiv.appendChild(controlUI);

                // Set CSS for the control interior.
                var controlText = document.createElement('div');
                controlText.style.color = 'rgb(25,25,25)';
                controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
                controlText.style.fontSize = '16px';
                controlText.style.lineHeight = '38px';
                controlText.style.paddingLeft = '5px';
                controlText.style.paddingRight = '5px';
                controlText.innerHTML = 'Delete Selected Area';
                controlUI.appendChild(controlText);

                // Setup the click event listeners: simply set the map to Chicago.
                controlUI.addEventListener('click', function() {
                    deleteSelectedShape();
                });

            }
            drawingManager.setMap(map);
            var getPolygonCoords = function(newShape) {
                console.log("We are one");
                var len = newShape.getPath().getLength();
                for (var i = 0; i < len; i++) {
                    console.log(newShape.getPath().getAt(i).toUrlValue(6));
                }
            };

            // google.maps.event.addListener(drawingManager, 'polygoncomplete', function(event) {

            //     event.getPath().getLength();
            //     google.maps.event.addListener(event.getPath(), 'insert_at', function() {
            //         var len = event.getPath().getLength();
            //         for (var i = 0; i < len; i++) {
            //             console.log(event.getPath().getAt(i).toUrlValue(5));
            //         }
            //     });
            //     google.maps.event.addListener(event.getPath(), 'set_at', function() {
            //         var len = event.getPath().getLength();
            //         for (var i = 0; i < len; i++) {
            //             console.log(event.getPath().getAt(i).toUrlValue(5));
            //         }
            //     });
            // });
            //Load Circle
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
                all_overlays.push(event);
                if (event.type !== google.maps.drawing.OverlayType.MARKER) {

                    drawingManager.setDrawingMode(null);

                    //Write code to select the newly selected object.

                    var newShape = event.overlay;
                    newShape.type = event.type;
                    shape.push(newShape)
                    google.maps.event.addListener(newShape, 'click', function() {
                        setSelection(newShape);
                    });
                    setSelection(newShape);
                    if (event.type == 'circle') {
                        var radius = event.overlay.getRadius();
                        var center = event.overlay.getCenter();
                        // createCircle(center,radius)
                        // event.overlay.setMap(null);
                        drawingManager.setDrawingMode(null);
                        drawingManager.setOptions({
                            drawingControl: false
                        });   
                        
                        shape[0].radius = newShape.getRadius()
                        var data=IO.IN(shape,true);
                        bounds = newShape.getBounds();
                        console.log(bounds);
                        $("#latitude").val(data[0].geometry[0]);
                        $("#longitude").val(data[0].geometry[1]);
                        $("#radius").val(data[0].radius);
                        $("#type").val(data[0].type);
                        $("#lat1").val(bounds.f.b)
                        $("#lat2").val(bounds.f.f)
                        $("#long1").val(bounds.b.b);
                        $("#long2").val(bounds.b.f);
                        google.maps.event.addListener(newShape, 'radius_changed', function (event) {
                            shape[0].radius = newShape.getRadius()
                            var data=IO.IN(shape,true);
                            bounds = newShape.getBounds();
                            $("#latitude").val(data[0].geometry[0]);
                            $("#longitude").val(data[0].geometry[1]);
                            $("#radius").val(data[0].radius);
                            $("#type").val(data[0].type);
                            $("#lat1").val(bounds.f.b)
                            $("#lat2").val(bounds.f.f)
                            $("#long1").val(bounds.b.b);
                            $("#long2").val(bounds.b.f);

                        });

                        google.maps.event.addListener(newShape, 'center_changed', function (event) {
                            shape[0].center = newShape.getCenter()
                            var data=IO.IN(shape,true);
                            bounds = newShape.getBounds();
                            $("#latitude").val(data[0].geometry[0]);
                            $("#longitude").val(data[0].geometry[1]);
                            $("#radius").val(data[0].radius);
                            $("#type").val(data[0].type);
                            $("#lat1").val(bounds.f.b)
                            $("#lat2").val(bounds.f.f)
                            $("#long1").val(bounds.b.b);
                            $("#long2").val(bounds.b.f);
                        });
                        
                    }
                }
            });
            //Circle
            loadCircle()
            function loadCircle(){

                
                if(circleData.length > 0){
                    radius = Number(circleData[0].radius)
                    center = {lat:Number(circleData[0].latitude),lng:Number(circleData[0].longitude)}
                    $("#location").val(circleData[0].name);
                    $("#id").val(circleData[0].id);
                    createCircle(center,radius)
                }
            }
            function createCircle(center, radius) {

                var circle = new google.maps.Circle({
                    fillColor: '#ffff00',
                    fillOpacity: 0.2,
                    strokeWeight: 3,
                    clickable: false,
                    editable: true,
                    zIndex: 1,
                    radius: radius,
                    center: center,
                    map: map,
                    type: "circle"
                });
                setSelection(circle);
                //circle.type = "CIRCLE";
                //console.log(circle.type)
                all_overlays.push(circle);
                shape.push(circle)
                if(pageKey != "update"){
                    drawingManager.setDrawingMode(null);
                    drawingManager.setOptions({
                        drawingControl: false
                    });
                } 
                circle.setMap(map)
                var data=IO.IN(shape,true);
                bounds = shape[0].getBounds();
                console.log(bounds);
                $("#latitude").val(data[0].geometry[0]);
                $("#longitude").val(data[0].geometry[1]);
                $("#radius").val(data[0].radius);
                $("#type").val(data[0].type);
                map.setCenter({lat:data[0].geometry[0], lng:data[0].geometry[1]});
                //console.log(data);
                /*$("#lat1").val(bounds.f.b)
                $("#lat2").val(bounds.f.f)
                $("#long1").val(bounds.b.b);
                $("#long2").val(bounds.b.f);*/
                google.maps.event.addListener(circle, 'radius_changed', function (event) {
                            
                    // var newShape = event.overlay;
                    // newShape.type = event.type;
                    shape[0].radius = circle.getRadius()
                    var data=IO.IN(shape,true);
                    bounds = shape[0].getBounds();
                    $("#latitude").val(data[0].geometry[0]);
                    $("#longitude").val(data[0].geometry[1]);
                    $("#radius").val(data[0].radius);
                    $("#type").val(data[0].type);
                    $("#lat1").val(bounds.f.b)
                    $("#lat2").val(bounds.f.f)
                    $("#long1").val(bounds.b.b);
                    $("#long2").val(bounds.b.f);
                });

                google.maps.event.addListener(circle, 'center_changed', function (event) {
                    shape[0].center = circle.getCenter()
                    var data=IO.IN(shape,true);
                    bounds = shape[0].getBounds();
                    $("#latitude").val(data[0].geometry[0]);
                    $("#longitude").val(data[0].geometry[1]);
                    $("#radius").val(data[0].radius);
                    $("#type").val(data[0].type);
                    $("#lat1").val(bounds.f.b)
                    $("#lat2").val(bounds.f.f)
                    $("#long1").val(bounds.b.b);
                    $("#long2").val(bounds.b.f);
                });

            }

            var centerControlDiv = document.createElement('div');
            var centerControl = new CenterControl(centerControlDiv, map);

            centerControlDiv.index = 1;
            map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(centerControlDiv);




            // Custom Codes

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

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

            });

        });
    }
};


var IO={
  //returns array with storable google.maps.Overlay-definitions
    IN:function(arr,//array with google.maps.Overlays
              encoded//boolean indicating whether pathes should be stored encoded
              ){
        var shapes     = [],
        goo=google.maps,
        shape,tmp;
      
      for(var i = 0; i < arr.length; i++)
      {   
        shape=arr[i];
        tmp={type:this.t_(shape.type),id:shape.id||null};
        
        
        switch(tmp.type){
           case 'CIRCLE':
           tmp.radius=shape.getRadius();
           tmp.geometry=this.p_(shape.getCenter());
           break;
           case 'MARKER': 
           tmp.geometry=this.p_(shape.getPosition());   
           break;  
           case 'RECTANGLE': 
           tmp.geometry=this.b_(shape.getBounds()); 
           break;   
           case 'POLYLINE': 
           tmp.geometry=this.l_(shape.getPath(),encoded);
           break;   
           case 'POLYGON': 
           tmp.geometry=this.m_(shape.getPaths(),encoded);

           break;   
       }
       shapes.push(tmp);
    }

    return shapes;
    },
      //returns array with google.maps.Overlays
      OUT:function(arr,//array containg the stored shape-definitions
                   map//map where to draw the shapes
                   ){
          var shapes     = [],
          goo=google.maps,
          map=map||null,
          shape,tmp;
          
          for(var i = 0; i < arr.length; i++)
          {   
            shape=arr[i];       
            
            switch(shape.type){
               case 'CIRCLE':
               tmp=new goo.Circle({radius:Number(shape.radius),center:this.pp_.apply(this,shape.geometry)});
               break;
               case 'MARKER': 
               tmp=new goo.Marker({position:this.pp_.apply(this,shape.geometry)});
               break;  
               case 'RECTANGLE': 
               tmp=new goo.Rectangle({bounds:this.bb_.apply(this,shape.geometry)});
               break;   
               case 'POLYLINE': 
               tmp=new goo.Polyline({path:this.ll_(shape.geometry)});
               break;   
               case 'POLYGON': 
               tmp=new goo.Polygon({paths:this.mm_(shape.geometry)});

               break;   
           }
           tmp.setValues({map:map,id:shape.id})
           shapes.push(tmp);
       }
       return shapes;
    },
    l_:function(path,e){
        path=(path.getArray)?path.getArray():path;
        if(e){
          return google.maps.geometry.encoding.encodePath(path);
      }else{
          var r=[];
          for(var i=0;i<path.length;++i){
            r.push(this.p_(path[i]));
        }
        return r;
    }
    },
    ll_:function(path){
        if(typeof path==='string'){
          return google.maps.geometry.encoding.decodePath(path);
      }
      else{
          var r=[];
          for(var i=0;i<path.length;++i){
            r.push(this.pp_.apply(this,path[i]));
        }
        return r;
    }
    },

    m_:function(paths,e){
        var r=[];
        paths=(paths.getArray)?paths.getArray():paths;
        for(var i=0;i<paths.length;++i){
            r.push(this.l_(paths[i],e));
        }
        return r;
    },
    mm_:function(paths){
        var r=[];
        for(var i=0;i<paths.length;++i){
            r.push(this.ll_.call(this,paths[i]));
            
        }
        return r;
    },
    p_:function(latLng){
        return([latLng.lat(),latLng.lng()]);
    },
    pp_:function(lat,lng){
        return new google.maps.LatLng(lat,lng);
    },
    b_:function(bounds){
        return([this.p_(bounds.getSouthWest()),
            this.p_(bounds.getNorthEast())]);
    },
    bb_:function(sw,ne){
        return new google.maps.LatLngBounds(this.pp_.apply(this,sw),
            this.pp_.apply(this,ne));
    },
    t_:function(s){
        var t=['CIRCLE','MARKER','RECTANGLE','POLYLINE','POLYGON'];
        for(var i=0;i<t.length;++i){
           if(s===google.maps.drawing.OverlayType[t[i]]){
             return t[i];
         }
     }
    }

}
