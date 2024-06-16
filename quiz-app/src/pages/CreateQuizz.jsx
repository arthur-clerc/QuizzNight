import React, { useState, useEffect } from 'react';
import axios from 'axios';

const CreateQuiz = () => {
  const [quizTitle, setQuizTitle] = useState('');
  const [questions, setQuestions] = useState([{ question_text: '', answers: [] }]);
  const user_id = 1

  const handleQuizTitleChange = (e) => {
    setQuizTitle(e.target.value);
  };

  const handleQuestionChange = (index, e) => {
    const newQuestions = [...questions];
    newQuestions[index].question_text = e.target.value;
    setQuestions(newQuestions);
  };

  const handleAnswerChange = (questionIndex, answerIndex, e) => {
    const newQuestions = [...questions];
    newQuestions[questionIndex].answers[answerIndex].answer_text = e.target.value;
    setQuestions(newQuestions);
  };

  const handleAddAnswer = (questionIndex) => {
    const newQuestions = [...questions];
    newQuestions[questionIndex].answers.push({ answer_text: '', is_correct: false });
    setQuestions(newQuestions);
  };

  const handleAddQuestion = () => {
    setQuestions([...questions, { question_text: '', answers: [] }]);
  };

  const handleCorrectAnswer = (questionIndex, answerIndex) => {
    const newQuestions = [...questions];
    newQuestions[questionIndex].answers.forEach((answer, index) => {
      answer.is_correct = index === answerIndex;
    });
    setQuestions(newQuestions);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost:8000/api/quiz', { title: quizTitle, user_id, questions });
      // Gérer la réponse du backend
      console.log(response.data);
    } catch (error) {
      console.error(error);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-md mx-auto p-4 bg-white shadow-md rounded-md">
      <div className="mb-4">
        <label htmlFor="quizTitle" className="block text-gray-700 font-bold mb-2">
          Titre du quiz :
        </label>
        <input
          type="text"
          id="quizTitle"
          value={quizTitle}
          onChange={handleQuizTitleChange}
          className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
      </div>
      {questions.map((question, questionIndex) => (
        <div key={questionIndex} className="mb-4">
          <div>
            <label htmlFor={`question-${questionIndex}`} className="block text-gray-700 font-bold mb-2">
              Question :
            </label>
            <input
              type="text"
              id={`question-${questionIndex}`}
              value={question.question_text}
              onChange={(e) => handleQuestionChange(questionIndex, e)}
              className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
          </div>
          {question.answers.map((answer, answerIndex) => (
            <div key={answerIndex} className="flex items-center mb-2">
              <input
                type="text"
                value={answer.answer_text}
                onChange={(e) => handleAnswerChange(questionIndex, answerIndex, e)}
                className="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2"
              />
              <button
                type="button"
                onClick={() => handleCorrectAnswer(questionIndex, answerIndex)}
                className={`${answer.is_correct ? 'bg-green-500 hover:bg-green-700' : 'bg-gray-700 hover:bg-gray-900'} text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2`}
              >
                {answer.is_correct ? 'Réponse correcte' : 'Marquer comme correcte'}
              </button>
            </div>
          ))}
          <button
            type="button"
            onClick={() => handleAddAnswer(questionIndex)}
            className="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-2"
          >
            Ajouter une réponse
          </button>
        </div>
      ))}
      <button
        type="button"
        onClick={handleAddQuestion}
        className="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4"
      >
        Ajouter une question
      </button>
      <button
        type="submit"
        className="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
      >
        Créer le quiz
      </button>
    </form>
  );
};

export default CreateQuiz;
