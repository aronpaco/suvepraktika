document.addEventListener('DOMContentLoaded', function() {
    const startSection = document.getElementById('start-section');
    const questionSection = document.getElementById('question-section');
    const endSection = document.getElementById('end-section');
    const nameInput = document.getElementById('name');
    const ageInput = document.getElementById('age');
    const educationInput = document.getElementById('education');
    const jobInput = document.getElementById('job');
    const questionElement = document.getElementById('question');
    const answerButtonsElement = document.getElementById('answer-buttons');
    const nextButton = document.getElementById('next-btn');
    const endTitleElement = document.getElementById('end-title');
    const endScoreElement = document.getElementById('end-score');
  
    let currentQuestionIndex = 0;
    let score = 0;
  
    let playerData = {};
  
    // Fetching the CSV file
    fetch('temp.csv')                       // TEMP //
      .then(response => response.text())
      .then(data => {
        const csvData = parseCSVData(data);
        playerData = {
          name: nameInput.value,
          age: ageInput.value,
          education: educationInput.value,
          job: jobInput.value
        };
  
        // Event listener for the start button
        document.getElementById('start-btn').addEventListener('click', () => {
          startSection.style.display = 'none';
          questionSection.style.display = 'block';
          displayNextQuestion();
        });
  
        // Event listener for the next button
        nextButton.addEventListener('click', () => {
          const selectedAnswer = document.querySelector('.answer-btn.selected');
  
          if (selectedAnswer) {
            const scoreValue = selectedAnswer.dataset.score;
            score += parseInt(scoreValue);
            const nextQuestionIndex = parseInt(selectedAnswer.dataset.nextquestion);
            if (nextQuestionIndex >= 0) {
              currentQuestionIndex = nextQuestionIndex;
              displayNextQuestion();
              console.log(nextQuestionIndex);
            } else {
              showEndScreen();
              console.log(nextQuestionIndex + " end");
            }
          }
        });
  
        // Function to parse the CSV data
        function parseCSVData(csvData) {
          const lines = csvData.split('\n');
          const header = lines[0].split(';');
          const questions = [];
  
          for (let i = 1; i < lines.length; i++) {
            const currentLine = lines[i].split(';');
            const question = {};
  
            for (let j = 0; j < header.length; j++) {
              question[header[j]] = currentLine[j];
            }
  
            questions.push(question);
          }
  
          return questions;
        }
  
        // Function to display the next question
        function displayNextQuestion() {
          const currentQuestion = csvData[currentQuestionIndex];
          const question = currentQuestion['Question'];
  
          questionElement.innerText = question;
          answerButtonsElement.innerHTML = '';
  
          for (let i = 1; i <= 4; i++) {
            const answer = currentQuestion[`Answer${i}`];
            const score = currentQuestion[`Score${i}`];
            const nextQuestion = currentQuestion[`NextQuestion${i}`];
  
            createAnswerButton(answer, score, nextQuestion);
          }
  
          currentQuestionIndex = findQuestionIndex(currentQuestionIndex);
        }
  
        // Function to find the index of the next question
        function findQuestionIndex(currentIndex) {
          const currentQuestion = csvData[currentIndex];
          console.log("currentIndex: " + currentIndex)
          for (let i = 1; i <= 4; i++) {
            const nextQuestion = currentQuestion[`NextQuestion${i}`].trim();
            console.log("currentQuestion: " + currentIndex)
            if (nextQuestion !== '') {
              const nextIndex = csvData.findIndex(question => question['QuestionNumber'].trim() === nextQuestion);
  
              if (nextIndex !== -1) {
                return nextIndex;
              }
            }
          }
  
          return -1;
        }
  
        // Function to create an answer button
        function createAnswerButton(answer, score, nextQuestion) {
          const button = document.createElement('button');
          button.innerText = answer;
          button.classList.add('answer-btn');
          button.dataset.score = score;
          button.dataset.nextquestion = nextQuestion !== '' ? nextQuestion : -1;
          button.addEventListener('click', selectAnswer);
          answerButtonsElement.appendChild(button);
        }
  
        // Function to handle the answer selection
        function selectAnswer(e) {
          const selectedButton = e.target;
  
          const answerButtons = document.querySelectorAll('.answer-btn');
          answerButtons.forEach(button => {
            button.classList.remove('selected');
          });
  
          selectedButton.classList.add('selected');
          nextButton.disabled = false;
        }
  
        // Function to show the end screen
        function showEndScreen() {
          questionSection.style.display = 'none';
          endSection.style.display = 'block';
  
          const endScore = calculateEndScore();
          endScoreElement.innerText = endScore;
  
          let endTitle = '';
  
          if (endScore <= 30) {
            endTitle = 'Title A';
          } else if (endScore <= 60) {
            endTitle = 'Title B';
          } else {
            endTitle = 'Title C';
          }
  
          endTitleElement.innerText = endTitle;
        }
  
        // Function to calculate the end score
        function calculateEndScore() {
          const age = parseInt(playerData.age);
          const education = parseInt(playerData.education);
          const job = parseInt(playerData.job);
  
          let endScore = score;

  
          return endScore;
        }
      });
  });