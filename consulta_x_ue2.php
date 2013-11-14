<?php
require("cabecera_consulta.html");
require_once ('funciones/funciones.php');

/*$sql="select cast(cod_pgen as integer), cod_psub, cod_pespe, coalesce(cod_pespe_sub,'00'), cod_pgen || cod_psub || cod_pespe || coalesce(cod_pespe_sub,'00') as cuenta,  case when cod_pespe_sub is null then nombre_pespe else nombre_pespe_sub end 
		from partidas_generales 
		inner join partidas_sub using (id_pgen) 
		inner join partidas_especificas using (id_psub)
		left outer join partidas_espe_sub using(id_pespe)
		order by cast(cod_pgen as integer), cod_psub, cod_pespe, cod_pespe_sub";

$resultado=queryDB($sql);
$arrResultado=pg_fetch_all($resultado);*/

//se obtienen todos los presupuestos
$anho=2011;
$sExt="f";
$idFuente=1;
$sql="select  cast(cast(cod_pgen as integer)|| cod_psub|| cod_pespe|| coalesce(cod_pespe_sub,'00') as integer) as cuenta, sum(p.monto_presup) as total 
		from presupuestos p
		inner join acciones_centralizadas ac using(id_ac)
		inner join un_ejec_locales uel using(id_ue,id_ae)
		inner join partidas_generales using (id_pgen)
		inner join partidas_sub using (id_psub)
		inner join partidas_especificas using(id_pespe)
		left outer join partidas_espe_sub using(id_pespe_sub)
		WHERE uel.s_ext='$sExt' AND p.id_fuente=$idFuente AND p.year_presup= '$anho-01-01'
		group by cod_pgen, cod_psub, cod_pespe, coalesce(cod_pespe_sub,'00')
		union
		select cast(cast(cod_pgen as integer)|| cod_psub||'0000' as integer) as cuenta, sum(p.monto_presup) as total 
		from presupuestos p
		inner join acciones_centralizadas ac using(id_ac)
		inner join un_ejec_locales uel using(id_ue,id_ae)
		inner join partidas_generales using (id_pgen)
		inner join partidas_sub using (id_psub)
		inner join partidas_especificas using(id_pespe)
		left outer join partidas_espe_sub using(id_pespe_sub)
		WHERE uel.s_ext='$sExt' AND p.id_fuente=$idFuente AND p.year_presup= '$anho-01-01'
		group by cod_pgen, cod_psub
		union
		select cast(cast(cod_pgen as integer)|| '000000' as integer) as cuenta, sum(p.monto_presup) as total 
		from presupuestos p
		inner join acciones_centralizadas ac using(id_ac)
		inner join un_ejec_locales uel using(id_ue,id_ae)
		inner join partidas_generales using (id_pgen)
		WHERE uel.s_ext='$sExt' AND p.id_fuente=$idFuente AND p.year_presup= '$anho-01-01'
		group by cod_pgen
		union
		select cast(cod_pgen|| cod_psub|| cod_pespe|| coalesce(cod_pespe_sub,'00') as integer) as cuenta,sum(monto_presup) as total from presupuestos p
		inner join acciones_centralizadas ac using(id_ac)
		inner join un_ejec_locales uel using(id_ue,id_ae)
		inner join partidas_generales a using (id_pgen)
		inner join partidas_sub b using (id_psub)
		inner join partidas_especificas c using(id_pespe)
		left outer join partidas_espe_sub d using(id_pespe_sub)
		where cast(p.id_pgen as character varying)||cast(p.id_psub as character varying)||cast(p.id_pespe as character varying)||cast(p.id_pespe_sub as character varying) not in (
		select cast(id_pgen as character varying)||cast(id_psub as character varying)||cast(id_pespe as character varying)||cast(id_pespe_sub as character varying) from registros_gastos)
		and year_presup='$anho-01-01' and uel.s_ext='$sExt' AND p.id_fuente=$idFuente
		group by cuenta
		order by 1";
$resPresupuestos=queryDB($sql);
$arrPresupuestos=pg_fetch_all($resPresupuestos);
//se obtienen todos los gastos registrados ordenados y sumarizados por cuenta, por mes y trimestre y totalizado 
$sql="select cast(cast(cod_pgen as integer)|| cod_psub|| cod_pespe|| coalesce(cod_pespe_sub,'00') as integer) as cuenta, date_part('month',fecha_gasto) as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-01-01' and '$anho-12-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub, cod_pespe, coalesce(cod_pespe_sub,'00'),date_part('month',fecha_gasto)
union
select cast(cast(cod_pgen as integer)|| cod_psub|| cod_pespe|| coalesce(cod_pespe_sub,'00') as integer) as cuenta, 99 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-01-01' and '$anho-12-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub, cod_pespe, coalesce(cod_pespe_sub,'00')
union
select cast(cast(cod_pgen as integer)|| cod_psub||'0000' as integer) as cuenta, date_part('month',fecha_gasto) as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-01-01' and '$anho-12-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub, date_part('month',fecha_gasto)
union
select cast(cast(cod_pgen as integer)|| cod_psub||'0000' as integer) as cuenta, 99 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-01-01' and '$anho-12-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub
union
select cast(cast(cod_pgen as integer)||'000000' as integer) as cuenta, date_part('month',fecha_gasto) as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-01-01' and '$anho-12-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer), date_part('month',fecha_gasto)
union
select cast(cast(cod_pgen as integer)||'000000' as integer) as cuenta, 99 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-01-01' and '$anho-12-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer)
union
--primer trimestre a nivel pgen
select cast(cast(cod_pgen as integer)||'000000' as integer) as cuenta, 3.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-01-01' and '$anho-03-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer)
union
--segundo trimestre a nivel pgen
select cast(cast(cod_pgen as integer)||'000000' as integer) as cuenta, 6.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-04-01' and '$anho-06-30' and id_fuente=$idFuente
group by cast(cod_pgen as integer)
union
--tercer trimestre a nivel pgen
select cast(cast(cod_pgen as integer)||'000000' as integer) as cuenta, 9.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-07-01' and '$anho-09-30' and id_fuente=$idFuente
group by cast(cod_pgen as integer)
union
--tercer trimestre a nivel pgen
select cast(cast(cod_pgen as integer)||'000000' as integer) as cuenta, 12.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-10-01' and '$anho-12-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer)
union
select cast(cast(cod_pgen as integer)|| cod_psub|| cod_pespe|| coalesce(cod_pespe_sub,'00') as integer) as cuenta, 3.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-01-01' and '$anho-03-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub, cod_pespe, coalesce(cod_pespe_sub,'00')
union
select cast(cast(cod_pgen as integer)|| cod_psub|| cod_pespe|| coalesce(cod_pespe_sub,'00') as integer) as cuenta, 6.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-04-01' and '$anho-06-30' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub, cod_pespe, coalesce(cod_pespe_sub,'00')
union
select cast(cast(cod_pgen as integer)|| cod_psub|| cod_pespe|| coalesce(cod_pespe_sub,'00') as integer) as cuenta, 9.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-07-01' and '$anho-09-30' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub, cod_pespe, coalesce(cod_pespe_sub,'00')
union
select cast(cast(cod_pgen as integer)|| cod_psub|| cod_pespe|| coalesce(cod_pespe_sub,'00') as integer) as cuenta, 12.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-09-01' and '$anho-12-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub, cod_pespe, coalesce(cod_pespe_sub,'00')
union
select cast(cast(cod_pgen as integer)|| cod_psub||'0000' as integer) as cuenta, 3.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-01-01' and '$anho-03-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub

union
select cast(cast(cod_pgen as integer)|| cod_psub||'0000' as integer) as cuenta, 6.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-04-01' and '$anho-06-30' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub

union
select cast(cast(cod_pgen as integer)|| cod_psub||'0000' as integer) as cuenta, 9.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-07-01' and '$anho-09-30' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub

union
select cast(cast(cod_pgen as integer)|| cod_psub||'0000' as integer) as cuenta, 12.1 as mes,sum(monto_gasto) as total
from registros_gastos
inner join acciones_centralizadas ac using(id_ac)
inner join un_ejec_locales uel using(id_ue,id_ae)
inner join partidas_generales using (id_pgen)
inner join partidas_sub using (id_psub)
inner join partidas_especificas using(id_pespe)
left outer join partidas_espe_sub using(id_pespe_sub)
where fecha_gasto between '$anho-10-01' and '$anho-12-31' and id_fuente=$idFuente
group by cast(cod_pgen as integer), cod_psub
order by 1,2";

$resGasto=queryDB($sql);
$arrGasto=pg_fetch_all($resGasto);
$i=0;

$arrNombresTot=getNombreCuentaTot();

foreach ($arrGasto as $value) {
	$arrGastoMes[$value['cuenta']]=array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
	$arrGastoMes[$value['cuenta']]=array('3.1'=>0,'6.1'=>0,'9.1'=>0,'12.1'=>0,'99'=>0);
}

foreach ($arrGasto as $value) {
	$arrGastoMes[$value['cuenta']][$value['mes']]=$value['total'];
}

foreach ($arrPresupuestos as $value) {
	$arrPresuOrd[$value['cuenta']]=$value['total'];
	$theMatrix[$value['cuenta']]=array('1'=>'0,00','2'=>'0,00','3'=>'0,00','t1'=>'0,00','4'=>'0,00','5'=>'0,00','6'=>'0,00','t2'=>'0,00','7'=>'0,00','8'=>'0,00','9'=>'0,00','t3'=>'0,00','10'=>'0,00','11'=>'0,00','12'=>'0,00','12.1'=>'0,00','totalgasto'=>'0,00','3.1'=>'0,00','6.1'=>'0,00','9.1'=>'0,00','t4'=>'0,00','99'=>'0,00');
}

foreach ($arrGastoMes as $key=>$value) {
	$theMatrix[$key]=array('1'=>0,'2'=>0,'3'=>0,'3.1'=>0,'4'=>0,'5'=>0,'6'=>0,'6.1'=>0,'7'=>0,'8'=>0,'9'=>0,'9.1'=>0,'10'=>0,'11'=>0,'12'=>0,'12.1'=>0,'totalgasto'=>0);
}

foreach ($arrGastoMes as $key=>$value) {
	$totalGasto=$value['3.1']+$value['6.1']+$value['9.1']+$value['12.1'];
	$totalGasto1=$totalGasto;
	$totalGasto=number_format($totalGasto,2,",",".");
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
	
	$theMatrix[$key]=array(	'1'=>$m1,'2'=>$m2,'3'=>$m3,'t1'=>$t1,
							'4'=>$m4,'5'=>$m5,'6'=>$m6,'t2'=>$t2,
							'7'=>$m7,'8'=>$m8,'9'=>$m9,'t3'=>$t3,
							'10'=>$m10,'11'=>$m11,'12'=>$m12,'t4'=>$t4,'totalgasto'=>$totalGasto,'tgastocalc'=>$totalGasto1);
}



echo "<table style='font-family:arial;font-size:.7em;background-color:#999;' border='0' CELLPADDING='2'>";
echo "<tr style='background-color:#DDD;'>";
echo "<td WIDTH='70'>Partida</td><td WIDTH='120'>Denominacion</td><td align='CENTER'>Presupuesto</td>
		<td align='CENTER'>ENE</td>
		<td align='CENTER'>FEB</td>
		<td align='CENTER'>MAR</td>
		<td align='CENTER'>1ER TRIMESTRE</td>
		<td align='CENTER'>ABR</td>
		<td align='CENTER'>MAY</td>
		<td align='CENTER'>JUN</td>
		<td align='CENTER'>2DO TRIMESTRE</td>
		<td align='CENTER'>JUL</td>
		<td align='CENTER'>AGO</td>
		<td align='CENTER'>SEP</td>
		<td align='CENTER'>3ER TRIMESTRE</td>
		<td align='CENTER'>OCT</td>
		<td align='CENTER'>NOV</td>
		<td align='CENTER'>DIC</td>
		<td align='CENTER'>4TO TRIMESTRE</td>
		<td align='CENTER'>TOTAL GASTO</td>
		<td align='CENTER'>SALDO</td>";
echo "</tr>";


foreach ($arrPresuOrd as $key=>$value) {
	$presup=$arrPresuOrd[$key];
	$saldo=number_format($presup-$theMatrix[$key]['tgastocalc'],2,",",".");
	$presup=number_format($presup,2,",",".");
	$color="#fff";
	if (substr($key, 3,8)=='000000'){
		$color="#66CCFF";
	}elseif (substr($key, 5,8)=='0000'){
		$color="#11bb11";
	}
	
	$cuenta=substr($key, 0,3)." ".substr($key, 3,2)." ".substr($key, 5,2)." ".substr($key, 7,2);
	
	echo "<tr style='background-color:$color;'>";	
	echo "<td>$cuenta</td><td>".$arrNombresTot[$key]."</td><td align='right'>$presup</td>
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
			<td align='right'>".$saldo."</td>";
	echo "</tr>";
}

function getNombreCuentaTot(){
	$sql="select cast(cod_pgen || cod_psub || cod_pespe || coalesce(cod_pespe_sub,'00') as integer) as cuenta,  case when cod_pespe_sub is null then nombre_pespe else nombre_pespe_sub  end as nombre
			from partidas_generales 
			inner join partidas_sub using (id_pgen) 
			inner join partidas_especificas using (id_psub)
			left outer join partidas_espe_sub using(id_pespe)
			union
			select cast(cod_pgen||cod_psub||'0000' as integer) as cuenta, nombre_psub as nombre from partidas_sub
			inner join partidas_generales using(id_pgen)
			union
			select cast(cod_pgen||'000000' as integer) as cuenta, nombre_pgen as nombre from partidas_generales
			order by 1";
	$res=queryDB($sql);
	$nombre=pg_fetch_all($res);
	foreach ($nombre as $value) {
		$arrNombre[$value['cuenta']]=$value['nombre'];
	}
	return $arrNombre;	
}
require("footer.html");