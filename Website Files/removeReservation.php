<?php
session_start();

if ($_SESSION["loggedIn"] == false){
	header('Location: login.php');
} else {
	$userType = $_SESSION["userType"];
    $username = $_SESSION["username"];
}	

if ($userType == "Manager"){
	header('Location: Login.html');
}

//Get index of array where ticket to remove exists
$ticketToRemove = $_GET["ticketToRemove"];


//Unset ticket to remove from all session varibles related to it and reset index
unset($_SESSION["departsFrom"][$ticketToRemove]);
$_SESSION["departsFrom"] = array_values($_SESSION["departsFrom"]);

unset($_SESSION["arrivesAt"][$ticketToRemove]);
$_SESSION["arrivesAt"] = array_values($_SESSION["arrivesAt"]);

unset($_SESSION["departureDate"][$ticketToRemove]);
$_SESSION["departureDate"] = array_values($_SESSION["departureDate"]);

unset($_SESSION["trainNumber"][$ticketToRemove]);
$_SESSION["trainNumber"] = array_values($_SESSION["trainNumber"]);

unset($_SESSION["class"][$ticketToRemove]);
$_SESSION["class"] = array_values($_SESSION["class"]);

unset($_SESSION["numOfBaggages"][$ticketToRemove]);
$_SESSION["numOfBaggages"] = array_values($_SESSION["numOfBaggages"]);

unset($_SESSION["passengerName"][$ticketToRemove]);
$_SESSION["passengerName"] = array_values($_SESSION["passengerName"]);


//Redirect to makeReservation.php
header('Location: makeReservation.php');