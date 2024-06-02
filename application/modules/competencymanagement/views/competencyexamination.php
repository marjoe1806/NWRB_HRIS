<script type="text/javascript">
var validation = <?php echo json_encode($validation); ?>;
</script>
<?php 
function convertToHoursMins($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}
?>
<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Competency Exam <small>Manage Competency Exam</small>
                </h2>

            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <div class="body">
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <button type="button" id="start"
                                                class="btn btn-lg btn-block btn-primary waves-effect waves-float examinationFormBtn">
                                                <span class="hours" id="hour"></span>:
                                                <span class="minutes" id="minute"></span>:
                                                <span class="seconds" id="second"></span></button>
                                            <div class="examinationForm">
                                                <form id="<?php echo $key; ?>"
                                                    action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>"
                                                    method="POST">
                                                    <?php if(isset($exam) && sizeof($exam) > 0): ?>
                                                    <input type="hidden" name="accessemail_id"
                                                        value="<?php echo $validation['Data']['accessemail_id'] ?>"
                                                        class="sequence form-control">
                                                    <input type="hidden" name="competency_id"
                                                        value="<?php echo $validation['Data']['type_id'] ?>"
                                                        class="sequence form-control">
                                                    <br>
                                                    <br>
                                                    <h4 class="text-primary">ENUMERATION</h4>
                                                    <?php for ($i=0; $i < sizeof($exam); $i++): if($exam[$i]->exam_type == 'enumeration'): ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label"><?php echo $exam[$i]->question; ?>
                                                                <span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="text"
                                                                        name="<?php echo $exam[$i]->question_id ?>"
                                                                        class="sequence form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; endfor; ?>
                                                    <h4 class="text-primary">MULTIPLE CHOICE</h4>
                                                    <?php for ($b=0; $b < sizeof($exam); $b++): if($exam[$b]->exam_type == 'multiple'): ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label"><?php echo $exam[$b]->question; ?>
                                                                <span class="text-danger">*</span></label>
                                                            <?php
                                                                    $choicesArray = str_replace('"', "", $exam[$b]->choices);
                                                                    $choices = explode(',', $choicesArray);
                                                                ?>
                                                            <?php for ($c=0; $c < sizeof($choices); $c++): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="<?php echo $exam[$b]->question_id; ?>"
                                                                    id="<?php echo $choices[$c]; ?>"
                                                                    value="<?php echo $choices[$c]; ?>">
                                                                <label class="form-check-label"
                                                                    for="<?php echo $choices[$c]; ?>">
                                                                    <?php echo $choices[$c]; ?>
                                                                </label>
                                                            </div>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; endfor; ?>
                                                    <h4 class="text-primary">FILL IN THE BLANK</h4>
                                                    <?php for ($d=0; $d < sizeof($exam); $d++): if($exam[$d]->exam_type == 'fill'): ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-label"><?php echo $exam[$d]->question; ?>
                                                                <span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <input type="text"
                                                                        name="<?php echo $exam[$d]->question_id ?>"
                                                                        id="as" class="sequence form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; endfor; ?>
                                                    <h4 class="text-primary">ESSAY</h4>
                                                    <?php for ($e=0; $e < sizeof($exam); $e++): if($exam[$e]->exam_type == 'essay'): ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label"><?php echo $exam[$e]->question; ?>
                                                                <span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <div class="form-line">
                                                                    <textarea class="form-control"
                                                                        name="<?php echo $exam[$e]->question_id ?>"
                                                                        rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; endfor; ?>
                                                    <div class="text-right" style="width:100%;">
                                                        <button class="btn btn-primary btn-sm waves-effect"
                                                            type="submit">
                                                            <i class="material-icons">add</i><span> Submit</span>
                                                        </button>
                                                    </div>
                                                    <?php endif; ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"
    src="<?php echo base_url(); ?>assets/modules/js/competencymanagement/competencyexamination.js">
</script>
<!-- Your HTML code, assuming you have elements with id "hour", "minute", "second" -->
<!-- ... -->

<!-- Your JavaScript code -->
<script>
  // Set the countdown duration in milliseconds
  const exam_duration = parseInt("<?php echo $validation['Data']['exam_duration']; ?>");
  const countdownDuration = exam_duration * 60 * 1000; // 60 minutes in milliseconds

  // Function to start the countdown
  function startCountdown() {
    let deadline;

    // Check if a deadline is already stored in local storage
    if (localStorage.getItem("deadline")) {
      deadline = parseInt(localStorage.getItem("deadline"));
    } else {
      // If no deadline is found, calculate the new deadline and store it in local storage
      deadline = new Date().getTime() + countdownDuration;
      localStorage.setItem("deadline", deadline);
    }

    // Calling defined function at certain interval
    let x = setInterval(function() {
      // Getting current date and time in format
      let now = new Date().getTime();

      // Calculating difference
      let t = deadline - now;

      let hours = Math.floor(t / (1000 * 60 * 60));
      let minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
      let seconds = Math.floor((t % (1000 * 60)) / 1000);

      document.getElementById("hour").innerHTML = hours;
      document.getElementById("minute").innerHTML = minutes;
      document.getElementById("second").innerHTML = seconds;

      // Show overtime output
      if (t < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "TIME UP";

        document.getElementById("hour").innerHTML = "0";
        document.getElementById("minute").innerHTML = "0";
        document.getElementById("second").innerHTML = "0";
      }
    }, 1000);
  }

  // Start the countdown automatically when the page loads
  startCountdown();
</script>
