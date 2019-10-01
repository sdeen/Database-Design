<?php
session_start();

include "functions.php";
include "dbSettings.php";	

$cardNumber = $_POST["cardn"];

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

$sql = "UPDATE Reservation SET CardNumber=NULL WHERE CardNumber='$cardNumber' AND CardNumber NOT IN (SELECT CardNumber FROM (SELECT CardNumber FROM Reservation JOIN Reserves ON Reservation.ReservationID=Reserves.ReservationID WHERE CURDATE()<=DepartureDate AND IsCancelled='0') AS Cn);";

$sql .= "DELETE FROM PaymentInfo WHERE CardNumber NOT IN (SELECT CardNumber FROM (SELECT CardNumber FROM Reservation JOIN Reserves ON Reservation.ReservationID=Reserves.ReservationID WHERE CURDATE()<=DepartureDate AND IsCancelled='0') AS Cn) AND CardNumber='$cardNumber';";

if (mysqli_multi_query($connection, $sql)) {
	header('Location: makeReservation.php');
} else {
	header('Location: displayMessage.php?message=Error&type=error');
}

disconnectDatabase();

?>