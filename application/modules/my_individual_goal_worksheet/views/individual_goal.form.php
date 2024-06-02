<style type="text/css">

	/*.material-icons{
		font-size: 14pt;
	}*/

    .form thead{
        font-size: 9px;
    }

    #foot label{
        color: black;
        font-family: arial;
        font-size: 10pt;
    }


    /*#foot label center{
        font-weight: bold;
    }*/

</style>

<!--  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <button class="btn btn-success btn-just-icon btn-round" id = "add_modal" data-toggle = "modal" data-target = "#add_pes">
                        <i class="material-icons">add</i>
                    </button>
                    <div class = "table-responsive">
                        <table id = "tbl_reports" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead style="font-size: 12px; text-align: center;">
                                <tr>
                                    <th>Report ID:</th>
                                    <th>Employee Name:</th>
                                    <th>Date Created:</th>
                                    <th class = "disabled-sorting" style="width: 7%; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
               <!--  <div class="card-content">
                    <button class="btn btn-success btn-just-icon btn-round" id = "add_modal" data-toggle = "modal" data-target = "#add_pes">
                        <i class="material-icons">add</i>
                    </button>
                    <div class = "table-responsive">
                        <table id = "tbl_reports" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead style="font-size: 12px; text-align: center;">
                                <tr>
                                    <th>Report ID:</th>
                                    <th>Employee Name:</th>
                                    <th>Date Created:</th>
                                    <th class = "disabled-sorting" style="width: 7%; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;"></tbody>
                        </table>
                    </div>
                </div> -->
                <div class = "card">
                    <div class="header bg-blue">
                        <h2>
                            PES - REPORT <small>Description text here...</small>
                        </h2>
                        <ul class="header-dropdown m-r-0">
                            <li>
                                <button type = "button" class="btn bg-light-green btn-circle-lg waves-effect waves-circle waves-float" id = "add_modal" data-toggle = "modal" data-target = "#add_pes">
                                    <i class = "material-icons" style="margin-bottom: 10px;">add</i>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class = "body">
                        <div class = "table-responsive">
                            <table id = "tbl_reports" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead style="font-size: 12px; text-align: center;">
                                    <tr>
                                        <th>Report ID:</th>
                                        <th>Employee Name:</th>
                                        <th>Date Created:</th>
                                        <th style="width: 16%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 14px;"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_pes" tabindex="-1" role="dialog" aria-labelledby="add_pes" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <h2 style="position: relative; top: -10px;">
                   ADD PES - REPORT
                </h2>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class = "body">
                        <div class="container-fluid">
                             <center>
                                <h4 class="card-title" style="font-weight: bold;">NATIONAL WATER RESOURCES BOARD</h4>
                                <small style="font-weight: bold;">
                                    8th Floor, NIA Building, EDSA, Diliman. Quezon City
                                </small>
                            </center>
                            <table>
                                <tr>
                                    <td><label style="color: black;">FORM A: INDIVIDUAL GOAL WORKSHEET</label></td>
                                </tr>
                                <tr>
                                    <td style="color: black;">PART I</td>
                                </tr>
                            </table>
                            <div class = "row">
                                <div class = "col-md-12 col-lg-12">
                                    <div class = "table-responsive">
                                        <table class = "table form" id = "dynamic_row">
                                            <thead>
                                                <tr>
                                                    <td style="font-size: 12pt;">
                                                        NAME:
                                                        <select style="width: 70%; font-size: 15px;" id = "emp_name" class="employee_select" data-live-search="true" disabled></select>
                                                    </td>
                                                    <td style="font-size: 9pt;">
                                                        POSITION: <span class="employee_position"></span>
                                                    </td>
                                                    <td style="font-size: 9pt;"> 
                                                        DIVISION: <span class="employee_division"></span>
                                                    </td>
                                                    <td style="font-size: 9pt;">
                                                        IMMEDIATE SUPERVISOR
                                                    </td>
                                                    <td style="font-size: 8pt;">
                                                        <select style="width: 70%; font-size: 15px;" id = "supervisor_name" class="employee_select" data-live-search="true">
                                                            
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <center>
                                                            INDIVIDUAL GOALS<br>
                                                            <small>
                                                                (Must be specified with target dates as appropriate)
                                                            </small>
                                                        </center>
                                                    </th>
                                                    <th>
                                                        <center>
                                                            3rd QUARTER RESULTS<br>
                                                            <small>Date:_____ Initials:_____</small>
                                                        </center>
                                                    </th>
                                                     <th>
                                                        <center>
                                                            4th QUARTER RESULTS<br>
                                                            <small>Date:_____ Initials:_____</small>
                                                        </center>
                                                    </th>
                                                    <th>
                                                        <center>
                                                            RESULT AND REMARKS
                                                        </center>
                                                    </th>
                                                    <th>
                                                        <center>
                                                            PERCENTAGE SCORE<br>
                                                            <small>RESULTS _____ * 100%</small>
                                                        </center>
                                                    </th>
                                                    <th>
                                                        <center>
                                                            ACTION
                                                        </center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <form id = "form_pes">
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="indv-goals[]" placeholder="Input text here.."></td>
                                                        <td><input type="text" class="form-control" name="third-res[]" placeholder="Input text here.."></td>
                                                        <td><input type="text" class="form-control" name="fourth-res[]" placeholder="Input text here.."></td>
                                                        <td><input type="text" class="form-control" name="res-remarks[]" placeholder="Input text here.."></td>
                                                        <td><input type="number" step = "0.01" class="form-control" name="percentage[]" placeholder="Input text here.."></td>
                                                        <td>
                                                            <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" id = "add">
                                                                <i class="material-icons">add</i>
                                                            </button>
                                                        </td>   
                                                    </tr>
                                                </form>
                                            </tbody>
                                        </table>
                                    </div>
                                    <center>
                                         <h6 style="font-weight: bold;">
                                            FORM B: <br> 
                                            EMPLOYEE APPRAISAL AND DEVELOPMENT REPORT <br>
                                        </h6>
                                    </center>
                                    <div class = "row">
                                        <h6 style="position: relative; left: 15px;"><b>PART II:</b> FACTORS AFFECTING JOB PERFORMANCE</h6>
                                        <div class = "col-md-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">                                
                                                    <input type="number" step = "0.01" class="form-control" id = "quality_of_job" name = "quality_of_job">
                                                    <label class="form-label">
                                                        Quality of Job
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">                                                
                                                    <input type="number" step = "0.01" class="form-control" id = "public_and_emp_rel" name = "public_and_emp_rel">
                                                    <label class="form-label">
                                                        Public and Employee Relation
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group form-float">
                                                <div class = "form-line">
                                                    <label class="form-label">
                                                        Punctuality and Attendance
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>
                                                    <input type="number" step = "0.01" class="form-control" id = "punc_and_attend" name = "punc_and_attend">
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group form-float">
                                                <div class = "form-line">
                                                    <label class="form-label">
                                                        Industry
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>
                                                    <input type="number" step = "0.01" class="form-control" id = "industry" name = "industry">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Total Score
                                                    <small>
                                                        *
                                                    </small>
                                                </label>   
                                                 <div class="form-line focused">                                            
                                                    <input type="number" step = "0.01" class="form-control" id = "total_score" name = "total_score" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Average Score
                                                    <small>
                                                        *
                                                    </small>
                                                </label> 
                                                <div class="form-line focused">                                              
                                                    <input type="number" step = "0.01" class="form-control" id = "average_score" name = "average_score" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Rating
                                                    <small>
                                                        *
                                                    </small>
                                                </label>   
                                                <div class="form-line focused">                                                                                                  
                                                    <input type="number" step = "0.01" class="form-control" id = "rating" name = "rating" readonly>
                                                </div>                                           
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <h6 style="position: relative; left: 15px;"><b>PART III:</b> FINAL RATING</h6>
                                        <div class = "col-md-3">
                                            <div class="form-group form-float">
                                                <label class="form-label">
                                                    PART 1
                                                    <small>
                                                        *
                                                    </small>
                                                </label>
                                                <div class="form-line">                                                    
                                                    <input type="number" step = "0.01" class="form-control" id = "final_rating_part" name = "final_rating_part">
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group form-float">
                                                <label class="form-label">
                                                    PART 2
                                                    <small>
                                                        *
                                                    </small>
                                                </label>
                                                <div class = "form-line">
                                                    <input type="number" step = "0.01" class="form-control" id = "final_rating_part2" name = "final_rating_part2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    NUMERICAL RATING
                                                    <small>
                                                        *
                                                    </small>
                                                </label>
                                                <div class="form-line focused">
                                                    <input type="number" step = "0.01" class="form-control" id = "numerical_rate" name = "numerical_rate" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group form-float">
                                                <label class="form-label">
                                                    ADJECTIVAL RATING
                                                    <small>
                                                        *
                                                    </small>
                                                </label>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id = "adjectival_rate" name = "adjectival_rate">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class = "row">
                                        <h6 style="position: relative; left: 15px;"><b>PART IV:</b> SUGGESTED ACTION (Related to improve performance in present position)</h6>
                                        <div class = "col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Weaknesses
                                                    <small>
                                                        *
                                                    </small>
                                                </label>
                                                <textarea class="form-control" id = "weakness" name = "weakness"></textarea>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Recommendation to Employee to his/her improvement (specify:)
                                                    <small>
                                                        *
                                                    </small>
                                                </label>
                                                <textarea class="form-control" id = "weakness" name = "weakness"></textarea>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class = "row">
                                        <div class = "col-md-3">
                                            <div class="form-group">
                                                <label>Rater Name:</label>
                                                <select class = "employee_select" id = "rater_name" data-live-search = "true"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-3">
                                            <div class="form-group">
                                                <label>First Approver:</label>
                                                <select class = "employee_select first_approver" id = "first_approver" data-live-search = "true"></select>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group">
                                                <label>Position:</label>
                                                <input type = "text" class="form-control first_approver_position" id = "first_approver_position" readonly>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group">
                                                <label>Secord Approver:</label>
                                                <select class = "employee_select second_approver" id = "second_approver" data-live-search = "true"></select>
                                            </div>
                                        </div>
                                        <div class = "col-md-3">
                                            <div class="form-group">
                                                <label>Position:</label>
                                                <input type = "text" class="form-control second_approver_position" id = "second_approver_position" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                               
                            <div class="form-group">
                                <!-- <input type="submit" class="btn btn-success btn-md" style="width: 100%;"> -->
                                <!-- <button class="btn btn-success btn-md" style="width: 100%;" id = "print" data-toggle = "modal" data-target = "#print_preview_modal">Print</button> -->
                                <button class="btn btn-success btn-lg" style="width: 100%;" id = "submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="update_pes" tabindex="-1" role="dialog" aria-labelledby="update_pes" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <h2 style="position: relative; top: -10px;">
                   UPDATE PES - REPORT
                </h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="card">
                        <div class="body">
                            <center>
                                <h4 class="card-title" style="font-weight: bold;">AGRICUTURAL CREDIT POLICY COUNCIL</h4>
                                <small style="font-weight: bold;">
                                    28th Floor OSMA Bldg., One San Miguel Ave. cor Shaw Blvd. Ortigas Pasig City
                                </small>
                            </center>
                            <table>
                                <tr>
                                    <td><label style="color: black;">FORM A: INDIVIDUAL GOAL WORKSHEET</label></td>
                                </tr>
                                <tr>
                                    <td style="color: black;">PART I</td>
                                </tr>
                            </table>
                            <div class = "row">
                                <!-- <form action = "http://localhost/acpc_report/individual_goal_worksheet/report_list/exportExcel" method="POST" target = "_blank"> -->
                                    <div class = "col-md-12 col-lg-12">
                                        <div class = "table-responsive">
                                            <table class = "table form" id = "dynamic_row_update">
                                                <thead>
                                                    <tr>
                                                        <td style="font-size: 9pt;">
                                                            NAME:
                                                            <select id = "emp_name_display" class="employee_select" data-live-search="true" required disabled></select>
                                                        </td>
                                                        <td style="font-size: 9pt;">
                                                            POSITION: <span class="emp_position_display"></span>
                                                        </td>
                                                        <td style="font-size: 9pt;"> 
                                                            DIVISION: <span class="emp_division_display"></span>
                                                        </td>
                                                        <td style="font-size: 9pt;">
                                                            IMMEDIATE SUPERVISOR
                                                        </td>
                                                        <td style="font-size: 8pt;">
                                                            <select id = "supervisor_name_display" class="employee_select" data-live-search="true" required></select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <center>
                                                                INDIVIDUAL GOALS<br>
                                                                <small>
                                                                    (Must be specified with target dates as appropriate)
                                                                </small>
                                                            </center>
                                                        </th>
                                                        <th>
                                                            <center>
                                                                3rd QUARTER RESULTS<br>
                                                                <small>Date:_____ Initials:_____</small>
                                                            </center>
                                                        </th>
                                                         <th>
                                                            <center>
                                                                4th QUARTER RESULTS<br>
                                                                <small>Date:_____ Initials:_____</small>
                                                            </center>
                                                        </th>
                                                        <th>
                                                            <center>
                                                                RESULT AND REMARKS
                                                            </center>
                                                        </th>
                                                        <th>
                                                            <center>
                                                                PERCENTAGE SCORE<br>
                                                                <small>RESULTS _____ * 100%</small>
                                                            </center>
                                                        </th>
                                                        <th>
                                                            <center>
                                                                ACTION
                                                            </center>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id = "empty">
                                                    <form id = "update_form_pes">
                                                       <!--  <tr>
                                                            <td><input type="text" class="form-control" name="update-indv-goals[]" placeholder="Input text here.."></td>
                                                            <td><input type="text" class="form-control" name="update-third-res[]" placeholder="Input text here.."></td>
                                                            <td><input type="text" class="form-control" name="update-fourth-res[]" placeholder="Input text here.."></td>
                                                            <td><input type="text" class="form-control" name="update-res-remarks[]" placeholder="Input text here.."></td>
                                                            <td><input type="text" class="form-control" name="update-percentage[]" placeholder="Input text here.."></td>
                                                            <td>
                                                                <button class="btn btn-success btn-just-icon btn-round" id = "update_add">
                                                                    <i class="material-icons">add</i>
                                                                </button>
                                                            </td>   
                                                        </tr> -->
                                                    </form>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-success btn-lg" style="width: 100%;" id = "update_add">Add Row</button>
                                        </div>
                                        <br>
                                        <center>
                                             <h6 style="font-weight: bold;">
                                                FORM B: <br> 
                                                EMPLOYEE APPRAISAL AND DEVELOPMENT REPORT <br>
                                            </h6>
                                        </center>
                                        <div class = "row">
                                            <h6 style="position: relative; left: 15px;"><b>PART II:</b> FACTORS AFFECTING JOB PERFORMANCE</h6>
                                            <div class = "col-md-3">
                                                <div class="form-group form-float">
                                                    <div class="form-line focused">                                
                                                        <input type="number" step = "0.01" class="form-control" id = "update_quality_of_job" name = "update_quality_of_job">
                                                        <label class="form-label">
                                                            Quality of Job
                                                            <small>
                                                                *
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class = "col-md-3">
                                                <div class="form-group form-float">
                                                    <div class="form-line focused">                                                
                                                        <input type="number" step = "0.01" class="form-control" id = "update_public_and_emp_rel" name = "update_public_and_emp_rel">
                                                        <label class="form-label">
                                                            Public and Employee Relation
                                                            <small>
                                                                *
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class = "col-md-3">
                                                <div class="form-group form-float">
                                                    <div class = "form-line focused">
                                                        <label class="form-label">
                                                            Punctuality and Attendance
                                                            <small>
                                                                *
                                                            </small>
                                                        </label>
                                                        <input type="number" step = "0.01" class="form-control" id = "update_punc_and_attend" name = "update_punc_and_attend">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class = "col-md-3">
                                                <div class="form-group form-float">
                                                    <div class = "form-line focused">
                                                        <label class="form-label">
                                                            Industry
                                                            <small>
                                                                *
                                                            </small>
                                                        </label>
                                                        <input type="number" step = "0.01" class="form-control" id = "update_industry" name = "update_industry">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "row">
                                            <div class = "col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        Total Score
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>   
                                                     <div class="form-line focused">                                                                                       
                                                        <input type="number" step = "0.01" class="form-control" id = "update_total_score" name = "update_total_score" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class = "col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        Average Score
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>  
                                                    <div class="form-line focused">                                                                                                     
                                                        <input type="number" step = "0.01" class="form-control" id = "update_average_score" name = "update_average_score" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class = "col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        Rating
                                                        <small>
                                                            *
                                                        </small>
                                                    </label> 
                                                    <div class="form-line focused">                                                                                                        
                                                        <input type="number" step = "0.01" class="form-control" id = "update_rating" name = "update_rating" readonly>
                                                    </div>                                           
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class = "row">
                                            <h6 style="position: relative; left: 15px;"><b>PART III:</b> FINAL RATING</h6>
                                            <div class = "col-md-3">
                                                <div class="form-group form-float">
                                                    <label class="form-label">
                                                        PART 1
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>
                                                    <div class="form-line focused">                                                        
                                                        <input type="number" step = "0.01" class="form-control" id = "update_final_rating_part" name = "update_final_rating_part">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class = "col-md-3">
                                                <div class="form-group form-float">
                                                    <label class="form-label">
                                                        PART 2
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>
                                                    <div class = "form-line focused">                                                        
                                                        <input type="number" step = "0.01" class="form-control" id = "update_final_rating_part2" name = "update_final_rating_part2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class = "col-md-3">
                                                <div class="form-group">
                                                     <label class="form-label">
                                                        NUMERICAL RATING
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>
                                                    <div class="form-line focused">                                                       
                                                        <input type="number" step = "0.01" class="form-control" id = "update_numerical_rate" name = "update_numerical_rate" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class = "col-md-3">
                                                <div class="form-group form-float">
                                                    <label class="form-label">
                                                        ADJECTIVAL RATING
                                                        <small>
                                                            *
                                                        </small>
                                                    </label>
                                                    <div class="form-line focused">
                                                        <input type="text" class="form-control" id = "update_adjectival_rate" name = "update_adjectival_rate">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "row">
                                            <div class = "col-md-3">
                                                <div class="form-group">
                                                    <label>Rater Name:</label>
                                                    <select class = "employee_select" id = "rater_name_update" data-live-search = "true"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class = "row">
                                            <div class = "col-md-3">
                                                <div class="form-group">
                                                    <label>First Approver:</label>
                                                    <select class = "employee_select first_approver" id = "first_approver_update" data-live-search = "true"></select>
                                                </div>
                                            </div>
                                            <div class = "col-md-3">
                                                <div class="form-group">
                                                    <label>Position:</label>
                                                    <input type = "text" class="form-control first_approver_position" id = "first_approver_position_update" readonly>
                                                </div>
                                            </div>
                                            <div class = "col-md-3">
                                                <div class="form-group">
                                                    <label>Secord Approver:</label>
                                                    <select class = "employee_select" id = "second_approver_update" data-live-search = "true"></select>
                                                </div>
                                            </div>
                                            <div class = "col-md-3">
                                                <div class="form-group">
                                                    <label>Position:</label>
                                                    <input type = "text" class="form-control" id = "second_approver_position_update" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id = "hidden_id" name="hidden_id">
                                        <input type="hidden" id = "hidden_id_form" name="hidden_id_form">
                                        <!-- <div class="form-group"> -->
                                            <!-- <input type="submit" class="btn btn-success btn-md" style="width: 100%;"> -->
                                            <!-- <button class="btn btn-success btn-md" style="width: 100%;" id = "print" data-toggle = "modal" data-target = "#print_preview_modal">Print</button> -->
                                            
                                        <!-- </div> --><br>
                                        <div class="form-group">
                                            <button class="btn btn-warning btn-lg" type="submit" style="width: 100%;" id = "update_submit">Update</button>
                                        </div>
                                    </div>
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="print_preview_modal" tabindex="-1" role="dialog" aria-labelledby="print_preview_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
                <h4 class="modal-title">Print Preview</h4>
            </div>
            <div class = "table-responsive">
                <div id = "content">
                    <style type="text/css" media="print">
                        @media print {
                            @page { 
                                size: legal landscape;
                                width:816px;
                                height:1344px;
                                margin: 10mm 10mm 10mm 10mm;
                            }

                            #foot label{
                                color: black;
                                font-family: arial;
                                font-size: 10pt;
                            }

                            .break{
                                page-break-before: always;
                            }

                            span{
                                font-family: calibri;
                                font-size: 11pt;
                            }

                            #table-pageii{
                                font-family: calibri;
                                font-size: 11pt;
                            }

                            h5{
                                font-family: calibri;
                                font-size: 11pt;
                            }
                        </style>
                    <center>
                        <h3 style="font-family: calibri; margin: 0px;">AGRICULTURAL CREDIT POLICY COUNCIL</h3>
                        <small>28th Floor OSMA Bldg., One San Miguel Ave. cor Shaw Blvd. Ortigas Pasig City</small>
                        <h5 style="font-family: calibri;">Static Date</h5>
                    </center>
                    <div class = "container-fluid">
                        <h5 style="font-family: calibri; margin: 0px;">FORM A: INDIVIDUAL GOAL WORKSHEET</h5>
                        <h5 style="font-family: calibri; margin: 0px; font-weight: normal;">PART I</h5>
                        <br>
                        <table style="border-collapse: collapse; width: 100%;">
                            <thead style="font-family: calibri;">
                                <tr>
                                    <th style="text-align: left; font-size: 10pt;">NAME: <label style="color:  black;" id = "report_name"></label></th>
                                    <td style="font-size: 10pt;">POSITION: <span class = "disp_position"></span></td>
                                    <td style="font-size: 10pt;">DIVISION: <span class = "disp_divison"></span></td>
                                    <td style="font-size: 10pt;">IMMEDIATE SUPERVISOR:</td>
                                    <td style="font-size: 10pt;"><span class = "disp_super"></span></td>
                                </tr>
                                <tr style="border: 1px solid black;">
                                    <th style="padding: 10px 80px 10px 80px; text-align: center; border: 1px solid black;">
                                        INDIVIDUAL GOALS<br>
                                        <small>(Must be specified with target dates as appropriate)</small>
                                    </th>
                                    <th style="padding: 10px 20px 10px 20px; text-align: center; border: 1px solid black;">
                                        3rd QUARTER RESULTS<br>
                                        <small>Date:_____ Initials:_____</small>
                                    </th>
                                    <th style="padding: 10px 20px 10px 20px; text-align: center; border: 1px solid black;">
                                        4th QUARTER RESULTS<br>
                                        <small>Date:_____ Initials:_____</small>
                                    </th>
                                    <th style="padding: 10px 20px 10px 20px; text-align: center; border: 1px solid black;">
                                        RESULTS AND REMARKS<br>
                                        <small>Date:_____ Initials:_____</small>
                                    </th>
                                    <th style="padding: 10px 20px 10px 20px; text-align: center; border: 1px solid black;">
                                        PERCENTAGE SCORE<br> 
                                        <small>RESULTS _____ * 100%</small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id = "table-content"></tbody>
                        </table>
                        <div id = "foot">
                            <label>Goal Approved:</label>
                            <label style="position: relative; left: 250px;">Total Score in Percentage: 1000% _______</label>
                            <label style="position: relative; left: 280px;">Average Score in % _____ 10 ______</label>
                            <label style="position: relative; left: 300px;">Rating (Average Score x 75%) in % _____ 75 ______</label>
                            <br>
                            <label style="position: relative; left: 340px;">(Result 1 + Result 2 + Result 3 + Result 4 + Result 5)</label>
                            <br>
                            <label style="position: relative; left: 50px;">_________________________<br></label>
                            <br>
                            <label style="position: relative; left:  120px; top: -5px;">DATE:</label>
                        </div>  
                        <div class = "break">
                            <table>
                                <tr>
                                    <td>
                                        <h5>FORM B</h5>
                                    </td>                       
                                </tr>
                                <tr>
                                    <td>
                                        <h5>EMPLOYEE APPRAISAL AND DEVELOPMENT REPORT</h5>
                                    </td>
                                    <td style="width: 19%;">

                                    </td>
                                    <td>
                                        <h5>Rating Scale:</h5>
                                        <span style="font-size: 11pt;">Outstanding: 125% and over &nbsp &nbsp &nbsp &nbsp &nbsp Satisfactory: 81% - 95% &nbsp &nbsp &nbsp &nbsp &nbsp Poor: 74% and bellow</span><br>
                                        <span style="font-size: 11pt;">Very Satisfactory: 96% - 124% &nbsp &nbsp &nbsp  Unsatisfactory: 75% - 80%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Instruction: Must be accomplished in the handwriting of the Rater</span>
                                    </td>
                                </tr>
                            </table>
                            <!-- 
                                <h5>Rating Scale</h5>
                                <span style="font-size: 11pt;">Outstanding: 125% and over &nbsp &nbsp &nbsp &nbsp &nbsp Satisfactory: 81% - 95% &nbsp &nbsp &nbsp &nbsp &nbsp Poor: 74% and bellow</span><br>
                                <span style="font-size: 11pt;">Very Satisfactory: 96% - 124% &nbsp &nbsp &nbsp  Unsatisfactory: 75% - 80%</span>
                            </div>  -->
                            <table id = "table-pageii">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; width: 37%;">
                                            PART II: FACTORS AFFECTING JOB PERFORMANCE
                                        </th>
                                        <th style="width: 35%; text-align: left;"><br>
                                            PART IV: SUGGESTED ACTION (Related to improve performance in present position)
                                        </th>
                                        <th style="text-align: left;">
                                            PART V: FUTURE DEVELOPMENT (check one)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding-bottom: 15px;">
                                            A. QUALITY OF JOB: 
                                            <input type="text" name="qob" id = "qob" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                            B. PUBLIC AND EMPLOYEE RELATION: 
                                            <input type="text" name="public" id = "public" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                            C. PUNCTUALITY AND ATTENDANCE: 
                                            <input type="text" name="punc" id = "punc" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                            D. INDUSTRY: 
                                            <input type="text" name="ind" id = "ind" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                            TOTAL SCORE: 
                                            <input type="text" name="total" id = "total" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                             AVERAGE SCORE: 
                                            <input type="text" name="ave" id = "ave" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                            RATING: 
                                            <input type="text" name="rate" id = "rate" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                            <span style="font-weight: bold;">PART III: FINAL RATING</span> <br> <br>
                                            PART I: 
                                            <input type="text" name="parti" id = "parti" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                            PART II: 
                                            <input type="text" name="partii" id = "partii" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                            NUMERICAL RATING: 
                                            <input type="text" name="nume" id = "nume" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>
                                            ADJECTIVAL RATING: 
                                            <input type="text" name="adj" id = "adj" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; text-align: center;" readonly> <br> <br>

                                        </td>
                                        <td>
                                            <span style="position: relative; left: 20px;">
                                                (a) Weakness:<br>
                                                ________________________________________________<br>
                                                ________________________________________________<br>
                                                ________________________________________________<br>
                                            </span> <br>

                                            <span style="position: relative;  left: 20px;">
                                                (b) Recommendation to Employee for his/her improvement<br>(specify:)<br>
                                                ________________________________________________<br>
                                                ________________________________________________<br>
                                                ________________________________________________<br>
                                            </span> <br>

                                            <span style="position: relative;  left: 20px;">
                                                (c) Special Ability and Qualifications:<br>
                                                ________________________________________________<br>
                                                ________________________________________________<br>
                                                ________________________________________________<br>
                                            </span>

                                            <span style="position: relative; top: 20px; left: 20px;">
                                                (d) Reaction of Employee:<br>
                                                ________________________________________________<br>
                                                ________________________________________________<br>
                                                ________________________________________________<br>
                                            </span> <br>

                                            <span style="position: relative; top: 20px; left: 20px;">
                                                (e) This is to certify that the appraisal and <br>all above points
                                                where discussed with me by my supervisor.<br><br>
                                                ________________________________________________<br>
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp &nbspEmployee Signature
                                                
                                            </span><br> <br>
                                            <span style="position: relative; left: 90px;">____________________________</span><br>
                                                <span style="position: relative; left: 170px; ">Date</span>
                                        </td>
                                        <td>
                                            <div style="border: 1px solid black; height: 20px; width: 20px; float: left; display: block;"></div>
                                            <span style = "float: left; display: block;">&nbsp Promotable and Ready</span><br><br>
                                            <div style="border: 1px solid black; height: 20px; width: 20px; float: left; display: block;"></div>
                                            <span style = "float: left; display: block;">&nbsp Promotable with additional experience</span><br><br>
                                            <div style="border: 1px solid black; height: 20px; width: 20px; float: left; display: block;"></div>
                                            <span style = "float: left; display: block;">&nbsp Properly placed. Meets all requirements</span><br><br>
                                            <div style="border: 1px solid black; height: 20px; width: 20px; float: left; display: block;"></div>
                                            <span style = "float: left; display: block;">&nbsp Needs Improvement</span><br><br>
                                            <div style="border: 1px solid black; height: 20px; width: 20px; float: left; display: block;"></div>
                                            <span style = "float: left; display: block;">&nbsp Needs further trial and approval on</span><br><br>
                                            <span>___________________________________________</span> <br> <br> 

                                            <div style="position: relative; left: 80px; width: 200px; text-align: center;">
                                                <input type="text" id = "rater_display" name="" style="border: 0px; border-bottom: 1px solid black; text-align: center;" required> <br>
                                                <span style="position: relative;">Rater</span>  <br> <br> <br>
                                                <input type="text" id = "director_display" name="" style="border: 0px; border-bottom: 1px solid black; text-align: center;" required> <br>
                                                <span style="position: relative;"><span class = "director_view"></span><br> Approved</span> <br> <br> <br>
                                                <input type="text" id = "exe_director" name="" style="border: 0px; border-bottom: 1px solid black; width: 100%; text-align: center;" readonly> <br> 
                                                <span style="position: relative;"><span id = "third_position"></span><br>Approved</span>
                                            </div>                                    
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>  
                </div>
            </div>
            <div class = "modal-footer">
                <div class = "container-fluid">  
                    <button type="submit" class="btn btn-success" id = "print_preview">Print Preview</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/local/module/js/myworksheet/worksheet.js"></script> 
