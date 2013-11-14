<?php
session_start();
if (!isset($_GET['id']) || $_SESSION['admin']!='t' || !isset($_SESSION['id_user']))
{
	print_r ($_GET);
	print_r ($_SESSION);
	session_destroy();
	header ("Location: index.php");
	exit();
}
else
{
	require_once ('funciones/postgres_server.php');

	$query = "UPDATE usuarios SET clave='865cc410a1b7c60ae8a38c8761b2b342' WHERE id_user=".$_GET['id'].";";
	$resulta=queryDB($query);	
	if (pg_affected_rows($resulta)==1)
	{
		header ("Location: admiuser.php");
		exit();
	}	
}
?>