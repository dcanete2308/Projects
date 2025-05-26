import express from 'express'
import { deleteUser, loginUser, registerUser, updateUser, getUser, logoutUser} from '../controllers/users.controller.js'
import authMiddleware from '../middelewares/authMiddleware.js';

const router = express.Router()

router.post('/users/login', loginUser);
router.post('/users/register', registerUser);
router.get('/users/getUser', getUser);
router.post('/users/logout', logoutUser);


// añade un middleware para comprobar que tiene un token el user (por lo tanto esta loggeado)
router.delete('/users/delete/:userId', authMiddleware, deleteUser);
router.put('/users/update/:userId', authMiddleware, updateUser);


export default router