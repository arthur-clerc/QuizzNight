import React from 'react';
import { useNavigate } from 'react-router-dom';
import Presenter from '../assets/presenter.png';

function Home() {
  const navigate = useNavigate();

  return (
<div className="pb-16">
<div className="flex flex-col items-center justify-center pb-4">
</div>
  <h1 className="text-center font-bold pb-4">Bienvenue dans l'univers captivant de Quiz Night !</h1>
  <p className="text-center font-bold">
    Rejoignez-nous chaque mercredi soir pour des sessions de quiz entre famille ou amis, où l'expérience est façonnée selon vos désirs, loin des limitations des applications traditionnelles.
  </p>
  <div className="flex justify-center pb-16">
  <img src={Presenter} alt="Présentateur fou" className="w-80 mx-auto mt-8" />
  </div>
</div>
  );
}

export default Home;
