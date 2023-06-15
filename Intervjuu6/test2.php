<?php
session_start();
var_dump($_POST);
if (isset($_POST['score'])) {
  $score = $_POST['score'];
  echo "The score is: " . $score;
} else {
  echo "Score value not found.";
}
echo $_SESSION['kasutaja_id'];
$kasutaja_id = $_SESSION['kasutaja_id'];

?>

<!DOCTYPE html>
<html>
<head>
  <title>Intervjuu</title>
  <link rel="stylesheet" type="text/css" href="style_disain.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
  <div class="container">
    <div id="start-section">
      
      <label for="name">Name:</label>
      <input type="text" id="name" >

      <label for="education">Education:</label>
      <input type="text" id="education" >

      <label for="profession">Profession:</label>
      <input type="text" id="profession" >

      <label for="hobbies">Hobbies:</label>
      <input type="text" id="hobbies" >

      

      <button id="start-btn">Start</button>
    </div>
    
    <div id="intro-section" style="display: none;">
      
      <button id="intro-btn">Intro</button>
    </div>






    <div id="question-section" style="display: none;">

      
      <div id="video-container">
        <video id="default-video" width="800" autoplay="autoplay" muted defaultmuted>
          <source src="default-video.mp4" type="video/mp4">
        </video>
    
    
        <video id="video1" width="800" autoplay="autoplay" muted defaultmuted>
          <source src="video1.mp4" type="video/mp4">
        </video>
    
        <video id="video2" width="800" autoplay="autoplay" muted defaultmuted>
          <source src="video2.mp4" type="video/mp4">
        </video>

        <button onclick="playVideo('video1')">Play Video 1</button>
        <button onclick="playVideo('video2')">Play Video 2</button>
        
      </div>



      <h2 id="question"></h2>
      <div id="answer-buttons"></div>
      
      <button id="next-btn" disabled>Next Question</button>
    </div>

    

    <div id="end-section" style="display: none;">
      <h2 id="end-title"></h2>
      <p id="end-score"></p>
    </div>
  </div>

  <script src="script.js" defer></script>
</body>
</html>