<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="css/functionalities.css">
    <link rel="stylesheet" type="text/css" href="css/menu.css">
</head>
<body>
    <div id = "holder">
        <div id ="header"> </div>
        <div id = "nav">
            <nav>
                <ul>
                    <li> <a href = "logOut.php">Logout</a> </li>
                </ul>
            </nav>
        </div>
        <div id ="content">
            <div id="pgHeading"><h1>Choose Functionality</h1></div>
            <div id="main">
            <table>
<?php
session_start();
	
include "functions.php";


if ($_SESSION["loggedIn"] == false){
	header('Location: Login.html');
} else {
	$userType = $_SESSION["userType"];
    $username = $_SESSION["username"];
}



if ($userType == "Customer"){
	echo '<a href="View%20Train%20Schedule.html"><h2>View Train Schedule</h2></a>  
                
                <a href="searchTrain.php"><h2>Make a reservation</h2></a> 
                
                <a href="Update%20Reservation.html"><h2>Update Reservation</h2></a> 
                
                <a href="Cancel%20Reservation.html"><h2>Cancel Reservation</h2></a>
                
                <a href="Give%20Review.html"><h2>Give Review</h2></a>
                <a href="View%20Review.html"><h2>View Review</h2></a>
                
                <a href="Add%20School%20Info.html"><h2>Add School Info (for student discount)</h2></a>';
} elseif ($userType == "Manager"){
	echo '<a href="revenueReport.php"><h2>View revenue report</h2></a>  
                
                <a href="popularRoute.php"><h2>View popular route report</h2></a> ';
} else {
	echo 'Error: Try logging in again';
}



?>
            </table>
            </div>
        </div>
        
    </div>
</body>
</html>