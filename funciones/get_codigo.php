<?php
session_start();
if (isset($_POST['id']))
{
require_once ('postgres_server.php');
require_once ('funciones.php');
$idcombo = $_POST["id"];
$action =$_POST["combo"];

switch($action)
{
	case "acc_cent":
	{
		$resulta=queryDB("SELECT cod_ac FROM acciones_centralizadas WHERE id_ac='".$idcombo."'");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		while ($c= pg_fetch_assoc($resulta)) 
		{
			echo $c['cod_ac'];
		}	
	  break;
  }
	case "acc_esp":
	{
		$resulta=queryDB("SELECT cod_ae FROM acciones_especificas WHERE id_ae='".$idcombo."'");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		while ($c= pg_fetch_assoc($resulta)) 
		{
			echo $c['cod_ae'];
		}	
	  break;
  }
	case "un_eje":
	{
		$resulta=queryDB("SELECT cod_ue FROM un_ejec_locales WHERE id_ue='".$idcombo."'");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		while ($c= pg_fetch_assoc($resulta)) 
		{
			echo $c['cod_ue'];
		}	
	  break;
  }
	case "pgen":
	{
		$resulta=queryDB("SELECT cod_pgen FROM partidas_generales WHERE id_pgen='".$idcombo."'");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		while ($c= pg_fetch_assoc($resulta)) 
		{
			echo $c['cod_pgen'];
		}	
	  break;
  }
	case "psub":
	{
		$resulta=queryDB("SELECT cod_psub FROM partidas_sub WHERE id_psub='".$idcombo."'");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		while ($c= pg_fetch_assoc($resulta)) 
		{
			echo $c['cod_psub'];
		}	
	  break;
  }
	case "pespe":
	{
		$resulta=queryDB("SELECT cod_pespe FROM partidas_especificas WHERE id_pespe='".$idcombo."'");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		while ($c= pg_fetch_assoc($resulta)) 
		{
			echo $c['cod_pespe'];
		}	
	  break;
  }
	case "pespe_sub":
	{
		$resulta=queryDB("SELECT cod_pespe_sub FROM partidas_espe_sub WHERE id_pespe_sub='".$idcombo."'");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		while ($c= pg_fetch_assoc($resulta)) 
		{
			echo $c['cod_pespe_sub'];
		}	
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