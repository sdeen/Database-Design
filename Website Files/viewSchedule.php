<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="css/viewtrain1.css">
    <link rel="stylesheet" type="text/css" href="css/menu.css">
  <script src="js/javascript.js"></script>

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
            <div id="pgHeading"><h1> View Train Schedule</h1></div>
            <div id="main">
                <h2>
                <form action="#"> 
                    <table>
	                    <tr>
                    <th>Train<br />(Train Number)</th>
                    <th>Arrival Time</th>
                    <th>Departure Time</th>
                    <th>Station</th>
                  </tr>
	                    
<?php
session_start();

include "functions.php";
include "dbSettings.php";	

//updateReservation.html POSTs to this page
$trainNumber = $_POST["trainnumber"];

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

$query = "SELECT TrainNumber, ArrivalTime, DepartureTime, Stop.Name, Location FROM Stop INNER JOIN Station ON Stop.Name=Station.Name WHERE TrainNumber='$trainNumber' ORDER BY ArrivalTime;";

$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    $i = 0;
    while($row = mysqli_fetch_assoc($result)) {
	    //Checks if Train Number is already in table to prevent duplicates
	    $train = $row["TrainNumber"];
	    if ($i != 0){
		    $train = "";
	    }
        echo "<tr><td>".$train."</td><td>".$row["ArrivalTime"]."</td><td>".$row["DepartureTime"]."</td><td>".$row["Name"]." (".$row["Location"].")</td></tr>";
        $i++;
    }
} else {
    header('Location: displayMessage.php?message=Invalid%20Train%20Number&type=error');
}

disconnectDatabase()

?>
</table>
                   <div class="left">
                        <button type="button" onclick="goBack()">Back</button>
                    </div>
                </form>
                </h2>
                
            </div>
        </div>
    </div>
</body>
</html>