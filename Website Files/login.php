<?php
session_start();
  	
include "functions.php";
include "dbSettings.php";

$connection = connectToDatabase($DbAddress,$DbUsername,$DbPassword,$DbName);

$username = $_POST["usern"];
$password = hash("sha256", $_POST["pass"]);

$customerQuery = "SELECT Password FROM User INNER JOIN Customer ON User.Username=Customer.Username WHERE User.Username='$username';";

$managerQuery = "SELECT Password FROM User INNER JOIN Manager ON User.Username=Manager.Username WHERE User.Username='$username';";

$customerResult = mysqli_query($connection, $customerQuery);
if (mysqli_num_rows($customerResult) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($customerResult)) {
        if ($password == $row["Password"]) {
			logIn($username, "Customer");
			disconnectDatabase();
			header('Location: chooseFunctionality.php');
		} else {
			echo 'Incorrect username and password combination';
		}
    }
} else {
    $managerResult = mysqli_query($connection, $managerQuery);
    if (mysqli_num_rows($managerResult) > 0) {
    	// output data of each row
		while($row = mysqli_fetch_assoc($managerResult)) {
      	  	if ($password == $row["Password"]) {
				logIn($username, "Manager");
				disconnectDatabase();
				header('Location: chooseFunctionality.php');
			} else {
				echo 'Incorrect username and password combination';
			}
    	}
	} else {
   		echo 'Incorrect username and password combination';
	}
}





disconnectDatabase();

?>