import React, { useState } from 'react';
import axios from 'axios';

const CreateQuiz = () => {
  const [quizTitle, setQuizTitle] = useState('');
  const [questions, setQuestions] = useState([{ question_text: '', answers: [] }]);
  const user_id = 1;

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

  const handleDeleteAnswer = (questionIndex, answerIndex) => {
    const newQuestions = [...questions];
    newQuestions[questionIndex].answers.splice(answerIndex, 1);
    setQuestions(newQuestions);
  };

  const handleDeleteQuestion = (questionIndex) => {
    const newQuestions = [...questions];
    newQuestions.splice(questionIndex, 1);
    setQuestions(newQuestions);
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
    <form onSubmit={handleSubmit} className="max-w-md mx-auto p-6 bg-white text-black shadow-md rounded-md">
      <div className="mb-4">
        <label htmlFor="quizTitle" className="block text-black font-bold mb-2">
          Titre du quiz :
        </label>
        <input
          type="text"
          id="quizTitle"
          value={quizTitle}
          onChange={handleQuizTitleChange}
          className="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 bg-white text-black leading-tight focus:outline-none focus:shadow-outline"
        />
      </div>
      {questions.map((question, questionIndex) => (
        <div key={questionIndex} className="mb-4">
          <div>
            <label htmlFor={`question-${questionIndex}`} className="block text-black font-bold mb-2">
              Question :
            </label>
            <input
              type="text"
              id={`question-${questionIndex}`}
              value={question.question_text}
              onChange={(e) => handleQuestionChange(questionIndex, e)}
              className="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 bg-white text-black leading-tight focus:outline-none focus:shadow-outline"
            />
            <button
              type="button"
              onClick={() => handleDeleteQuestion(questionIndex)}
              className="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline ml-2"
            >
              Supprimer Question
            </button>
          </div>
          {question.answers.map((answer, answerIndex) => (
            <div key={answerIndex} className="flex items-center mb-2">
              <input
                type="text"
                value={answer.answer_text}
                onChange={(e) => handleAnswerChange(questionIndex, answerIndex, e)}
                className="shadow appearance-none border border-gray-300 rounded py-2 px-3 bg-white text-black leading-tight focus:outline-none focus:shadow-outline mr-2"
              />
              <button
                type="button"
                onClick={() => handleCorrectAnswer(questionIndex, answerIndex)}
                className={`${answer.is_correct ? 'bg-gray-300' : 'bg-gray-100'} text-black font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2 border border-gray-300`}
              >
                {answer.is_correct ? 'Correct' : 'Marquer'}
              </button>
              <button
                type="button"
                onClick={() => handleDeleteAnswer(questionIndex, answerIndex)}
                className="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline"
              >
                Supprimer
              </button>
            </div>
          ))}
          <button
            type="button"
            onClick={() => handleAddAnswer(questionIndex)}
            className="bg-gray-100 hover:bg-gray-200 text-black font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mt-2 mb-2 border border-gray-300"
          >
            Ajouter une réponse
          </button>
        </div>
      ))}
      <button
        type="button"
        onClick={handleAddQuestion}
        className="bg-blue-500 hover:bg-gray-200 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mb-4 border border-gray-300"
      >
        Ajouter une question
      </button>
      <br />
      <button
        type="submit"
        className="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline border border-gray-300"
      >
        Créer le quiz
      </button>
    </form>
  );
};

export default CreateQuiz;
