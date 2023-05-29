<?php

use App\Tablas\Usuario;

session_start(); 
require_once __DIR__.'/../vendor/autoload.php';
$pdo = conectar();
$usuario = \App\Tablas\Usuario::logueado();
$usuario_id = $usuario->id;
$resultado = obetener_datos_usuarios($usuario_id);
$intereses = obetener_datos_usuarios_etiquetas($usuario_id)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Perfil completo</title>
</head>
<body>
    <div class="container mx-auto">
        <?php require_once '../src/_menu.php' ?>
        <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Nombre:</strong></td>
                        <td><?=hh($resultado['usuario'])?></td>
                    </tr>
                    <tr>
                        <td><strong>Primer apellido:</strong></td>
                        <td><?=hh($resultado['apellido1'])?></td>
                    </tr>
                    <tr>
                        <td><strong>Segundo apellido:</strong></td>
                        <td><?=hh($resultado['apellido2'])?></td>
                    </tr>
                    <tr>
                        <td><strong>Fecha de nacimiento:</strong></td>
                        <td><?=hh($resultado['fecha_nacimiento'])?></td>
                    </tr>
                    <tr>
                        <td><strong>Ciudad:</strong></td>
                        <td><?=hh($resultado['ciudad'])?></td>
                    </tr>
                    <tr>
                        <td><strong>Intereses:</strong></td>
                        <td>
                            <?php foreach($intereses as $interes) :?>
                                <?=hh($interes['nombre'])?>
                                <br>
                            <?php endforeach?>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>
</html>
