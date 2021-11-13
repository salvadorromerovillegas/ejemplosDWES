CREATE TABLE usuarios (
	id bigint NOT NULL auto_increment, 
	nombre VARCHAR(35) not null,
	apellidos VARCHAR(35) not null,
	email VARCHAR(254) not null, /*email de usuario*/
	password VARCHAR(64) not null, /*password encriptada 64 bytes --> 512 bits (máximo de SHA2) */
	creacion TIMESTAMP not null default now(), /*Fecha de creación */
	ultimo_acceso TIMESTAMP,  /*Último acceso al sistema*/
    habilitado BOOL default true, /*usuario habilitado o deshabilitado*/
    constraint usuarios_pk primary key (id),
    constraint usuarios_email unique (email)
);