import React from "react";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import Error from "./components/Error-404/Error";
import Home from "./components/Home/Home";
import Register from "./components/Register/Register";
import Login from "./components/Login/Login";
import Profile from "./components/Profile/Profile";
import Note from "./components/Note/Note";
import Header from "./components/Header/Header";
import Footer from "./components/Footer/Footer";
import { AuthProvider } from "./components/Contexto/contexto";
import SignOut from "./components/SignOut/SignOut";

function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Header />
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/profile" element={<Profile />} />
          <Route path="/note" element={<Note />} />
          <Route path="/signout" element={<SignOut />} />
          <Route path="*" element={<Error />} />
        </Routes>
        <Footer></Footer>
      </AuthProvider>
    </BrowserRouter>
  );
}

export default App;