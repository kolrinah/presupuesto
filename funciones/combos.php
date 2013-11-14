<?php
session_start();
if (isset($_POST['id']) && isset($_SESSION['id_user']))
{
require_once ('postgres_server.php');
require_once ('funciones.php');
$idcombo = $_POST["id"];
$action =$_POST["combo"];

switch($action)
{
	case "acc_cent":
	{
		//$resulta=queryDB("SELECT * FROM acciones_especificas WHERE id_ac='".$idcombo."' ORDER BY id_ae");
    $query="SELECT acciones_especificas.cod_ae, acciones_especificas.denominacion_ae, acciones_especificas.id_ae
FROM (acciones_centralizadas INNER JOIN acciones_especificas ON acciones_centralizadas.id_ac = acciones_especificas.id_ac) INNER JOIN un_ejec_locales ON acciones_especificas.id_ae = un_ejec_locales.id_ae
WHERE (((acciones_especificas.id_ac)='".$idcombo."') AND ((un_ejec_locales.s_ext)=".$_SESSION['s_ext']."))
GROUP BY acciones_especificas.cod_ae, acciones_especificas.denominacion_ae, acciones_especificas.id_ae
ORDER BY acciones_especificas.cod_ae;";
	
		$resulta=queryDB($query);
		if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		$a=array('0'); $b=array('[Seleccione]');
		while ($c= pg_fetch_assoc($resulta)) 
		{
			array_push($a,$c['id_ae']) ; 
			array_push($b,$c['cod_ae']." ".$c['denominacion_ae']);
		}	
		$md_list=array_combine($a,$b);												
		buildOptions($md_list,0);  
    break;
  }
	case "acc_esp":
	{       
	  $resulta=queryDB("SELECT * FROM un_ejec_locales WHERE id_ae='".$idcombo."' AND s_ext='".$_SESSION['s_ext']."' ORDER BY cod_ue ASC");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		$a=array('0'); $b=array('[Seleccione]');
		while ($c= pg_fetch_assoc($resulta)) 
		{
			array_push($a,$c['id_ue']) ; 
			array_push($b,$c['cod_ue']." ".$c['denominacion_ue']);
		}	
		$md_list=array_combine($a,$b);												
		buildOptions($md_list,0);  
    break;
  }
	case "pgen":
	{       
	  $resulta=queryDB("SELECT * FROM partidas_sub WHERE id_pgen='".$idcombo."' ORDER BY cod_psub ASC");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		$a=array('0'); $b=array('[Seleccione]');
		while ($c= pg_fetch_assoc($resulta)) 
		{
			array_push($a,$c['id_psub']) ; 
			array_push($b,$c['cod_psub']." ".$c['nombre_psub']);
		}	
		$md_list=array_combine($a,$b);												
		buildOptions($md_list,0);  
    break;
  }
	case "psub":
	{       
	  $resulta=queryDB("SELECT * FROM partidas_especificas WHERE id_psub='".$idcombo."' ORDER BY cod_pespe ASC");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		$a=array('0'); $b=array('[Seleccione]');
		while ($c= pg_fetch_assoc($resulta)) 
		{
			array_push($a,$c['id_pespe']) ; 
			array_push($b,$c['cod_pespe']." ".$c['nombre_pespe']);
		}	
		$md_list=array_combine($a,$b);												
		buildOptions($md_list,0);  
    break;
  }
	case "pespe":
	{       
	  $resulta=queryDB("SELECT * FROM partidas_espe_sub WHERE id_pespe='".$idcombo."' ORDER BY cod_pespe_sub ASC");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		if (pg_num_rows($resulta)>0)
		{
			$a=array('0'); $b=array('[Seleccione]');
		}
		else
		{
			$a=array('1000'); $b=array('00');
		}
		while ($c= pg_fetch_assoc($resulta)) 
		{
			array_push($a,$c['id_pespe_sub']) ; 
			array_push($b,$c['cod_pespe_sub']." ".$c['nombre_pespe_sub']);
		}	
		$md_list=array_combine($a,$b);												
		buildOptions($md_list,0);  
    break;
  }
}
}
else
{
	session_destroy();
	header ("Location: ../index.php");
	exit();
}
?>