<?php
//echo '<!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
session_start();
require_once ('funciones/funciones.php');

//Obtener variables pasado por POST
if (!isset($_POST['monto']) || !isset($_SESSION['id_user']) || !isset($_POST['nrodoc']))
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
$reg['fecha_gasto']=$_POST['fecha'];
$reg['monto_gasto']=str_ireplace(",",".",str_ireplace(".","",$_POST['monto']));
$reg['nrodoc']=trim(substr($_POST['nrodoc'],0,49));
$reg['id_tipodoc']=$_POST['tipodoc'];
$reg['id_user']=$_SESSION['id_user'];
$reg['historial']="Registrado por: ".get_nombre_analista($_SESSION['id_user']).
									", el d&iacute;a: ".diasemana($fecha).", ".$Hoy." a las: ".Date('h:i:s A');
if (!validarcampos($reg))
{
//	$_SESSION['mensajes']="<img src='images/alerta.png'/><font color='red'> ¡Error! Existen campos vacíos en el formulario</font>";
	echo "<img src='images/alerta.png'/><font color='red'> ¡Error! Existen campos vac&iacute;os en el formulario</font>";
}
else
{
	if (!insertarRegistro("registros_gastos", $reg))
	{
	//	$_SESSION['mensajes']="<img src='images/alerta.png'/><font color='red'>&nbsp;&nbsp;No se pudo registrar el gasto en el Servidor</font>";
	echo "<img src='images/alerta.png'/><font color='red'>&nbsp;&nbsp;No se pudo registrar el gasto en el Servidor</font>";
	}
	else
	{
		$cod=get_codigos($reg);
		echo "Registrado Satisfactoriamente:<br/> Unidad Ejecutora: <b>".$cod['cod_ac']."-".$cod['cod_ae']."-".$cod['cod_ue'].
													"</b> --> Partida Presupuestaria: <b>".$cod['cod_pgen']."-".$cod['cod_psub']."-".$cod['cod_pespe']."-".
													$cod['cod_pespe_sub']."</b> --> Monto (BsF): <b>".$_POST['monto']."</b> --> Fecha: <b>".$_POST['fecha']."</b>";
	}
}
?>