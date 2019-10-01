<?php
session_start();	
	
include "functions.php";
include "dbSettings.php";

$connection = connectToDatabase($DbAddress,$DbUsername,$DbPassword,$DbName);

$username = strtolower($_POST["username"]);
$emailAddress = $_POST["email_address"];
$password = $_POST["password"];
$confirmPassword = $_POST["confirm_password"];

if ($password == $confirmPassword){
	$hashedPassword = hash("sha256", $password);;
	$sql = "INSERT INTO User (Username, Password) VALUES ('$username', '$hashedPassword');";
	$sql .= "INSERT INTO Customer (Username, Email, IsStudent) VALUES ('$username', '$emailAddress', 0);";
	if (mysqli_multi_query($connection, $sql)) {
    	disconnectDatabase();
    	logIn($username, "Customer");
		header('Location: displayMessage.php?message=Your%20account%20has%20been%20successfully%20created!&type=message');
	} else {
  	  echo "Could not create account. Try again and make sure Username and Email Address are less than or equal to 50 characters. Your username and email may already be in use.";
	}
} else {
	echo 'Your passwords did not match';
}






disconnectDatabase();

?>