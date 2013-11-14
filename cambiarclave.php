<?php
session_start();
require_once ('funciones/funciones.php');

if ($_POST['clave1']===$_POST['clave2'] && $_POST['clave1']!="")
{
	if (cambiaclave($_POST['email'],$_POST['clave'],$_POST['clave1']))
	{
		$_SESSION['need_change']="f"; $_SESSION['mensaje']="Su contraseña ha sido cambiada satisfactoriamente";
	}
	else
	{	$_SESSION['need_change']="t"; $_SESSION['mensaje']="<img src='images/alerta.png'/><font color='red'>&nbsp;&nbsp;¡Error validando datos!</font>";}
}
else
{$_SESSION['mensaje']="<img src='images/alerta.png'/><font color='red'>&nbsp;&nbsp;Introduzca los datos correctamente</font>";}
header ('Location: index.php');
exit();
?>