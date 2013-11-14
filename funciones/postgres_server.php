<?php
require_once ('config_pgserver.php');

function conectarDB()
{
	$cadena_conexion = "host=".DB_HOST." port=".DB_PUERTO." dbname=".DB_DATABASE." user=".DB_USER." password=".DB_CLAVE;
	$conn= pg_connect($cadena_conexion);
	return $conn;	
}

function queryDB($consulta)
{
	$conn=conectarDB();
	if (!$conn){return false;	exit;	}
	$result=pg_query($conn, $consulta);
	cerrarDB($conn);
	return $result;
}

function cerrarDB($conn)
{
	pg_close($conn);
}

function insertarRegistro($tabla,$array)
{
	//Armado del query de inserción
	$campos;
	$valores;
	foreach ($array as $key=>$value)
	{	
		$campos=$campos.$key.", ";
		$valores=$valores."'".pg_escape_string($array[$key])."', ";
	}
	$campos="(".substr($campos, 0, -2).")";
	$valores="(".substr($valores, 0, -2).")";
	$query="INSERT INTO ".$tabla." ".$campos." VALUES ".$valores;
	
	// Conectando a DB y Ejecutar
	$conn=conectarDB();
	if (!$conn) {echo "ERROR CONECTANDO A LA BASE DE DATOS";echo $query; return false;}
	$resulta=queryDB($query);
	if (!$resulta){echo "ERROR AGREGANDO REGISTROS A LA BASE DE DATOS";echo $query; return false;}
	else
	{return true;}
}


// SECCION DE PRUEBAS

/*$consulta="SELECT id_pais, nombre_pais FROM paises ORDER BY id_pais ASC";
$resulta=queryDB($consulta);
if ($resulta){echo 'QUE BIEN, GLORIA A DIOS';}
else {echo ' Upps';}*/

/*$o=3;
$consulta="SELECT id_mision, nombre_md FROM misiones_dip WHERE id_pais=$o ORDER BY id_mision ASC";
$resulta=queryDB($consulta);
if ($resulta){echo 'QUE BIEN, GLORIA A DIOS';print_r($c= pg_fetch_assoc($resulta));}
else {echo ' Upps';}*/
?>