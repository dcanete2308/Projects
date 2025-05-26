import React, { useContext } from 'react';
import { AuthContext } from "../Contexto/contexto";
import { useNavigate } from "react-router-dom";

function SignOut() {
  const { logout } = useContext(AuthContext); // coge el contexto para pillar la función de logout
  const navigate = useNavigate();

  const handleLogout = async () => {
    try {
      await logout(); // llama a la función del contexto
      navigate("/login"); 
    } catch (error) {
      console.error("Error durante el logout:", error);
    }
  };

  // al darle click se llama al handle
  return (
    <div onClick={handleLogout}> 
      SIGNOUT
    </div>
  );
}

export default SignOut;