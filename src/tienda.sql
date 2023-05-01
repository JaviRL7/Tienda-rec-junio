DROP TABLE IF EXISTS articulos CASCADE;

CREATE TABLE articulos (
    id          bigserial     PRIMARY KEY,
    codigo      varchar(13)   NOT NULL UNIQUE,
    descripcion varchar(255)  NOT NULL,
    precio      numeric(7, 2) NOT NULL,
    stock       int           NOT NULL
);
DROP TABLE IF EXISTS etiquetas CASCADE;

CREATE TABLE etiquetas (
    id          bigserial   PRIMARY KEY,
    nombre      varchar(25) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS articulos_etiquetas CASCADE;

CREATE TABLE articulos_etiquetas (
    articulo_id bigint NOT NULL REFERENCES articulos (id),
    etiqueta_id  bigint NOT NULL REFERENCES etiquetas (id),
    PRIMARY KEY (articulo_id, etiqueta_id)
);

DROP TABLE IF EXISTS categorias CASCADE;

CREATE TABLE categorias (
    id          bigserial   PRIMARY KEY,
    nombre      varchar(25) NOT NULL UNIQUE
);

INSERT INTO categorias (nombre)
    VALUES ('Electrodomesticos'),
            ('Alimentos'),
            ('Otros');



DROP TABLE IF EXISTS articulos_usuarios CASCADE;

CREATE TABLE articulos_usuarios (
    articulo_id bigint NOT NULL REFERENCES articulos (id),
    usuario_id bigint NOT NULL REFERENCES usuarios (id),
    nota       int    NOT NULL,
    PRIMARY KEY (articulo_id, usuario_id)
);

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios (
    id       bigserial    PRIMARY KEY,
    usuario  varchar(255) NOT NULL UNIQUE,
    password varchar(255) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    hobbies VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    validado bool         NOT NULL
);

DROP TABLE IF EXISTS facturas CASCADE;

CREATE TABLE facturas (
    id         bigserial  PRIMARY KEY,
    created_at timestamp  NOT NULL DEFAULT localtimestamp(0),
    usuario_id bigint NOT NULL REFERENCES usuarios (id)
);

DROP TABLE IF EXISTS articulos_facturas CASCADE;

CREATE TABLE articulos_facturas (
    articulo_id bigint NOT NULL REFERENCES articulos (id),
    factura_id  bigint NOT NULL REFERENCES facturas (id),
    cantidad    int    NOT NULL,
    PRIMARY KEY (articulo_id, factura_id)
);

-- Carga inicial de datos de prueba:


INSERT INTO articulos (codigo, descripcion, precio, stock)
    VALUES ('18273892389', 'Yogur piña', 200.50, 4),
           ('83745828273', 'Tigretón', 50.10, 2),
           ('51736128495', 'Disco duro SSD 500 GB', 150.30, 0),
           ('83746828273', 'Kinder Bueno', 50.10, 3),
           ('51786128435', 'Camara', 150.30, 5),
           ('83745228673', 'Manga Oshi no Ko', 50.10, 8),
           ('83741111113', 'Puerta para laura', 50.10, 8),
           ('83745244673', 'Manga Berserk', 50.10, 8),
           ('83745444673', 'Manga Kaguya', 50.10, 8),
           ('51786198495', 'Pelicula Naruto', 150.30, 1);

INSERT INTO usuarios (usuario, password, fecha_nacimiento, ciudad, validado)
    VALUES ('admin', crypt('admin', gen_salt('bf', 10)),'05/12/1998', 'Sanlucar de Barrameda', true),
           ('pepe', crypt('pepe', gen_salt('bf', 10)),'03/1/1997', 'Chipiona', false),
           ('juan', crypt('juan', gen_salt('bf', 10)), '14/7/2000', 'Madrid', true),
           ('jose', crypt('jose', gen_salt('bf', 10)), '31/12/1999', 'Malaga', true);

INSERT INTO etiquetas (nombre)
    VALUES ('Cafe'),
            ('Informacion'),
            ('Entretenimiento'),
            ('Lectura'),
            ('Dulce'),
            ('Descuento');

INSERT INTO articulos_etiquetas (articulo_id, etiqueta_id)
    VALUES (1,2),
            (2,2),
            (2,3),
            (3,1),
            (4,5),
            (3,2),
            (2,1);