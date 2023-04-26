<?php

use App\Tablas\Usuario;

 session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Portal</title>
</head>

<body>
    <?php
    require '../vendor/autoload.php';

    $carrito = unserialize(carrito());
    $where = [];
    $nota = obtener_get('nota');
    $articulo_id = obtener_get(('id_art'));
    $usuario = \App\Tablas\Usuario::logueado();
    $usuario_id = $usuario->id;

    $etiquetas = obtener_get('etiquetas');
    $lista_de_etiquetas = explode(" ", $etiquetas);
    $lista2 = "('" . implode("', '", $lista_de_etiquetas). "')";

    $in = "";
    $i = 0;

    $pdo = conectar();

    if ($lista_de_etiquetas != [""]){
        foreach ($lista_de_etiquetas as $eti){
            $key = ":id".$i++;
            $in .= "$key,";
            $parametro[$key] = $eti;
            }
        $in = rtrim($in,",");
        $total = count($lista_de_etiquetas);
        $sent = $pdo->prepare("SELECT DISTINCT(a.*) 
                            FROM articulos a JOIN articulos_etiquetas ae ON a.id = ae.articulo_id 
                            JOIN etiquetas e ON e.id = ae.etiqueta_id 
                            WHERE e.nombre IN ($in) 
                            ");                  
        $sent->execute($parametro);
        }
        else{
            $sent = $pdo->query("SELECT * FROM articulos ORDER BY codigo");
        }
        /* Cambiar consulta por la del where in*/

    if (isset($nota) && isset($articulo_id) && isset($usuario_id) && $nota != '' && $usuario_id != '' && $articulo_id != '' && $nota != null & $articulo_id != null & $usuario_id != null){
        
        $sent4 = $pdo->prepare('SELECT COUNT(articulo_id) FROM articulos_usuarios WHERE articulo_id = :articulo_id AND usuario_id = :usuario_id');
        $sent4->execute([':usuario_id' => $usuario_id, ':articulo_id' => $articulo_id,]);
        $count = $sent4->fetchColumn();
        if ($count != 0){
            $sent5 = $pdo->prepare('UPDATE articulos_usuarios SET nota = :nota WHERE usuario_id = :usuario_id AND articulo_id = :articulo_id');
            $sent5->execute([':nota' => $nota, ':articulo_id' => $articulo_id, ':usuario_id' => $usuario_id ]);
        }
        else{
        $sent3 = $pdo->prepare("INSERT INTO articulos_usuarios (articulo_id, usuario_id, nota) VALUES ( :articulo_id, :usuario_id, :nota)");
        $sent3->execute([':nota' => $nota, ':articulo_id' => $articulo_id, ':usuario_id' => $usuario_id ]);
        }
    };

    ?>

    <div class="container mx-auto">
        <?php require '../src/_menu.php' ?>
        <?php require '../src/_alerts.php' ?>

        <form action="" method="GET">
            <label for="etiquetas">Busca por etiquetas: </label>
            <input type="text" name="etiquetas" id="etiquetas">
            <button type="submit" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Buscar</button>
        </form>

    <div class="flex">
            <?= $lista_de_etiquetas?>
            <?= $etiquetas?>
            <?= $lista2?>
            <main class="flex-1 grid grid-cols-3 gap-4 justify-center justify-items-center">
                <?php foreach ($sent as $fila) : ?>
                    <div class="p-6 max-w-xs min-w-full bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?= hh($fila['descripcion']) ?></h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= hh($fila['descripcion']) ?></p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Existencias: <?= hh($fila['stock']) ?></p>
                        <br>
                        <p>Nota del producto: </p> 
                            <?php 
                            $sent7 = $pdo->prepare('SELECT COUNT(articulo_id) FROM articulos_usuarios GROUP BY articulo_id HAVING articulo_id = :articulo_id');
                            $sent7->execute([':articulo_id' => $fila['id']]);
                            $count2 = $sent7->fetchColumn();
                            if ($count2 == 0) {
                                echo "<p>Este producto aun no fue evaluado</p>";
                            }
                            else{
                                $sent8 = $pdo->prepare('SELECT ROUND(AVG(nota),2) FROM articulos_usuarios GROUP BY articulo_id HAVING articulo_id = :articulo_id');
                                $sent8->execute([':articulo_id' => $fila['id']]);
                                $nota_actual = $sent8->fetchColumn();
                                echo "<p>$nota_actual</p>";
                            }
                        

                        ?>
                        
                        <form action="" method="GET">
                        <select name="nota" id="nota">
                                <?php
                                        foreach (range(0, 5) as $num) {
                                echo "<option value='$num'>$num</option>";
                                    }
                                ?>
                        <input type="hidden" name="id_art" value="<?=hh($fila['id'])?>">
                        </select>
                        
                        <?php if (\App\Tablas\Usuario::esta_logueado()) :?>

                        <button type="submit" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enviar</button>
                        <?php else: ?>
                        <button class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">No esta logueado</button>
                        <?php endif ?>
                        </form>
                        <br>
                        
                        <?php if ($fila['stock'] > 0): ?>
                            <a href="/insertar_en_carrito.php?id=<?= $fila['id'] ?>" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Añadir al carrito
                                <svg aria-hidden="true" class="ml-3 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        <?php else: ?>
                            <a class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                Sin existencias
                            </a>
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
            </main>

            <?php if (!$carrito->vacio()) : ?>
                <aside class="flex flex-col items-center w-1/4" aria-label="Sidebar">
                    <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
                        <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <th scope="col" class="py-3 px-6">Descripción</th>
                                <th scope="col" class="py-3 px-6">Cantidad</th>
                                <th scope="col" class="py-3 px-6">Etiquetas</th>
                            </thead>
                            <tbody>
                                <?php foreach ($carrito->getLineas() as $id => $linea):
                                    $articulo = $linea->getArticulo();
                                    $cantidad = $linea->getCantidad();
                                    $sent = $pdo->prepare('SELECT DISTINCT(e.nombre) FROM etiquetas e 
                                                                JOIN articulos_etiquetas ae ON ae.etiqueta_id = e.id 
                                                                JOIN articulos a ON a.id = ae.articulo_id 
                                                                WHERE a.id = :articulo_id');
                                        $sent->execute([':articulo_id' => $articulo->getId()]);
                                        $etiquetas = $sent->fetchAll();
                                    ?>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="py-4 px-6"><?= $articulo->getDescripcion() ?></td>
                                        <td class="py-4 px-6 text-center"><?= $cantidad ?></td>
                                        <td class="py-4 px-6 text-center"> 
                                        <?php foreach ($etiquetas as $etiqueta): ?>
                                            <?= $etiqueta["nombre"] ?>
                                        <?php endforeach ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <a href="/vaciar_carrito.php" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Vaciar carrito</a>
                        <a href="/comprar.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">Comprar</a>
                    </div>
                </aside>
            <?php endif ?>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>
