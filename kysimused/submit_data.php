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
$correctAnswers = isset($_POST['correctAnswers']) ? $_POST['correctAnswers'] : '';

// Check if score is a valid integer value
if (!is_numeric($score) || intval($score) != $score) {
  echo 'Error: Invalid score value';
  exit;
}

// Check if correctAnswers is a valid integer value
if (!is_numeric($correctAnswers) || intval($correctAnswers) != $correctAnswers) {
  echo 'Error: Invalid answers value';
  exit;
}

// Create a new MySQLi object
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($mysqli->connect_error) {
  die('Connection failed: ' . $mysqli->connect_error);
}


// Retrieve the score and correctAnswers from the AJAX request
$score = $_POST['score'];
$correctAnswers = $_POST['correctAnswers'];

// Connect to the database and perform the necessary operations
// ...

// Check if the kasutaja_id exists in the database
$query = "SELECT COUNT(*) FROM skoorid WHERE kasutaja_id = '" . $_SESSION['kasutaja_id'] . "'";
$result = mysqli_query($mysqli, $query);
$row = mysqli_fetch_row($result);
$count = $row[0];


echo 'enne iffi: ';
echo $correctAnswers;
// Prepare the SQL statement based on the kasutaja_id existence
if ($correctAnswers == 1 && $count == 0) {
  // Create a new row
  $sql = "INSERT INTO skoorid (score, kasutaja_id, oigesti) VALUES ('$score', '" . $_SESSION['kasutaja_id'] . "', '$correctAnswers')";
  echo 'First row created';
} else {
  // Update the existing row
  $sql = "UPDATE skoorid SET score = '$score', oigesti = '$correctAnswers' WHERE kasutaja_id = '" . $_SESSION['kasutaja_id'] . "'";
  echo 'Row updated';
  echo $correctAnswers;
}


// Execute the SQL statement
// ...

// Return a response to the AJAX request



// Execute the SQL statement
if ($mysqli->query($sql) === true) {
  echo 'Score submitted successfully!';
} else {
  echo 'Error: ' . $mysqli->error;
}

// Close the database connection
$mysqli->close();
?>