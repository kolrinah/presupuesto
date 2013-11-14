<?php 
session_start();
if (!isset($_SESSION['id_user']) || !isset($_SESSION['email']))
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
<title>Registro de Gastos</title>
<!-- jQuery -->
<script type="text/javascript" src="js/jquery-latest.js"></script>
<!-- jquery.datePicker.js -->
<link rel="stylesheet" type="text/css" media="screen" href="css/formclases.css">
<!-- page specific scripts -->
<link rel="stylesheet" type="text/css" media="screen" href="css/serv.css">
<script type="text/javascript" src="js/presupuesto.js"></script>

</head>
<body >
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
   			<td class="aplic">DIRECCI&Oacute;N DE PRESUPUESTO </td>
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
						       		  		<a href="consultar_gastos.php?s_ext=TRUE" class="tipo_serv">Servicio Exterior</a>
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
        									<a href="consultar_gastos.php?s_ext=FALSE" class="tipo_serv">Servicio Interno</a>
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

  <form id="selector" action="consulta_x_ue.php" method="post">
   <div align="right"><a href="index.php"><img src="images/cross.gif" /></a></div>
   <div align="center"><strong>CONSULTAS CONSOLIDADAS</strong></div>
   <fieldset><legend> Unidad Generadora del Gasto </legend>
    <table width=100% cellspacing="5" cellpadding="1" border="0">
			<tr>
      	<td width="5%">
        	<label>C&oacute;digo:</label>
          <input id="cod_acc_cent" class="EntradaCorta" readonly="readonly" />          
        </td>        
        <td width="95%">         
          <label>Acci&oacute;n Centralizada o Proyecto</label>          
          <select name="acc_cent" id="acc_cent" tabindex="101" class="EntradaLarga" >
          <?php buildOptions(get_listado_acc_cent(),$_SESSION['acc_cent']); ?>
          </select>
        </td>
      </tr>  
      <tr>
      	<td>
        	<label>C&oacute;digo:</label>
          <input id="cod_acc_esp" class="EntradaCorta" readonly="readonly" />          
        </td> 
      	<td><label>Acci&oacute;n Espec&iacute;fica:</label>
        	  <select name="acc_esp" id="acc_esp" class="EntradaLarga"  tabindex="102" >
            	<option value="0" selected="selected" >--</option>           
   					</select></td>          
      </tr> 
      <tr>
      	<td>
        	<label>C&oacute;digo:</label>
          <input id="cod_un_eje" class="EntradaCorta" readonly="readonly" />          
        </td> 
      	<td><label>Unidad Ejecutora:</label>
        	  <select name="un_eje" id="un_eje" class="EntradaLarga"  tabindex="103" >
            	<option value="0" selected="selected" >--</option>           
   					</select></td>          
      </tr>         
    </table>   
 

  	<table width=100% cellspacing="5px" cellpadding="1" border="0">
			<tr>
	      <td><label>A&ntilde;o del POA:</label><div class="POA">        	
          <img src="images/back_disabled.jpg" onmouseover="this.style.cursor='pointer';" 
          	onclick="javascript:document.getElementById('year_poa').value--"/>
          <?php
						date_default_timezone_set('America/Caracas'); // Establece la Hora de Venezuela para funciones de fecha y hora
						$anio=getdate();						
						echo '<input name="year_poa" id="year_poa" size="4" maxlength="4" class="POA" readonly="readonly"';
						echo 'value="'.$anio['year'].'"/>';
						unset($anio);
					?>                    
          <img src="images/forward_disabled.jpg" onmouseover="this.style.cursor='pointer';"
          							 onclick="javascript:document.getElementById('year_poa').value ++"/>
          </div>
        </td>
        <td><label>Fuente Presupuestaria:</label>
        	  <select name="fuente" id="fuente" class="EntradaLarga"  tabindex="104" >
            	<?php buildOptions(get_fuente_presup(),1); ?>           
   					</select></td>   
				<td valign="bottom" align="right"><input id="BotonConsultar" name="xpartida" type="submit" value="x Partida" tabindex="110" />
        <input id="xuel" name="xuel" type="submit" value="x Uni Ejec" tabindex="111" />
          								<input type="button" value="Cerrar" onclick="window.location='index.php'"/></td>
     </tr>
		</table>


  <div id="Mensaje">
  <?php
	if (!isset($_SESSION['mensajes'])){ echo 'Seleccione el tipo de Consulta';}
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