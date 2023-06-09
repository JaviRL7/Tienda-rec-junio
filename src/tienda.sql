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
    apellido1 varchar(255),
    apellido2 varchar(255),
    password varchar(255) NOT NULL,
    fecha_nacimiento DATE,
    ciudad VARCHAR(100),
    validado bool         NOT NULL,
    completo bool         NOT NULL
);

DROP TABLE IF EXISTS facturas CASCADE;

CREATE TABLE facturas (
    id         bigserial  PRIMARY KEY,
    created_at timestamp  NOT NULL DEFAULT localtimestamp(0),
    usuario_id bigint NOT NULL REFERENCES usuarios (id),
    cupon_id   bigint REFERENCES cupones (id)
);

DROP TABLE IF EXISTS articulos_facturas CASCADE;

CREATE TABLE articulos_facturas (
    articulo_id bigint NOT NULL REFERENCES articulos (id),
    factura_id  bigint NOT NULL REFERENCES facturas (id),
    cantidad    int    NOT NULL,
    PRIMARY KEY (articulo_id, factura_id)
);

DROP TABLE IF EXISTS usuarios_etiquetas CASCADE;

CREATE TABLE usuarios_etiquetas (
    usuario_id bigint NOT NULL REFERENCES usuarios (id),
    etiqueta_id  bigint NOT NULL REFERENCES etiquetas (id),
    PRIMARY KEY (usuario_id, etiqueta_id)
);

DROP TABLE IF EXISTS ofertas CASCADE;

CREATE TABLE ofertas (
    id bigserial PRIMARY KEY,  
    nombre varchar(25)
);

DROP TABLE IF EXISTS articulos_ofertas CASCADE;

CREATE TABLE articulos_ofertas(
    articulo_id bigint NOT NULL REFERENCES articulos(id),
    oferta_id bigint NOT NULL REFERENCES ofertas(id),
    fecha_caducidad DATE NOT NULL,
    PRIMARY KEY (articulo_id, oferta_id)
);

DROP table if EXISTS cupones CASCADE;
CREATE TABLE cupones(
    id bigserial PRIMARY KEY,
    nombre varchar(25) NOT NULL,
    descuento bigint NOT NULL
);

DROP table if EXISTS usuarios_cupones CASCADE;
CREATE TABLE usuarios_cupones(
    usuario_id bigint NOT NULL REFERENCES usuarios(id),
    cupon_id bigint NOT NULL REFERENCES cupones(id),
    cantidad bigint NOT NULL,
    PRIMARY KEY (usuario_id, cupon_id)
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

INSERT INTO usuarios (usuario, password, validado, completo)
    VALUES ('admin', crypt('admin', gen_salt('bf', 10)), true, false),
           ('pepe', crypt('pepe', gen_salt('bf', 10)), false, false),
           ('juan', crypt('juan', gen_salt('bf', 10)), true, false),
           ('jose', crypt('jose', gen_salt('bf', 10)), true, false);

INSERT INTO etiquetas (nombre)
    VALUES ('Cafe'),
            ('Informacion'),
            ('Entretenimiento'),
            ('Lectura'),
            ('Dulce'),
            ('Descuento');

INSERT INTO ofertas (nombre)
    VALUES ('2x1'),
            ('- 25%'),
            ('- 15%');

INSERT INTO articulos_etiquetas (articulo_id, etiqueta_id)
    VALUES (1,2),
            (2,2),
            (2,3),
            (3,1),
            (4,5),
            (3,2),
            (2,1);



INSERT INTO articulos_ofertas (articulo_id, oferta_id, fecha_caducidad)
    VALUES (1,2,'15/08/2023'),
            (2,2,'14/09/2023'),
            (4,1,'14/09/2023'),
            (5,3,'14/09/2023'),
            (3,2,'18/08/2023');

INSERT INTO cupones(nombre, descuento)
    VALUES ('Descuento10', 10),
            ('Descuento20', 20),
            ('DescuentoFnatic', 50);