<?php
session_start();	
?>
<!DOCTYPE html>
<html>
<head>
    <script src="js/javascript.js"></script>
    <link rel="stylesheet" type="text/css" href="css/viewroute.css">
    <link rel="stylesheet" type="text/css" href="css/menu.css">
</head>
<body>
    <div id = "holder">
        <div id ="header"> </div>
        <div id = "nav">
            <nav>
                <ul>
                    <li> <a href = "Login.html">Logout</a> </li>
                </ul>
            </nav>
        </div>
        <div id ="content">
            <div id="pgHeading"><h1>View Popular Route Report</h1></div>
            <div id="main">
                <h2>
                <form action="action_page.php" method="post">        
                    <table>
                  <tr>
                    <th>Month</th>
                    <th>Train number</th>
                    <th># of Reservations</th>
                  </tr>
<?php
	
	
include "functions.php";
include "dbSettings.php";	

if ($_SESSION["loggedIn"] == false){
	header('Location: Login.html');
} else {
	$userType = $_SESSION["userType"];
    $username = $_SESSION["username"];
}	

if ($userType == "Customer"){
	header('Location: chooseFunctionality.php');
}

$connection = connectToDatabase($DbAddress,$DbUsername,$DbPassword,$DbName);

$currentMonth = date('m');
$currentYear = date('Y');

for ($i = 2; $i >= 0; $i--) {
	if (($currentMonth < 3) && ($i == 2)) {
		$monthNumber = $currentMonth - $i + 12;
		$year = $currentYear - 1;
	} elseif (($i == 1) && ($currentMonth == 1)) {
		$monthNumber = $currentMonth - $i + 12;
		$year = $currentYear - 1;
	} else {
		$monthNumber = $currentMonth - $i;
		$year = $currentYear;
	}
	
	
	switch ($monthNumber) {
    	case 1:
        	$monthName = "January";
			break;
		case 2:
			$monthName = "February";
			break;
    	case 3:
        	$monthName = "March";
			break;
		case 4:
			$monthName = "April";
			break;
    	case 5:
        	$monthName = "May";
			break;
		case 6:
			$monthName = "June";
			break;
    	case 7:
        	$monthName = "July";
			break;
		case 8:
			$monthName = "August";
			break;
    	case 9:
        	$monthName = "September";
			break;
		case 10:
			$monthName = "October";
			break;
    	case 11:
        	$monthName = "November";
			break;
		case 12:
			$monthName = "December";
			break;
		default:
        	echo 'Error';
	}

	$query = "SELECT TrainNumber, COUNT(Reserves.ReservationID) AS Total FROM Reserves JOIN Reservation ON Reserves.ReservationID=Reservation.ReservationID WHERE EXTRACT(MONTH FROM DepartureDate)='$monthNumber' AND EXTRACT(YEAR FROM DepartureDate)='$year' AND IsCancelled='0' GROUP BY TrainNumber ORDER BY COUNT(Reserves.ReservationID) DESC LIMIT 3;";

	$result = mysqli_query($connection, $query);
	if (mysqli_num_rows($result) > 0) {
    	// output data of each row
    	$j = 0;
		while($row = mysqli_fetch_assoc($result)) {
			if ($j != 0){
		    	$monthName = "";
	    	}
			echo "<tr><td>".$monthName."</td><td>".$row["TrainNumber"]."</td><td>".$row["Total"]."</td></tr>";
			$j++;
		}
	} else {
    	echo "<tr><td>".$monthName."</td><td>N/A</td><td>N/A</td></tr>";
	}
}



disconnectDatabase();

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