import React from "react";
import { NavLink } from "react-router-dom";
import styles from '../Header/header.module.scss';
import MainMenu from "../MainMenu/MainMenu";

function Header() {
  return (
    <div className={styles.header}>
      <h2 className="title">SOFTGPL</h2>
      <MainMenu></MainMenu>
    </div>
  );
}

export default Header;
