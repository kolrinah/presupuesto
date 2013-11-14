<?php
session_start();
if ($_SESSION['admin']!="t" || (!isset($_SESSION['id_user']) || (!isset($_SESSION['email']))))
{
	session_destroy();
	header ("Location: index.php");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Administraci&oacute;n de Usuarios</title>
<link rel="stylesheet" type="text/css" media="screen" href="css/formclases.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/serv.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/demo_page.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/demo_table.css"/>
<!-- jQuery -->
<!--Incluimos la liberia jQuery-->
<script type="text/javascript" charset="utf-8" src="js/jquery-latest.js"></script> 
<script type="text/javascript" charset="utf-8" src="js/jquery.dataTables.js"></script> 
<script type="text/javascript" charset="utf-8" src="js/login.js"></script> 

</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0px" cellspacing="10px">
 	 		<tr>
  			<td width="50%"  valign="bottom"><img src="images/lg_gob.png"/></td>
    		<td width="50%" >&nbsp;</td>
  		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#EC1E23">
  		<tr>
    		<td bgcolor="#FFFFFF"><img src="images/pixel.gif" width="4" height="4" /></td>
  		</tr>
  		<tr>
    		<td><img src="images/pixel.gif" width="8" height="8" /></td>
  		</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
 	 		<tr>
  		  <td bgcolor="#C0C0C0"><img src="images/pixel.gif" width="2" height="2" /></td>
  		</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  	<tr>
  	  	<td width="1" height="0" bgcolor="#EFEFEF"><img src="images/pixel.gif" width="1" height="1" /></td>
  		</tr>
  		<tr>
   			<td class="aplic">USUARIOS DEL SISTEMA DE PRESUPUESTO</td>
  		</tr>
		</table>
<?php
		require_once ('funciones/funciones.php');
		echo '<div id="menu"><ul>';
		echo '<li><a href="nuevo_usuario.php">Crear Usuario</a></li>';
		echo '<li><a href="index.php">&nbsp;&nbsp;Inicio&nbsp;&nbsp;</a></li>';
		echo '</ul></div>';			
		
		$query="SELECT usuarios.id_user ,usuarios.nombre_user, usuarios.email, usuarios.admin
FROM usuarios
WHERE usuarios.clave<>'000'
ORDER BY usuarios.admin DESC , usuarios.nombre_user;";
		get_usuarios($query);
?>
<div id="loading">
	<img src="loading4.gif" border="0" />
</div>
</body>
</html>