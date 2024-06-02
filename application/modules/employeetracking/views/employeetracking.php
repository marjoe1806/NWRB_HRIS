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
    table.scroll thead {
        width: 100%;
        background: #FC6822;
    }
    table.scroll thead tr:after {
        content: '';
        overflow-y: scroll;
        visibility: hidden;
    }
    table.scroll thead th {
        flex: 1 auto;
        display: block;
        color: #fff;
    }
    table.scroll tbody {
        display: block;
        width: 100%;
        overflow-y: auto;
        height: auto;
        max-height: 300px;
    }
    table.scroll thead tr,
    table.scroll tbody tr {
        display: flex;
    }
    table.scroll tbody tr td {
        flex: 1 auto;
        word-wrap: break;
    }
    table.scroll thead tr th,
    table.scroll tbody tr td {
        width: 25%;
        padding: 5px;
        text-align-left;
        border-bottom: 1px solid rgba(0,0,0,0.3);
    }
</style>
<div class="container">
    <br>
    <div class="row clearfix">
        <div class="col-md-5">
            <div class="card">
                <div class="header bg-blue"><h2>Satellite Locations</h2></div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive table-full-width">
                                <table class="table table-hover table-striped scroll">                                    
                                    <thead class="bg-blue">
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
                        <div class="col-md-12">
                            <a id="viewAttendingEmployees" href="<?php echo base_url().'employeetracking/EmployeeTracking/viewAttendingEmployees'; ?>">
                            <button  type="button" class="btn btn-block btn-lg btn-success waves-effect">TOTAL ATTENDING EMPLOYEES
                                <span id="attendingEmployees" class="badge">0</span>
                            </button> 
                            </a>
                        </div>
    		        </div>                 
                </div>
            </div>
        </div>    
        <div class="col-md-7">
            <div class="card">
                <div class="header bg-yellow">
                    <h2>Navigation</h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-12">
                    		<div id="map"  style="height: 500px;"></div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/modules/js/employeetracking/employeetracking.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCiaCn76w3qFRQCHaAv3tqGhYoS7jN-PHA&libraries=places,drawing&callback=runMaps"></script>