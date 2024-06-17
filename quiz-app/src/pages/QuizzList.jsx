import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

function QuizList() {
  const [quizzes, setQuizzes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchQuizzes = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/quizzes');
        console.log(response.data);
        setQuizzes(response.data);
      } catch (error) {
        console.error('Error fetching quizzes:', error);
        setError('Failed to load quizzes.');
      } finally {
        setLoading(false);
      }
    };

    fetchQuizzes();
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error}</div>;
  }

  return (
    <div className="container mx-auto py-6">
      <h2 className="text-3xl font-bold mb-6">Liste des Quiz</h2>
      <ul className="space-y-4">
        {quizzes.map((quiz) => (
          <li key={quiz.id} className="p-4 bg-white rounded-lg shadow-md">
            <Link to={`/quiz/${quiz.id}`} className="text-xl font-bold text-indigo-600 hover:underline">
              {quiz.title} {Array.isArray(quiz.questions) ? quiz.questions.length : 0}
            </Link>
            <p className="text-gray-600">{quiz.description}</p>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default QuizList;
