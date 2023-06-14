<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $education = $_POST['education'];
    $profession = $_POST['profession'];
    $hobbies = $_POST['hobbies'];

    // Check if at least one field is filled out
    if (!empty($name)) {
        require_once "../config.php";
        $conn = new mysqli($server_host, $server_user_name, $server_password, $dbname);
        $stmt = $conn->prepare("INSERT INTO kasutaja (nimi, haridus, amet, huvid) VALUES(?,?,?,?)");
        $conn->set_charset("utf8");
        echo $conn->error;
        $stmt->bind_param("ssss", $name, $education, $profession, $hobbies);
        $stmt->execute();
        echo $stmt->error;
        $stmt->close();
        $conn->close();
    
        $conn = new mysqli($server_host, $server_user_name, $server_password, $dbname);
        $stmt = $conn->prepare("SELECT id FROM kasutaja WHERE nimi = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->bind_result($id);
    
        if ($stmt->fetch()) {
            $_SESSION['kasutaja_id'] = $id;
            echo $_SESSION['kasutaja_id'];
        } else {
            echo "Nothing found";
        }
    
        $stmt->close();
        $conn->close(); 
    } else {
        echo "Palun täida vähemalt üks lahter, et saaksid simulaatorit kasutada";
    }
  } 
}


?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Tere tulemast</title>
</head>
<body>
    <div id="container">
        <div id="main">
        <form method="POST" action="../Intervjuu/test2.html">
            <label for="name">Sisesta oma nimi:</label>
            <input type="text" name="name" id="name">
            <label for="education">Vali oma haridustase:</label>
            <select id="education" name="education">
                <option value="bakalaureus">Bakalaureus</option>
                <option value="magister">Magister</option>
                <option value="doktor">Doktor</option>
                <option value="muu">Muu</option>
            </select>
            <label for="profession">Sisesta oma amet:</label>
            <input type="text" name="profession" id="profession">
            <label for="hobbies">Sisesta oma hobid:</label>
            <textarea type="text" name="hobbies" id="hobbies"></textarea>
        </div>
        <div id="buttonContainer">
        
            <input type="submit" name="submit" id="nextButton" value="Saada ära">
        
            <?php echo $_SESSION['kasutaja_id'];?>

    </div>
    <br><hr>
    <!--
    <div id="technicalContainer">Tehniline pool<br>
        <button id="save">Salvesta andmed</button>
        <button id="load">Laadi andmed</button>
        <button id="delete">Kustuta andmed</button>
    </div>
    <div id="textContainer"> <br> Siia laeme andmed</div>
    -->
</body>
</html>