import React, { useContext } from "react";
import { NavLink } from "react-router-dom";
import styles from '../MainMenu/mainMenu.module.scss';
import SignOut from "../SignOut/SignOut";
import { AuthContext } from "../Contexto/contexto";

function MainMenu() {
  const { user } = useContext(AuthContext); // gracias al contexto mira si esta definifdoi el usurio y si esta cambia el header
  console.log("Current user:", user);
  return (
    <nav className={styles.mainMenu}>
      <ul>
        <li>
          <NavLink to="/" end>
            HOME
          </NavLink>
        </li>
        {user ? (
          <>
            <li>
              <NavLink to="/profile">PROFILE</NavLink>
            </li>
            <li>
              <NavLink to="/note">NOTES</NavLink>
            </li>
            <li>
              <SignOut></SignOut>
            </li>
          </>
        ) : (
          <>
            <li>
              <NavLink to="/login">LOGIN</NavLink>
            </li>
            <li>
              <NavLink to="/register">REGISTER</NavLink>
            </li>
          </>
        )}
      </ul>
      <form method="GET">
        <input type="text" placeholder="Search" />
      </form>
    </nav>
  );
}

export default MainMenu;
