<?php
session_start();
// session_start had a missing semicolon at the end
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- The CSS path might be incorrect, make sure it's correct -->
    <link href="/css/output.css" rel="stylesheet">
    <title>Cupones</title>
</head>

<body>
    <?php
    require '../vendor/autoload.php';
    // We have no information so we can't see what "conectar()" returns so we can't debug it
    $pdo = conectar();
    $sent = $pdo->query("SELECT * FROM usuarios");
    $cupones = obtener_cupones();
    $numeros = range(1, 10);

    $cupon_seleccionado = obtener_get('cupones');
    $cantidad_seleccionada = obtener_get('cantidad');

    if (!empty($cantidad_seleccionada) && !empty($cupon_seleccionado)) {
        $cantidad = $cantidad_seleccionada;
        $descuento = $cupon_seleccionado;
    };
    ?>
    
    <div class="container mx-auto">

        <div class="overflow-x-auto relative mt-4">

            <form action="" method="GET">
                <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">Nombre</th>
                            <th scope="col" class="py-3 px-6">Tipos de cupones</th>
                            <th scope="col" class="py-3 px-6">Cantidad</th>
                            <th scope="col" class="py-3 px-6">Enviar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sent as $fila) : ?>
                            <form action="" method="GET">
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="py-4 px-6"><?= hh($fila['usuario']) ?></td>
                                <td>
                                    <select name="cupones" id="cupones" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <?php foreach ($cupones as $cupon) : ?>
                                            <option value="<?= hh($cupon['nombre']) ?>"><?= hh($cupon['nombre']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="cantidad" id="cantidad" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <?php foreach ($numeros as $num) : ?>
                                            <option value="<?= hh($num) ?>"><?= hh($num) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Enviar</button>
                                </td>
                            </tr>
                            </form>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </form>

        </div>
    </div>
    <?= hh($cantidad) ?>
    <?= hh($descuento) ?>
</body>

</html>