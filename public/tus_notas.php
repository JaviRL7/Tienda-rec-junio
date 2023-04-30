<?php

use App\Tablas\Usuario;

session_start(); 
require_once __DIR__.'/../vendor/autoload.php';
$pdo = conectar();
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
        <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="py-3 px-6">Nombre del articulo</th>
                    <th scope="col" class="py-3 px-6">Tu notas</th>
                    <th scope="col" class="py-3 px-6">Nota media</th>
                </thead>
                <tbody>
                    <?php
                    $id_usuario = Usuario::logueado()->id;
                    $sent = $pdo->prepare('SELECT a.descripcion, au.nota, a.id
                                            FROM usuarios u JOIN articulos_usuarios au on au.usuario_id = u.id 
                                            JOIN articulos a on au.articulo_id = a.id 
                                            WHERE u.id = :id');
                    $sent->execute([':id' => $id_usuario ]);
                    $filas = $sent->fetchAll();

                    
                    $sent8 = $pdo->prepare('SELECT ROUND(AVG(nota),2) FROM articulos_usuarios 
                                            GROUP BY articulo_id 
                                            HAVING articulo_id = :articulo_id');
                                $sent8->execute([':articulo_id' => $fila['id']]);
                                $nota_actual = $sent8->fetchColumn();
                    ?>
                    <?php foreach ($filas as $fila) :?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="py-4 px-6">
                            <?=hh($fila['descripcion'])?>
                        </td>
                        <td class="py-4 px-6">
                            <?=hh($fila['nota'])?>
                        </td>
                        <td class="py-4 px-6">
                            <?php 
                            $sent = $pdo->prepare('SELECT ROUND(AVG(nota),2) FROM articulos_usuarios 
                                                    GROUP BY articulo_id 
                                                    HAVING articulo_id = :articulo_id');
                            $sent->execute([':articulo_id' => $fila['id']]);
                            $nota_media = $sent->fetchColumn();
                            ?>
                            <?= hh($nota_media)?>
                        </td>
                    </tr>
                    <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>
</html>
