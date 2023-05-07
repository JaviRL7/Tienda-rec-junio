<?php

use App\Tablas\Usuario;

function conectar()
{
    return new \PDO('pgsql:host=localhost,dbname=tienda', 'tienda', 'tienda');
}

function hh($x)
{
    return htmlspecialchars($x ?? '', ENT_QUOTES | ENT_SUBSTITUTE);
}

function dinero($s)
{
    return number_format($s, 2, ',', ' ') . ' €';
}

function obtener_get($par)
{
    return obtener_parametro($par, $_GET);
}

function obtener_post($par)
{
    return obtener_parametro($par, $_POST);
}

function obtener_parametro($par, $array)
{
    return isset($array[$par]) ? trim($array[$par]) : null;
}

function volver()
{
    header('Location: /index.php');
}

function carrito()
{
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = serialize(new \App\Generico\Carrito());
    }

    return $_SESSION['carrito'];
}

function carrito_vacio()
{
    $carrito = unserialize(carrito());

    return $carrito->vacio();
}

function volver_admin()
{
    header("Location: /admin/");
}

function redirigir_login()
{
    header('Location: /login.php');
}
function obtener_etiquetas(){
    $pdo = conectar();
    $sent = $pdo->query('SELECT * FROM etiquetas ORDER BY nombre');
    $etiquetas = $sent->fetchAll();
    return $etiquetas;
}
function completar_usuarios($apellido1, $apellido2, $fecha_nacimiento, $ciudad, $usuario_id){
    $pdo = conectar();
    $sent = $pdo->prepare("UPDATE usuarios SET apellido1 = :apellido1, apellido2 = :apellido2, fecha_nacimiento = :fecha_nacimiento, ciudad = :ciudad WHERE id = :usuario_id") ;
    $sent->execute([':apellido1' => $apellido1, ':apellido2' => $apellido2, ':ciudad' => $ciudad, ':usuario_id' => $usuario_id, ':fecha_nacimiento' => $fecha_nacimiento ]);
}

function completar_usuarios_etiquetas(){
    $pdo = conectar();
}
function aux(){
    $numero1 = $usuario_id;
    $array_etiquetas = $etiquetas;
    nuevo_array = [];
    foreach ($array_etiquetas as $eti){
        $nuevo_array[] = [$numero, $eti];
    }
}