// Function to parse CSV content and extract question and answer options
function extractQuestionAndAnswerOptionsFromCSV(csv, lineIndex) {
  var lines = csv.split("\n");

  if (lines.length > lineIndex) {
    var cells = lines[lineIndex].split(";");
    if (cells.length >= 9) {
      var answerOptions = [];
      for (var i = 1; i < cells.length; i += 2) {
        answerOptions.push(cells[i]);
      }
      return {
        question: cells[0],
        answerOptions: answerOptions
      };
    }
  }

  return null;
}

// Function to shuffle an array
function shuffleArray(array) {
  for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
  }
  return array;
}

// Fetch CSV file
fetch('kysimused.csv')
  .then(response => response.text())
  .then(csvContent => {
    var csvLines = csvContent.split("\n");
    var totalQuestions = csvLines.length - 1;
    var currentQuestionIndex = 1;
    var score = 0;

    function displayQuestion() {
      if (currentQuestionIndex <= totalQuestions) {
        var data = extractQuestionAndAnswerOptionsFromCSV(csvContent, currentQuestionIndex);

        if (data) {
          var { question, answerOptions } = data;

          // Display question
          var questionDiv = document.getElementById("question");
          questionDiv.textContent = question;

          // Shuffle answer options
          var shuffledAnswerOptions = shuffleArray(answerOptions);

          // Generate answer option buttons
          var answerOptionsDiv = document.getElementById("answerOptions");
          answerOptionsDiv.innerHTML = ""; // Clear previous answer options

          for (var i = 0; i < shuffledAnswerOptions.length; i++) {
            var answerButton = document.createElement("button");
            answerButton.textContent = shuffledAnswerOptions[i];
            answerButton.classList.add("answer-button");
            answerOptionsDiv.appendChild(answerButton);
          }

          // Disable submit button until an answer is selected
          var submitBtn = document.getElementById("submitBtn");
          submitBtn.disabled = true;

          // Enable submit button when an answer is selected
          answerOptionsDiv.addEventListener("click", function(event) {
            var clickedButton = event.target;
            if (clickedButton.classList.contains("answer-button")) {
              submitBtn.disabled = false;
            }
          });

          // Handle button click event
          answerOptionsDiv.addEventListener("click", function(event) {
            var clickedButton = event.target;
            if (clickedButton.classList.contains("answer-button")) {
              var selectedAnswer = clickedButton.textContent;
              updateScore(selectedAnswer);
              currentQuestionIndex++;
              displayQuestion();
            }
          });


          // Handle form submission
          submitBtn.addEventListener("click", function(event) {
            event.preventDefault();
            var selectedAnswer = document.querySelector('.answer-button.selected').textContent;
            updateScore(selectedAnswer);
            currentQuestionIndex++;
            displayQuestion();
          });

          // Update button style on selection
          answerOptionsDiv.addEventListener("click", function(event) {
            var clickedButton = event.target;
            if (clickedButton.classList.contains("answer-button")) {
              var selectedButton = document.querySelector('.answer-button.selected');
              if (selectedButton) {
                selectedButton.classList.remove("selected");
              }
              clickedButton.classList.add("selected");
            }
          });
        } else {
          console.log("Invalid CSV format or line index/question number. Cannot extract question and answer options.");
        }
      } else {
        // All questions answered
        showFinalScore();
      }
    }

    // Rest of the code...

    // Start displaying the first question
    displayQuestion();
  });
