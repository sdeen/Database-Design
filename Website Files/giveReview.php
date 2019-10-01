<?php
session_start();	
	
include "functions.php";
include "dbSettings.php";	

$trainNumber = $_POST["trainNumber"];
$rating = $_POST["rate"];
$comment = $_POST["comment"];

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

//Make sure train number is valid
$sql = "SELECT TrainNumber FROM TrainRoute WHERE TrainNumber='$trainNumber';";

$result = mysqli_query($connection, $sql);
if (mysqli_num_rows($result) > 0) {
} else {
    die('Invalid Train Number');
}


if (empty($comment)){
	$query = "INSERT INTO Review (Rating, Username, TrainNumber) VALUES ('$rating', '$username', '$trainNumber');";
} else {
	$query = "INSERT INTO Review (Comment, Rating, Username, TrainNumber) VALUES ('$comment', '$rating', '$username', '$trainNumber');";
}

if (mysqli_query($connection, $query)) {
	disconnectDatabase();
	header('Location: displayMessage.php?message=Your%20review%20has%20been%20submitted%20successfully!&type=message');
} else {
	echo "Error";
}

disconnectDatabase();

?>