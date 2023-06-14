<?php
// Assuming you have the necessary credentials to connect to your database
$servername = "localhost";
$username = "if22";
$password = "if22pass";
$dbname = "if22_Grupp1Tarkvaraarendus";

// Get the score from the POST data
$score = isset($_POST['score']) ? $_POST['score'] : '';

// Check if score is a valid integer value
if (!is_numeric($score) || intval($score) != $score) {
  echo 'Error: Invalid score value';
  exit;
}

// Create a new MySQLi object
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($mysqli->connect_error) {
  die('Connection failed: ' . $mysqli->connect_error);
}

// Prepare the SQL statement
$sql = "INSERT INTO skoorid (score) VALUES ('$score')";

// Execute the SQL statement
if ($mysqli->query($sql) === true) {
  echo 'Score submitted successfully!';
} else {
  echo 'Error: ' . $mysqli->error;
}

// Close the database connection
$mysqli->close();
?>