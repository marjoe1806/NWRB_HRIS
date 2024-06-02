var timeoutID;

function setup() {
    this.addEventListener("mousemove", resetTimer, false);
    this.addEventListener("mousedown", resetTimer, false);
    this.addEventListener("keypress", resetTimer, false);
    this.addEventListener("DOMMouseScroll", resetTimer, false);
    this.addEventListener("mousewheel", resetTimer, false);
    this.addEventListener("touchmove", resetTimer, false);
    this.addEventListener("MSPointerMove", resetTimer, false);
 
    startTimer();
}
setup();
 
function startTimer() {
	// 1000 milliseconds = 1 second
    timeoutID = window.setTimeout(goInactive, 28800000); //3600000 1 hour
}
 
function resetTimer(e) {
    window.clearTimeout(timeoutID);
 
    goActive();
}

function goInactive() {
	$.get(commons.baseurl+'session/logout', function(ret) {
		window.location.reload();
	});
}
 
function goActive() {
    startTimer();
}