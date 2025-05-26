import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom"; 
import styles from "../Profile/profile.module.scss";
function Profile() {
  const [user, setUser] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    const obtenerPerfil = async () => {
      try {
        const respuesta = await fetch("http://localhost:8091/users/getUser", { // se hace la petición a la bd
          mode: 'cors',
          method: "GET",
          credentials: "include", // para el token
        });

        if (respuesta.ok) {
          const datos = await respuesta.json(); // coge lo que recibe y lo transofma edel json
          setUser(datos); // lo guarda en el usuario
        } else {
          throw new Error("No se pudo obtener el perfil");
        }
      } catch (error) {
        console.error("Error al obtener el perfil:", error);
        navigate("/login");
      }
    };
    obtenerPerfil();
  }, [navigate]);

  // enseña los datos que le llegan a través del usuario que se ha definifo arriba
  return (
    <div className={styles.Profile}>
      {user ? (
        <div className={styles.datosUser}>
          <h2>Profile:</h2>
          <span>
            Id: <p>{user._id}</p>
          </span>
          <span>
            Name: <p>{user.name}</p>
          </span>
          <span>
            Surname: <p>{user.surname}</p>
          </span>
          <span>
            Email: <p>{user.email}</p>
          </span>
        </div>
      ) : (
        <p>Cargando perfil...</p>
      )}
    </div>
  );
}

export default Profile;
