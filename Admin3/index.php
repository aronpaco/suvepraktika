<style>
<?php include 'style.css'; ?>
</style>
<?php
// Check if the date range is provided by the user
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Create the SQL query with the date range and ORDER BY clause to sort by date
$sql = "SELECT * FROM feedback";

// Add the date range condition if both start and end dates are provided
if (!empty($startDate) && !empty($endDate)) {
    // Assuming the 'datetime' column is of type 'DATE'
    $sql .= " WHERE DATE(datetime) BETWEEN '$startDate' AND '$endDate'";
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'descending';
$order = ($sort === 'ascending') ? 'ASC' : 'DESC';
$sql .= " ORDER BY datetime $order";

// Execute the query
// Replace 'your_database_name' with the actual name of your database
$conn = new mysqli('localhost', 'if22', 'if22pass', 'if22_Grupp1Tarkvaraarendus');
$result = $conn->query($sql);

if (!$result) {
    echo "Error executing the query: " . $conn->error;
} else {
    echo "<table>";
    echo "<tr><th>ID</th><th>Overall Feedback</th><th>Detail Feedback</th><th>Question</th><th>Email</th><th>Date and Time</th></tr>";

    $rowCount = 0; // Track the row count

    while ($row = $result->fetch_assoc()) {
        $rowCount++;
        $rowClass = ($rowCount % 2 == 0) ? 'even' : 'odd'; // Determine the row class

        echo "<tr>";
        echo "<td>".$row['id']."</td>";
        echo "<td>".$row['overall_feedback']."</td>";
        echo "<td>".$row['detail_feedback']."</td>";
        echo "<td>".$row['question']."</td>";
        echo "<td>".$row['email']."</td>";
        echo "<td>".$row['datetime']."</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br>";
    echo "<form action=\"index.php\" method=\"get\">";
    echo "<input type=\"hidden\" name=\"sort\" value=\"$sort\">";
    echo "<label for=\"start_date\">Start Date:</label>";
    echo "<input type=\"date\" id=\"start_date\" name=\"start_date\" value=\"$startDate\">";
    echo "<label for=\"end_date\">End Date:</label>";
    echo "<input type=\"date\" id=\"end_date\" name=\"end_date\" value=\"$endDate\">";
    echo "<input type=\"submit\" value=\"Apply\">";
    echo "</form>";
    echo "<br>";
    echo "<button onclick=\"window.location.href='index.php?sort=".($sort === 'ascending' ? 'descending' : 'ascending')."&start_date=$startDate&end_date=$endDate'\">Sort ".($sort === 'ascending' ? 'Descending' : 'Ascending')."</button>";
    echo "<br>";
    echo "<form action=\"index.php\" method=\"post\">";
    echo "<input type=\"hidden\" name=\"start_date\" value=\"$startDate\">";
    echo "<input type=\"hidden\" name=\"end_date\" value=\"$endDate\">";
    echo "<input type=\"hidden\" name=\"sort\" value=\"$sort\">";
    echo "<input type=\"submit\" name=\"export_csv\" value=\"Export as CSV\">";
    echo "<input type=\"submit\" name=\"export_excel\" value=\"Export as Excel\">";
    echo "</form>";

    // Check if the export_csv button is clicked
    if (isset($_POST['export_csv'])) {
        // Set the headers to force download the file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="feedback.csv"');
        
        // Create a file pointer
        $output = fopen('php://output', 'w');
        
        // Write the column headers
        fputcsv($output, ['ID', 'Overall Feedback', 'Detail Feedback', 'Question', 'Email', 'Date and Time']);
        
        // Execute the query again to get the data
        $result = $conn->query($sql);
        
        // Iterate over the rows and write them to the file
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
        
        // Close the file pointer
        fclose($output);
        exit(); // Stop further execution
    }

}

// Close the database connection
$conn->close();
?>
