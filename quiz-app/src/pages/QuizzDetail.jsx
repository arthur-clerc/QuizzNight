import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';

function QuizDetail() {
  const { id } = useParams();
  const [quiz, setQuiz] = useState(null);
  const [userAnswers, setUserAnswers] = useState({});
  const [results, setResults] = useState({});
  const [showResults, setShowResults] = useState(false);

  useEffect(() => {
    const fetchQuiz = async () => {
      try {
        const response = await axios.get(`http://localhost:8000/api/quiz/${id}`);
        console.log(response.data);
        setQuiz(response.data);
      } catch (error) {
        console.error('Error fetching quizzes:', error);
      }
    };
    fetchQuiz();
  }, [id]);

  const handleChange = (questionId, answerId) => {
    setUserAnswers({
      ...userAnswers,
      [questionId]: answerId,
    });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const newResults = {};

    Object.values(quiz.questions).forEach((question) => {
      const correctAnswer = Object.values(question.answers).find((answer) => answer.isCorrect);
      if (correctAnswer) {
        const userAnswer = userAnswers[question.id];
        newResults[question.id] = userAnswer === correctAnswer.id;
      }
    });

    setResults(newResults);
    setShowResults(true);
  };

  if (!quiz) return <p>Chargement...</p>;

  return (
    <div className="container mx-auto py-6">
      <h2 className="text-3xl font-bold mb-6">{quiz.title}</h2>
      <form onSubmit={handleSubmit}>
        {Object.values(quiz.questions).map((question) => (
          <div key={question.id} className="mb-6">
            <p className="text-lg font-semibold">{question.questionText}</p>
            <div className="mt-2">
              {Object.values(question.answers).map((option, index) => (
                <label key={index} className="block mb-2">
                  <input
                    type="radio"
                    name={`question-${question.id}`}
                    value={option.id}
                    checked={userAnswers[question.id] === option.id}
                    onChange={() => handleChange(question.id, option.id)}
                    className="mr-2"
                    disabled={showResults} 
                  />
                  {option.answerText}
                  {showResults && userAnswers[question.id] === option.id && (
                    <span className="ml-2">
                      {results[question.id] === true ? (
                        <span className="text-green-500">&#10003;</span>
                      ) : (
                        <span className="text-red-500">&#10007;</span>
                      )}
                    </span>
                  )}
                </label>
              ))}
            </div>
          </div>
        ))}
        <button
          type="submit"
          className="bg-indigo-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75"
          disabled={showResults} 
        >
          Soumettre
        </button>
      </form>
    </div>
  );
}

export default QuizDetail;
