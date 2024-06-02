<div class="row clearfix" id="userLevelForm">
    <div class="col-md-4">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Satellite Locations <small>Satellite Locations Maintenance</small>
                </h2>
                
            </div>
            <div class="body">
                <form id="addSatelliteLocations" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addSatelliteLocations'; ?>" method="POST">
                    <div class="hidden-content">
                        <input type="hidden" name="id" id="id" class="id">
                        <input type="hidden" name="type" id="type" class="type">
                        <input type="hidden" name="lat1" id="lat1" class="lat1">
                        <input type="hidden" name="lat2" id="lat2" class="lat2">
                        <input type="hidden" name="long1" id="long1" class="long1">
                        <input type="hidden" name="long2" id="long2" class="long2">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Location</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="name" value="<?php echo isset($_GET['location'])? $_GET['location']: "" ; ?>" id="location" class="location form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Latitude</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="latitude" value="<?php echo isset($_GET['latitude'])? $_GET['latitude']: "" ; ?>" id="latitude" class="latitude form-control" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Longitude</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="longitude" value="<?php echo isset($_GET['longitude'])? $_GET['longitude']: "" ; ?>" id="longitude" class="longitude form-control" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Radius</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="radius" value="<?php echo isset($_GET['radius'])? $_GET['radius']: "" ; ?>" id="radius" class="radius form-control" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Status</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="is_active form-control" name="satellite_location" id="is_active" data-live-search="true">
                                        <option value="1" selected>ACTIVE</option>
                                        <option value="0">INACTIVE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-circle waves-effect waves-circle waves-float" id="btnSave" data-toggle="tooltip" data-placement="top" title="Save">
                                    <i class="material-icons">save</i>
                                </button>
                                <a class="btn btn-warning btn-circle waves-effect waves-circle waves-float" id="btnUpdate" data-toggle="tooltip" data-placement="top" title="Update" disabled="">
                                    <i class="material-icons">mode_edit</i>
                                </a>
                                <a class="btn btn-primary btn-circle waves-effect waves-circle waves-float" id="loadSatelliteLocationsForm" href="<?php echo base_url().'fieldmanagement/SatelliteLocations/loadSatelliteLocationsForm' ?>" data-toggle="tooltip" data-placement="top" title="Load">
                                    <i class="material-icons">pageview</i>
                                </a>
                                <a class="btn btn-danger btn-circle waves-effect waves-circle waves-float" id="btnReset" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reset">
                                    <i class="material-icons">refresh</i>
                                </a>
                            </div>
                        </div>
                    </div>   
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-line">
                                <label>Search Location</label>
                                <input type="text" name="pac-input" id="pac-input" class="pac-input form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="map"  style="height: 460px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/fieldmanagement/satellitelocations.js"></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyCQYx12j3yb0nh4HAhbanoiq5kE8DxjM3Q&libraries=places,drawing&callback=runMaps"></script>