import React, { useState } from "react";
import styles from "./modalDelete.module.scss";

const ModalDelete = ({ abierto, cerrado, confirmar }) => {
    const [inputValue, setInputValue] = useState("");

    const handleInputChange = (e) => { // guadra los cambios
        setInputValue(e.target.value);
    };

    const handleConfirm = () => {
        if (inputValue === "DELETE") { // si se ha escrito DELETE se elimina la nota
            confirmar(); 
        } else {
            alert("Por favor, escribe 'DELETE' para confirmar.");
        }
    };

    if (!abierto) return null;

    // aplica el html y añade funciones on click 
    return (
        <div className={styles.modalOverlay}>
            <div className={styles.contenido}>
                <h3>¿Estás seguro de que deseas eliminar esta nota?</h3>
                <p>Escribe <strong>'DELETE'</strong> para confirmar:</p>
                <input
                    type="text"
                    value={inputValue}
                    onChange={handleInputChange}
                    placeholder="Escribe DELETE"
                />
                <div className={styles.accion}>
                    <button className={styles.cancel} onClick={cerrado}>
                        Cancelar
                    </button>
                    <button
                        className={styles.confirm}
                        onClick={handleConfirm}
                        disabled={inputValue !== "DELETE"}
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    );
};

export default ModalDelete;
