import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Layout from './components/Layout';
import Register from './pages/Register';
import Login from './pages/Login';
import QuizList from './pages/QuizzList';
import QuizDetail from './pages/QuizzDetail';
import CreateQuiz from './pages/CreateQuizz';

function App() {
  return (
    <Router>
      <Layout>
        <div className="container mx-auto px-4">
          <Routes>
            <Route path="/register" element={<Register />} />
            <Route path="/login" element={<Login />} />
            <Route path="/quizzes" element={<QuizList />} />
            <Route path="/quiz/:id" element={<QuizDetail />} />
            <Route path="/addquizz" element={<CreateQuiz />} />
          </Routes>
        </div>
      </Layout>
    </Router>
  );
}

export default App;
