import jwt from "jsonwebtoken";

/**
 * comprueba que tiene un token iniciado el usuario
 * @param {*} req request
 * @param {*} res respuesta
 * @param {*} next 
 * @returns 
 */
const authMiddleware = (req, res, next) => {
    const token = req.cookies.token_user; // coge el tokem que tiene el usuario

    if (!token) { // si no existe salta un error
        return res.status(401).json({ message: "Acceso denegado, token no encontrado" });
    }

    try {
        const decoded = jwt.verify(token, process.env.JWT_SECRET_KEY); // deocdifica el token 
        req.user = decoded; 
        next(); 
    } catch (error) {
        return res.status(401).json({ message: "Token inválido o expirado" });
    }
};

export default authMiddleware;
