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
function completar($usuario_id){
    $pdo = conectar();
    $sent = $pdo->prepare("SELECT count(usuario) FROM usuarios WHERE id = :id AND (apellido1 is NULL or apellido2 is NULL or ciudad is NULL or fecha_nacimiento is NULL )");
    $sent->execute([':id'=>$usuario_id]);
    $resultado = $sent->fetchColumn();
    if ($resultado == 0){
        $sent = $pdo->prepare("UPDATE usuarios SET completo = 'true' WHERE id = :usuario_id");
        $sent->execute([':usuario_id'=>$usuario_id]);
    }
}
function comprobar_completo($usuario_id){
    $pdo = conectar();
    $sent = $pdo->prepare("SELECT completo FROM usuarios WHERE id = :id");
    $sent->execute([':id'=>$usuario_id]);
    $resultado = $sent->fetchColumn();
    return $resultado;
}
function obetener_datos_usuarios($usuario_id){
    $pdo = conectar();
    $sent = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $sent->execute([':id'=>$usuario_id]);
    $resultado = $sent->fetch();
    return $resultado;
}
function obetener_datos_usuarios_etiquetas($usuario_id){
    $pdo = conectar();
    $sent = $pdo->prepare("SELECT e.nombre FROM usuarios_etiquetas ue JOIN etiquetas e ON e.id = ue.etiqueta_id WHERE usuario_id = :id");
    $sent->execute([':id'=>$usuario_id]);
    $intereses = $sent->fetchAll();
    return $intereses;
}
function obtener_nombre_etiqueta($etiqueta_id){
    $pdo = conectar();
    $sent = $pdo->prepare("SELECT nombre FROM etiquetas WHERE id = :etiqueta_id");
    $sent->execute([':id'=>$etiqueta_id]);
    $etiquetas = $sent->fetch();
    return $etiquetas;
}
/*function completar_usuarios_etiquetas(){
    $pdo = conectar();
}/*
/*function aux(){
    $numero1 = $usuario_id;
    $array_etiquetas = $etiquetas;
    nuevo_array = [];
    foreach ($array_etiquetas as $eti){
        $nuevo_array[] = [$numero, $eti]; 
    }
}*/