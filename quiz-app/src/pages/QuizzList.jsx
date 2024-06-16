import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';


function QuizList() {
  const [quizzes, setQuizzes] = useState([]);

  useEffect(() => {
    
    const fetchQuizzes = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/quizzes');
        console.log(response.data);
        setQuizzes(response.data);
        quizzes.map(quiz => {
          console.log(quiz.questions);
        })
      } catch (error) {
        console.error('Error fetching quizzes:', error);
      }
    };
    fetchQuizzes();
  }, []);

  return (
    <div className="container mx-auto py-6">
      <h2 className="text-3xl font-bold mb-6">Liste des Quiz</h2>
      <ul className="space-y-4">
        {quizzes.map((quiz) => (
          <li key={quiz.id} className="p-4 bg-white rounded-lg shadow-md">
            <Link to={`/quiz/${quiz.id}`} className="text-xl font-bold text-indigo-600 hover:underline">
              {quiz.title} {Object.keys(quiz.questions).length}
            </Link>
            <p className="text-gray-600">{quiz.description}</p>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default QuizList;
