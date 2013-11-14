<?php
session_start();
if (!isset($_GET['id']) || !isset($_SESSION['id_user']) || $_SESSION['admin']!='t')
{
	session_destroy();
	header ("Location: index.php");
	exit();
}
else
{
	require_once ('funciones/funciones.php');
	$registro=get_usuario_detalle($_GET['id']);
}
?>
<!--NOTA:Cuando el calendario se deforme colocar en comentario la linea UTF-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Borrar Registro</title>
<!-- jQuery -->
<script type="text/javascript" src="js/jquery-latest.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="css/formclases.css">
<link rel="stylesheet" type="text/css" media="screen" href="css/serv.css">
<script type="text/javascript" src="js/presupuesto.js"></script>

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
   			<td class="aplic">DIRECCI&Oacute;N DE PRESUPUESTO</td>
  		</tr>
		</table>				 		
<div class="Formulario">
    <div align="right"><a href="admiuser.php"><img src="images/cross.gif" /></a>
    </div>
    <fieldset>
      <legend>Detalles del Usuario</legend>
    <table width=100% cellspacing="5" cellpadding="1" border="0" >
			<tr>
      	<td align="center"><label>Nombre:</label>
						<?php echo $registro['nombre_user']?></td>
        <td align="center"><label>Email:</label>
						<?php echo $registro['email']?></td>
        <td align="center"><label>Tipo de Usuario:</label>
        		<?php echo (($registro['admin']=="t")? 'ADMINISTRADOR': 'ANALISTA');?>
        </td>       
			</tr>
    </table>
		</fieldset>   
		<fieldset><legend> Acciones a Ejecutar: </legend>
     <input type="hidden" id="id_usuario" name="id_usuario" value=<?php echo "'".$registro['id_user']."'"?> />
	  <table width=100% cellspacing="5px" cellpadding="1" border="0">  
    	<tr>
      	<td align="center"><input type="button" value="Reiniciar" onclick="reiniciar()"/></td>
	      <td align="center"><input type="button" value="Desincorporar" onclick="borrar_user()"/></td>
        <td align="center"><input type="button" value=" Cerrar " onclick="window.location='admiuser.php'"/></td>
      </tr>  
    </table>
	</fieldset>
</div>
</div>
<div id="loading">
<img src="loading4.gif" border="0" />
</div>
</body>
</html>