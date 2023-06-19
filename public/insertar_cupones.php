<?php
session_start();
require '../vendor/autoload.php';

$cupon_id= obtener_post('cupones');
$cantidad = obtener_post('cantidad');
$usuario_id = obtener_post('usuario_id');

// if (!comprobar_csrf()) {
//     return volver_admin();
// }

//if (!isset($id)) {
//  return volver_admin();
//}

// TODO: Validar id
// Comprobar si el departamento tiene empleados

if (isset($usuario_id) && isset($cupon_id) && isset($cantidad) && $usuario_id != null && $cupon_id != null && $cantidad != null ) {
    insertar_cupones($usuario_id, $cupon_id, $cantidad);
}

$_SESSION['exito'] = 'Los cupones se han añadido al usuario';

volver_cupones();
?>
