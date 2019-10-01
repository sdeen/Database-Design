<!DOCTYPE html>
<html>
<head>
    <script src="js/javascript.js"></script>
    <link rel="stylesheet" type="text/css" href="css/reservation.css">
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
                <h1> Update Reservation</h1>
            </div>
        <div id ="content">
        	<form action="updateReservation3.php" method="post">
            <div id="main">
                <h2><b>Currently Selected</b></h2>
                <table>
                  <tr>
                    <th>Select</th>
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

$query = "SELECT Stop.TrainNumber, DepartureDate, Stop.DepartureTime, S.ArrivalTime, TIMEDIFF(S.ArrivalTime, Stop.DepartureTime) AS TimeDifference, (SELECT Location FROM Station WHERE Name=DepartsFrom) AS DepartureLocation, DepartsFrom, (SELECT Location FROM Station WHERE Name=ArrivesAt) AS ArrivalLocation, ArrivesAt, Class, FirstClassPrice, SecondClassPrice, NumberOfBaggages, PassengerName FROM Stop CROSS JOIN Stop AS S INNER JOIN TrainRoute ON Stop.TrainNumber=TrainRoute.TrainNumber JOIN Reserves ON Reserves.TrainNumber=TrainRoute.TrainNumber JOIN Reservation ON Reserves.ReservationID=Reservation.ReservationID WHERE S.TrainNumber=Stop.TrainNumber AND S.ArrivalTime>Stop.DepartureTime AND Stop.Name=Reserves.DepartsFrom AND S.Name=Reserves.ArrivesAt AND Reserves.ReservationID='$reservationID' AND Username='$username' AND IsCancelled='0' AND CURDATE()<DepartureDate;";

$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	    //Get class price
    	if ($row["Class"] == "1"){
	    	$price = $row["FirstClassPrice"];
    	} else {
	    	$price = $row["SecondClassPrice"];
    	}
    	echo '<tr><td><input type="radio" name="trainNumber" value="'.$row["TrainNumber"].'" '.$checked.'><br></td><td>'. $row["TrainNumber"] . '</td><td>' . $row["DepartureDate"] ." ". $row["DepartureTime"] ."-". $row["ArrivalTime"] . "<br />Duration: " . $row["TimeDifference"] . "</td><td>" .$row["DepartureLocation"]." (".$row["DepartsFrom"] . ")</td><td>" .$row["ArrivalLocation"]." (".$row["ArrivesAt"] . ")</td><td>" . $row["Class"] . "</td><td>" . $price . "</td><td>" . $row["NumberOfBaggages"] . "</td><td>" . $row["PassengerName"] . "</td></tr>";

    }
    
} else {
    header('Location: displayMessage.php?message=This%20reservation%20cannot%20be%20updated&type=error');
}

//POST ReservationID to next page
echo '<input type="text" value="'.$reservationID.'" name="reservationID" style="display: none;">';

disconnectDatabase();

?>
                </table>
           
            </div>
            <div class="right">
                <input type="submit" id="nextb" value="Next">
            </div>
        	</form>
            <div class="left">
                <button type="button" onclick="goBack()">Back</button>
            </div>
        </div>
    </div>
</body>
</html>