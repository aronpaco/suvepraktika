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
  fetch('kysimused.csv')
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
          } else {
            showEndScreen();
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

          if (nextQuestion === '') {
            continue;
          }

          createAnswerButton(answer, score, nextQuestion);
        }

        currentQuestionIndex = findQuestionIndex(currentQuestionIndex);
      }

      // Function to find the index of the next question
      function findQuestionIndex(currentIndex) {
        const currentQuestion = csvData[currentIndex];
        
        for (let i = 1; i <= 4; i++) {
          const nextQuestion = currentQuestion[`NextQuestion${i}`].trim();
          
          if (nextQuestion !== '') {
            const nextIndex = csvData.findIndex(question => question['QuestionNumber'].trim() === nextQuestion);
            
            if (nextIndex !== -1) {
              return nextIndex;
            }
          }
        }
        
        // If no next question is found, return the current index
        return currentIndex;
      }


      
      // Function to create an answer button
      function createAnswerButton(answer, score, nextQuestionIndex) {
        if (!answer || !score || !nextQuestionIndex) {
          return;
        }

        const button = document.createElement('button');
        button.innerText = answer;
        button.dataset.score = score;
        button.dataset.nextquestion = nextQuestionIndex;
        button.classList.add('answer-btn');

        button.addEventListener('click', () => {
          const selectedAnswer = document.querySelector('.answer-btn.selected');
          if (selectedAnswer) {
            selectedAnswer.classList.remove('selected');
          }

          button.classList.add('selected');
          nextButton.disabled = false;
        });

        answerButtonsElement.appendChild(button);
      }

      // Function to show the end screen
      function showEndScreen(endType) {
        questionSection.style.display = 'none';
        endSection.style.display = 'block';

        endTitleElement.innerText = 'End of the Questionnaire';
        if (endType === 'END1') {
          endScoreElement.innerText = 'Congratulations! You have reached the END1.';
        } else if (endType === 'END2') {
          endScoreElement.innerText = `Your final score is: ${score}. You have reached the END2.`;
        }
      }
    })
    .catch(error => console.error(error));
});
