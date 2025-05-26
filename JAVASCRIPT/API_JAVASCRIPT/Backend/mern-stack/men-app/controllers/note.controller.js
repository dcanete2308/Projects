import Note from "../db/models/note.schema.js";
import User from '../db/models/user.schema.js'; 

/**
 * crea nuevas notas
 * @param {*} req request
 * @param {*} res respuesta de la bd
 * @returns 
 */
const crearNotas = async (req, res) => {
  try {
    const { title, text, status = "Draft" } = req.body; // rocoge los valores que el enetran por el body
    const author = req.user._id; // cogemos los datos del usuario a traves de la private route

    if (!title || !text || !status || !author) { // mira si existen
      return res.status(400).json({ message: "No puede estar vacío" });
    }

    if (/^\d/.test(title)) { // mira si el título empieza con un número
      return res.status(400).json({ message: "El título no puede empezar por un número" });
    }

    if (!["Published", "Draft", "Archived"].includes(status)) { //comprueba que el status es uno de los escogidos
      return res.status(400).json({ message: "Estado no válido" });
    }

    const user = await User.findById(author);  // busca el usuario por el id para asignarlo al creador de la nota
    if (!user) {
      return res.status(400).json({ error: "El autor no se ha podido encontrar en la BD" });
    }

    const nota = new Note({ title, text, status, author: user._id }); // crea y guarda una nueva nota
    await nota.save();

    res.status(201).json(nota);
  } catch (error) {
    res.status(500).json({ message: "Error al crear la nota", error: error.message });
  }
};

/**
 * enseña las notas
 * @param {*} req request
 * @param {*} res respuesta de la bd
 * @returns 
 */
const llistarNotas = async (req, res) => {
  try {
    const userId = req.user._id; // cogemos los datos del usuario a través del private route
    if (!userId) { 
      return res
        .status(400)
        .json({ message: "El author no puede estar vacio" });
    }

    const notes = await Note.find({ author: userId }).sort({ updatedAt: -1 }); // busca las notas que tiene el id en base al author y lo ordena por fecha
    res.status(200).json(notes);
  } catch (error) {
    res
      .status(500)
      .json({ message: "Error al listar las notas", error: error.message });
  }
};

/**
 * elimina las notas
 * @param {*} req request
 * @param {*} res respuesta de la bd
 * @returns 
 */
const eliminarNotas = async (req, res) => {
  try {
    const noteId = req.params.noteId;  // coge de los paranetros el id de la nota
    if (!noteId) {// si no existe da error
      return res.status(400).json({ message: "El ID de la nota no puede estar vacío" });
    }
    const notaBorrada = await Note.findByIdAndDelete(noteId);  // método que encuentra por id y lo elimina
    if (!notaBorrada) {
      return res.status(404).json({ message: "Nota no encontrada o no pertenece al usuario" });
    }
    res.status(200).json({ message: "Nota eliminada correctamente", notaBorrada });
  } catch (error) {
    res.status(500).json({ message: "Error al eliminar la nota", error: error.message });
  }
};

/**
 * modifica las notas
 * @param {*} req request
 * @param {*} res respuesta de la bd
 * @returns 
 */
const modificarNotas = async (req, res) => {
  try {
    const { noteId } = req.params; //cogemos valores que entran por el request 
    const { title, text, status } = req.body; //cogemos valores que entran por el body 

    // si todos están vacio salta error
    if (!title && !text && !status) {
      return res.status(400).json({ message: "Debe actualizar al menos un campo" });
    }

    // si el status no encaja salta error
    if (status && !["Published", "Draft", "Archived"].includes(status)) {
      return res.status(400).json({ message: "No está entre las opciones [Published, Draft, Archived]" });
    }

    
    const updatedNote = await Note.findByIdAndUpdate( //busca la nota por el id y cambia los valores 
      noteId,
      { $set: { title, text, status } }, // solo actualiza los datos especificos
      { new: true, runValidators: true }
    );

    if (!updatedNote) { //si no se puede actualizar salta error
      return res.status(404).json({ message: "No se ha podido actualizar la nota" });
    }

    res.status(200).json(updatedNote);
  } catch (error) {
    res.status(500).json({ message: "Error al actualizar la nota", error: error.message });
  }
};

export { crearNotas, llistarNotas, eliminarNotas, modificarNotas };
