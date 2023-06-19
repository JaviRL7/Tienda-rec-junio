<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Comprar</title>
</head>

<body>
    <?php require '../vendor/autoload.php';

    if (!\App\Tablas\Usuario::esta_logueado()) {
        return redirigir_login();
    }
    $usuario = \App\Tablas\Usuario::logueado();
    $usuario_id = $usuario->id;

    $carrito = unserialize(carrito());

    if (obtener_post('_testigo') !== null) {
        $pdo = conectar();
        $sent = $pdo->prepare('SELECT *
                                 FROM articulos
                                WHERE id IN (:ids)');
        $sent->execute([':ids' => implode(', ', $carrito->getIds())]);
        foreach ($sent->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            if ($fila['stock'] < $carrito->getLinea($fila['id'])->getCantidad()) {
                $_SESSION['error'] = 'No hay existencias suficientes para crear la factura.';
                return volver();
            }
        }
        // Crear factura
        
        $pdo->beginTransaction();
        $sent = $pdo->prepare('INSERT INTO facturas (usuario_id)
                               VALUES (:usuario_id)
                               RETURNING id');
        $sent->execute([':usuario_id' => $usuario_id]);
        $factura_id = $sent->fetchColumn();
        $lineas = $carrito->getLineas();
        $values = [];
        $execute = [':f' => $factura_id];
        $i = 1;

        foreach ($lineas as $id => $linea) {
            $values[] = "(:a$i, :f, :c$i)";
            $execute[":a$i"] = $id;
            $execute[":c$i"] = $linea->getCantidad();
            $i++;
        }

        $values = implode(', ', $values);
        $sent = $pdo->prepare("INSERT INTO articulos_facturas (articulo_id, factura_id, cantidad)
                               VALUES $values");
        $sent->execute($execute);
        foreach ($lineas as $id => $linea) {
            $cantidad = $linea->getCantidad();
            $sent = $pdo->prepare('UPDATE articulos
                                      SET stock = stock - :cantidad
                                    WHERE id = :id');
            $sent->execute([':id' => $id, ':cantidad' => $cantidad]);
        }
        $pdo->commit();
        $_SESSION['exito'] = 'La factura se ha creado correctamente.';
        unset($_SESSION['carrito']);
        return volver();
    }

    ?>

    <div class="container mx-auto">

        <?php require '../src/_menu.php' ?>
        <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
        <form action="" method="GET">
            <select name="cupon" id="">
                <?php foreach(obtener_cupones_usuario($usuario_id) as $cupon)?>
                <option value="<?=hh($cupon["id"])?>">Del cupon <?=hh($cupon["nombre"])?>, tienes <?=hh($cupon["cantidad"])?> unidades</option>
            </select>
            <button type="submit" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Aplicar cupon</button>
        </form>
            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <th scope="col" class="py-3 px-6">Código</th>
                    <th scope="col" class="py-3 px-6">Descripción</th>
                    <th scope="col" class="py-3 px-6">Cantidad</th>
                    <th scope="col" class="py-3 px-6">Precio</th>
                    <th scope="col" class="py-3 px-6">Importe</th>
                </thead>
                <tbody>
                    <?php $total = 0 ?>
                    <?php foreach ($carrito->getLineas() as $id => $linea) : ?>
                        <?php
                        $articulo = $linea->getArticulo();
                        $codigo = $articulo->getCodigo();
                        $cantidad = $linea->getCantidad();
                        $precio = $articulo->getPrecio();

                        $articulo_id = $articulo->getId();
                        $oferta_id= obtener_ofertas_id($articulo_id);

                        $restante = 1;
                        $extra = 0;

                        $cupon_id = obtener_get('cupon');
                        $descuento = obtener_descuento($cupon_id);


                        switch($oferta_id){
                            case 1:
                                if ($cantidad ==1){
                                    $restante = 1;
                                    break;
                                }
                                if ($cantidad ==2){
                                    $restante = 0.5;
                                    break;
                                } else{
                                    $extra = $precio;
                                    break;
                                }
                            case 2:
                                $restante = 0.75;
                                break;
                            case 3:
                                $restante = 0.75;
                                break;
                            default:
                                $restante = 1;
                                $extra = 0;
                                break;
                        };

                        $importe = ($cantidad * $precio)*$restante - $extra;
                        $total += $importe;

                        $total_restado = $total*($descuento/100);
                        $total = $total - $total_restado;
                        ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6"><?= $articulo->getCodigo() ?></td>
                            <td class="py-4 px-6"><?= $articulo->getDescripcion() ?></td>
                            <td class="py-4 px-6 text-center"><?= $cantidad ?></td>
                            <td class="py-4 px-6 text-center">
                                <?= dinero($precio) ?>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?= dinero($importe) ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <td colspan="3"></td>
                    <td class="text-center font-semibold">TOTAL:</td>
                    <td class="text-center font-semibold"><?= dinero($total) ?></td>
                    <?php if (isset($cupon_id) && $cupon_id!=null):?>
                        <td class="text-center font-semibold">se ha descontado por el cupon: <?= hh($total_restado) ?></td>
                    <?php endif?>
                </tfoot>
            </table>
            <form action="" method="POST" class="mx-auto flex mt-4">
                <input type="hidden" name="_testigo" value="1">
                <button type="submit" href="" class="mx-auto focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">Realizar pedido</button>
            </form>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>