import User from "../db/models/user.schema.js";
import bcrypt from "bcrypt";
import jwt from "jsonwebtoken";
// import { userSchema } from '../validation/userSchema';

/**
 * se actualiza el user
 * @param {*} req request
 * @param {*} res respuesta de la bd
 * @returns
 */
const updateUser = async (req, res) => {
  try {
    const { userId } = req.params; // recoge los valores por el parametro
    const { name, surname, email, password } = req.body; // recoge los valores por el body

    if (!name && !surname && !email && !password) { // mira si no todos no estan vacios
      return res.status(400).json({ message: "Debe actualizar al menos un campo" });
    }

    const updates = {}; 
    if (name) {
      updates.name = name;
    }

    if (surname) {
      updates.surname = surname;
    } 

    if (email) {
      updates.email = email;
    } 
    if (password) {
      updates.password = await bcrypt.hash(password, 10); // encripta la contraseña nueva
    }

    const updatedUser = await User.findByIdAndUpdate( //busca por id y lo actualiza
      userId,
      { $set: updates },
      { new: true, runValidators: true }
    );

    if (!updatedUser) {
      return res.status(404).json({ message: "No se ha podido actualizar el usuario" });
    }

    res.status(200).json(updatedUser);
  } catch (error) {
    res.status(500).json({ message: "Error al actualizar el usuario", error: error.message });
  }
};

/**
 * se loggea el user
 * @param {*} req request
 * @param {*} res respuesta de la bd
 * @returns
 */
const loginUser = async (req, res) => {
  try {
    const { email, password } = req.body; // recoge los valores por el body
    const user = await User.findOne({ email: email }).exec(); //busca por el email y con el exc lo ejecuta

    if (!user) {
      return res.status(404).json({ message: "Usuario no registrado" });
    }

    const contraCorrecta = await bcrypt.compare(password, user.password); // mira si esta correcto el usuario con el bcrypt ya que esta encriptada
    if (!contraCorrecta) {
      return res.status(401).json({ message: "Contraseña incorrecta" });
    }

    // datos que irá dentro de la cookie
    const profile = {
      _id: user._id,
      name: user.name,
      surname: user.surname,
      email: user.email,
    };

    // crear token con perfil
    const token = jwt.sign(profile, process.env.JWT_SECRET_KEY, {
      expiresIn: "1h",
    });

    res.cookie("token_user", token, {
      httpOnly: true,
      maxAge: 3600000,
    });

    return res.status(200).json({ message: "Login exitoso" });
  } catch (error) {
    console.error("Error en loginUser:", error);
    return res.status(500).json({ message: "Error en el servidor: " + error });
  }
};

/**
 * cierra la sesión del usuario
 * @param {*} req request
 * @param {*} res respuesta
 * @returns
 */
const logoutUser = (req, res) => {
  try {
    res.clearCookie("token_user", {
      httpOnly: true,
      secure: process.env.NODE_ENV === "production",
      sameSite: "strict",
      expires: new Date(0),
      domain: process.env.COOKIE_DOMAIN || "localhost",
    });

    return res.status(200).json({ message: "Logout exitoso" });
  } catch (error) {
    return res.status(500).json({ message: "Error en el servidor: " + error });
  }
};

/**
 * elimina el user
 * @param {*} req request
 * @param {*} res respuesta de la bd
 * @returns
 */
const deleteUser = async (req, res) => {
  try {
    const { userId } = req.params; // coge el id del usuario 

    if (!userId) {
      return res
        .status(400)
        .json({ message: "No se ha pasado el id del usuario" });
    }

    const userDeleted = await User.findByIdAndDelete(userId); // busca el id del usuario y lo elimina si lo encuentra

    if (!userDeleted) {
      return res.status(404).json({ message: "Usuario no encontrado" });
    }

    return res.status(200).json({ message: "Usuario eliminado correctamente" });
  } catch (error) {
    return res.status(500).json({ message: "Error en el servidor: " + error });
  }
};

/**
 * registra un nuevo usuario
 * @param {*} req request
 * @param {*} res respuesta de la bd
 * @returns
 */
const registerUser = async (req, res) => {
  const { name, surname, email, password } = req.body; // recoge por el body los datos

  //comprobar si existe
  const usuarioRegistrado = await User.findOne({ email: email });
  if (usuarioRegistrado) {
    return res.status(409).json({ message: "L'usuari ja existeix" });
  }

  //cifrar la contra con hash
  const contraCifrada = await bcrypt.hash(password, 10);

  const newUser = new User({ name, surname, email, password: contraCifrada }); // crea un nuevo usuario
  await newUser.save();

  res.status(200).json();
};

const getUser = async (req, res) => {
  try {
    const token = req.cookies.token_user; // Leer la cookie

    if (!token) {
      return res.status(401).json({ message: "No autorizado" });
    }

    const userProfile = jwt.verify(token, process.env.JWT_SECRET_KEY); // Decodificar el token
    return res.json(userProfile); // Devolver datos del usuario
  } catch (error) {
    return res.status(401).json({ message: "Token inválido" });
  }
};

export { loginUser, registerUser, deleteUser, updateUser, getUser, logoutUser};
