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
                <h1> Make Reservation</h1>
            </div>
        <div id ="content">
           
            <div id="main">
                <h2><b>Currently Selected</b></h2>
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
                    <th>Remove</th>
                  </tr>
<?php
session_start();

include "functions.php";
include "dbSettings.php";	


$departsFrom = $_SESSION["departsFrom"];
$arrivesAt = $_SESSION["arrivesAt"];
$departureDate = $_SESSION["departureDate"];
$trainNumber = $_SESSION["trainNumber"];
$class = $_SESSION["class"];
$numOfBaggages = $_SESSION["numOfBaggages"];
$passengerName = $_SESSION["passengerName"];

//Create variable for totalCost
$totalCost = 0;


if ($_SESSION["loggedIn"] == false){
	header('Location: Login.html');
} else {
	$userType = $_SESSION["userType"];
    $username = $_SESSION["username"];
}	

if ($userType == "Manager"){
	header('Location: chooseFunctionality.php');
}

//Connect to database
$connection = connectToDatabase($DbAddress,$DbUsername,$DbPassword,$DbName);

//Loop through tickets and add each to table
for ($i = 0; $i < count($trainNumber); $i++){
	
	$populateTable = "SELECT Stop.DepartureTime, S.ArrivalTime, TIMEDIFF(S.ArrivalTime, Stop.DepartureTime) AS TimeDifference, (SELECT Location FROM Station WHERE Name='".$departsFrom[$i]."') AS DepartureLocation, (SELECT Location FROM Station WHERE Name='".$arrivesAt[$i]."') AS ArrivalLocation, FirstClassPrice, SecondClassPrice FROM Stop CROSS JOIN Stop AS S INNER JOIN TrainRoute ON Stop.TrainNumber=TrainRoute.TrainNumber WHERE S.TrainNumber=Stop.TrainNumber AND S.ArrivalTime>Stop.DepartureTime AND Stop.Name = '".$departsFrom[$i]."' AND S.Name='".$arrivesAt[$i]."' AND TrainRoute.TrainNumber='".$trainNumber[$i]."';";
	
	$result = mysqli_query($connection, $populateTable);
	if (mysqli_num_rows($result) > 0) {
	    // output data of each row
	    while($row = mysqli_fetch_assoc($result)) {
		    //Get price from class
		    if ($class[$i] == "1"){
			    $price = $row["FirstClassPrice"];
		    } else if ($class[$i] == 2) {
			    $price = $row["SecondClassPrice"];
		    } else {
			    echo 'Error';
		    }
		    //Add to totalCost
		    $totalCost = $totalCost + $price;
		    //Echo to table
		    echo '<tr><td>'.$trainNumber[$i].'</td><td>'.$departureDate[$i].' '.$row["DepartureTime"].'-'.$row["ArrivalTime"].'<br />Duration: '.$row["TimeDifference"].'</td><td>'.$departsFrom[$i].' ('.$row["DepartureLocation"].')</td><td>'.$arrivesAt[$i].' ('.$row["ArrivalLocation"].')</td><td>'.$class[$i].'</td><td>'.$price.'</td><td>'.$numOfBaggages[$i].'</td><td>'.$passengerName[$i].'</td><td><button type="button" onclick="removeTicket('.$i.')">Remove</button></td></tr>';
	    }
	} else {
	    echo 'An error has occurred on line 91';
	}
	
}

//Check student discount and get card numbers
$getCardNums = "SELECT CardNumber FROM Customer JOIN PaymentInfo ON Customer.Username=PaymentInfo.Username WHERE Customer.Username='$username';";

//placeholder for array of credit cards
$cards = array();

$result = mysqli_query($connection, $getCardNums);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	    //add to array of credit cards
	    array_push($cards, $row["CardNumber"]);

    }
} else {
    //echo 'An error has occurred on line 112';
}

//Check student status
$checkStudent = "SELECT IsStudent FROM Customer WHERE Username='$username';";

$result = mysqli_query($connection, $checkStudent);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	    $isStudent = $row["IsStudent"];
    }
} else {
    echo 'An error has occurred on line 128';
}

//Get number of free baggage and student discount
$getSystemInfo = "SELECT NumOfFreeBaggage, StudentDiscount FROM SystemInfo;";

$result = mysqli_query($connection, $getSystemInfo);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	    $numOfFreeBaggage = $row["NumOfFreeBaggage"];
	    //Student discount stored as percent off so convert to percent of totalCost
	    $studentDiscountRate = 1 - $row["StudentDiscount"];

    }
} else {
    echo 'An error has occurred on line 128';
}


disconnectDatabase();

?>
                </table>
                <form action="makeReservation2.php" method="post">
                                  <br>
<?php
	//Compute cost after paid baggage
	for ($j = 0; $j < count($numOfBaggages); $j++){
		if ($numOfBaggages[$j] > $numOfFreeBaggage) {
			$totalCost = $totalCost + (30 * ($numOfBaggages[$j] - $numOfFreeBaggage));
		}
	}
	//Compute cost after student discount
	if ($isStudent == 1){
		$totalCost = $studentDiscountRate * $totalCost;
		echo '<h2>Student Discount Applied</h2>';
	}
                
?>
                <br>
                <label for="cost"><h2>Total Cost</h2></label>
<?php
echo '<input type="text" id ="cost" name="cost" value="'.$totalCost.'" readonly>';	
?>
                <br>
                
                <label for="card"> <h2> Use Card</h2></label>
                    <div id="cardnum">
                    <select name="cardn">
<?php
	//add cards to dropdown menu
	for ($k = 0; $k < count($cards); $k++){
		$lastFour = substr($cards[$k], -4);
		echo '<option value="'.$cards[$k].'">'.$lastFour.'</option>';
	}
?>
                    </select>
                        <a href = "paymentInfo.php">Add Card</a>
                    </div>
                <br>
                <br>
                <a href="searchTrain.php">Continue adding a train</a>
                 <div class="right">
                    <input type="submit" id ="submitb" onclick="" value="Submit">
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