function goBack() {
    window.history.back();
}

function searchAvailability() {
	var today = new Date();
	//var selectedDate = new Date(document.getElementById("date"));
	var selectedDate = new Date(document.getElementById("date").value);
	console.log(today);
	console.log(selectedDate);
	if (today < selectedDate){
		document.getElementById("tNum").innerHTML = document.getElementById("currentTNum").innerHTML;
		document.getElementById("newDate").innerHTML = document.getElementById("date").value;
		document.getElementById("time").innerHTML = document.getElementById("currentTime").innerHTML;
		document.getElementById("dFrom").innerHTML = document.getElementById("currentDFrom").innerHTML;
		document.getElementById("aAt").innerHTML = document.getElementById("currentAAt").innerHTML;
		document.getElementById("class").innerHTML = document.getElementById("currentClass").innerHTML;
		document.getElementById("price").innerHTML = document.getElementById("currentPrice").innerHTML;
		document.getElementById("numOfBags").innerHTML = document.getElementById("currentNumOfBags").innerHTML;
		document.getElementById("pName").innerHTML = document.getElementById("currentPName").innerHTML;
		document.getElementById("submitb").style.visibility = "visible";
	} else {
		document.getElementById("tNum").innerHTML = "";
		document.getElementById("newDate").innerHTML = "";
		document.getElementById("time").innerHTML = "";
		document.getElementById("dFrom").innerHTML = "";
		document.getElementById("aAt").innerHTML = "";
		document.getElementById("class").innerHTML = "";
		document.getElementById("price").innerHTML = "";
		document.getElementById("numOfBags").innerHTML = "";
		document.getElementById("pName").innerHTML = "";
		document.getElementById("submitb").style.visibility = "hidden";
		alert("This date is not available");
	}
}

function hideSubmit() {
	document.getElementById("submitb").style.visibility = "hidden";
}

function removeTicket(ticketNumber) {
	window.location.assign("removeReservation.php?ticketToRemove=".concat(ticketNumber));
}

function goHome() {
	window.location.assign("index.php");
}

/*function goTravelExtras() {
    window.location.assign("Travel Extras & Passenger Info.html")
}

function gotoMakeReservation() {
    window.location.assign("Make Reservation.html")
}



function gotoFunctionality() {
    window.location.assign("Functionality.html")
}

function viewSchedule() {
    window.location.assign("Functionality.html")
}*/