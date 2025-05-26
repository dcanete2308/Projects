import { useEffect, useState } from 'react';
import styles from './missatge.module.scss';
import io from 'socket.io-client';

const socket = io('http://192.168.119.7:3001'); // si cambiamos la ip podemos hablar desde otros pcs

export default function Missatges() {
    const [missatge, setMissatge] = useState(''); // mensaje 
    const [missatges, setMissatges] = useState([]); // toso los mensajes
    const [nomUsuari, setNomUsuari] = useState(''); // nombre user

    useEffect(() => {
        socket.on('mensaje', (dades) => { // esucha el evento mensaje, y añade el nuevo mensaje a la lista
            setMissatges((prev) => [...prev, dades]);
        });

        return () => socket.off('mensaje');
    }, []);

    const enviarMissatge = (e) => {
        e.preventDefault();
        if (missatge.trim() === '') return; // controla que no envien nada vacio

        const nouMissatge = { text: missatge, origen: nomUsuari }; // crea un mensaje con un diccionario de mensaje y nombre Usser
        setMissatges((prev) => [...prev, nouMissatge]); // mete el nuevo mensaje
        socket.emit('mensaje', nouMissatge); // envia el mensaje
        setMissatge(''); // limia el mensaje
    };

    const enviarNom = (e) => {
        e.preventDefault();
        if (missatge.trim() === '') return; // controla que no envien nada vacio
        setNomUsuari(missatge.trim()); // pone el nombre del usuario
        setMissatge(''); // limpia el nombre del user
    };

    return (
        <div className={styles.msg}>
            <h2 className={styles.titol}>Missatgeria en temps real</h2>

            {!nomUsuari ? (
                <form onSubmit={enviarNom} className={styles.formulari}> {/* si no hay nombre de usuario enseña el form para poner nombre */}
                    {/* envia el nombre de usuario al cambio */}
                    <input
                        value={missatge}
                        onChange={(e) => setMissatge(e.target.value)} 
                        placeholder="Escriu el teu nom..."
                        className={styles.input}
                    />
                    <button type="submit" className={styles.boto}>
                        Iniciar
                    </button>
                </form>
            ) : (
                <>
                    {/* Enseña una lista de mensajes mediante un map*/}
                    <div className={styles.llistaMissatges}>
                        {/* ensaña el mensaje y de quien es, también pone un estilo o otro dependiendo si lo has recibido o enviado*/}
                        {missatges.map((m, i) => (
                            <div
                                key={i}
                                className={`${styles.missatge} ${m.origen === nomUsuari ? styles.jo : styles.altre
                                    }`}
                            >
                                <strong>{m.origen}:</strong> <span>{m.text}</span>
                            </div>
                        ))}
                    </div>
                    {/* Formulario para enviar y recibir mensajes*/}
                    <form onSubmit={enviarMissatge} className={styles.formulari}>
                        <input
                            value={missatge}
                            onChange={(e) => setMissatge(e.target.value)}
                            placeholder="Escriu un missatge..."
                            className={styles.input}
                        />
                        <button type="submit" className={styles.boto}>
                            Enviar
                        </button>
                    </form>
                </>
            )}
        </div>
    );
}


  

 
