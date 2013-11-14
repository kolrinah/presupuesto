<?php 
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['admin']!='t' || !isset($_SESSION['email']))
{
	session_destroy();
	header ("Location: index.php");
	exit();
}
else
{
	require_once ('funciones/funciones.php');

	//	print_r($_SESSION); //DEPURACION
}
?>
<!--NOTA:Cuando el calendario se deforme colocar en comentario la linea UTF-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Crear Usuario</title>
<!-- jQuery -->
<script type="text/javascript" src="js/jquery-latest.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="css/formclases.css">
<!-- page specific scripts -->
<link rel="stylesheet" type="text/css" media="screen" href="css/serv.css">
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
   			<td class="aplic">CREAR USUARIO</td>
  		</tr>
		</table>
<div class="Formulario">
  <form name="FormUser" action="crea_user.php" method="post" onSubmit="return compruebaNewUser(this)">
   <div align="right"><a href="admiuser.php"><img src="images/cross.gif" /></a>
   </div>
   <fieldset><legend>Detalles del Usuario</legend>
    <table width=100% cellspacing="15" cellpadding="10" border="0">
			<tr>      	
        <td width="40%">         
          <label>Nombre y Apellido:</label>          
          <input name="nombre" id="nombre" class="EntradaLarga" tabindex="101"/>
        </td>
     	  <td width="40%">         
          <label>Correo Electr&oacute;nico:</label>          
          <input name="email" id="email" class="EntradaLarga" tabindex="102"/>
        </td>       	
      	<td><label>Tipo de Usuario:</label>
        	  <select name="tipo" id="tipo" class="EntradaLarga"  tabindex="103" >
            	<option value="f" selected="selected" >Analista</option>           
             	<option value="t" >Administrador</option>           
   					</select></td>          
      </tr>         
    </table>   
  </fieldset>
  <fieldset><legend> Acciones a Ejecutar: </legend>
    <table width=100% cellspacing="5px" cellpadding="1" border="0">  
    	<tr>
      	<td align="center"><input type="submit" value="Guardar" tabindex="104"/></td>	      
        <td align="center"><input type="button" value=" Cerrar " onclick="window.location='admiuser.php'"/></td>
      </tr>  
    </table>
      </fieldset>

  <div id="Mensaje">
  <?php
	if (!isset($_SESSION['mensajes'])){ echo 'Todos los campos son necesarios para poder crear al Nuevo Usuario';}
	else {echo $_SESSION['mensajes'];unset($_SESSION['mensajes']);}
	?>
  </div>
</form>

</div>
</div>
<div id="loading">
<img src="loading4.gif" border="0" />
</div>
</body>
</html>