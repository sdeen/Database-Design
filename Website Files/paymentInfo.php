<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/pay.css">
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
            <div id="pgHeading"><h1>Payment Information</h1></div>
            <div id="res_form">
                <table>
                    <div class="left">
                    
                    <h2>
                    <div id="ctext2">
                        <b>Add Card</b>
                    </div>
                    <form action="addCard.php" method="post">
                    <label for="name">Name on Card</label>
                    <input type="text" id ="name" name="name_on_card">
                    
                    <label for="cnum">Card Number</label>
                    <input type="text" id ="num" name="cnum">
                        
                    <label for="cvv">CVV</label>
                    <div id="cvv">
                        <input type="text" id ="cvv" name="cvvnum">
                    </div>
                    
                    
                    <label for="con_pass">Expiration Date</label>
                    <input type="month" id ="date" name="expiration">   
                    
                    <input id="submit1" type="submit" value="Submit">
                </form>
                </h2>
                    
                </div>  
                <div class="right" >
                    <h2>
                    <div id="ctext1">
                        <b>Delete Card</b>
                    </div>
                    <form action="deleteCard.php" method="post">
                        <div id="lab">
                            <label for="cnum">Card Number</label>
                        </div>
                        
                        <select name="cardn">
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

$query = "SELECT CardNumber FROM PaymentInfo WHERE Username='$username';";

$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	    $lastFour = substr($row["CardNumber"], -4);
        echo '<option value="'.$row['CardNumber'].'">'.$lastFour.'</option>';
    }
} else {
    echo 'An error has occurred';
}

disconnectDatabase();

?>
</select>
                        <input id ="submit2" type="submit" value="Submit">  
                    </form>
                </h2>
                  
                </div>
                </table>   
            </div>
        </div>
    </div>
</body>
</html>