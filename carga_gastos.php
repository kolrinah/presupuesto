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
<!-- required plugins -->
<script type="text/javascript" src="js/date.js"></script>
<!-- jquery.datePicker.js -->
<script type="text/javascript" src="js/jquery.datePicker.js"></script>
<!-- datePicker required styles -->
<link rel="stylesheet" type="text/css" media="screen" href="css/datePicker.css">
<!-- page specific styles -->
<link rel="stylesheet" type="text/css" media="screen" href="css/formclases.css">
<!-- page specific scripts -->
<link rel="stylesheet" type="text/css" media="screen" href="css/serv.css">
<script type="text/javascript" src="js/presupuesto.js"></script>

</head>
<body onLoad="inicializacion();">
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
						       		  		<a href="carga_gastos.php?s_ext=TRUE" class="tipo_serv">Servicio Exterior</a>
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
        									<a href="carga_gastos.php?s_ext=FALSE" class="tipo_serv">Servicio Interno</a>
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

  <form id="forma">
   <div align="right"><a href="index.php"><img src="images/cross.gif" /></a>
   </div>
   <fieldset><legend> Fuente Presupuestaria </legend>
   	<select name="fuente" id="fuente" class="EntradaLarga"  tabindex="100" >
            	<?php buildOptions(get_fuente_presup(),1); ?>           
   	</select>
   </fieldset>
   <fieldset><legend> Unidad Generadora del Gasto </legend>
    <table width=100% cellspacing="5" cellpadding="1" border="0">
			<tr>
      	<td width="5%">
        	<label>C&oacute;digo:</label>
          <input id="cod_acc_cent" class="EntradaCorta" readonly="readonly" />          
        </td>        
        <td width="95%">         
          <label>Acci&oacute;n Centralizada o Proyecto</label>          
          <select name="acc_cent" id="acc_cent" tabindex="101" class="EntradaLarga" 
          				onchange="DesbloquearBotonRegistrar();" onblur="DesbloquearBotonRegistrar();" >
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
        	  <select name="acc_esp" id="acc_esp" class="EntradaLarga"  tabindex="102" 
          					onchange="DesbloquearBotonRegistrar();" onblur="DesbloquearBotonRegistrar();">
            	<option value="0" selected="selected" >--</option>           
   					</select></td>          
      </tr> 
      <tr>
      	<td>
        	<label>C&oacute;digo:</label>
          <input id="cod_un_eje" class="EntradaCorta" readonly="readonly" />          
        </td> 
      	<td><label>Unidad Ejecutora:</label>
        	  <select name="un_eje" id="un_eje" class="EntradaLarga"  tabindex="103" 
          					onchange="DesbloquearBotonRegistrar();" onblur="DesbloquearBotonRegistrar();">
            	<option value="0" selected="selected" >--</option>           
   					</select></td>          
      </tr>         
    </table>   
  </fieldset>
  <fieldset><legend> Partidas Presupuestarias </legend>
    <table width=100% cellspacing="5" cellpadding="1" border="0">
			<tr>
      	<td width="5%">
        	<label>C&oacute;digo:</label>
          <input id="cod_pgen" class="EntradaCorta" readonly="readonly" />          
        </td>        
        <td width="95%">         
          <label> Partida: </label>          
          <select name="pgen" id="pgen" tabindex="104" class="EntradaLarga" 
          				onchange="DesbloquearBotonRegistrar();" onblur="DesbloquearBotonRegistrar();" >
          <?php buildOptions(get_listado_pgen(),$_SESSION['pgen']); ?>
          </select>
        </td>
      </tr> 
      <tr>
      	<td>
        	<label>C&oacute;digo:</label>
          <input id="cod_psub" class="EntradaCorta" readonly="readonly" />          
        </td> 
      	<td><label>Partida Gen&eacute;rica:</label>
        	  <select name="psub" id="psub" class="EntradaLarga"  tabindex="105" 
          					onchange="DesbloquearBotonRegistrar();" onblur="DesbloquearBotonRegistrar();">
            	<option value="0" selected="selected" >--</option>           
   					</select></td>          
      </tr>
      <tr>
      	<td>
        	<label>C&oacute;digo:</label>
          <input id="cod_pespe" class="EntradaCorta" readonly="readonly" />          
        </td> 
      	<td><label>Partida Espec&iacute;fica:</label>
        	  <select name="pespe" id="pespe" class="EntradaLarga"  tabindex="106" 
          					onchange="DesbloquearBotonRegistrar();" onblur="DesbloquearBotonRegistrar();">
            	<option value="0" selected="selected" >--</option>           
   					</select></td>          
      </tr>   
      <tr>
      	<td>
        	<label>C&oacute;digo:</label>
          <input id="cod_pespe_sub" class="EntradaCorta" readonly="readonly" />          
        </td> 
      	<td><label>Partida Sub-Espec&iacute;fica:</label>
        	  <select name="pespe_sub" id="pespe_sub" class="EntradaLarga"  tabindex="107" 
          					onchange="DesbloquearBotonRegistrar();" onblur="DesbloquearBotonRegistrar();">
            	<option value="0" selected="selected" >--</option>           
   					</select></td>          
      </tr> 
    </table>
  </fieldset>
	<fieldset><legend> Descripci&oacute;n del Gasto </legend>
  	<table width=100% cellspacing="10px" cellpadding="1" border="0">
			<tr>
				<td width="20%"><label>Fecha:</label>
	          <input name="fecha" id="fecha" class="date-pick" size="10" 
                    maxlength="10" readonly="readonly" 
                    onchange="DesbloquearBotonRegistrar();" onblur="DesbloquearBotonRegistrar();"/></td>
				<td width="15%"><label>Monto <b>(BsF)</b>:</label>
	          <input name="monto" id="monto" size="15" maxlength="20" class="dinero" tabindex="108" 
            onblur="this.value=formatMoneda(this.value,',','.',2);" onkeyup="formatMoneda(this.value,',','.',2);DesbloquearBotonRegistrar();" 
            onkeypress="return onlyDigits(event, this.value,true,true,true,',','.',2);"/></td>
         
	<td><label>Tipo de Documento:</label>
          <select name="tipodoc" id="tipodoc" class="EntradaLarga"  tabindex="109" 
          					onchange="DesbloquearBotonRegistrar();" onblur="DesbloquearBotonRegistrar();">
		<?php buildOptions(get_tipodoc_list()); ?>  </select></td>
	<td><label>Nro Doc:</label>
          <input name="nrodoc" id="nrodoc" class="EntradaLarga"  tabindex="110" 
          					onchange="DesbloquearBotonRegistrar();" onkeyup="DesbloquearBotonRegistrar();"/></td>
			<td valign="bottom" width="25%" align="right"><input id="BotonRegistrar" type="button" value="Registrar" disabled="disabled" tabindex="111" />
        		<input type="button" value="Cerrar" onclick="window.location='index.php'"/></td>
		</tr>
	</table>
      </fieldset>

  <div id="Mensaje">
  <?php
	if (!isset($_SESSION['mensajes'])){ echo 'Todos los campos son necesarios para poder guardar el nuevo registro';}
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