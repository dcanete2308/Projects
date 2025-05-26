import React, { useState } from "react";
import style from "../Register/register.module.scss";
import { useNavigate } from "react-router-dom";


export default function Register() {
  const navigate = useNavigate(); //permite la navegacion 
  const [datosForm, setFormData] = useState({ // guarda los datos del formulario
    nombre: "",
    apellido: "",
    email: "",
    password: "",
    confirmarContra: "",
    terminos: false,
  });

  const cambioForm = (e) => {
    const { name, value, type, checked } = e.target; // pilla los valores del form
    setFormData({ // guarda los datos
      ...datosForm,
      [name]: type === "checkbox" ? checked : value,
    });
  };


  const enviarDatos = async (e) => {
    e.preventDefault();
    for (const key in datosForm) {
      if (typeof datosForm[key] === "boolean") continue;
      if (datosForm[key].trim() === "") {
        alert(`El campo ${key} está vacío`);
        return;
      }


    if (!datosForm.terminos) { // si no se aceptan los teminos no deja
      alert("Debes aceptar los términos y condiciones para registrarte.");
      return;
  }
    }

    if (datosForm.password !== datosForm.confirmarContra) { // si no conidicen las contras da error
      alert("La contra no coincide");
      return;
    }

    const datosEnviar = { // guadra los datos para enviarlo a la api
      name: datosForm.nombre,
      surname: datosForm.apellido,
      email: datosForm.email,
      password: datosForm.password,
    };
  
    try {
      const respuesta = await fetch("http://localhost:8091/users/register", { // se hace la petición a la api
        mode: 'cors',
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(datosEnviar), // se envian en el body de forma json
      });
    
      if (!respuesta.ok) {
        throw new Error("Error al registrarse");
      } else {
        alert("Cuenta creada con éxito");
        navigate('/login')
      }

    } catch (error) {
      alert(`Error: ${error.message}`);
    }
  };
  
  //CREACIÓN DEL FORMULARIO DE REGISTRO
  return (
    <div className={style.registro}> 
      <form className={style.formregistro} onSubmit={enviarDatos}>
        <h2>Crear una nueva cuenta</h2>
        <input
          type="text"
          name="nombre"
          placeholder="Nombre"
          value={datosForm.nombre}
          onChange={cambioForm}
          required
        />
        <input
          type="text"
          name="apellido"
          placeholder="Apellido"
          value={datosForm.apellido}
          onChange={cambioForm}
          required
        />
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
        <input
          type="password"
          name="confirmarContra"
          placeholder="Confirmar contraseña"
          value={datosForm.confirmarContra}
          onChange={cambioForm}
          required
        />
        <label>
          <input 
            className={style.acceptar}
            type="checkbox"
            name="terminos"
            checked={datosForm.terminos}
            onChange={cambioForm}
            required
          />
          Acepto los términos y condiciones
        </label>
        <button type="submit">Crear cuenta</button>
      </form>
    </div>
  );
}
