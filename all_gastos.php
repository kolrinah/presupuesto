<?php
session_start();
require("cabecera.html");
if (isset($_GET['s_ext'])){$_SESSION['s_ext']=$_GET['s_ext']; unset($_GET);}
if (!isset($_SESSION['s_ext'])){$_SESSION['s_ext']="FALSE";} // Al decir que es "FALSE" indico que es de Servicio Interno 
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
						       		  		<a href="index.php?s_ext=TRUE" class="tipo_serv">Servicio Exterior</a>
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
        									<a href="index.php?s_ext=FALSE" class="tipo_serv">Servicio Interno</a>
						        		</td>
  	  						  		<td width="40%" id="se_ext" class="nav_menu_on" >
						       		  	Servicio Exterior
        								</td>
						    				<td width="10%" class="nav_fondo">&nbsp;</td>
								  		</tr>
										</table>'; break;
}
//print_r($_SESSION);  //DEPURACION VER ESTADO DE LAS VARIABLES DE SESSION

if(isset($_SESSION['nombre']) && isset($_SESSION['id_user']) && isset($_SESSION['email']))
{
	if ($_SESSION['need_change']=="f" && !isset($_GET['need_change']))
	{		
		date_default_timezone_set('America/Caracas'); // Establece la Hora de Venezuela para funciones de fecha y hora
		$hora=getdate();
		echo '<div id="menu"><ul>';
		echo '<li><a id="actual">';
				if (($hora['hours']) <12 && $hora['hours']>=5){echo 'Buenos d&iacute;as ';}
				elseif ($hora['hours']<19 && $hora['hours']>=12){echo 'Buenas tardes ';}
				elseif ($hora['hours']>=19) {echo 'Buenas noches ';}
				elseif ($hora['hours']>=0 && $hora['hours']<5) {echo 'Buenas noches ';}
				echo $_SESSION['nombre'];	
		echo '</a></li>';
		echo '<li><a href="index.php">Ultimos Gastos</a></li>';
		echo '<li><a href="carga_gastos.php">Registrar Gastos</a></li>';
		echo '<li><a href="consultar_gastos.php">Consultar Gastos</a></li>';		
		if ($_SESSION['admin']=='t')
				{echo '<li><a href="carga_presupuesto.php">Asignar Presupuesto</a></li>';
  			 echo '<li><a href="admiuser.php">Administrar Usuarios</a></li>';}
		echo '<li><a href="index.php?need_change">&nbsp;Cambiar Clave&nbsp;</a></li>';
		echo '<li><a href="salir.php">&nbsp;&nbsp;Salir&nbsp;&nbsp;</a></li>';
		echo '</ul></div>';		

		require_once ('funciones/funciones.php');
		$query="SELECT registros_gastos.id_registros_gastos, fuente_presup.cod_fuente, fuente_presup.nombre_fuente, acciones_centralizadas.cod_ac, acciones_especificas.cod_ae, un_ejec_locales.cod_ue, partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub, registros_gastos.fecha_gasto, registros_gastos.monto_gasto, tipodoc.tipodoc, registros_gastos.nrodoc, usuarios.nombre_user
FROM ((partidas_generales INNER JOIN (((((((registros_gastos INNER JOIN usuarios ON registros_gastos.id_user = usuarios.id_user) INNER JOIN acciones_centralizadas ON registros_gastos.id_ac = acciones_centralizadas.id_ac) INNER JOIN acciones_especificas ON registros_gastos.id_ae = acciones_especificas.id_ae) INNER JOIN un_ejec_locales ON registros_gastos.id_ue = un_ejec_locales.id_ue) INNER JOIN partidas_espe_sub ON registros_gastos.id_pespe_sub = partidas_espe_sub.id_pespe_sub) INNER JOIN partidas_especificas ON registros_gastos.id_pespe = partidas_especificas.id_pespe) INNER JOIN partidas_sub ON registros_gastos.id_psub = partidas_sub.id_psub) ON partidas_generales.id_pgen = registros_gastos.id_pgen) INNER JOIN tipodoc ON registros_gastos.id_tipodoc = tipodoc.id_tipodoc) INNER JOIN fuente_presup ON registros_gastos.id_fuente = fuente_presup.id_fuente
WHERE (((un_ejec_locales.s_ext)='".$_SESSION['s_ext']."'))
ORDER BY registros_gastos.id_registros_gastos DESC;";
	echo '<div class="titul">Totalidad de Gastos Registrados</div>';	
	get_registros_gastos($query);
	}
	else
	{
		echo '<div class="login">
				<form name="FormClave" action="cambiarclave.php" method="post" onSubmit="return compruebaClaves(this)">	
				<h3>Cambio de Contrase&ntilde;a</h3>
				<div id="Autentica">
					<input type="hidden" name="email" value="'.$_SESSION['email'].'"/>
					<label>Contrase&ntilde;a actual:</label>
					<input type="password" name="clave" id="usuario" size=50" tabindex="10"/>
					<label><br/><br/>Contrase&ntilde;a Nueva:</label>
					<input type="password" name="clave1" id="clave1" size=50" tabindex="11"/>
					<label>Repetir Contrase&ntilde;a:</label>
					<input type="password" name="clave2" id="clave2" size=50" tabindex="12"/>
				</div>
					<input type="button" value="Cancelar" onclick="history.back()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" id="Entrar" value="&nbsp;Aceptar&nbsp;" tabindex="13"/>
				<div id="Mensaje" class="Mensaje">';
	if(isset($_SESSION['mensaje'])){echo $_SESSION['mensaje']; unset($_SESSION['mensaje']);}	
	else { echo 'Introduzca su Contrase&ntilde;a';}	  
	echo '</div>       	
				</form>
				</div>';
	}
}
else 
{
	echo'<div class="login">
				<form name="FormLogin" action="login.php" method="post" onSubmit="return compruebaFormulario(this)">	
				<h3>Identif&iacute;quese</h3>
				<div id="Autentica">
					<label>Usuario:</label>
					<input type="text" name="usuario" id="usuario" size=50" tabindex="10"/>
					<label>Clave:</label>
					<input type="password" name="clave" id="clave" size=50" tabindex="11"/>
				</div> <input type="submit" id="Entrar" value="Entrar" tabindex="12"/>
				<div id="Mensaje" class="Mensaje">';
	if(isset($_SESSION['mensaje'])){echo $_SESSION['mensaje']; unset($_SESSION['mensaje']);}	
	else { echo 'Introduzca su Usuario y Contrase&ntilde;a';}	  
	echo '</div>       	
				</form>
				</div>';
}	
require("footer.html");
?>