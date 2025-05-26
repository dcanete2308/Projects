import { createContext, useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    const obtenerPerfil = async () => { 
      try {
        const respuesta = await fetch("http://localhost:8091/users/getUser", { // hace una peticion para coger los datos del usuario
          method: "GET",
          credentials: "include",
          mode: 'cors',
        });

        if (respuesta.ok) {
          const datos = await respuesta.json();// guarda los datos recibidos
          setUser(datos);
          console.log("Usuario autenticado:", datos);
        } else {
          setUser(null); 
          console.log("No hay sesión activa");
        }
      } catch (error) {
        console.error("Error al verificar autenticación:", error);
        setUser(null); 
      }
    };
    
    obtenerPerfil();
  }, []);

  const logout = async () => {
    try {
      await fetch("http://localhost:8091/users/logout", { // hace una peticción a la api
        method: "POST",
        credentials: "include" 
      });
      setUser(null); 
      localStorage.removeItem("user");
      navigate("/");
    } catch (error) {
      console.error("Error al cerrar sesión:", error);
    }
  };

  return (
    <AuthContext.Provider value={{ user, logout }}>
      {children}
    </AuthContext.Provider>
  );
};