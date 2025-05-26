CREATE DATABASE IF NOT EXISTS myweb;

use myweb;

create table if not exists registro (
	id int auto_increment primary key,
	usuario varchar(100) not null,
    contrasenya varchar(100) not null,
    repetirContrasenya varchar(100) not null,
    dni varchar(100) not null,
    identificacion varchar(100) not null,
    nombre varchar(100) not null,
    apellido varchar(100) not null,
    fechaNacimiento varchar(100) not null,
    sexo varchar(100) default null,  	
    direccion varchar(100) default null,
    provincia varchar(100) default null,
    codigoPostal varchar(6) default null, 
    telefono varchar(9) default null,
    fotoPerfil varchar(100) default null,
    autorizado int(1) default 0
    -- 0->Pendiente\1->Autorizado\2->Admin'
);

create table if not exists eventos (
    id int auto_increment primary key, 
    nombre_evento varchar(255) not null unique, 
    fecha_inicio date not null,          
    hora_inicio time,          
    fecha_fin date not null,             
    hora_fin time,  
    etiqueta text,
    descripcion text                 
);

select * from eventos;
select * from registro;


/*********************CREATE USERS*********************/

create user 'usr_consulta'@'localhost' identified by '2025@Thos';
grant select on myweb.* to 'usr_consulta'@'localhost';

create user 'usr_generic'@'localhost' identified by '2025@Thos';
grant insert, update, delete on myweb.* to 'usr_consulta'@'localhost';
SELECT * FROM registro;
