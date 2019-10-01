<!DOCTYPE html>
<html>
<head>
    <script src="js/javascript.js"></script>
    <link rel="stylesheet" type="text/css" href="css/baggage.css">
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
            <div id="pgHeading"><h1> Travel Extras & Passenger Info</h1></div>
            <div id="main">
                <h2>
                <form action="sessionVariables.php" method="post">
                    <label for="bag"> <h2>Number of baggage
                        </h2></label>
                    <div id="num">
                    <select name="baggage">
<?php
session_start();

include "functions.php";
include "dbSettings.php";	

$departsFrom = $_POST["departsFrom"];
$arrivesAt = $_POST["arrivesAt"];
$departureDate = $_POST["departureDate"];
//Parse values of trainNumber and class Selection from radio button and create a variable for each
parse_str($_POST["select"]);


//Validate user
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

$query = "SELECT MaxNumOfBaggage, NumOfFreeBaggage FROM SystemInfo;";

$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
	    $maxBaggage = $row["MaxNumOfBaggage"];
	    $freeBaggage = $row["NumOfFreeBaggage"];
	    $paidBaggage = $maxBaggage - $freeBaggage;
    }
} else {
    echo 'An error has occurred';
}


//Add baggage numbers to dropdown menu
for ($i = 1; $i <= $maxBaggage; $i++){
	echo '<option value="' . $i . '">' . $i . '</option>';
}

echo '</select></div><div id="passenger">';
echo "<h5>Every passenger can bring up to " . $maxBaggage . " baggage " . $row['NumOfFreeBaggage'] . " free of charge, " . $paidBaggage . " for $30 per bag.</h5>";

//Send selections from previous pages to next page
echo '<input type="text" value="'.$departsFrom.'" name="departsFrom" style="display: none;">';
echo '<input type="text" value="'.$arrivesAt.'" name="arrivesAt" style="display: none;">';
echo '<input type="text" value="'.$departureDate.'" name="departureDate" style="display: none;">';
echo '<input type="text" value="'.$trainNumber.'" name="trainNumber" style="display: none;">';
echo '<input type="text" value="'.$class.'" name="class" style="display: none;">';



disconnectDatabase();

?>
                    </div>
                    
                    <label for="p_name"><h2>Passenger Name</h2></label>
                    <input type="text" id ="p_name" name="passenger">
                    <div class="right">
                        <input type="submit" id="nextb" value="Next" onclick="">
                    </div>
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