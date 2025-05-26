import React, { useState, useEffect, useMemo, useCallback, useRef } from "react";
import styles from "../Note/note.module.scss";
import { useNavigate } from "react-router-dom"; 
import ModalCreate from "../ModalCreate/ModalCreate";
import ModalDelete from "../ModalDelete/ModalDelete"

function Note() {
  const [notes, setNotes] = useState([]);
  const [userId, setUserId] = useState(null);
  const [loading, setLoading] = useState(true);
  const [modalAbierto, setmodalAbierto] = useState(false);
  const [crearModel, setcrearModel] = useState(false);
  const modalCreateRef = useRef(null);
  const idNotaAct = useRef(null);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchUser = async () => {
      try {
        console.log("Fetching user...");
        const response = await fetch("http://localhost:8091/users/getUser", { // hace la petición para coger los datos del usuario
          credentials: "include",
        });

        if (!response.ok) throw new Error("No autorizado");

        const userData = await response.json(); //pilla los datos que le llegan
        setUserId(userData._id); // coge el id, se pone _id por mongo db y lo guarda
      } catch (error) {
        console.error("Error obteniendo usuario:", error);
        navigate("/login");
      }
    };

    fetchUser();
  }, [navigate]);

  useEffect(() => {
    const fetchNotes = async () => {
      try {
        const response = await fetch(`http://localhost:8091/note/llistar/${userId}`, { // llama a la api pasandole el id del usuario
          credentials: "include",
        });

        if (!response.ok) throw new Error("Error al obtener notas");

        const data = await response.json(); 
        setNotes(data); //guadra lo que recibe
      } catch (error) {
        console.error("Error obteniendo notas:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchNotes();
  }, [userId]);

  const handleNoteChange = useCallback((id, field, value) => {
    setNotes((prevNotes) =>
      prevNotes.map((note) =>
        note._id === id ? { ...note, [field]: value } : note
      )
    );
  }, []);

  const updateNote = useCallback(async (note) => { // hace función callback para renderizar
    console.log("nota id: " + note._id);
    if (!note.title || !note.text || !note.status) { // mira si algun dato no se ha registrado
      alert("Todos los campos deben estar completos antes de actualizar.");
      return;
    }

    try {
      const response = await fetch(`http://localhost:8091/note/modificar/${note._id}`, { // hace la petición a la api
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        credentials: "include",
        body: JSON.stringify({ // le pasa en forma de json los datos de la nota a actualizar
          title: note.title,
          text: note.text,
          status: note.status,
        }),
      });

      if (!response.ok) throw new Error("No se ha podido actualizar la nota");

      const updatedNote = await response.json();
      setNotes((prevNotes) =>
        prevNotes.map((n) => (n.id === updatedNote.id ? updatedNote : n)) // busca la noita por su id y lo actualiza
      );
      alert("Se han actualizado los datos");
      window.location.reload(); // actauliza la pagina para enseñar las notas
    } catch (error) {
      console.error("Error al actualizar la nota:", error);
    }
  }, []);

  const abrirDeleteModal = useCallback((noteId) => {
    idNotaAct.current = noteId; // guarda el id de la nota actual y abre el modal
    setmodalAbierto(true);
  }, []);

  const cerrarDeleteModal = useCallback(() => {
    setmodalAbierto(false); 
    idNotaAct.current = null; // quita el id de la nota y cierra el modal
  }, []);

  const handleDeleteConfirm = useCallback(async () => {
    if (!idNotaAct.current) return;

    try {
      const response = await fetch(`http://localhost:8091/note/eliminar/${idNotaAct.current}`, { // llama a la api
        method: "DELETE",
        credentials: "include",
      });

      if (!response.ok) throw new Error("No se ha podido eliminar la nota");

      setNotes(prevNotes => prevNotes.filter(note => note._id !== idNotaAct.current)); // elimina la  nota en base al id
      cerrarDeleteModal(); // cierra el model
      alert("Nota eliminada correctamente");
      window.location.reload();
    } catch (error) {
      console.error("Error al eliminar la nota:", error);
      alert("Error al eliminar la nota");
    }
  }, [cerrarDeleteModal]);

  const abrirCreateModel = useCallback(() => { //abre el modal de crear
    setcrearModel(true);
  }, []);

  const cerrarCreateModal = useCallback(() => {
    setcrearModel(false); // cierra el modal de crear y lo limipa
    if (modalCreateRef.current) {
      modalCreateRef.current.clearForm();
    }
  }, []);

  const handleCreateNote = useCallback(async () => {
    if (!modalCreateRef.current) return;

    const { title, text, status } = modalCreateRef.current.getFormValues(); // coge los datos del formulario 
    
    if (!title || !text || !status) {
      alert("Todos los campos son obligatorios");
      return;
    }

    if (/^\d/.test(title)) {
      alert("El título no puede empezar por un número");
      return;
    }

    try {
      const response = await fetch("http://localhost:8091/note/crear", { // llama a la api
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        credentials: "include",
        body: JSON.stringify({ // envia los datos por el body
          title,
          text,
          status,
          author: userId
        }),
      });

      if (!response.ok) throw new Error("Error al crear la nota");

      const newNote = await response.json(); // pilla los datos que recibe y los guada
      setNotes(prevNotes => [...prevNotes, newNote]); // lo añade a las notas que ya habían
      cerrarCreateModal(); //cierra el modal
      alert("Nota creada correctamente");
      window.location.reload();
    } catch (error) {
      console.error("Error al crear la nota:", error);
      alert("Error al crear la nota");
    }
  }, [userId, cerrarCreateModal]);

  const renderedNotes = useMemo(() => { // crea las notas con los datos que obtiene 
    return notes.map((note) => (
      <div key={note._id} className={styles.note}>
        <label>Title:</label>
        <input
          type="text"
          value={note.title}
          onChange={(e) => handleNoteChange(note._id, "title", e.target.value)}
        />

        <label>State:</label>
        <select
          value={note.status}
          onChange={(e) => handleNoteChange(note._id, "status", e.target.value)}
        >
          <option value="Published">Published</option>
          <option value="Archived">Archived</option>
          <option value="Draft">Draft</option>
        </select>

        <label>Body:</label>
        <textarea
          value={note.text}
          onChange={(e) => handleNoteChange(note._id, "text", e.target.value)}
        />

        <div className={styles.buttonsDiv}>
          <button className={styles.update} onClick={() => updateNote(note)}>
            Update
          </button>
          <button
            className={styles.delete}
            onClick={() => abrirDeleteModal(note._id)}
          >
            Delete
          </button>
        </div>
      </div>
    ));
  }, [notes, handleNoteChange, updateNote, abrirDeleteModal]);

  return (
    <div className={styles.nota}>
      <div className={styles.noteContainer}>
        <h2>User Notes</h2>
        <button className={styles.createNote} onClick={abrirCreateModel}>
          Create Note
        </button>

        {loading ? <p>Cargando notas...</p> : notes.length === 0 ? <p>No hay notas disponibles.</p> : renderedNotes}
      </div>

      <ModalDelete
        abierto={modalAbierto}
        cerrado={cerrarDeleteModal}
        confirmar={handleDeleteConfirm}
      />

      <ModalCreate
        abrirCreate={crearModel}
        cerradoCreate={cerrarCreateModal}
        creacionNota={handleCreateNote}
        ref={modalCreateRef}
      />
    </div>
  );
}

export default Note;