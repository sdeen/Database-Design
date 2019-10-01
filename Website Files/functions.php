<?php
function logIn($username, $userType){
  //Verify username and password are correct else return error
  //If correct
  $_SESSION["loggedIn"] = true;
  $_SESSION["username"] = $username;//Username
  $_SESSION["userType"] = $userType;//Manager or Customer
}

function verifyUser(){
  if ($_SESSION["loggedIn"] == false){
    header('Location: login.php');
  } else {
    return $userType = $_SESSION["userType"];
    return $username = $_SESSION["username"];
  }
}

function logOut(){
  session_unset();
  session_destroy();
}

function executeQuery($query){
  if (mysqli_query($connection, $query)) {
     print("Query executed successfully");
  } else {
      echo "Error: " . mysqli_error($connection);
  }
}

function sanitizeInput($string) {
  return mysqli_real_escape_string($connection, $string);
}

function connectToDatabase($DbAddress,$DbUsername,$DbPassword,$DbName){
  // Create connection
  $connection = mysqli_connect($DbAddress,$DbUsername,$DbPassword,$DbName);
  // Check connection
  if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
  } else {
	  return $connection;
  }
}

function disconnectDatabase(){
  mysqli_close($connection);
}
?>