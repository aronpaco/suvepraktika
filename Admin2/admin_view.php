<?php
// Step 1: Connect to the database
$host = "localhost";
$dbname = "if22_Grupp1Tarkvaraarendus";
$username = "if22";
$password = "if22pass";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Step 2: Fetch data from the database
$query = "SELECT * FROM feedback ORDER BY datetime DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Step 3: Display the feedbacks
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin View</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Admin View</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Overall Feedback</th>
            <th>Detail Feedback</th>
            <th>Question</th>
            <th>Email</th>
            <th>Date/Time</th>
        </tr>
        <?php foreach ($feedbacks as $feedback) { ?>
            <tr>
                <td><?php echo $feedback['id']; ?></td>
                <td><?php echo $feedback['overall_feedback']; ?></td>
                <td><?php echo $feedback['detail_feedback']; ?></td>
                <td><?php echo $feedback['question']; ?></td>
                <td><?php echo $feedback['email']; ?></td>
                <td><?php echo $feedback['datetime']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
