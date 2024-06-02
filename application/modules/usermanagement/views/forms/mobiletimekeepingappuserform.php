<?php 
	$readonly = "";
	if($key == "viewMobileUserConfig")
		$readonly = "disabled";
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Location <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<select class="location_id form-control" id="location_id" data-live-search="true" <?php echo $readonly ?>>
                            <option value="" selected></option>
                            <?php foreach ($locations as $k => $v) : ?>
                                <option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
                            <?php endforeach; ?>
                            
                        </select>
                	</div>
            	</div>
            </div>
			<?php if($key == "viewMobileUserConfig"):?>
			<div class="col-md-12" style="pointer-events:none;">
				<div class="form-group form-float">
					<label class="form-label">Employee <span class="text-danger">*</span></label>
					<div class="form-line employee_select">
						<select class="employee_id form-control" id="employee_id" name="employee_id" data-live-search="true" readonly>
							<option value=""></option>
						</select>
					</div>
				</div>
			</div>
			<?php else:?>
				<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Employee <span class="text-danger">*</span></label>
					<div class="form-line employee_select">
						<select class="employee_id form-control" id="employee_id" name="employee_id" data-live-search="true" readonly>
							<option value=""></option>
						</select>
					</div>
				</div>
			</div>
			<?php endif;?>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Position </label>
					<div class="form-line">
						<input type="text" class="position_name form-control" id="position_name" aria-required="true" readonly>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Department <span class="text-danger">*</span></label>
					<div class="form-line">
						<input type="text" class="department_name required form-control" id="department_name" required="" aria-required="true" readonly>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group form-float">
					<label class="form-label">Calendar <span class="text-danger">*</span></label>
					<div class="form-line">
						<div id="calendar"></div>
					</div>
				</div>
			</div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addMobileUserConfig"): ?>
    		<button class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Create</span>
	        </button>
    	<?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script>
	$(document).ready(function(){
    var calendar = $('#calendar').fullCalendar({
        editable:true,
        header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'
        },
        events:"<?php echo base_url(); ?>fullcalendar/load",
        selectable:true,
        selectHelper:true,
        select:function(start, end, allDay)
        {
            var title = prompt("Enter Event Title");
            if(title)
            {
                var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                $.ajax({
                    url:"<?php echo base_url(); ?>fullcalendar/insert",
                    type:"POST",
                    data:{title:title, start:start, end:end},
                    success:function()
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert("Added Successfully");
                    }
                })
            }
        },
        editable:true,
        eventResize:function(event)
        {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");

            var title = event.title;

            var id = event.id;

            $.ajax({
                url:"<?php echo base_url(); ?>fullcalendar/update",
                type:"POST",
                data:{title:title, start:start, end:end, id:id},
                success:function()
                {
                    calendar.fullCalendar('refetchEvents');
                    alert("Event Update");
                }
            })
        },
        eventDrop:function(event)
        {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            //alert(start);
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            //alert(end);
            var title = event.title;
            var id = event.id;
            $.ajax({
                url:"<?php echo base_url(); ?>fullcalendar/update",
                type:"POST",
                data:{title:title, start:start, end:end, id:id},
                success:function()
                {
                    calendar.fullCalendar('refetchEvents');
                    alert("Event Updated");
                }
            })
        },
        eventClick:function(event)
        {
            if(confirm("Are you sure you want to remove it?"))
            {
                var id = event.id;
                $.ajax({
                    url:"<?php echo base_url(); ?>fullcalendar/delete",
                    type:"POST",
                    data:{id:id},
                    success:function()
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert('Event Removed');
                    }
                })
            }
        }
    });
});
</script>

