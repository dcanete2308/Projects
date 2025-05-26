import React, { useState } from "react";
import style from "../Login/login.module.scss";
import { Link, useNavigate } from "react-router-dom";

function Login() {
  const [datosForm, setFormData] = useState({
    email: "",
    password: "",
  });
  const navigate = useNavigate();

  const cambioForm = (e) => {
    const { name, value } = e.target; // coge los datos que cambia y los alamcena
    setFormData({
      ...datosForm,
      [name]: value,
    });
  };

  const enviarDatos = async (e) => { // mira si los valores estan vacios antes de enciar datois
    e.preventDefault();
    for (const key in datosForm) {
      if (datosForm[key].trim() === "") {
        alert(`El campo ${key} está vacío`);
        return;
      }
    }

    const datosEnviar = { // crea el componete para enviar
      email: datosForm.email,
      password: datosForm.password,
    };

    try {
      const respuesta = await fetch("http://localhost:8091/users/login", { // hace una petición a  la api
        mode: 'cors',
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(datosEnviar), // transdormca en json
        credentials: "include",
      });
    
      if (!respuesta.ok) {
        throw new Error("Error al registrarse");
      } else {
        alert("Inicio de sesión exitoso");
        navigate("/profile")
        window.location.reload();
      }      
    } catch (error) {
      alert(`Error: ${error.message}`);
    }
  };

  return (
    <div className={style.login}>
      <form className={style.formLogin} onSubmit={enviarDatos}>
        <h2>Sing in</h2>
        <input
          type="email"
          name="email"
          placeholder="Correo electrónico"
          value={datosForm.email}
          onChange={cambioForm}
          required
        />
        <input
          type="password"
          name="password"
          placeholder="Contraseña"
          value={datosForm.password}
          onChange={cambioForm}
          required
        />
        <button type="submit">Log in</button>
      </form>
    </div>
  );
}

export default Login;
