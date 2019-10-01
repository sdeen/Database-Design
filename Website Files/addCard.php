<?php
session_start();

include "functions.php";
include "dbSettings.php";	

$nameOnCard = $_POST["name_on_card"];
$cardNumber = $_POST["cnum"];
$cvv = $_POST["cvvnum"];
$expirationDate = $_POST["expiration"];

if ($_SESSION["loggedIn"] == false){
	header('Location: Login.html');
} else {
	$userType = $_SESSION["userType"];
    $username = $_SESSION["username"];
}	

if ($userType == "Manager"){
	header('Location: chooseFunctionality.php');
}

//Check to see if card is expired
if ($expirationDate < date('Y-m')) {
	die('Your card is expired');
}

//Day of month does not matter so auto set day to 01 for storage in database
$dateToInsert = strtotime($expirationDate."-01");
$dateToInsert = date("Y-m-d", $dateToInsert);

$connection = connectToDatabase($DbAddress,$DbUsername,$DbPassword,$DbName);

$sql = "INSERT INTO PaymentInfo VALUES ('$cardNumber', '$cvv', '$dateToInsert', '$nameOnCard', '$username');";
if (mysqli_query($connection, $sql)) {
	disconnectDatabase();
	header('Location: makeReservation.php');
} else {
	echo "Error";
}


disconnectDatabase();

?>