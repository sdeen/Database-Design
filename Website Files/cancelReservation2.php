<!DOCTYPE html>
<html>
<head>
	<script src="js/javascript.js"></script>
    <link rel="stylesheet" type="text/css" href="css/cancelR.css">
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
         <div id="pgHeading">
                <h1> Cancel Reservation</h1>
            </div>
        <div id ="content">
           
            <div id="main">
            
                 <table>
                  <tr>
                    <th>Train (Train Number)</th>
                    <th>Time (Duration)</th>
                    <th> Departs From</th>
                    <th>Arrives At</th>
                    <th>Class</th>
                    <th>Price</th>
                    <th>#of Baggages</th>
                    <th>Passenger Name</th>
                  </tr>
<?php
session_start();	
	
include "functions.php";
include "dbSettings.php";	

$reservationID = $_POST["reservationid"];

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

$query = "SELECT Stop.TrainNumber, DepartureDate, Stop.DepartureTime, S.ArrivalTime, TIMEDIFF(S.ArrivalTime, Stop.DepartureTime) AS TimeDifference, (SELECT Location FROM Station WHERE Name=DepartsFrom) AS DepartureLocation, DepartsFrom, (SELECT Location FROM Station WHERE Name=ArrivesAt) AS ArrivalLocation, ArrivesAt, Class, FirstClassPrice, SecondClassPrice, NumberOfBaggages, PassengerName FROM Stop CROSS JOIN Stop AS S INNER JOIN TrainRoute ON Stop.TrainNumber=TrainRoute.TrainNumber JOIN Reserves ON Reserves.TrainNumber=TrainRoute.TrainNumber JOIN Reservation ON Reserves.ReservationID=Reservation.ReservationID WHERE S.TrainNumber=Stop.TrainNumber AND S.ArrivalTime>Stop.DepartureTime AND Stop.Name=Reserves.DepartsFrom AND S.Name=Reserves.ArrivesAt AND Reserves.ReservationID='$reservationID' AND Username='$username' AND IsCancelled='0';";

$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    
    //Make placeholder departure date for later comparison
	$departureDate = array();
    while($row = mysqli_fetch_assoc($result)) {
	    if (date("Y-m-d") < $row["DepartureDate"]){
		    //Get departure dates
			
			array_push($departureDate, strtotime($row["DepartureDate"]));

		    //Get class price
	    	if ($row["Class"] == "1"){
		    	$price = $row["FirstClassPrice"];
	    	} else {
		    	$price = $row["SecondClassPrice"];
	    	}
        	echo "<tr><td>" . $row["TrainNumber"] . "</td><td>" . $row["DepartureDate"] ." ". $row["DepartureTime"] ."-". $row["ArrivalTime"] . "<br />Duration: " . $row["TimeDifference"] . "</td><td>" .$row["DepartureLocation"]." (".$row["DepartsFrom"] . ")</td><td>" .$row["ArrivalLocation"]." (".$row["ArrivesAt"] . ")</td><td>" . $row["Class"] . "</td><td>" . $price . "</td><td>" . $row["NumberOfBaggages"] . "</td><td>" . $row["PassengerName"] . "</td></tr>";
    	} else {
	    	header('Location: displayMessage.php?message=You%20may%20no%20longer%20make%20changes%20to%20this%20reservation&type=error');
    	}
    }
    
} else {
    header('Location: displayMessage.php?message=You%20may%20not%20make%20changes%20to%20this%20reservation&type=error');
}

?>
                </table>
                <br>
                <form action="cancelReservation3.php" method="post">
                <label for="fee"><h2>Total Cost of Reservation</h2></label>
<?php

$getOldCost = "SELECT TotalCost FROM Reservation WHERE ReservationID='$reservationID';";

$result = mysqli_query($connection, $getOldCost);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
	while($row = mysqli_fetch_assoc($result)) {
	    $oldCost = $row["TotalCost"];
		echo '<input type="text" value="$'.$oldCost.'" id ="change" name="oldCost" readonly>';
    }
} else {
    echo 'Error';
}
                    
echo '<label for="Ddate"><h2>Date of Cancellation</h2></label>';

//Output today's date in user readable format
$todayOutputFormat = date('m/d/Y');
echo '<input type="text" value="'.$todayOutputFormat.'" id ="date" name="Ddate" readonly>';


$today = strtotime("today");
//Sort departure dates to find earliest
sort($departureDate);
$earliestDepartureDate = $departureDate[0];
//Time of one week from today
$weekFromToday = strtotime("+7 days");

//Calculate possible refund
if ($weekFromToday < $earliestDepartureDate){
	$possibleRefund = ($oldCost * 0.8) - 50;
} else {
	$possibleRefund = ($oldCost * 0.5) - 50;
}
		
if ($possibleRefund > 0){
	$refund = $possibleRefund;
	$newCost = $oldCost - $refund;
} else {
	$refund = 0;
	$newCost = $oldCost;
}

echo '<label for="update_cost"><h2>Amount to be Refunded</h2></label><input type="text" value="$'.$refund.'" id ="cost" name="ucost" readonly>';
//POST ReservationID
echo '<input type="text" value="'.$reservationID.'" name="reservationID" style="display: none;"><input type="text" value="'.$newCost.'" name="newCost" style="display: none;"';

?>
            </div>
            <div class="right">
                <input type="submit" id="submitb" value="Submit" onclick="">
            </div>
            <div class="left">
                <button type="button" onclick="goBack()">Back</button>
            </div>
        </form>
        </div>
    </div>
</body>
</html>