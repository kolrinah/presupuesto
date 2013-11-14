<?php
session_start();
require("cabecera_consulta.html");
//print_r($_SESSION);  //DEPURACION VER ESTADO DE LAS VARIABLES DE SESSION
//print_r($_POST);
if(!isset($_SESSION['id_user']) || !isset($_POST['acc_cent']) || !isset($_POST['acc_esp']) || !isset($_POST['un_eje']))
{
	session_destroy();
	header ("Location: index.php");
	exit();
}
	
require_once ('funciones/funciones.php');
date_default_timezone_set('America/Caracas'); // Establece la Hora de Venezuela para funciones de fecha y hora
$fecha=getdate();	
$fecha['mday']=($fecha['mday']<10)?"0".$fecha['mday'] :$fecha['mday'];
$fecha['mon']=($fecha['mon']<10)?"0".$fecha['mon'] :$fecha['mon'];
$Hoy=$fecha['mday']."/".$fecha['mon']."/".$fecha['year'];	
/////////////////////////////////////////////////	
// SELECCION DEL BOTON "CONSUTLA POR PARTIDA" //
///////////////////////////////////////////////
if (isset($_POST['xpartida']))
{		
	$arrGasto=get_gasto_x_partida($_POST['acc_cent'], $_POST['acc_esp'], $_POST['un_eje'], $_POST['year_poa'], $_POST['fuente']);
	
	if ($arrGasto)
	{
		$reg['id_ac']=$_POST['acc_cent'];
		$reg['id_ae']=$_POST['acc_esp'];
		$reg['id_ue']=$_POST['un_eje'];
		$reg=get_codigos($reg);	
		
		// CREACON DE TITULO DE LA CONSULTA
		if ($_POST['acc_cent']!=0 && $_POST['acc_esp']!=0 && $_POST['un_eje']!=0)
		{
			$titulo="UNIDAD EJECUTORA: ".$reg['cod_ac'].'-'.$reg['cod_ae'].'-'.$reg['cod_ue'];
			$titulo=$titulo."<br/>(".get_nombre_fuente($_POST['fuente']).")";		
		}
		
		if ($_POST['acc_cent']==0 && $_POST['acc_esp']==0 && $_POST['un_eje']==0)
		{
			$titulo=($_SESSION['s_ext']=="FALSE")?"CONSOLIDADO DEL SERVICIO INTERNO":"CONSOLIDADO DEL SERVICIO EXTERIOR";	
			$titulo=$titulo."<br/>(".get_nombre_fuente($_POST['fuente']).")";	
		}
		
		if ($_POST['acc_cent']!=0 && $_POST['acc_esp']==0 && $_POST['un_eje']==0)
		{
			$titulo=($_SESSION['s_ext']=="FALSE")?"CONSOLIDADO DE ACCION CENTRALIZADA ".$reg['cod_ac']." EN EL SERVICIO INTERNO":"CONSOLIDADO DE ACCION CENTRALIZADA ".$reg['cod_ac']." EN EL SERVICIO EXTERIOR";			
			$titulo=$titulo."<br/>(".get_nombre_fuente($_POST['fuente']).")";		
		}
		
		if ($_POST['acc_cent']!=0 && $_POST['acc_esp']!=0 && $_POST['un_eje']==0)
		{
			$titulo=($_SESSION['s_ext']=="FALSE")?"CONSOLIDADO DE ".$reg['cod_ac']."-".$reg['cod_ae']." EN EL SERVICIO INTERNO":"CONSOLIDADO DE ".$reg['cod_ac']."-".$reg['cod_ae']." EN EL SERVICIO EXTERIOR";			
			$titulo=$titulo."<br/>(".get_nombre_fuente($_POST['fuente']).")";	
		}		
		echo '<table cellpadding="0" cellspacing="0" border="1" id="Export2Excel" class="Consulta">	<thead>	
					<tr><th colspan="21" id="tit_tabla">'.$titulo.'</th> </tr>
					<tr><th id="partu">PARTIDAS</th><th id="deno">DENOMINACI&Oacute;N</th>
					<th>PRESUPUESTO<br/>'.$_POST['year_poa'].' (BsF)</th>
					<th>ENE<br/>(BsF)</th><th>FEB<br/>(BsF)</th><th>MAR<br/>(BsF)</th>
					<th>1ER TRIMESTRE<br/>(BsF)</th><th>ABR<br/>(BsF)</th><th>MAY<br/>(BsF)</th><th>JUN<br/>(BsF)</th>
					<th>2DO TRIMESTRE<br/>(BsF)</th><th>JUL<br/>(BsF)</th><th>AGO<br/>(BsF)</th><th>SEP<br/>(BsF)</th>
					<th>3ER TRIMESTRE<br/>(BsF)</th><th>OCT<br/>(BsF)</th><th>NOV<br/>(BsF)</th><th>DIC<br/>(BsF)</th>
					<th>4TO TRIMESTRE<br/>(BsF)<br/></th><th>TOTAL GASTO<br/>(BsF)</th>
					<th>SALDO AL<br/>'.$Hoy.' (BsF)</th>
					</tr></thead><tbody>';
	
	$arrNombresTot=getNombreCuentaTot();

// COLOCAMOS LOS GASTOS EN FORMA DE TABLA
foreach ($arrGasto as $value) {
	$arrGastoMes[$value['cuenta']]=array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,
																			 '77'=>0,'3.1'=>0,'6.1'=>0,'9.1'=>0,'12.1'=>0,'99'=>0,'saldo'=>0);
}

// LE AGREGAMOS  A $arrGastoMes LOS TOTALES x MES
foreach ($arrGasto as $value) {
	$arrGastoMes[$value['cuenta']][$value['mes']]=$value['total'];
}

// AGREGAMOS A $arrGastoMes LOS SALDOS
foreach ($arrGastoMes as $key=>$value) {
	$arrGastoMes[$key]['saldo']=$arrGastoMes[$key]['77']-$arrGastoMes[$key]['99'];
}

// CREAMOS $theMatrix (NUMEROS CON FORMATO) desde $arrGastoMes 
foreach ($arrGastoMes as $key=>$value) {
	$theMatrix[$key]=array('presupuesto'=>0,'1'=>0,'2'=>0,'3'=>0,'3.1'=>0,'4'=>0,'5'=>0,'6'=>0,'6.1'=>0,'7'=>0,'8'=>0,'9'=>0,'9.1'=>0,'10'=>0,'11'=>0,'12'=>0,'12.1'=>0,'totalgasto'=>0, 'saldo'=>0);
}

// VACIAMOS EN MATRIX $arrGastoMes con FORMATO
foreach ($arrGastoMes as $key=>$value) {
	$pre=number_format($value['77'],2,",",".");
	$m1=number_format($value['1'],2,",",".");
	$m2=number_format($value['2'],2,",",".");
	$m3=number_format($value['3'],2,",",".");
	$m4=number_format($value['4'],2,",",".");
	$m5=number_format($value['5'],2,",",".");
	$m6=number_format($value['6'],2,",",".");
	$m7=number_format($value['7'],2,",",".");
	$m8=number_format($value['8'],2,",",".");
	$m9=number_format($value['9'],2,",",".");
	$m10=number_format($value['10'],2,",",".");
	$m11=number_format($value['11'],2,",",".");
	$m12=number_format($value['12'],2,",",".");
	$t1=number_format($value['3.1'],2,",",".");
	$t2=number_format($value['6.1'],2,",",".");
	$t3=number_format($value['9.1'],2,",",".");
	$t4=number_format($value['12.1'],2,",",".");
	$total=number_format($value['99'],2,",",".");
	$saldo=number_format($value['saldo'],2,",",".");
	
	$theMatrix[$key]=array(	'presupuesto'=>$pre, '1'=>$m1,'2'=>$m2,'3'=>$m3,'t1'=>$t1,
							'4'=>$m4,'5'=>$m5,'6'=>$m6,'t2'=>$t2,
							'7'=>$m7,'8'=>$m8,'9'=>$m9,'t3'=>$t3,
							'10'=>$m10,'11'=>$m11,'12'=>$m12,'t4'=>$t4,'totalgasto'=>$total,'saldo'=>$saldo);
}

foreach ($theMatrix as $key=>$value) {
	
	$estilo="pespe_sub"; // Estilo para pespe_sub
	if (substr($key, 3,6)=='000000'){ // Estilo para pgen
		$estilo="pgen";
	}elseif (substr($key, 5,4)=='0000'){ // Estilo para psub
		$estilo="psub";
	}elseif (substr($key, 7,2)=='00'){ // Estilo para pespe
		$estilo="pespe";
	}
	
	$cuenta=substr($key, 0,3)." ".substr($key, 3,2)." ".substr($key, 5,2)." ".substr($key, 7,2);
	
	echo "<tr class=$estilo>";	
	echo "<td align='center'>$cuenta</td><td>".$arrNombresTot[$key]."</td><td align='right'>".$theMatrix[$key]['presupuesto']."</td>
			<td align='right'>".$theMatrix[$key]['1']."</td>
			<td align='right'>".$theMatrix[$key]['2']."</td>
			<td align='right'>".$theMatrix[$key]['3']."</td>
			<td align='right'>".$theMatrix[$key]['t1']."</td>
			<td align='right'>".$theMatrix[$key]['4']."</td>
			<td align='right'>".$theMatrix[$key]['5']."</td>
			<td align='right'>".$theMatrix[$key]['6']."</td>
			<td align='right'>".$theMatrix[$key]['t2']."</td>
			<td align='right'>".$theMatrix[$key]['7']."</td>
			<td align='right'>".$theMatrix[$key]['8']."</td>
			<td align='right'>".$theMatrix[$key]['9']."</td>
			<td align='right'>".$theMatrix[$key]['t3']."</td>
			<td align='right'>".$theMatrix[$key]['10']."</td>
			<td align='right'>".$theMatrix[$key]['11']."</td>
			<td align='right'>".$theMatrix[$key]['12']."</td>
			<td align='right'>".$theMatrix[$key]['t4']."</td>
			<td align='right'>".$theMatrix[$key]['totalgasto']."</td>
			<td align='right'>".$theMatrix[$key]['saldo']."</td>";
	echo "</tr>";
}

	foreach ($arrGastoMes as $key=>$value)
	{
		if (substr($key, 3,6)=='000000')
		{	
			$totales['presupuesto']+=$arrGastoMes[$key]['77'];
			$totales['01']+=$arrGastoMes[$key]['1'];
			$totales['02']+=$arrGastoMes[$key]['2'];
			$totales['03']+=$arrGastoMes[$key]['3'];
			$totales['1T']+=$arrGastoMes[$key]['3.1'];
			$totales['04']+=$arrGastoMes[$key]['4'];
			$totales['05']+=$arrGastoMes[$key]['5'];
			$totales['06']+=$arrGastoMes[$key]['6'];
			$totales['2T']+=$arrGastoMes[$key]['6.1'];
			$totales['07']+=$arrGastoMes[$key]['7'];
			$totales['08']+=$arrGastoMes[$key]['8'];
			$totales['09']+=$arrGastoMes[$key]['9'];
			$totales['3T']+=$arrGastoMes[$key]['9.1'];
			$totales['10']+=$arrGastoMes[$key]['10'];
			$totales['11']+=$arrGastoMes[$key]['11'];
			$totales['12']+=$arrGastoMes[$key]['12'];
			$totales['4T']+=$arrGastoMes[$key]['12.1'];
			$totales['totalgastos']+=$arrGastoMes[$key]['99'];
			$totales['saldo']+=$arrGastoMes[$key]['saldo'];
		}
	}

	echo "<tr id='totalizacion'><td> </td><td align='center'><b> T O T A L E S ( BsF ) </b></td>
				<td align='right'>".number_format($totales['presupuesto'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['01'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['02'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['03'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['1T'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['04'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['05'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['06'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['2T'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['07'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['08'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['09'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['3T'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['10'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['11'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['12'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['4T'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['totalgastos'], 2, ',', '.')."</td>
				<td align='right'><b>".number_format($totales['saldo'], 2, ',', '.')."</b></td></tr>";
	echo "</tbody></table>";
	}
	else { echo '<center><h1><b>NO SE ENCONTRARON ASIGNACIONES NI GASTOS</b></h1></center>';exit();}		

}	

///////////////////////////////////////////////////////////////
// SELECCION DEL BOTON "CONSULTA POR UNIDAD EJECUTORA LOCAL" //
///////////////////////////////////////////////////////////////	

if (isset($_POST['xuel']))
{		
	$arrGasto=get_gasto_x_uel($_POST['acc_cent'], $_POST['acc_esp'], $_POST['un_eje'], $_POST['year_poa'], $_POST['fuente']);
	
	if ($arrGasto)
	{
		$reg['id_ac']=$_POST['acc_cent'];
		$reg['id_ae']=$_POST['acc_esp'];
		$reg['id_ue']=$_POST['un_eje'];
		$reg=get_codigos($reg);	
		
		// CREACON DE TITULO DE LA CONSULTA
		if ($_POST['acc_cent']!=0 && $_POST['acc_esp']!=0 && $_POST['un_eje']!=0)
		{
			$titulo="UNIDAD EJECUTORA: ".$reg['cod_ac'].'-'.$reg['cod_ae'].'-'.$reg['cod_ue'];
			$titulo=$titulo."<br/>(".get_nombre_fuente($_POST['fuente']).")";		
		}
		
		if ($_POST['acc_cent']==0 && $_POST['acc_esp']==0 && $_POST['un_eje']==0)
		{
			$titulo=($_SESSION['s_ext']=="FALSE")?"CONSOLIDADO DEL SERVICIO INTERNO":"CONSOLIDADO DEL SERVICIO EXTERIOR";	
			$titulo=$titulo."<br/>(".get_nombre_fuente($_POST['fuente']).")";	
		}
		
		if ($_POST['acc_cent']!=0 && $_POST['acc_esp']==0 && $_POST['un_eje']==0)
		{
			$titulo=($_SESSION['s_ext']=="FALSE")?"CONSOLIDADO DE ACCION CENTRALIZADA ".$reg['cod_ac']." EN EL SERVICIO INTERNO":"CONSOLIDADO DE ACCION CENTRALIZADA ".$reg['cod_ac']." EN EL SERVICIO EXTERIOR";			
			$titulo=$titulo."<br/>(".get_nombre_fuente($_POST['fuente']).")";		
		}
		
		if ($_POST['acc_cent']!=0 && $_POST['acc_esp']!=0 && $_POST['un_eje']==0)
		{
			$titulo=($_SESSION['s_ext']=="FALSE")?"CONSOLIDADO DE ".$reg['cod_ac']."-".$reg['cod_ae']." EN EL SERVICIO INTERNO":"CONSOLIDADO DE ".$reg['cod_ac']."-".$reg['cod_ae']." EN EL SERVICIO EXTERIOR";			
			$titulo=$titulo."<br/>(".get_nombre_fuente($_POST['fuente']).")";	
		}		
		echo '<table cellpadding="0" cellspacing="0" border="1" id="Export2Excel" class="Consulta Misiones">	<thead>	
					<tr><th colspan="21" id="tit_tabla">'.$titulo.'</th> </tr>
					<tr><th colspan="2" id="partu">UNIDAD EJECUTORA LOCAL</th>
					<th>PRESUPUESTO<br/>'.$_POST['year_poa'].' (BsF)</th>
					<th>ENE<br/>(BsF)</th><th>FEB<br/>(BsF)</th><th>MAR<br/>(BsF)</th>
					<th>1ER TRIMESTRE<br/>(BsF)</th><th>ABR<br/>(BsF)</th><th>MAY<br/>(BsF)</th><th>JUN<br/>(BsF)</th>
					<th>2DO TRIMESTRE<br/>(BsF)</th><th>JUL<br/>(BsF)</th><th>AGO<br/>(BsF)</th><th>SEP<br/>(BsF)</th>
					<th>3ER TRIMESTRE<br/>(BsF)</th><th>OCT<br/>(BsF)</th><th>NOV<br/>(BsF)</th><th>DIC<br/>(BsF)</th>
					<th>4TO TRIMESTRE<br/>(BsF)<br/></th><th>TOTAL GASTO<br/>(BsF)</th>
					<th>SALDO AL<br/>'.$Hoy.' (BsF)</th>
					</tr></thead><tbody>';
	
	//$arrNombresTot=getNombreCuentaTot();

// COLOCAMOS LOS GASTOS EN FORMA DE TABLA
foreach ($arrGasto as $value) {
	$arrGastoMes[$value['cuenta']]=array('nombre'=>'', '0'=>0, 1=>0, 2=>0, 3=>0, '3.1'=>0,
	                                      4=>0, 5=>0, 6=>0, '6.1'=>0, 7=>0, 8=>0, 9=>0, '9.1'=>0,
																				10=>0, 11=>0, 12=>0, '12.1'=>0, '99'=>0,'saldo'=>0																		
																		   );
}

// LE AGREGAMOS  A $arrGastoMes LOS TOTALES x MES
foreach ($arrGasto as $value) {
	$arrGastoMes[$value['cuenta']][$value['mes']]=$value['total'];
	$arrGastoMes[$value['cuenta']]['nombre']=$value['nombre'];
}

// AGREGAMOS A $arrGastoMes LOS SALDOS
foreach ($arrGastoMes as $key=>$value) {
	$arrGastoMes[$key]['saldo']=$arrGastoMes[$key]['0']-$arrGastoMes[$key]['99'];
}

// CREAMOS $theMatrix (NUMEROS CON FORMATO) desde $arrGastoMes 
foreach ($arrGastoMes as $key=>$value) {
	$theMatrix[$key]=array('nombre'=>0, 'presupuesto'=>0,'1'=>0,'2'=>0,'3'=>0,'3.1'=>0,'4'=>0,'5'=>0,'6'=>0,'6.1'=>0,'7'=>0,'8'=>0,'9'=>0,'9.1'=>0,'10'=>0,'11'=>0,'12'=>0,'12.1'=>0,'totalgasto'=>0, 'saldo'=>0);
}

// VACIAMOS EN MATRIX $arrGastoMes con FORMATO
foreach ($arrGastoMes as $key=>$value) {
	$pre=number_format($value['0'],2,",",".");
	$m1=number_format($value['1'],2,",",".");
	$m2=number_format($value['2'],2,",",".");
	$m3=number_format($value['3'],2,",",".");
	$m4=number_format($value['4'],2,",",".");
	$m5=number_format($value['5'],2,",",".");
	$m6=number_format($value['6'],2,",",".");
	$m7=number_format($value['7'],2,",",".");
	$m8=number_format($value['8'],2,",",".");
	$m9=number_format($value['9'],2,",",".");
	$m10=number_format($value['10'],2,",",".");
	$m11=number_format($value['11'],2,",",".");
	$m12=number_format($value['12'],2,",",".");
	$t1=number_format($value['3.1'],2,",",".");
	$t2=number_format($value['6.1'],2,",",".");
	$t3=number_format($value['9.1'],2,",",".");
	$t4=number_format($value['12.1'],2,",",".");
	$total=number_format($value['99'],2,",",".");
	$saldo=number_format($value['saldo'],2,",",".");
	
	$theMatrix[$key]=array(	'nombre'=>$value['nombre'],'presupuesto'=>$pre, '1'=>$m1,'2'=>$m2,'3'=>$m3,'t1'=>$t1,
							'4'=>$m4,'5'=>$m5,'6'=>$m6,'t2'=>$t2,
							'7'=>$m7,'8'=>$m8,'9'=>$m9,'t3'=>$t3,
							'10'=>$m10,'11'=>$m11,'12'=>$m12,'t4'=>$t4,'totalgasto'=>$total,'saldo'=>$saldo);
}

foreach ($theMatrix as $key=>$value) {
	
/*	$estilo="pespe_sub"; // Estilo para pespe_sub
	if (substr($key, 3,6)=='000000'){ // Estilo para pgen
		$estilo="pgen";
	}elseif (substr($key, 5,4)=='0000'){ // Estilo para psub
		$estilo="psub";
	}elseif (substr($key, 7,2)=='00'){ // Estilo para pespe
		$estilo="pespe";
	}*/
	
	//$cuenta=substr($key, 0,3)." ".substr($key, 3,2)." ".substr($key, 5,2)." ".substr($key, 7,2);
	
	echo "<tr class=$estilo>";	
	echo "<td align='center'>$key</td><td>".$theMatrix[$key]['nombre']."</td><td align='right'>".$theMatrix[$key]['presupuesto']."</td>
			<td align='right'>".$theMatrix[$key]['1']."</td>
			<td align='right'>".$theMatrix[$key]['2']."</td>
			<td align='right'>".$theMatrix[$key]['3']."</td>
			<td align='right'>".$theMatrix[$key]['t1']."</td>
			<td align='right'>".$theMatrix[$key]['4']."</td>
			<td align='right'>".$theMatrix[$key]['5']."</td>
			<td align='right'>".$theMatrix[$key]['6']."</td>
			<td align='right'>".$theMatrix[$key]['t2']."</td>
			<td align='right'>".$theMatrix[$key]['7']."</td>
			<td align='right'>".$theMatrix[$key]['8']."</td>
			<td align='right'>".$theMatrix[$key]['9']."</td>
			<td align='right'>".$theMatrix[$key]['t3']."</td>
			<td align='right'>".$theMatrix[$key]['10']."</td>
			<td align='right'>".$theMatrix[$key]['11']."</td>
			<td align='right'>".$theMatrix[$key]['12']."</td>
			<td align='right'>".$theMatrix[$key]['t4']."</td>
			<td align='right'>".$theMatrix[$key]['totalgasto']."</td>
			<td align='right'>".$theMatrix[$key]['saldo']."</td>";
	echo "</tr>";
}
	
	foreach ($arrGastoMes as $key=>$value)
	{
			$totales['presupuesto']+=$arrGastoMes[$key]['0'];
			$totales['01']+=$arrGastoMes[$key]['1'];
			$totales['02']+=$arrGastoMes[$key]['2'];
			$totales['03']+=$arrGastoMes[$key]['3'];
			$totales['1T']+=$arrGastoMes[$key]['3.1'];
			$totales['04']+=$arrGastoMes[$key]['4'];
			$totales['05']+=$arrGastoMes[$key]['5'];
			$totales['06']+=$arrGastoMes[$key]['6'];
			$totales['2T']+=$arrGastoMes[$key]['6.1'];
			$totales['07']+=$arrGastoMes[$key]['7'];
			$totales['08']+=$arrGastoMes[$key]['8'];
			$totales['09']+=$arrGastoMes[$key]['9'];
			$totales['3T']+=$arrGastoMes[$key]['9.1'];
			$totales['10']+=$arrGastoMes[$key]['10'];
			$totales['11']+=$arrGastoMes[$key]['11'];
			$totales['12']+=$arrGastoMes[$key]['12'];
			$totales['4T']+=$arrGastoMes[$key]['12.1'];
			$totales['totalgastos']+=$arrGastoMes[$key]['99'];
			$totales['saldo']+=$arrGastoMes[$key]['saldo'];
	}

	echo "<tr id='totalizacion'><td colspan='2' align='center'><b> T O T A L E S ( BsF ) </b></td>
				<td align='right'>".number_format($totales['presupuesto'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['01'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['02'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['03'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['1T'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['04'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['05'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['06'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['2T'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['07'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['08'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['09'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['3T'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['10'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['11'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['12'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['4T'], 2, ',', '.')."</td>
				<td align='right'>".number_format($totales['totalgastos'], 2, ',', '.')."</td>
				<td align='right'><b>".number_format($totales['saldo'], 2, ',', '.')."</b></td></tr>";
	echo "</tbody></table>";
	}
	else { echo '<center><h1><b>NO SE ENCONTRARON ASIGNACIONES NI GASTOS</b></h1></center>';exit();}		

}	

//////////////////////////////////////////////////////////////////////////////////////
require("footer.html");
?>