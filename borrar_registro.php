<?php
session_start();
if (!isset($_GET['id']) || !isset($_SESSION['id_user']) || !isset($_GET['usuario']))
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

	if (($_GET['usuario']==$_SESSION['id_user']) || ($_SESSION['admin']=="t"))
 	{
		queryDB("DELETE FROM registros_gastos WHERE id_registros_gastos=".$_GET['id'].";");	
	}

	header ("Location: index.php");
	exit();
}
?>