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
else
{
	require_once ('funciones/funciones.php');
	// ARMAMOS NUESTRO ARRAY PARA CONSULTAR
	$reg['id_ac']=$_POST['acc_cent'];
	$reg['id_ae']=$_POST['acc_esp'];
	$reg['id_ue']=$_POST['un_eje'];
//	$presupuesto=get_tabla_presupuesto($_POST['acc_cent'], $_POST['acc_esp'], $_POST['un_eje'], $_POST['year_poa'],$_POST['fuente']);	
//	$tabla=get_tabla_consulta($_POST['acc_cent'], $_POST['acc_esp'], $_POST['un_eje'], $_POST['year_poa'],$_POST['fuente']);
	$matrix_pespe_sub= get_matrix_pespe_sub($_POST['acc_cent'], $_POST['acc_esp'], $_POST['un_eje'], $_POST['year_poa'],$_POST['fuente']);
	$matrix_pespe= get_matrix_pespe($_POST['acc_cent'], $_POST['acc_esp'], $_POST['un_eje'], $_POST['year_poa'],$_POST['fuente']);
	$matrix_psub= get_matrix_psub($_POST['acc_cent'], $_POST['acc_esp'], $_POST['un_eje'], $_POST['year_poa'],$_POST['fuente']);
	$matrix_pgen= get_matrix_pgen($_POST['acc_cent'], $_POST['acc_esp'], $_POST['un_eje'], $_POST['year_poa'],$_POST['fuente']);

	if ($matrix_pgen || $matrix_psub || $matrix_pespe || $matrix_pespe_sub)
	{
		date_default_timezone_set('America/Caracas'); // Establece la Hora de Venezuela para funciones de fecha y hora
		$fecha=getdate();	
		$fecha['mday']=($fecha['mday']<10)?"0".$fecha['mday'] :$fecha['mday'];
		$fecha['mon']=($fecha['mon']<10)?"0".$fecha['mon'] :$fecha['mon'];
		$Hoy=$fecha['mday']."/".$fecha['mon']."/".$fecha['year'];

/*		$matrix_pespe_sub=get_matrix($tabla, $presupuesto, "id_pespe_sub");
		$matrix_pespe=get_matrix($tabla, $presupuesto, "id_pespe");
		$matrix_psub=get_matrix($tabla, $presupuesto, "id_psub");
		$matrix_pgen=get_matrix($tabla, $presupuesto, "id_pgen");	*/	
		$reg=get_codigos($reg);	
		
/*	var_dump ($matrix_pespe_sub);
		echo '<br/>';
		var_dump ($matrix_pespe);
		echo '<br/>';
		var_dump ($matrix_psub);
		echo '<br/>';
		var_dump ($matrix_pgen);
		echo '<br/>';*/
		
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
	
		/*$i=1;
		$j=1;
		$k=1;
		$l=1;
		while ($i<=count($matrix_pgen))
		{
			$pgen= get_codigos($matrix_pgen[$i]);
			echo '<tr class="pgen"><td align="center">'.$pgen['cod_pgen']." 00 00 00</td><td>".strtoupper($pgen['nombre_pgen'])."</td>";    
			echo get_fila_tabla($matrix_pgen[$i])."</tr>";
			
			while ($matrix_psub[$j]['id_pgen']==$matrix_pgen[$i]['id_pgen'])
			{
				$psub= get_codigos($matrix_psub[$j]);
				echo '<tr class="psub"><td align="center">'.$pgen['cod_pgen']." ".$psub['cod_psub']." 00 00</td><td>".$psub['nombre_psub']."</td>";    
				echo get_fila_tabla($matrix_psub[$j])."</tr>";
			
				while ($matrix_pespe[$k]['id_psub']==$matrix_psub[$j]['id_psub'])
				{
					$pespe= get_codigos($matrix_pespe[$k]);
					echo '<tr class="pespe"><td align="center">'.$pgen['cod_pgen']." ".$psub['cod_psub']." ".
								$pespe['cod_pespe']." 00</td><td>".$pespe['nombre_pespe']."</td>";    
					echo get_fila_tabla($matrix_pespe[$k])."</tr>";
			
					while ($matrix_pespe_sub[$l]['id_pespe']==$matrix_pespe[$k]['id_pespe'])
					{
						if ($matrix_pespe_sub[$l]['id_pespe_sub']!=1000)
						{
							$pespe_sub= get_codigos($matrix_pespe_sub[$l]);
							echo '<tr class="pespe_sub"><td align="center">'.$pgen['cod_pgen']." ".$psub['cod_psub']." ".
										$pespe['cod_pespe']." ".$pespe_sub['cod_pespe_sub']."</td><td>".$pespe_sub['nombre_pespe_sub']."</td>";    
							echo get_fila_tabla($matrix_pespe_sub[$l])."</tr>";
						}
						$l++;
					}
					$k++;
				}
				$j++;
			}			
			$i++;
		}		*/
	}
	else { echo '<center><h1><b>NO SE ENCONTRARON ASIGNACIONES NI GASTOS</h1></b></center>';exit();}		
	}
	
	$i=1;
	while ($i<=count($matrix_pgen))
	{
		$totales['monto_presup']=$totales['monto_presup']+$matrix_pgen[$i]['monto_presup'];
		$totales['01']=$totales['01']+$matrix_pgen[$i]['01'];
		$totales['02']=$totales['02']+$matrix_pgen[$i]['02'];
		$totales['03']=$totales['03']+$matrix_pgen[$i]['03'];
		$totales['1T']=$totales['01']+$totales['02']+$totales['03'];
		$totales['04']=$totales['04']+$matrix_pgen[$i]['04'];	
		$totales['05']=$totales['05']+$matrix_pgen[$i]['05'];
		$totales['06']=$totales['06']+$matrix_pgen[$i]['06'];
		$totales['2T']=$totales['04']+$totales['05']+$totales['06'];
		$totales['07']=$totales['07']+$matrix_pgen[$i]['07'];		
		$totales['08']=$totales['08']+$matrix_pgen[$i]['08'];
		$totales['09']=$totales['09']+$matrix_pgen[$i]['09'];
		$totales['3T']=$totales['07']+$totales['08']+$totales['09'];
		$totales['10']=$totales['10']+$matrix_pgen[$i]['10'];
		$totales['11']=$totales['11']+$matrix_pgen[$i]['11'];
		$totales['12']=$totales['12']+$matrix_pgen[$i]['12'];
		$totales['4T']=$totales['10']+$totales['11']+$totales['12'];
		$totales['totalgastos']=$totales['1T']+$totales['2T']+$totales['3T']+$totales['4T'];
		$totales['saldos']=$totales['monto_presup']-$totales['totalgastos'];
		$i++;
	}
	echo "<tr id='totalizacion'><td> </td><td align='center'><b> T O T A L E S ( BsF ) </b></td>
				<td align='right'>".number_format($totales['monto_presup'], 2, ',', '.')."</td>
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
				<td align='right'><b>".number_format($totales['saldos'], 2, ',', '.')."</b></td></tr>";
	echo "</tbody></table>";
require("footer.html");
?>