<?php
session_start();
if (!isset($_GET['id']) || (!isset($_SESSION['id_user'])))
{
	session_destroy();
	header ("Location: index.php");
	exit();
}
else
{
	require_once ('funciones/funciones.php');
	$registro=get_gasto($_GET['id']);
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
   			<td class="aplic">DETALLES DEL REGISTRO DE GASTOS</td>
  		</tr>
		</table>
<?php		
if (isset($_GET['s_ext'])){$_SESSION['s_ext']=$_GET['s_ext']; unset($_GET);}
switch ($_SESSION['s_ext'])
{
	case "FALSE": // SERVICIO EXTERIOR ES "FALSE"
							echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  		<tr height="5px">
						    				<td width="10%" class="nav_fondo">&nbsp;</td>
							    			<td width="40%" id="se_int" class="nav_menu_on" >
        									Servicio Interno
						        		</td>
  	  						  		<td width="40%" id="se_ext" class="nav_menu_off" >
						       		  		Servicio Exterior
        								</td>
						    				<td width="10%" class="nav_fondo">&nbsp;</td>
								  		</tr>
										</table>'; break;
	default:	// SERVICIO EXTERIOR ES "TRUE"
							$_SESSION['s_ext']="TRUE";
							echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  		<tr height="5px">
						    				<td width="10%" class="nav_fondo">&nbsp;</td>
							    			<td width="40%" id="se_int" class="nav_menu_off" >
        									Servicio Interno
						        		</td>
  	  						  		<td width="40%" id="se_ext" class="nav_menu_on" >
						       		  	Servicio Exterior
        								</td>
						    				<td width="10%" class="nav_fondo">&nbsp;</td>
								  		</tr>
										</table>'; break;
}
?>				 		
<div class="Formulario">
    <div align="right"><a href="index.php"><img src="images/cross.gif" /></a>
    </div>
    <fieldset>
      <legend>Unidad Ejecutora y Partida</legend>
    <table width=100% cellspacing="5" cellpadding="1" border="0" >
			<tr>
      	<td align="center"><label>id Registro:</label>
						<b><?php echo $registro['id_registros_gastos']?></b></td>
        <td align="center"><label>Fuente de Financiamiento:</label>
						<?php echo $registro['cod_fuente']." - ".$registro['nombre_fuente']?></td>
				<td align="center"><label>Unidad Ejecutora:</label>
						<?php echo $registro['cod_ac']."-".$registro['cod_ae']."-".$registro['cod_ue']?></td>
        <td align="center"><label>Partida:</label>
						<?php echo $registro['cod_pgen']." ".$registro['cod_psub']." ".$registro['cod_pespe']." ".$registro['cod_pespe_sub'] ?></td>       
			</tr>      
    </table>
		</fieldset>
    <fieldset><legend> Detalles del Registro </legend>
    <table width=100% cellspacing="5px" cellpadding="1" border="0">
   	 <tr>      	
      	<td align="center"><label>Tipo de Documento:</label>
						<?php echo $registro['tipodoc']?></td>  
        <td align="center"><label>Nro Doc:</label>
						<?php echo $registro['nrodoc']?></td>  
        <td align="center"><label>Fecha:</label>
        		<?php echo Date("d/m/Y",strtotime($registro['fecha_gasto']))?>
        </td>
        <td align="center"><label>Monto (BsF):</label>
        		<?php echo trim(number_format($registro['monto_gasto'], 2, ',', '.'))?>
        </td>            
			</tr>
    </table>
		</fieldset>
		<fieldset><legend> Historial de Acciones </legend>
    <table width=100% cellspacing="5px" cellpadding="1" border="0">  
    <?php	echo $registro['historial']; ?>
		</table>
		</fieldset>
		<fieldset><legend> Acci&oacute;n </legend>
    <input type="hidden" id="id_registro" name="id_registro" value=<?php echo "'".$registro['id_registros_gastos']."'"?> />
     <input type="hidden" id="id_usuario" name="id_usuario" value=<?php echo "'".$registro['id_user']."'"?> />
	  <table width=100% cellspacing="5px" cellpadding="1" border="0">  
    	<tr>
	      <td align="center"><input type="button" value="Eliminar" onclick="confirmar_borrar()"
        <?php
					if ($_SESSION['admin']=="f")
					{
						if ($registro['id_user']!=$_SESSION['id_user']){echo ' disabled="disabled"';}
					}
				?>
        /></td>
        <td align="center"><input type="button" value=" Cerrar " onclick="window.location='index.php'"/></td>
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