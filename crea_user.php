<?php
session_start();
require_once ('funciones/funciones.php');

//Obtener variables pasado por POST
if (!isset($_POST['nombre']) || !isset($_SESSION['id_user']) || !isset($_POST['email']) || !isset($_POST['tipo']))
{
	 session_destroy();
	 header ("Location: index.php");
	 exit();
}	
// ARMAMOS NUESTRO ARRAY PARA INSERCION
$reg['nombre_user']=ucwords(strtolower(trim(substr($_POST['nombre'],0,98))));
$reg['email']=trim(substr($_POST['email'],0,145));
$reg['clave']="865cc410a1b7c60ae8a38c8761b2b342";
$reg['admin']=$_POST['tipo'];

if (!validarcampos($reg))
{
	$_SESSION['mensajes']="<img src='images/alerta.png'/><font color='red'> ¡Error! Existen campos vacíos en el formulario</font>";
}
else
{
	if (!insertarRegistro("usuarios", $reg))
	{
		$_SESSION['mensajes']="<img src='images/alerta.png'/><font color='red'>&nbsp;&nbsp;No se pudo crear al nuevo usuario</font>";
	}
	else
	{		
		$_SESSION['mensajes']="Creado Satisfactoriamente:<br/> Usuario: <b>".$reg['nombre_user']."</b> --> Login: <b>".$reg['email']."</b> --> Clave: <b>rosario</b> --> Tipo de Usuario: <b>".(($reg['admin']=='t')? "Administrador": "Analista")."</b>";
	}
}
header ("Location: nuevo_usuario.php");
exit();
?>