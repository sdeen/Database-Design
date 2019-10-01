<?php
session_start();	
	
include "functions.php";
include "dbSettings.php";	

$reservationID = $_POST["reservationID"];
$trainNumber = $_POST["trainNumber"];
$departureDate = $_POST["departureDate"];
$newCost = $_POST["newCost"];

if ($_SESSION["loggedIn"] == false){
	header('Location: Login.html');
} else {
	$userType = $_SESSION["userType"];
    $username = $_SESSION["username"];
}	

if ($userType == "Manager"){
	header('Location: chooseFunctionality.php');
}

$connection = connectToDatabase($DbAddress,$DbUsername,$DbPassword,$DbName);

$query = "UPDATE Reserves SET DepartureDate='$departureDate' WHERE ReservationID='$reservationID' AND TrainNumber='$trainNumber';";

$query .= "UPDATE Reservation SET TotalCost='$newCost' WHERE ReservationID='$reservationID';";

if (mysqli_multi_query($connection, $query)) {
    header('Location: displayMessage.php?message=Your%20reservation%20has%20been%20updated!&type=message');
} else {
    echo 'Error. Please try again';
}



disconnectDatabase();

?>