<?php
session_start();
require '../vendor/autoload.php';

$cupon_id= obtener_post('cupon_id');
if (isset($cupon_id) && $cupon_id!=null){
    $usuario = \App\Tablas\Usuario::logueado();
    $usuario_id = $usuario->id;
    restar_cupon($usuario_id, $cupon_id);
}

// if (!comprobar_csrf()) {
//     return volver_admin();
// }

//if (!isset($id)) {
//  return volver_admin();
//}

// TODO: Validar id
// Comprobar si el departamento tiene empleados


$_SESSION['exito'] = 'El cupon se te ha descontado de tu perfil';

volver();
?>