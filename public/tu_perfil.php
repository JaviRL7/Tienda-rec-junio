<?php

use App\Tablas\Usuario;

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$pdo = conectar();
$etiquetas = obtener_etiquetas();


$nombre_completo = obtener_get('nombre_completo');
$fecha_nacimiento = obtener_get('fecha_nacimiento');
$ciudad = obtener_get('ciudad');
$usuario = \App\Tablas\Usuario::logueado();
$usuario_id = $usuario->id;
$intereses = array();
foreach($etiquetas as $etiqueta){
    $intereses[] = obtener_get($etiqueta['nombre']);
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Tus Notas</title>
</head>

<body>
    <div class="container mx-auto">
        <?php require_once '../src/_menu.php' ?>
    </div>
    <div class="max-w-md mx-auto mt-4">
        <?= hh($nombre_completo) ?>
        <?= hh($ciudad) ?>
        <?= hh($fecha_nacimiento) ?>
        
        <ul>
            <?php foreach ($intereses as $interes) : ?>
                <li><?= hh($interes) ?></li>
            <?php endforeach ?>
        </ul>
        <h3 class="text-center text-2xl font-bold mb-4">Tus datos</h3>
        <form method="GET" action="">
            <div class="mb-3">
                <label for="nombre_completo" class="block font-medium text-gray-700">Nombre Completo:</label>
                <input type="text" name="nombre_completo" id="nombre_completo" required class="form-input mt-1 block w-full rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-3">
                <label for="fecha_nacimiento" class="block font-medium text-gray-700">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required class="form-input mt-1 block w-full rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class=" mb-3">
                <label for="ciudad" class="block font-medium text-gray-700">Ciudad:</label>
                <input type="text" name="ciudad" id="ciudad" required class="form-input mt-1 block w-full rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-3">
                <fieldset>
                    <legend class="block mb-2 font-semibold text-lg text-gray-700">Marca tus intereses:</legend>
                    <?php foreach ($etiquetas as $etiqueta) : ?>
                        <input class="form-checkbox h-5 w-5 text-gray-600" type="checkbox" name="<?= hh($etiqueta['nombre']) ?>" id="<?= hh($etiqueta['nombre']) ?>" value="<?= hh($etiqueta['nombre']) ?>">
                        <label class="inline-flex items-center" for="<?= hh($etiqueta['nombre']) ?>"><?= hh($etiqueta['nombre']) ?></label>
                        <br>
                    <?php endforeach ?>
                    <br>
                </fieldset>
            </div>
            <button type="submit" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enviar</button>
        </form>
        <?php 
        if (isset($nombre_completo) && $nombre_completo != ""){
            $array_nombre = explode(" ", $nombre_completo);
        }
        if ( count($array_nombre) == 3 && isset($array_nombre) && isset($ciudad) && isset($fecha_nacimiento)){
            $apellido1 = $array_nombre[1];
            $apellido2 = $array_nombre[2];
            $nueva_fecha = intval($fecha_nacimiento);
            $sent = $pdo->prepare("UPDATE usuarios SET apellido1 = :apellido1, apellido2 = :apellido2, fecha_nacimiento = :fecha_nacimiento, ciudad = :ciudad WHERE id = :usuario_id") ;
            $sent->execute([':apellido1' => $apellido1, ':apellido2' => $apellido2, ':ciudad' => $ciudad, ':usuario_id' => $usuario_id, ':fecha_nacimiento' => $fecha_nacimiento ]);
        }
        if (isset($intereses)){
            $sent = $pdo->prepare("INSERT IN TO usuarios_etiquetas (usuario_id, etiqueta_id) values (:usuario_id, :etiqueta_id)");
        }
        ?>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>