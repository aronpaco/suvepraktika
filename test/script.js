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

          // Generate radio answer options
          var answerOptionsDiv = document.getElementById("answerOptions");
          answerOptionsDiv.innerHTML = ""; // Clear previous answer options
          var form = document.getElementById("questionForm");

          for (var i = 0; i < shuffledAnswerOptions.length; i++) {
            var label = document.createElement("label");
            var answerBox = document.createElement("div"); // Create a div for the answer box
            answerBox.className = "answer-box"; // Add a CSS class to the answer box
            answerBox.textContent = shuffledAnswerOptions[i]; // Set the answer text

            var radioButton = document.createElement("input");
            radioButton.type = "radio";
            radioButton.name = "selectedAnswer";
            radioButton.value = shuffledAnswerOptions[i];
            console.log(radioButton.value);

            label.appendChild(radioButton);
            label.appendChild(answerBox); // Append the answer box to the label

            answerOptionsDiv.appendChild(label);
          }

          // Handle form submission
          form.addEventListener("submit", function(event) {
            event.preventDefault();
            var selectedAnswer = document.querySelector('input[name="selectedAnswer"]:checked').value;
            updateScore(selectedAnswer);
            currentQuestionIndex++;
            displayQuestion();
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

