<!DOCTYPE html>
<html>
<head>
    <script src="js/javascript.js"></script>
    <link rel="stylesheet" type="text/css" href="css/review1.css">
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
            <div id="pgHeading"><h1> View Review (Most Recent First)</h1></div>
            <div id="main">
                <h2>
                <form action="#" method="post">        
                    <table>
                  <tr>
                    <th>Rating</th>
                    <th>Comment</th>
                  </tr>
<?php
session_start();	
	
include "functions.php";
include "dbSettings.php";	

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

$query = "SELECT Comment, Rating FROM Review WHERE TrainNumber='$trainNumber' ORDER BY ReviewNumber DESC;";

$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>".$row['Rating']."</td><td>".$row['Comment']."</td></tr>";
    }
} else {
    header('Location: displayMessage.php?message=Invalid%20Train%20Number&type=error');
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