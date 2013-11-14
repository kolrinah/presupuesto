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

date_default_timezone_set('America/Caracas'); // Establece la Hora de Venezuela para funciones de fecha y hora
$fecha=getdate();	
$fecha['mday']=($fecha['mday']<10)?"0".$fecha['mday'] :$fecha['mday'];
$fecha['mon']=($fecha['mon']<10)?"0".$fecha['mon'] :$fecha['mon'];
$Hoy=$fecha['mday']."/".$fecha['mon']."/".$fecha['year'];
	
// ARMAMOS NUESTRO ARRAY PARA INSERCION
$reg['id_fuente']=$_POST['fuente'];
$reg['id_ac']=$_POST['acc_cent'];
$reg['id_ae']=$_POST['acc_esp'];
$reg['id_ue']=$_POST['un_eje'];
$reg['id_pgen']=$_POST['pgen'];
$reg['id_psub']=$_POST['psub'];
$reg['id_pespe']=$_POST['pespe'];
$reg['id_pespe_sub']=$_POST['pespe_sub'];
$reg['year_presup']="01-01-".$_POST['year_poa'];
$reg['monto_presup']=str_ireplace(",",".",str_ireplace(".","",$_POST['monto']));
$reg['id_user']=$_SESSION['id_user'];
$reg['historial']="Asignaci&oacute;n Ley: BsF ".$_POST['monto']." - Cargado por: ".get_nombre_analista($_SESSION['id_user']).
									", Fecha:".$Hoy;
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
	if (pg_num_rows($resulta)>0)  // YA EXISTE PRESUPUESTO ASIGNADO
	{
		$row=pg_fetch_assoc($resulta);
		$query="UPDATE presupuestos SET monto_presup=".$reg['monto_presup'].", historial='".$row['historial']."<br/> Gasto Acordado: BsF ".$_POST['monto']." - Modificado por: ".get_nombre_analista($_SESSION['id_user']).", Fecha:".$Hoy."' WHERE presupuestos.id_presup=".$row['id_presup'].";";
		$resulta=queryDB($query);
		if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
		
		echo "Gasto Acordado: BsF ".$_POST['monto']." Modificado por: ".get_nombre_analista($_SESSION['id_user']).", Fecha:".$Hoy;
		
	}
	else
	{
		if (!insertarRegistro("presupuestos", $reg))
		{
		//	$_SESSION['mensajes']="<img src='images/alerta.png'/><font color='red'>&nbsp;&nbsp;No se pudo registrar el gasto en el Servidor</font>";
			echo "<img src='images/alerta.png'/><font color='red'>&nbsp;&nbsp;No se pudo registrar el gasto en el Servidor</font>";
		}
		else
		{
			$cod=get_codigos($reg);
			echo "Asignado Satisfactoriamente - Fuente: ".get_nombre_fuente($_POST['fuente']).":<br/> Unidad Ejecutora: <b>".$cod['cod_ac']."-".$cod['cod_ae']."-".$cod['cod_ue'].
													"</b> --> Partida Presupuestaria: <b>".$cod['cod_pgen']."-".$cod['cod_psub']."-".$cod['cod_pespe']."-".
													$cod['cod_pespe_sub']."</b> --> Monto (BsF): <b>".$_POST['monto']."</b> --> Fecha: <b>".$Hoy."</b>";
		}
}
}
?>