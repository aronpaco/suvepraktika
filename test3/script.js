// Variables
var currentQuestionIndex = 0;
var score = 0;

var questions = [];

// Load questions from CSV and randomize the sequence of answers
function loadQuestionsFromCSV(file) {
  var rawFile = new XMLHttpRequest();
  rawFile.open("GET", file, false);
  rawFile.onreadystatechange = function () {
    if (rawFile.readyState === 4 && rawFile.status === 200) {
      var allText = rawFile.responseText;
      var lines = allText.split("\n");
      for (var i = 1; i < lines.length; i++) {
        var questionData = lines[i].split(";");
        var question = {
          question: questionData[0],
          answers: [
            { answer: questionData[1], score: parseInt(questionData[2]) },
            { answer: questionData[3], score: parseInt(questionData[4]) },
            { answer: questionData[5], score: parseInt(questionData[6]) },
            { answer: questionData[7], score: parseInt(questionData[8]) }
          ]
        };
        // Shuffle the answers for each question
        question.answers = shuffleArray(question.answers);
        questions.push(question);
      }
    }
  };
  rawFile.send(null);
}

// Display the current question
function displayQuestion() {
  var questionElement = document.getElementById("question");
  var optionsElement = document.getElementById("options");
  var currentQuestion = questions[currentQuestionIndex];
  questionElement.textContent = currentQuestion.question;

  // Clear previous options
  optionsElement.innerHTML = "";

  // Shuffle the answers for the current question
  var shuffledAnswers = shuffleArray(currentQuestion.answers);

  // Create answer options
  for (var i = 0; i < shuffledAnswers.length; i++) {
    var answer = shuffledAnswers[i];
    var optionElement = document.createElement("div");
    optionElement.className = "option-box";
    optionElement.setAttribute("data-score", answer.score);
    optionElement.textContent = answer.answer;
    optionsElement.appendChild(optionElement);
  }
}

// Calculate and update the score
function calculateScore(selectedOption) {
  score += parseInt(selectedOption.getAttribute("data-score"));
}

// Show the next question or display the result
function nextQuestion() {
  var selectedOption = document.querySelector(".option-box.selected");
  if (selectedOption) {
    calculateScore(selectedOption);
  }

  // Move to the next question
  currentQuestionIndex++;

  // Check if quiz is finished
  if (currentQuestionIndex >= questions.length) {
    showResult();
  } else {
    displayQuestion();
  }
}

// Display the final result
function showResult() {
  var quizContainer = document.getElementById("quiz");
  var questionContainer = document.getElementById("questionContainer");
  var scoreContainer = document.getElementById("scoreContainer");
  var resultContainer = document.getElementById("result");
  quizContainer.removeChild(questionContainer);
  scoreContainer.style.display = "block";
  resultContainer.textContent = "Congratulations! Your score is: " + score;
}

// Shuffle array using Fisher-Yates algorithm
function shuffleArray(array) {
  for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
  }
  return array;
}

// Load questions from CSV file and start the quiz
window.onload = function () {
  loadQuestionsFromCSV("kysimused.csv");
  displayQuestion();
  var nextButton = document.getElementById("nextButton");
  nextButton.onclick = nextQuestion;

  // Event listener for selecting an answer option
  var optionsElement = document.getElementById("options");
  optionsElement.addEventListener("click", function (event) {
    var clickedElement = event.target;
    if (clickedElement.classList.contains("option-box")) {
      var selectedOptions = document.querySelectorAll(".option-box.selected");
      if (selectedOptions) {
        selectedOptions.forEach(function (option) {
          option.classList.remove("selected");
        });
      }
      clickedElement.classList.add("selected");
    }
  });
};
