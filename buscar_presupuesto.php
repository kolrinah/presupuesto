<?php
session_start();
require_once ('funciones/funciones.php');

//Obtener variables pasado por POST
if (!isset($_POST['monto']) || !isset($_SESSION['id_user']) || !isset($_POST['year_poa']))
{
	 session_destroy();
	 header ("Location: index.php");
	 exit();
}

// ARMAMOS NUESTRO ARRAY PARA BUSQUEDA
$reg['id_fuente']=$_POST['fuente'];
$reg['id_ac']=$_POST['acc_cent'];
$reg['id_ae']=$_POST['acc_esp'];
$reg['id_ue']=$_POST['un_eje'];
$reg['id_pgen']=$_POST['pgen'];
$reg['id_psub']=$_POST['psub'];
$reg['id_pespe']=$_POST['pespe'];
$reg['id_pespe_sub']=$_POST['pespe_sub'];
$reg['year_presup']="01/01/".$_POST['year_poa'];

if (!validarcampos($reg))
{
//	$_SESSION['mensajes']="<img src='images/alerta.png'/><font color='red'> ¡Error! Existen campos vacíos en el formulario</font>";
	echo "<img src='images/alerta.png'/><font color='red'> ¡Error! Existen campos vac&iacute;os en el formulario</font>";
}
else
{
	$resulta=queryDB("SELECT presupuestos.id_presup, presupuestos.monto_presup, presupuestos.historial
										FROM presupuestos
										WHERE ((presupuestos.id_ac=".$reg['id_ac'].") AND (presupuestos.id_ae=".$reg['id_ae'].")
										AND (presupuestos.id_ue=".$reg['id_ue'].") AND (presupuestos.id_pgen=".$reg['id_pgen'].")
										AND (presupuestos.id_psub=".$reg['id_psub'].") AND (presupuestos.id_pespe=".$reg['id_pespe'].")
										AND (presupuestos.id_pespe_sub=".$reg['id_pespe_sub'].")
										AND (presupuestos.id_fuente=".$reg['id_fuente'].")
										AND (presupuestos.year_presup='".$reg['year_presup']."'));"); 
										
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	if (pg_num_rows($resulta)>0)
	{
		$row=pg_fetch_assoc($resulta);
		echo '<b> Ya Existe Presupuesto:<br/>'.$row['historial'].'</b>';
	}
	else
	{
		echo '<b> Presupuesto sin Asignar</b>'; 
	}
}
?>