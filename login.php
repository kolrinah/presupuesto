<?php
session_start();
require_once ('funciones/funciones.php');
//echo $_POST['usuario']." - " . $_POST['clave'];
$login=comprueba_usuario($_POST['usuario'],$_POST['clave']);
if (!isset($login['mensaje']))
{
	$_SESSION['nombre']=$login['nombre_user'];
	$_SESSION['email']=$login['email'];
	$_SESSION['id_user']=$login['id_user'];
	$_SESSION['admin']=$login['admin'];
	$_SESSION['need_change']=$login['need_change'];
}
else {$_SESSION['mensaje']="<img src='images/alerta.png'/><font color='red'>&nbsp;&nbsp;".$login['mensaje']."</font>";}
header ("Location: index.php");
exit;
?>