<?php
session_start();

// Assuming you have the necessary credentials to connect to your database
$servername = "localhost";
$username = "if22";
$password = "if22pass";
$dbname = "if22_Grupp1Tarkvaraarendus";

echo $_SESSION['kasutaja_id'];
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


// Retrieve the score and currentIndex from the AJAX request
$score = $_POST['score'];
$currentIndex = $_POST['currentIndex'];

// Connect to the database and perform the necessary operations
// ...

// Check if the kasutaja_id exists in the database
$query = "SELECT COUNT(*) FROM skoorid WHERE kasutaja_id = '" . $_SESSION['kasutaja_id'] . "'";
$result = mysqli_query($mysqli, $query);
$row = mysqli_fetch_row($result);
$count = $row[0];

// Prepare the SQL statement based on the kasutaja_id existence
if ($currentIndex == 0 && $count == 0) {
  // Create a new row
  $sql = "INSERT INTO skoorid (score, kasutaja_id) VALUES ('$score', '" . $_SESSION['kasutaja_id'] . "')";
} else {
  // Update the existing row
  $sql = "UPDATE skoorid SET score = '$score' WHERE kasutaja_id = '" . $_SESSION['kasutaja_id'] . "'";
}


// Execute the SQL statement
// ...

// Return a response to the AJAX request
echo "Score updated successfully.";


// Execute the SQL statement
if ($mysqli->query($sql) === true) {
  echo 'Score submitted successfully!';
} else {
  echo 'Error: ' . $mysqli->error;
}

// Close the database connection
$mysqli->close();
?>