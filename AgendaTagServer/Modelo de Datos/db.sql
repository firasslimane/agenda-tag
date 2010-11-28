CREATE TABLE USUARIO (
"id_usuario" SERIAL,
"username" VARCHAR(30) NOT NULL DEFAULT 'NULL' ,
"password" VARCHAR(32) DEFAULT NULL ,
"nombre" VARCHAR(60) DEFAULT NULL ,
"email" VARCHAR(60) DEFAULT NULL ,
"nacimiento" DATE DEFAULT NULL ,
"edad" INTEGER DEFAULT NULL ,
"sexo" CHAR(1) DEFAULT NULL ,
"avatar" VARCHAR(40) DEFAULT NULL ,
"color" VARCHAR(8) DEFAULT NULL ,
"estado" VARCHAR(20) DEFAULT NULL ,
"radio_visibilidad" INTEGER DEFAULT NULL ,
"precision" INTEGER DEFAULT NULL ,
"usuario_twitter" VARCHAR(20) DEFAULT NULL ,
"password_twitter" VARCHAR(20) DEFAULT NULL ,
PRIMARY KEY ("id_usuario")
);

CREATE TABLE CONTACTO (
"id_usuario" INTEGER NOT NULL DEFAULT NULL ,
"id_contacto" INTEGER NOT NULL DEFAULT NULL ,
"id_relacion" INTEGER NOT NULL DEFAULT NULL ,
PRIMARY KEY ("id_usuario", "id_contacto")
);

CREATE TABLE INTERES (
"id_interes" SERIAL,
"descripcion" VARCHAR(40) NOT NULL DEFAULT 'NULL' ,
PRIMARY KEY ("id_interes")
);

CREATE TABLE USUARIO_x_INTERES (
"id_usuario" INTEGER NOT NULL DEFAULT NULL ,
"id_interes" INTEGER NOT NULL DEFAULT NULL ,
"nivel" INTEGER NOT NULL DEFAULT NULL ,
PRIMARY KEY ("id_usuario", "id_interes")
);

CREATE TABLE PREFERENCIA (
"id_preferencia" SERIAL,
"descripcion" VARCHAR(40) NOT NULL DEFAULT 'NULL' ,
PRIMARY KEY ("id_preferencia")
);

CREATE TABLE RELACION (
"id_relacion" SERIAL,
"descripcion" VARCHAR(40) NOT NULL DEFAULT 'NULL' ,
PRIMARY KEY ("id_relacion")
);

CREATE TABLE USUARIO_x_RELACION (
"id_usuario" INTEGER NOT NULL DEFAULT NULL ,
"id_relacion" INTEGER NOT NULL DEFAULT NULL ,
"id_permiso" INTEGER NOT NULL DEFAULT NULL ,
"acceso" INTEGER DEFAULT NULL ,
PRIMARY KEY ("id_usuario", "id_relacion", "id_permiso")
);

CREATE TABLE TRAYECTO (
"id_usuario" INTEGER NOT NULL DEFAULT NULL ,
"id_posicion" INTEGER NOT NULL DEFAULT NULL ,
"tiempo" TIMESTAMP DEFAULT NULL ,
PRIMARY KEY ("id_usuario", "id_posicion","tiempo")
);

CREATE TABLE POSICION (
"id_posicion" SERIAL,
"latitud" INTEGER NOT NULL DEFAULT NULL ,
"longitud" INTEGER NOT NULL DEFAULT NULL ,
PRIMARY KEY ("id_posicion")
);

CREATE TABLE USUARIO_x_PREFERENCIA (
"id_usuario" INTEGER NOT NULL DEFAULT NULL ,
"id_preferencia" INTEGER NOT NULL DEFAULT NULL ,
"valor" INTEGER DEFAULT NULL ,
PRIMARY KEY ("id_usuario", "id_preferencia")
);

CREATE TABLE PERMISO (
"id_permiso" SERIAL,
"descripcion" VARCHAR(40) NOT NULL DEFAULT 'NULL' ,
PRIMARY KEY ("id_permiso")
);

CREATE TABLE ONLINE_USERS (
"id_usuario" INTEGER NOT NULL DEFAULT NULL ,
"username" VARCHAR(30) NOT NULL DEFAULT 'NULL' ,
"latitud" DECIMAL NOT NULL DEFAULT NULL ,
"longitud" DECIMAL NOT NULL DEFAULT NULL ,
"radio" INTEGER DEFAULT NULL ,
"ip_addr" CHAR(15) DEFAULT NULL ,
"estado" VARCHAR(20) DEFAULT NULL ,
"avatar" VARCHAR(40) DEFAULT NULL ,
"color" VARCHAR(8) DEFAULT NULL ,
"mensaje" VARCHAR(140) DEFAULT NULL ,
PRIMARY KEY ("id_usuario")
);

CREATE TABLE LUGAR (
"id_lugar" SERIAL,
"nombre" VARCHAR(60) DEFAULT NULL ,
"descripcion" VARCHAR(200) DEFAULT NULL ,
"vertice_sup_izq" INTEGER DEFAULT NULL ,
"vertice_sup_der" INTEGER DEFAULT NULL ,
"vertice_inf_izq" INTEGER DEFAULT NULL ,
"vertice_inf_der" INTEGER DEFAULT NULL ,
"contador" INTEGER DEFAULT NULL ,
PRIMARY KEY ("id_lugar")
);


ALTER TABLE USUARIO_x_INTERES ADD FOREIGN KEY ("id_usuario") REFERENCES USUARIO ("id_usuario") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE USUARIO_x_INTERES ADD FOREIGN KEY ("id_interes") REFERENCES INTERES ("id_interes") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE USUARIO_x_RELACION ADD FOREIGN KEY ("id_usuario") REFERENCES USUARIO ("id_usuario") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE USUARIO_x_RELACION ADD FOREIGN KEY ("id_relacion") REFERENCES RELACION ("id_relacion") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE USUARIO_x_RELACION ADD FOREIGN KEY ("id_permiso") REFERENCES PERMISO ("id_permiso") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE TRAYECTO ADD FOREIGN KEY ("id_usuario") REFERENCES USUARIO ("id_usuario") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE TRAYECTO ADD FOREIGN KEY ("id_posicion") REFERENCES POSICION ("id_posicion") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE USUARIO_x_PREFERENCIA ADD FOREIGN KEY ("id_usuario") REFERENCES USUARIO ("id_usuario") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE USUARIO_x_PREFERENCIA ADD FOREIGN KEY ("id_preferencia") REFERENCES PREFERENCIA ("id_preferencia") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ONLINE_USERS ADD FOREIGN KEY ("id_usuario") REFERENCES USUARIO ("id_usuario") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE CONTACTO ADD FOREIGN KEY ("id_usuario") REFERENCES USUARIO ("id_usuario") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE CONTACTO ADD FOREIGN KEY ("id_contacto") REFERENCES USUARIO ("id_usuario") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE CONTACTO ADD FOREIGN KEY ("id_relacion") REFERENCES RELACION ("id_relacion") ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE LUGAR ADD FOREIGN KEY ("id_lugar") REFERENCES POSICION ("id_posicion") ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE LUGAR ADD FOREIGN KEY ("vertice_sup_izq") REFERENCES POSICION ("id_posicion") ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE LUGAR ADD FOREIGN KEY ("vertice_sup_der") REFERENCES POSICION ("id_posicion") ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE LUGAR ADD FOREIGN KEY ("vertice_inf_izq") REFERENCES POSICION ("id_posicion") ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE LUGAR ADD FOREIGN KEY ("vertice_inf_der") REFERENCES POSICION ("id_posicion") ON DELETE CASCADE ON UPDATE CASCADE; 