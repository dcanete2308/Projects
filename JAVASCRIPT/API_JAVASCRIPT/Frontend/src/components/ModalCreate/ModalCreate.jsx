import React, { forwardRef, useImperativeHandle, useRef} from "react";
import styles from "./modalCreate.module.scss";

const ModalCreate = forwardRef(({ abrirCreate, cerradoCreate, creacionNota }, ref) => { // esto permite pasar una referencia entre padre e hijo
  const titleRef = useRef(null); // crea las referencias
  const textRef = useRef(null);
  const statusRef = useRef(null);

  useImperativeHandle(ref, () => ({ // permite que el padre pueda coger los metodos del hijo
    getFormValues: () => ({ // devuelve un objeto con los valores actuales
      title: titleRef.current.value,
      text: textRef.current.value,
      status: statusRef.current.value
    }),
    clearForm: () => { //limpia el formulario 
      titleRef.current.value = '';
      textRef.current.value = '';
      statusRef.current.value = 'Published';
    }
  }));

  if (!abrirCreate) return null;

  return (
    <div className={styles.modalOverlay}>
      <div className={styles.contenido}>
        <h3>Crear Nueva Nota</h3>
        
        <label>Título:</label>
        <input 
          type="text" 
          ref={titleRef} 
          placeholder="Título de la nota" 
        />
        
        <label>Estado:</label>
        <select ref={statusRef}>
          <option value="Published">Published</option>
          <option value="Draft">Draft</option>
          <option value="Archived">Archived</option>
        </select>
        
        <label>Contenido:</label>
        <textarea 
          ref={textRef} 
          placeholder="Escribe el contenido de la nota"
        ></textarea>
        
        <div className={styles.acciones}>
          <button className={styles.cancel} onClick={cerradoCreate}>
            Cancelar
          </button>
          <button className={styles.confirm} onClick={creacionNota}>
            Crear Nota
          </button>
        </div>
      </div>
    </div>
  );
});

export default ModalCreate;