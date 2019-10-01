<?php
session_start();
	
if ($_SESSION["loggedIn"] == false){
	header('Location: Login.html');
} else {
	$userType = $_SESSION["userType"];
    $username = $_SESSION["username"];
}	

if ($userType == "Manager"){
	header('Location: chooseFunctionality.php');
}

$departsFrom = $_POST["departsFrom"];
$arrivesAt = $_POST["arrivesAt"];
$departureDate = $_POST["departureDate"];
$trainNumber = $_POST["trainNumber"];
$class = $_POST["class"];
$numOfBaggages = $_POST["baggage"];
$passengerName = $_POST["passenger"];

//Create session variables for shopping cart if first ticket selected.
//If subsequent ticket, append variables to session
if (empty($_SESSION["departsFrom"])){
	$_SESSION["departsFrom"] = array($departsFrom);
} else {
	array_push($_SESSION["departsFrom"], $departsFrom);
}

if (empty($_SESSION["arrivesAt"])){
	$_SESSION["arrivesAt"] = array($arrivesAt);
} else {
	array_push($_SESSION["arrivesAt"], $arrivesAt);
}

if (empty($_SESSION["departureDate"])){
	$_SESSION["departureDate"] = array($departureDate);
} else {
	array_push($_SESSION["departureDate"], $departureDate);
}

if (empty($_SESSION["trainNumber"])){
	$_SESSION["trainNumber"] = array($trainNumber);
} else {
	array_push($_SESSION["trainNumber"], $trainNumber);
}

if (empty($_SESSION["class"])){
	$_SESSION["class"] = array($class);
} else {
	array_push($_SESSION["class"], $class);
}

if (empty($_SESSION["numOfBaggages"])){
	$_SESSION["numOfBaggages"] = array($numOfBaggages);
} else {
	array_push($_SESSION["numOfBaggages"], $numOfBaggages);
}

if (empty($_SESSION["passengerName"])){
	$_SESSION["passengerName"] = array($passengerName);
} else {
	array_push($_SESSION["passengerName"], $passengerName);
}

//Redirect to makeReservation
header('Location: makeReservation.php');

	
?>