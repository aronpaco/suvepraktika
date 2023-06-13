<?php
$servername = "localhost";
$username = "if22";
$password = "if22pass";
$dbname = "if22_Grupp1Tarkvaraarendus";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sql = "SELECT * FROM your_table_name ORDER BY $sort";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

// Return data as JSON
echo json_encode($data);

// Close the database connection
$conn->close();
?>
