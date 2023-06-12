<?php
// Retrieve form data
$overallFeedback = $_POST['feedback']; // Like: 1, Dislike: -1
$detailFeedback = $_POST['detail_feedback'];
$question = $_POST['question'];
$email = $_POST['email'];

// Validate and sanitize the data (optional)
// You can add your own validation and sanitization logic here

// Connect to the database
$host = 'localhost'; // Change if necessary
$username = 'if22'; // Change to your phpMyAdmin username
$password = 'if22pass'; // Change to your phpMyAdmin password
$dbName = 'if22_Grupp1Tarkvaraarendus'; // Change to your database name

$connection = mysqli_connect($host, $username, $password, $dbName);

// Check if the connection was successful
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Insert the form data into the database
$sql = "INSERT INTO feedback (overall_feedback, detail_feedback, question, email) 
        VALUES ('$overallFeedback', '$detailFeedback', '$question', '$email')";

if (mysqli_query($connection, $sql)) {
    echo "Feedback submitted successfully.";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($connection);
}

// Close the database connection
mysqli_close($connection);
?>
