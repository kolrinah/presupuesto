<?php
require_once ('funciones/postgres_server.php');
$idcombo = $_POST["id"];
$action =$_POST["combo"];
$idcombo=intval($idcombo);

switch($action)
{
	case "id_pais":
	{
		$a="SELECT despachos.despacho	FROM despachos 
				INNER JOIN misiones_dip ON despachos.id_despacho = misiones_dip.id_despacho 
				GROUP BY despachos.despacho, misiones_dip.id_pais
				HAVING (((misiones_dip.id_pais)=$idcombo))";
		$resulta=queryDB($a);
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		//print_r(pg_fetch_assoc($resulta));
		while ($a= pg_fetch_assoc($resulta)) 
		{
			echo $a['despacho'];
		}	
		
    break;
  }
	case "Estados":{       

	
	/*	$resulta=queryDB("SELECT id_mision, nombre_md FROM misiones_dip WHERE id_pais=$idcombo ORDER BY id_mision ASC");
    if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		$a=array('0'); $b=array('[Seleccione]');
		while ($c= pg_fetch_assoc($resulta)) 
		{
			array_push($a,$c['id_mision']) ; 
			array_push($b,$c['nombre_md']);
		}	
		$md_list=array_combine($a,$b);												
		buildOptions($md_list);  
    break;
     	break;*/
  }
}

?>

