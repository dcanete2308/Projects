create database if not exists frases_canete_didac;
use frases_canete_didac;

create table if not exists tbl_themes (
    id int primary key auto_increment,
    nom varchar(100) not null,
    descripcio text not null
);

create table if not exists tbl_authors (
    id int primary key auto_increment,
    nom varchar(100) not null,
    descripcio text not null,
    url varchar(100) not null
);

create table if not exists tbl_phrases (
    id int primary key auto_increment,
    texto text not null,
    author_id int not null,
    theme_id int not null,
    created_at datetime not null,
    updated_at datetime not null,
    foreign key (author_id) references tbl_authors(id) 
        on delete cascade 
        on update cascade,
    foreign key (theme_id) references tbl_themes(id) 
        on delete cascade 
        on update cascade
);