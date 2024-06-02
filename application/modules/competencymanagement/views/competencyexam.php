<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="path/to/loading-animation.css">
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> -->
<!-- Add this script tag to include the latest version of jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
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
                <!-- html -->
                <!-- Button to trigger the modal -->

                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Compentency Exam</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <!-- <span aria-hidden="true">&times;</span> -->
                                    <span class="hours" id="hour"></span>:
                                    <span class="minutes" id="minute"></span>:
                                    <span class="seconds" id="second"></span>
                                    <div>

                                </button>
                            </div>
                            <div class="modal-body">

                                <!-- Modal content goes here -->
                                <!-- <?php print_r($multiple_choice_data[0]); ?> -->
                                <!-- <button id="export-btn" onclick="exportToPDF()">Export to PDF</button> -->
                                <?php
                                    // PHP array
                                    $phpArray = $quiz_data;

                                    // Encode the PHP array as JSON
                                    $jsonArray = json_encode($phpArray);
                                    ?>

                                <form method="post">
                                    <?php foreach ($quiz_data as $question): ?>
                                    <p><?php echo $question->question; ?></p>
                                    <?php if ($question->exam_type === 'enumeration'): ?>
                                    <input class="form-control" type="text" name="<?php echo $question->id; ?>"
                                        id="<?php echo $question->exam_type ?>">
                                    <?php elseif ($question->exam_type === 'fill'): ?>
                                    <input class="form-control" type="text" name="<?php echo $question->id; ?>"
                                        id="<?php echo $question->exam_type ?>">
                                    <?php elseif ($question->exam_type === 'essay'): ?>
                                    <textarea class="form-control" name="<?php echo $question->id; ?>"
                                        id="<?php echo $question->exam_type ?>"></textarea>
                                    <?php elseif ($question->exam_type === 'multiple'): ?>

                                    <?php
                                        foreach ($multiple_choice_data as $array_value):
                                            foreach ($array_value as $key => $value): 
                                                if ($value == $question->id): 
                                                    $string = $array_value->choices;

                                                    // Remove the surrounding double quotes
                                                    $string = trim($string, '"');
                                                    
                                                    // Convert the string to an array
                                                    $array = explode('","', $string);
                                                    
                                                    ?>
                                    <?php 
                                   
                                                       for ($i = 0; $i < count($array); $i++):
                                                        // echo $array[$i];
                                                    ?>
                                    <input type="radio" name="<?php echo $question->id; ?>"
                                        style="background-color: black" id="<?php echo $question->exam_type ?>"
                                        value="<?=$array[$i]?>">
                                    <span><?=$array[$i]?></span><br>
                                    <?php endfor; ?>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php endforeach; ?>
                                    <!-- Add more options as needed -->
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    <br>
                                    <div class="modal-footer">
                                        <input class="fancy-button" type="submit" value="Submit">
                                    </div>

                                </form>


                                <!-- </body> -->
                            </div>
                            <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Hide</button> -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start Quiz button -->
                <!-- <div id="table-holder"> -->

            </div>
            <div class="start_btn"><button>Start Exam</button></div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="info_box">
        <div class="info-title"><span>Reminder</span></div>
        <div class="info-list">
            <div class="info">1. You will have only
                <span><?php echo ($validation['Code'] == 0) ? convertToHoursMins($validation['Data']['exam_duration'], '%02d hours %02d minutes') : '' ?></span>
                to complete the exam
            </div>
            <div class="info">2. Opening a new tab during the Exam is not allowed</div>
            <!-- <div class="info">3. You will have only <span>30 minutes</span></div>
            <div class="info">4. You will have only <span>30 minutes</span></div>
            <div class="info">5. You will have only <span>30 minutes</span></div> -->
        </div>
        <div class="buttons">
            <button class="quit">Exit Exam</button>
            <button class="restart" data-toggle="modal" data-target="#myModal" id="startButton">Continue</button>
        </div>
    </div>


</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
function exportToPDF() {

    const pdf = new jsPDF();
    const exportContent = document.getElementById('myModal');
    // Get the content of the div with id "pdfContent"
    const pdfContent = document.getElementById('myModal');

    // Create a new jsPDF instance with landscape orientation
    const doc = new jsPDF({
        orientation: 'landscape',
    });

    // Set the font size (optional)
    doc.setFontSize(12);

    // Convert the HTML content to a canvas element using html2canvas library
    html2canvas(pdfContent).then((canvas) => {
        // Get the width and height of the canvas
        const width = canvas.width;
        const height = canvas.height;

        // Add the canvas to the PDF document
        doc.addImage(canvas, 'PNG', 10, 10, width * 0.75, height * 0.75);

        // Save the PDF with a filename
        doc.save('info.pdf');
    });
}
// Function to count the correct answers
function countCorrectAnswers(examData, userAnswers) {
    let totalScore = 0;
    let score = {
        competency_id: 0,
        enumeration_res: 0,
        fill_res: 0,
        multiplication_res: 0,
        access_id: validation.Data.access_id
    }

    let answer = {
        access_email_id: 0,
        access_id: validation.Data.access_id,
        competency_id: 0,
        question_id: 0,
        answer: '',
        user_email_id: validation.Data.id,
        score: 0
    }
    // return;
    console.log('answer ', validation.Data);
    //looping array result
    for (const question of examData) {
        score.competency_id = Number(question.competency_id);
        answer.competency_id = Number(question.competency_id);
        answer.id = Number(question.id);


        if (question.exam_type == 'enumeration') {
            console.log(userAnswers[`${question.id}`].value);
            let correctAns = question.answer.toLowerCase() ? question.answer.toLowerCase() : '';
            let userAns = userAnswers[`${question.id}`].value ? userAnswers[`${question.id}`].value.toLowerCase() : 'no answer';
            //insert enumeration to answer table
            answer.answer = userAns;
            answer.score = Number(question.points);
            addPerAnswer(answer);
            if (correctAns == userAns) {
                score.enumeration += Number(question.points);
            }
        } else if (question.exam_type == 'fill') {
            console.log(userAnswers[`${question.id}`].value);
            let correctAns = question.answer.toLowerCase() ? question.answer.toLowerCase() : '';
            let userAns = userAnswers[`${question.id}`].value ? userAnswers[`${question.id}`].value.toLowerCase() : 'no answer';
            //insert fill to answer table
            answer.answer = userAns;
            answer.score = Number(question.points);
            addPerAnswer(answer);
            if (correctAns == userAns) {
                score.fill_res++;
            }
        } else if (question.exam_type == 'multiple') {

            let correctAns = question.answer.toLowerCase() ? question.answer.toLowerCase() : '';
            let userAns = userAnswers[`${question.id}`].value ? userAnswers[`${question.id}`].value.toLowerCase() : 'no answer';
            answer.answer = userAns;
            answer.score = Number(question.points);
            addPerAnswer(answer);
            if (correctAns == userAns) {
                score.multiplication_res++;
            }
        } else if (question.exam_type == 'essay') {

            // let correctAns = question.answer.toLowerCase() ? question.answer.toLowerCase() : '';
            let userAns = userAnswers[`${question.id}`].value ? userAnswers[`${question.id}`].value.toLowerCase() : 'no answer';
            answer.answer = userAns;
            addPerAnswer(answer);
        }
    }

    //insert result
    $.ajax({
        type: "POST",
        url: '<?=base_url()?>competencymanagement/CompetencyExam/add_result',
        data: score,
        dataType: "json",
        success: function(result) {
            // if (result.Code == "0") {
            //     console.log(result)
            // } else {
            //     console.log('error')
            // }
        },
        error: function(result) {
            console.log(result)
        }
    })
    setTimeout(() => {
        swal({
            title: "Competency Exam Result",
            text: `Multiplication : ${score.multiplication_res}, \n Enumaration : ${score.enumeration_res}, \n Fill in the blanks: ${score.fill_res}, \n Essay result pending result`,
            icon: "success",
            button: "Ok",
        });
    }, 2000);
    console.log('your score ', score);
}

function addPerAnswer(data) {
    console.log('data answer ', data.answer);
    return;
    // $.ajax({
    //     type: "POST",
    //     url: '<?=base_url()?>competencymanagement/CompetencyExam/add_answer',
    //     data: data
    //     dataType: "json",
    //     success: function(result) {
    //         if (result.Code == "0") {
    //             console.log(result)
    //         } else {

    //         }
    //     },
    //     error: function(result) {

    //     }
    // })
}

function createObjectFromForm(data) {
    console.log('data ', data)
    // Get the form element by its ID
    const form = document.querySelector('form');

    // const form = document.getElementById('quizForm');
    const formData = {};

    for (let i = 0; i < form.elements.length; i++) {
        const element = form.elements[i];
        const name = element.name;
        const value = element.value;
        const type = element.id; // Get the "id" attribute
        // console.log('type ', element.checked)
        // Check if the element is a radio input and if it's checked
        if (element.type === "radio" && element.checked) {
            formData[name] = {
                value: value,
                type: type
            };
        } else if (element.type !== "radio") {
            // For other input types, add them to formData
            formData[name] = {
                value: value,
                type: type
            };
        }
    }

    console.log(formData);
    // document.querySelector('#res').innerText = formData.toString();
    // Call the function and get the total score
    const score = countCorrectAnswers(data, formData);
    console.log('Total Score:', score);
}

// Add an event listener to the form's submit event
const form = document.querySelector('form');
form.addEventListener('submit', function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();
    swal({
        title: 'Loading...',
        allowOutsideClick: false,
        showConfirmButton: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        }
    });
    // Call the function to create the object
    createObjectFromForm(<?= $jsonArray ?>);
});




// Set the countdown duration in milliseconds

const exam_duration = parseInt("<?php echo $validation['Data']['exam_duration']; ?>");
const countdownDuration = exam_duration * 60 * 1000; // 60 minutes in milliseconds
let deadline = new Date().getTime() + countdownDuration;

// Calling defined function at certain interval
let x = setInterval(function() {

    // Getting current date and time in required format
    let now = new Date().getTime();

    // Calculating difference
    let t = deadline - now;

    let hours = Math.floor(
        t / (1000 * 60 * 60)
    );
    let minutes = Math.floor(
        (t % (1000 * 60 * 60)) / (1000 * 60)
    );
    let seconds = Math.floor(
        (t % (1000 * 60)) / 1000
    );

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

$(window).focus(function() {
    console.log('open new tab');
});

// $(window).blur(function() {
//     console.log('open new tab');
// });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/competencymanagement/competencyexam.js">
</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

[type="radio"]:not(:checked),
[type="radio"]:checked {
    position: unset;
    left: unset;
    opacity: unset;
}

.modal-dialog {
    width: 70%;
    margin: 0px auto;
}

input,
textarea {
    margin-left: 20px !important;
}

/* Styles for the button */
.fancy-button {
    display: inline-block;
    padding: 10px 20px;
    border: 2px solid #007bff;
    background-color: #ffffff;
    color: #007bff;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.2s, color 0.2s;
}

/* Styles for when the button is hovered */
.fancy-button:hover {
    background-color: #007bff;
    color: #ffffff;
}

/* Styles for when the button is clicked */
.fancy-button:active {
    background-color: #0056b3;
    color: #ffffff;
}

/* Styles for when the button is disabled */
.fancy-button:disabled {
    background-color: #cccccc;
    color: #888888;
    border: 2px solid #cccccc;
    cursor: not-allowed;
}


* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #007bff;
}

::selection {
    color: #fff;
    background: #007bff;
}

.start_btn,
.info_box,
.quiz_box,
.result_box {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2),
        0 6px 20px 0 rgba(0, 0, 0, 0.19);
}

.info_box.activeInfo,
.quiz_box.activeQuiz,
.result_box.activeResult {
    opacity: 1;
    z-index: 5;
    pointer-events: auto;
    transform: translate(-50%, -50%) scale(1);
}

.start_btn button {
    font-size: 25px;
    font-weight: 500;
    color: #007bff;
    padding: 15px 30px;
    outline: none;
    border: none;
    border-radius: 5px;
    background: #fff;
    cursor: pointer;
}

.info_box {
    width: 540px;
    background: #fff;
    border-radius: 5px;
    transform: translate(-50%, -50%) scale(0.9);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
}

.info_box .info-title {
    height: 60px;
    width: 100%;
    border-bottom: 1px solid lightgrey;
    display: flex;
    align-items: center;
    padding: 0 30px;
    border-radius: 5px 5px 0 0;
    font-size: 20px;
    font-weight: 600;
}

.info_box .info-list {
    padding: 15px 30px;
}

.info_box .info-list .info {
    margin: 5px 0;
    font-size: 17px;
}

.info_box .info-list .info span {
    font-weight: 600;
    color: #007bff;
}

.info_box .buttons {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 0 30px;
    border-top: 1px solid lightgrey;
}

.info_box .buttons button {
    margin: 0 5px;
    height: 40px;
    width: 100px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    outline: none;
    border-radius: 5px;
    border: 1px solid #007bff;
    transition: all 0.3s ease;
}

.quiz_box {
    margin-top: 10%;
    width: 550px;
    background: #fff;
    border-radius: 5px;
    transform: translate(-50%, -50%) scale(0.9);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
}

.quiz_box header {
    position: relative;
    z-index: 2;
    height: 70px;
    padding: 0 30px;
    background: #fff;
    border-radius: 5px 5px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0px 3px 5px 1px rgba(0, 0, 0, 0.1);
}

.quiz_box header .title {
    font-size: 20px;
    font-weight: 600;
}

.quiz_box header .timer {
    color: #004085;
    background: #cce5ff;
    border: 1px solid #b8daff;
    height: 45px;
    padding: 0 8px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 145px;
}

.quiz_box header .timer .time_left_txt {
    font-weight: 400;
    font-size: 17px;
    user-select: none;
}

.quiz_box header .timer .timer_sec {
    font-size: 18px;
    font-weight: 500;
    height: 30px;
    width: 45px;
    color: #fff;
    border-radius: 5px;
    line-height: 30px;
    text-align: center;
    background: #343a40;
    border: 1px solid #343a40;
    user-select: none;
}

.quiz_box header .time_line {
    position: absolute;
    bottom: 0px;
    left: 0px;
    height: 3px;
    background: #007bff;
}

section {
    padding: 25px 30px 20px 30px;
    background: #fff;
}

section .que_text {
    font-size: 25px;
    font-weight: 600;
}

section .option_list {
    padding: 20px 0px;
    display: block;
}

section .option_list .option {
    background: aliceblue;
    border: 1px solid #84c5fe;
    border-radius: 5px;
    padding: 8px 15px;
    font-size: 17px;
    margin-bottom: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

section .option_list .option:last-child {
    margin-bottom: 0px;
}

section .option_list .option:hover {
    color: #004085;
    background: #cce5ff;
    border: 1px solid #b8daff;
}

section .option_list .option.correct {
    color: #155724;
    background: #d4edda;
    border: 1px solid #c3e6cb;
}

section .option_list .option.incorrect {
    color: #721c24;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
}

section .option_list .option.disabled {
    pointer-events: none;
}

section .option_list .option .icon {
    height: 26px;
    width: 26px;
    border: 2px solid transparent;
    border-radius: 50%;
    text-align: center;
    font-size: 13px;
    pointer-events: none;
    transition: all 0.3s ease;
    line-height: 24px;
}

.option_list .option .icon.tick {
    color: #23903c;
    border-color: #23903c;
    background: #d4edda;
}

.option_list .option .icon.cross {
    color: #a42834;
    background: #f8d7da;
    border-color: #a42834;
}

footer {
    height: 60px;
    padding: 0 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid lightgrey;
}

footer .total_que span {
    display: flex;
    user-select: none;
}

footer .total_que span p {
    font-weight: 500;
    padding: 0 5px;
}

footer .total_que span p:first-child {
    padding-left: 0px;
}

footer button {
    height: 40px;
    padding: 0 13px;
    font-size: 18px;
    font-weight: 400;
    cursor: pointer;
    border: none;
    outline: none;
    color: #fff;
    border-radius: 5px;
    background: #007bff;
    border: 1px solid #007bff;
    line-height: 10px;
    opacity: 0;
    pointer-events: none;
    transform: scale(0.95);
    transition: all 0.3s ease;
}

footer button:hover {
    background: #0263ca;
}

footer button.show {
    opacity: 1;
    pointer-events: auto;
    transform: scale(1);
}

.result_box {
    background: #fff;
    border-radius: 5px;
    display: flex;
    padding: 25px 30px;
    width: 450px;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    transform: translate(-50%, -50%) scale(0.9);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
}

.result_box .icon {
    font-size: 100px;
    color: #007bff;
    margin-bottom: 10px;
}

.result_box .complete_text {
    font-size: 20px;
    font-weight: 500;
}

.result_box .score_text span {
    display: flex;
    margin: 10px 0;
    font-size: 18px;
    font-weight: 500;
}

.result_box .score_text span p {
    padding: 0 4px;
    font-weight: 600;
}

.result_box .buttons {
    display: flex;
    margin: 20px 0;
}

.result_box .buttons button {
    margin: 0 10px;
    height: 45px;
    padding: 0 20px;
    font-size: 18px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    outline: none;
    border-radius: 5px;
    border: 1px solid #007bff;
    transition: all 0.3s ease;
}

.buttons button.restart {
    color: #fff;
    background: #007bff;
}

.buttons button.restart:hover {
    background: #0263ca;
}

.buttons button.quit {
    color: #007bff;
    background: #fff;
}

.buttons button.quit:hover {
    color: #fff;
    background: #007bff;
}
</style>