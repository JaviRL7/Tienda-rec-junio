<?php

use App\Tablas\Usuario;

session_start();
require_once __DIR__ . '/../vendor/autoload.php';
$pdo = conectar();
$etiquetas = obtener_etiquetas();
$nombre_completo = obtener_get('nombre_completo');
$fecha_nacimiento = obtener_get('fecha_nacimiento');
$ciudad = obtener_get('ciudad');
$intereses = obtener_get('intereses');
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
        <?=hh($nombre_completo)?>
        <?=hh($ciudad)?>
        <?=hh($fecha_nacimiento)?>
        <?=hh($intereses)?>
        <h3 class="text-center text-2xl font-bold mb-4">Tus datos</h1>
            <form method="GET" action="procesar_datos.php">
                <div class="mb-3">
                    <label for="nombre_completo" class="block font-medium text-gray-700">Nombre Completo:</label>
                    <input type="text" name="nombre_completo" id="nombre_completo" required class="form-input mt-1 block w-full rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-3">
                    <label for="fecha_nacimiento" class="block font-medium text-gray-700">Fecha de Nacimiento:</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required class="form-input mt-1 block w-full rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-3">
                    <label for="hobbies" class="block font-medium text-gray-700">Hobbies:</label>
                    <input type="text" name="hobbies" id="hobbies" required class="form-input mt-1 block w-full rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-3">
                    <label for="ciudad" class="block font-medium text-gray-700">Ciudad:</label>
                    <input type="text" name="ciudad" id="ciudad" required class="form-input mt-1 block w-full rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-3">
                    <fieldset>
                        <legend class="block mb-2 font-semibold text-lg text-gray-700" name="intereses">Marca tus intereses: </legend>
                        <?php foreach ($etiquetas as $etiqueta) :?>
                        <input class="form-checkbox h-5 w-5 text-gray-600" type="checkbox" id="<?=hh($etiqueta['id'])?>" name="<?=hh($etiqueta['nombre'])?>">
                        <label class="inline-flex items-center" for="<?=hh($etiqueta['id'])?>"><?=hh($etiqueta['nombre'])?></label>
                        <br>
                        <?php endforeach ?>
                        <br>
                        <button type="submit" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enviar</button>
                    </fieldset>
                </div>
                
            </form>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>