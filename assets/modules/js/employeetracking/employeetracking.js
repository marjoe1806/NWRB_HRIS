var circleData = [];
$(document).ready(function(){         
    $('.li_side').removeClass('active');
    $('.map_side').addClass('active');
    $('#searchlatlng').click();
    $.ajax({
        url: commons.baseurl+"employeetracking/EmployeeTracking/getActiveLocations",
        dataType: "json",
        type: 'POST',
        cache: false,
    }).done(function(json){
        $(".mytbody").empty();
        console.log(json)
        if(json.Code == "0"){
            $.each(json.Data.details,function(i,v){
                var btn_data = "";
                $.each(v,function(i2,v2){
                    btn_data += 'data-'+i2+'="'+v2+'"';
                })
                $(".mytbody").append(
                    '<tr>'+
                        '<td>'+v.name+'</td>'+
                        '<td class="td-actions text-right">'+
                            '<a class="searchLocation btn btn-success btn-circle waves-effect waves-circle waves-float get_latlng" '+btn_data+'>'+
                                '<i class="material-icons">search</i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );
            })
        }
        $('[rel="tooltip"]').tooltip();
    })
    $(document).on('click','.searchLocation',function(e){
        if($(this).attr("disabled")){
          return false;
        }
        e.preventDefault();
        $('.searchLocation').removeAttr("disabled");
        $('.searchLocation').removeClass("selectedLocation");
        me = $(this)
        me.attr('disabled','disabled');
        me.addClass('selectedLocation');
        circleData[0] = me.data();
        console.log(me.data());
        lat = me.data("latitude")
        len = me.data("longitude");
        $.when(
            runMaps(lat,len)
        ).done(function(){
            $.AdminBSB.select.activate();  
            //me.removeAttr("disabled");
        })
        loadEmployeesByLocation(me.data("lat1"),me.data("lat2"),me.data("long1"),me.data("long2"));
    })
    //Ajax non-forms
    $(document).on('click','#viewAttendingEmployees',function(e){
        e.preventDefault();
        my = $(this)
        id = my.attr('data-id');
        url = my.attr('href');  
        //console.log(me.data())
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id},
            dataType: "json",
            success: function(result){
                if(result.hasOwnProperty("key")){
                    switch(result.key){
                        case 'viewAttendingEmployees':
                            $('#myModal .modal-dialog').attr('class','modal-dialog modal-lg');
                            $('#myModal .modal-title').html('Attending Employees');
                            $('#myModal .modal-body').html(result.form);
                            $('#myModal').modal('show');
                            loadEmployeesTable();
                            break;
                        
                    }
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
    $(document).on('keydown', '.latitude , .longitude', function (e) {
       var key = e.keyCode;
        // alphabet
        if(key >= 65 && key <= 90){
            return false;
        }
        // dash
        if(key == 189){
            return false;
        }
        // special characters
        if(key >= 186 && key <= 222){
            return false;
        }
        // numpad numbers
        if(key >= 96 && key <= 105){
            return false;
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
            return false;
        }
    });
    function loadEmployeesByLocation(lat1,lat2,long1,long2){
        $.ajax({
            type: "POST",
            url: commons.baseurl+"/employeetracking/EmployeeTracking/getEmployeeCounts",
            data: {lat1:lat1, lat2:lat2, long1:long1, long2:long2},
            dataType: "json",
            success: function(result){
                console.log(result);
                if(result.Code == "0"){
                  $("#attendingEmployees").html(result.Data.details[0].count);
                }
                
            }
        });
    }
    function loadEmployeesTable(){
        disabledBtn = $( ".selectedLocation" );
        console.log(disabledBtn.data("id"));
        var plus_url = "";
        if(disabledBtn.data("id") != undefined)
          plus_url = "?lat1="+disabledBtn.data("lat1")
                      +"&lat2="+disabledBtn.data("lat2")
                      +"&long1="+disabledBtn.data("long1")
                      +"&long2="+disabledBtn.data("long2");
        table = $('#datatables').DataTable({  
            "processing":true,  
            "serverSide":true,  
            "order":[],
            "ajax":{  
                url:commons.baseurl+ "employeetracking/EmployeeTracking/fetchEmployeeRows"+plus_url,  
                type:"POST"  
            },  
            "columnDefs":[  
                {  
                    "targets": [0],
                    "orderable":false,  
                },  
            ],  
        });
        table = $('#datatables').DataTable();
    }
});
var lat = "";
var len = "";
var runMaps = function(lat,len) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            if(lat != undefined && len != undefined){
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: parseFloat(lat),
                        lng: parseFloat(len)
                    },
                    zoom: 18
                });
            }
            else{
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    },
                    zoom: 18
                });
            }
            var shape = [];
            var all_overlays = [];
            var selectedShape;
            var drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.MARKER,
                drawingControl: false,
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
                    editable: false,
                    zIndex: 1
                },
                polygonOptions: {
                    clickable: false,
                    draggable: false,
                    editable: false,
                    fillColor: '#ffff00',
                    fillOpacity: 1,

                },
                rectangleOptions: {
                    clickable: false,
                    draggable: false,
                    editable: false,
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
                shape.setEditable(false);
                // google.maps.event.addListener(selectedShape.getPath(), 'insert_at', getPolygonCoords(shape));
                // google.maps.event.addListener(selectedShape.getPath(), 'set_at', getPolygonCoords(shape));
            }

            function deleteSelectedShape() {
                if (selectedShape) {
                    selectedShape.setMap(null);
                    drawingManager.setOptions({
                       drawingControl: false
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
            drawingManager.setMap(map);
            var getPolygonCoords = function(newShape) {
                console.log("We are one");
                var len = newShape.getPath().getLength();
                for (var i = 0; i < len; i++) {
                    console.log(newShape.getPath().getAt(i).toUrlValue(6));
                }
            };


            //Load Circle
            
            //Circle
            loadCircle()
            function loadCircle(){
                if(circleData.length > 0){
                    radius = Number(circleData[0].radius)
                    center = {lat:Number(circleData[0].latitude),lng:Number(circleData[0].longitude)}
                    $("#location").val(circleData[0].name);
                    $("#id").val(circleData[0].id);
                    createCircle(center,radius)
                    var marker = new google.maps.Marker({
                       position: center,
                       map: map,
                       title: circleData[0].name
                    });
                    marker.setMap(map);
                }
            }
            function createCircle(center, radius) {

                var circle = new google.maps.Circle({
                    fillColor: '#ffff00',
                    fillOpacity: 0.2,
                    strokeWeight: 3,
                    clickable: false,
                    editable: false,
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
                drawingManager.setDrawingMode(null);
                drawingManager.setOptions({
                    drawingControl: false
                });
                circle.setMap(map)
                var data=IO.IN(shape,false);
                bounds = shape[0].getBounds();
            }




            // Custom Codes

            var markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            var geocoder = new google.maps.Geocoder();
            

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