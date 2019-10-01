<!DOCTYPE html>
<html>
<head>
    <script src="js/javascript.js"></script>
    <link rel="stylesheet" type="text/css" href="css/selectdeparture.css">
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
                <h1> Select Departure</h1>
            </div>
        <div id ="content">
           
            <div id="main">
                <h2><b>Currently Selected</b></h2>
                <form action="travelExtras.php" method="post">
                   <table>
                  <tr>
                    <th>Train (Train Number)</th>
                    <th>Time (Duration)</th>
                    <th> 1st Class Price</th>
                    <th>2nd Class Price</th>
                  </tr>
<?php
session_start();

include "functions.php";
include "dbSettings.php";	

//updateReservation.html POSTs to this page
$departsFrom = $_POST["departsFrom"];
$arrivesAt = $_POST["arrivesAt"];
$departureDate = $_POST["date"];


if ($_SESSION["loggedIn"] == false){
	header('Location: Login.html');
} else {
	$userType = $_SESSION["userType"];
    $username = $_SESSION["username"];
}	

if ($userType == "Manager"){
	header('Location: chooseFunctionality.php');
}

//Check to see if departure date was entered and is after today
if ((date('m-d-Y', strtotime($departureDate)) <= date('m-d-Y')) || empty($departureDate)) {
	header('Location: displayMessage.php?message=Invalid%20departure%20date&type=error');
}



$connection = connectToDatabase($DbAddress,$DbUsername,$DbPassword,$DbName);

$query = "SELECT Stop.TrainNumber, Stop.DepartureTime, S.ArrivalTime, TIMEDIFF(S.ArrivalTime, Stop.DepartureTime) AS TimeDifference, FirstClassPrice, SecondClassPrice FROM Stop CROSS JOIN Stop AS S INNER JOIN TrainRoute ON Stop.TrainNumber=TrainRoute.TrainNumber WHERE S.TrainNumber=Stop.TrainNumber AND S.ArrivalTime>Stop.DepartureTime AND Stop.Name = '$departsFrom' AND S.Name='$arrivesAt';";

$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    // Populate table
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row["TrainNumber"] . "</td><td>" . $departureDate . " " . $row["DepartureTime"] . "-" . $row["ArrivalTime"] . "<br />Duration: " . $row["TimeDifference"] . '</td><td><input type="radio" name="select" value="trainNumber='.$row["TrainNumber"].'&class=1">' . $row["FirstClassPrice"] . '<br /></td><td><input type="radio" name="select" value="trainNumber='.$row["TrainNumber"].'&class=2">' . $row["SecondClassPrice"] . '<br /></td></tr>';
    }
    
} else {
    header('Location: displayMessage.php?message=Invalid%20train%20route&type=error');
}

disconnectDatabase();

echo '</table>';
//Pass values from previous page (Search Train) to next page (Travel Extras)
echo '<input type="text" value="'.$departsFrom.'" name="departsFrom" style="display: none;">';
echo '<input type="text" value="'.$arrivesAt.'" name="arrivesAt" style="display: none;">';
echo '<input type="text" value="'.$departureDate.'" name="departureDate" style="display: none;">';

?>
                <div class="right">
                    <input type="submit" id="nextb" value="Next">
                </div>
                <div class="left">
                    <button type="button" onclick="goBack()">Back</button>
                </div>
                </form>
             
           
            </div>
            
        </div>
    </div>
</body>
</html>