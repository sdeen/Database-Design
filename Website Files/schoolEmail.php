<?php
session_start();

	
include "functions.php";
include "dbSettings.php";

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

$emailAddress = $_POST["email"];


if (substr($emailAddress, -4) == ".edu"){
	$sql = "UPDATE Customer SET IsStudent=1 WHERE Username='$username';";
	if (mysqli_query($connection, $sql)) {
    	header('Location: displayMessage.php?message=You%20have%20successfully%20added%20your%20.edu%20email%20for%20discount!&type=message');
	} else {
    	echo "An error has occurred. Please go back and try again";
	}
} else {
	echo 'Please enter a valid .edu email address';
}





disconnectDatabase();

?>