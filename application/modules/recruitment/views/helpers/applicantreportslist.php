<div class="button-holder"></div>
<hr>
<div class="table-responsive listTable" style="width:100%;">
    <table id="datatables" class="table table-hover table-striped">
        <thead>
            <tr>
                <td>Last Name</td>
                <td>First Name</td>
                <td>Middle Name</td>
                <td>Position</td>
                <td>Contact Number</td>
                <td>Email</td>
                <td>Status</td>
            </tr>
        </thead>
		<tbody>
			<?php if(isset($applicants)):?>
				<?php foreach($applicants as $key => $applicant): ?>
					<tr>
						<td><?=$applicant['last_name']?></td>
						<td><?=$applicant['first_name']?></td>
						<td><?=$applicant['middle_name']?></td>
						<td><?=$applicant['position']?></td>
						<td><?=$applicant['contact_number']?></td>
						<td><?=$applicant['email']?></td>
						<td><?=$applicant['application_status']?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
    </table>
</div>
