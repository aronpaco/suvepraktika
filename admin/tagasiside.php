<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tagasiside vaade</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
  <?php
  // Connect to the database
  $host = "localhost";
  $username = "if22";
  $password = "if22pass";
  $dbname = "if22_Grupp1Tarkvaraarendus";
  
  $conn = new mysqli($host, $username, $password, $dbname);
  
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  // Fetch data from the database
  $sql = "SELECT * FROM your_table_name";
  $result = $conn->query($sql);
  ?>
</head>
<body>
  <h1>Tagasiside</h1>

  <div id="filter">
    <!-- Filter elements here -->
  </div>

  <table id="resultsTable">
    <thead>
      <tr>
        <!-- Table headers -->
      </tr>
    </thead>
    <tbody>
      <?php
      // Populate the table rows with data from the database
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row["overallFeedback"] . "</td>";
          echo "<td>" . $row["detailFeedback"] . "</td>";
          echo "<td>" . $row["question"] . "</td>";
          echo "<td>" . $row["email"] . "</td>";
          echo "<td>" . $row["currentDateTime"] . "</td>";
          echo "</tr>";
        }
      }
      ?>
    </tbody>
  </table>

  <script src="script.js"></script>
</body>
</html>
