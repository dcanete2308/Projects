import express from 'express'
import { crearNotas, llistarNotas, eliminarNotas, modificarNotas } from '../controllers/note.controller.js'
import authMiddleware from '../middelewares/authMiddleware.js';
const router = express.Router()

// añade un middleware para comprobar que tiene un token el user (por lo tanto esta loggeado)
router.post('/note/crear', authMiddleware, crearNotas);
router.get('/note/llistar/:userId',  authMiddleware, llistarNotas);
router.delete('/note/eliminar/:noteId',  authMiddleware, eliminarNotas);
router.put('/note/modificar/:noteId', authMiddleware, modificarNotas);

export default router;