<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/searchtrain.css">
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
            <div id="pgHeading"><h1>Search Train</h1></div>
            <div id="res_form">
                <table>
                     <h2>
                    <form action="selectDeparture.php" method="post">
                    <label for="usern">Departs From</label>
					<select name="departsFrom">
<?php
session_start();

include "functions.php";
include "dbSettings.php";	


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

$query = "SELECT Name, Location FROM Station;";

//Empty array for stations
$stationNameAndLocation = array();
$stationName = array();
//Execute SQL query
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
	    //Create array of stations
        array_push($stationNameAndLocation, $row['Location']." (".$row['Name'].")");
        array_push($stationName, $row["Name"]);
    }
} else {
    echo 'An error has occurred. Please try again.';
}

disconnectDatabase();

//Echo stations to dropdowns
for ($i = 0; $i < count($stationName); $i++) {
	echo '<option value="'.$stationName[$i].'">'.$stationNameAndLocation[$i].'</option>';
}

echo '</select><label for="email">Arrives At</label><select name="arrivesAt">';
      
for ($i = 0; $i < count($stationName); $i++) {
	echo '<option value="'.$stationName[$i].'">'.$stationNameAndLocation[$i].'</option>';
}

?>
                    </select>
                        
                    <label for="Ddate">Departure Date</label>
					<input type="date" id ="date" name="date">
                        
                    <input type="submit" value="Find Trains">
                        
                </form>
                </h2>
                </table>   
            </div>
        </div>
      
    </div>
</body>
</html>