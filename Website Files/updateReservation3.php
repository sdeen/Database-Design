<!DOCTYPE html>
<html>
<head>
    <script src="js/javascript.js"></script>
    <link rel="stylesheet" type="text/css" href="css/updatereservation.css">
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
           
            <div id="main">
                <h2><b>Current Train Ticket</b></h2>
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

$reservationID = $_POST["reservationID"];
$trainNumber = $_POST["trainNumber"];

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

$query = "SELECT Stop.TrainNumber, DepartureDate, Stop.DepartureTime, S.ArrivalTime, TIMEDIFF(S.ArrivalTime, Stop.DepartureTime) AS TimeDifference, (SELECT Location FROM Station WHERE Name=DepartsFrom) AS DepartureLocation, DepartsFrom, (SELECT Location FROM Station WHERE Name=ArrivesAt) AS ArrivalLocation, ArrivesAt, Class, FirstClassPrice, SecondClassPrice, NumberOfBaggages, PassengerName FROM Stop CROSS JOIN Stop AS S INNER JOIN TrainRoute ON Stop.TrainNumber=TrainRoute.TrainNumber JOIN Reserves ON Reserves.TrainNumber=TrainRoute.TrainNumber JOIN Reservation ON Reserves.ReservationID=Reservation.ReservationID WHERE S.TrainNumber=Stop.TrainNumber AND S.ArrivalTime>Stop.DepartureTime AND Stop.Name=Reserves.DepartsFrom AND S.Name=Reserves.ArrivesAt AND Reserves.ReservationID='$reservationID' AND Username='$username' AND IsCancelled='0' AND Reserves.TrainNumber='$trainNumber' AND CURDATE()<DepartureDate;";

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
    	echo '<tr><td id="currentTNum">'. $row["TrainNumber"] . '</td><td>' . $row["DepartureDate"] .'<div id="currentTime"> '. $row["DepartureTime"] ."-". $row["ArrivalTime"] . "<br />Duration: " . $row["TimeDifference"] . '</div></td><td id="currentDFrom">' .$row["DepartureLocation"]." (".$row["DepartsFrom"] . ')</td><td id="currentAAt">' .$row["ArrivalLocation"]." (".$row["ArrivesAt"] . ')</td><td id="currentClass">' . $row["Class"] . '</td><td id="currentPrice">' . $price . '</td><td id="currentNumOfBags">' . $row["NumberOfBaggages"] . '</td><td id="currentPName">' . $row["PassengerName"] . "</td></tr>";

    }
    
} else {
    	//header('Location: index.php');
}



?>

                </table>
                <br>
                <form action="updateReservation4.php" method="post">
                <label for="Ddate"><h4>Departure Date</h4></label>
                <input type="date" id ="date" name="departureDate" onfocus="hideSubmit()">
                <button type="button" id="searchA" onclick="searchAvailability()">Search availability</button>
                <br>
                <br>
                <br>
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
                  <tr> 
                    <td id="tNum"></td>
                    <td>
	                    <div id="newDate"></div>
	                    <div id="time"></div>
	                </td>
                    <td id="dFrom"></td>
                    <td id="aAt"></td>
                    <td id="class"></td>
                    <td id="price"></td>
                    <td id="numOfBags"></td>
                    <td id="pName"></td> 
                  </tr>
                </table>
                <br>
                
<?php
	
//POST ReservationID to next page
echo '<input type="text" value="'.$reservationID.'" name="reservationID" style="display: none;"><input type="text" value="'.$trainNumber.'" name="trainNumber" style="display: none;">';


$getChangeFee = "SELECT ChangeFee FROM SystemInfo;";

$result = mysqli_query($connection, $getChangeFee);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	    $changeFee = $row["ChangeFee"];
	    echo '<label for="fee"><h2>Change Fee</h2></label>
                    <input type="text" id ="change" name="fee" value="'.$changeFee.'" readonly>';
    }
} else {
    echo 'Error';
}

$getUpdatedCost = "SELECT TotalCost FROM Reservation WHERE ReservationID='$reservationID';";

$result = mysqli_query($connection, $getUpdatedCost);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	    $oldCost = $row["TotalCost"];
	    $newCost = $oldCost + $changeFee;
	    echo '<label for="update_cost"><h2>Update Total Cost</h2></label>
                    <input type="text" id ="cost" name="newCost" value="'.$newCost.'" readonly>';
    }
} else {
    echo 'Error';
}

disconnectDatabase();

?>
            </div>
            <div class="right">
                <input type="submit" id="submitb" onclick="" value="Submit" style="visibility: hidden;">
            </div>
            <div class="left">
                <button type="button" onclick="goBack()">Back</button>
            </div>
            </form?
        </div>
    </div>
</body>
</html>