<style>
    #pac-input {
        width: 30%;
        margin-top: 10px;
        margin-left: 10px;
        font-family: Roboto;
        box-sizing: border-box;
        border: 1px solid #e1e1e2;
        border-radius: 4px;
        font-size: 12px;
        background-position: 10px 10px; 
        background-repeat: no-repeat;
        padding: 8px 10px 8px 10px;
        -webkit-transition: width 0.4s ease-in-out;
        transition: width 0.4s ease-in-out;
    }

    #pac-input:focus {
        width: 65%;
        border-color: #4d90fe;
    }
</style>
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="header bg-blue"><h2>Search, Latitude and Longitude</h2></div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-12">
    	                    <label class="form-label">Latitude</label>
    	                    <div class="form-group">
    	                    	<div class="form-line">
    	                    		<input type="text" name="latitude" value="<?php echo isset($_GET['latitude'])? $_GET['latitude']: "" ; ?>" id="latitude" class="latitude form-control" required>
    	                    	</div>
    	                	</div>
    	                </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
    	                    <label class="form-label">Longitude</label>
    	                    <div class="form-group">
    	                    	<div class="form-line">
    	                    		<input type="text" name="longitude" value="<?php echo isset($_GET['longitude'])? $_GET['longitude']: "" ; ?>" id="longitude" class="longitude form-control" required>
    	                    	</div>
    	                	</div>
    	                </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive table-full-width">
                                <table class="table table-hover table-striped">                                    
                                    <thead>
                                        <tr>
                                            <th>Address</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="mytbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>     
                	<div class="row">
                		<div class="col-md-7 text-right"></div>
                        <div class="col-md-5 text-right">
    		                <button id="searchlatlng" class="form-control searchlatlng btn btn-sm btn-info waves-effect" data-dismiss="modal" type="button">
    		                    <i class="material-icons">search</i><span> Search</span>
    		                </button>
    		            </div>
    		        </div>                 
                </div>
            </div>
        </div>    
        <div class="col-md-8">
            <div class="card">
                <div class="header bg-yellow">
                    <h2>Navigation</h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-12">
                    	    <div class="form-group">
                    	    	<div class="form-line">
                    	    		<input type="text" name="pac-input" id="pac-input" class="pac-input form-control" required>
                    	    	</div>
                    		</div>
                    		<div id="mymap"  style="height: 500px;"></div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/modules/js/home/map.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCiaCn76w3qFRQCHaAv3tqGhYoS7jN-PHA&libraries=places&callback=initMap"></script>
