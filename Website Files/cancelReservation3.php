<?php
session_start();	
	
include "functions.php";
include "dbSettings.php";	

$reservationID = $_POST["reservationID"];
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

$query = "UPDATE Reservation SET TotalCost='$newCost', IsCancelled='1' WHERE ReservationID='$reservationID' AND Username='$username';";


if (mysqli_multi_query($connection, $query)) {
    header('Location: displayMessage.php?message=Your%20reservation%20has%20been%20cancelled&type=message');
} else {
    echo 'Error. Please try again';
}


disconnectDatabase();

?>