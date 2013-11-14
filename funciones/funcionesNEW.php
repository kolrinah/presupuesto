<?php
require_once ('postgres_server.php');

function comprueba_usuario($usuario, $clave)
{
	$clave_crypt = md5($clave);	
	$usuario=pg_escape_string($usuario);
	$query_login = sprintf("SELECT * FROM usuarios WHERE email='%s' AND clave='%s'", $usuario, $clave_crypt);
	$resulta=queryDB($query_login);
	if (!$resulta) 
	{
  	$array['mensaje']= "ERROR! No se pudo establecer conexi&oacute;n con la Base de Datos" ;
		return $array;
	}
	if (pg_num_rows($resulta)==0 && $resulta){$array['mensaje']="¡Usuario o Contrase&ntilde;a Incorrectos!"; return $array;}	
	else
	{
		while ($row = pg_fetch_assoc($resulta)) 
	  {
			if ($row['clave']=="865cc410a1b7c60ae8a38c8761b2b342"){$array['need_change']="t";}
			else {$array['need_change']="f";}
			$array['nombre_user']=$row['nombre_user'];
			$array['id_user']=$row['id_user'];
			$array['email']=$row['email'];
			$array['admin']=$row['admin'];		
 		}
		return $array;
	}		
}

function cambiaclave($email,$clave,$clave1)
{
	$clave_crypt = md5($clave);	
	$clave1_crypt= md5($clave1);
	$query_login = sprintf("UPDATE usuarios SET clave='%s' WHERE email='%s' AND clave='%s'", $clave1_crypt, $email, $clave_crypt);
	$resulta=queryDB($query_login);
	if (pg_affected_rows($resulta)==0){return false;}
	else {return true;}	
}

function diasemana($fecha)
{
	switch ($fecha['weekday'])
	{
		case "Sunday": $nombre_dia="Domingo"; break;
		case "Monday": $nombre_dia="Lunes"; break;
		case "Tuesday": $nombre_dia="Martes"; break;
		case "Wednesday": $nombre_dia="Mi&eacute;rcoles"; break;
		case "Thursday": $nombre_dia="Jueves"; break;
		case "Friday": $nombre_dia="Viernes"; break;
		case "Saturday": $nombre_dia="S&aacute;bado"; break;	
	}
	return $nombre_dia;
}

function buildOptions($options, $selectedOption)  // Devuelve Datos HTML con las opciones de Combo-Select
{
  if (!isset($selectedOption)){$selectedOption=0;}
  foreach ($options as $value => $text)
  {
    if ($value == $selectedOption)
    {
      echo '<option value="' . $value . 
           '" selected="selected">' . $text . '</option>';
    }
    else
    {
      echo '<option value="' . $value . '">'.$text.'</option>';
    }
	}
}

function get_tipodoc_list() // Obtiene listado de tipo documentos
{
	$resulta=queryDB("SELECT * FROM tipodoc WHERE s_ext='".$_SESSION['s_ext']."' ORDER BY id_tipodoc ASC");
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	$a=array('0'); $b=array('[Seleccione]');
	while ($c= pg_fetch_assoc($resulta)) 
	{
		array_push($a,$c['id_tipodoc']) ; 
		array_push($b,$c['tipodoc']);
	}	
	$tipodoc_list=array_combine($a,$b);
	return $tipodoc_list;
}


function get_listado_acc_cent()  /// Obtiene un Listado de las acciones centralizadas
{
	//$resulta=queryDB("SELECT * FROM acciones_centralizadas ORDER BY id_ac ASC");
	$query="SELECT acciones_centralizadas.cod_ac, acciones_centralizadas.denominacion_ac, acciones_centralizadas.id_ac
FROM (acciones_centralizadas INNER JOIN acciones_especificas ON acciones_centralizadas.id_ac = acciones_especificas.id_ac) INNER JOIN un_ejec_locales ON acciones_especificas.id_ae = un_ejec_locales.id_ae
GROUP BY acciones_centralizadas.cod_ac, acciones_centralizadas.denominacion_ac, acciones_centralizadas.id_ac, un_ejec_locales.s_ext
HAVING (((un_ejec_locales.s_ext)='".$_SESSION['s_ext']."'))
ORDER BY acciones_centralizadas.cod_ac;";
	
	$resulta=queryDB($query);
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	$a=array('0'); $b=array('[Seleccione]');
	while ($c= pg_fetch_assoc($resulta)) 
	{
		array_push($a,$c['id_ac']) ; 
		array_push($b,$c['cod_ac']." ".$c['denominacion_ac']);
	}	
	$acc_cent_list=array_combine($a,$b);
	return $acc_cent_list;
}

function get_listado_pgen()  /// Obtiene un Listado de las partidas generales
{
	$resulta=queryDB("SELECT * FROM partidas_generales ORDER BY id_pgen ASC");
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	$a=array('0'); $b=array('[Seleccione]');
	while ($c= pg_fetch_assoc($resulta)) 
	{
		array_push($a,$c['id_pgen']) ; 
		array_push($b,$c['cod_pgen']." ".$c['nombre_pgen']);
	}	
	$lista=array_combine($a,$b);
	return $lista;
}

function get_fuente_presup()  /// Obtiene un Listado de las fuentes presupuestarias
{
	$resulta=queryDB("SELECT * FROM fuente_presup ORDER BY id_fuente ASC");
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	$a=array('0'); $b=array('[Seleccione]');
	while ($c= pg_fetch_assoc($resulta)) 
	{
		array_push($a,$c['id_fuente']) ; 
		array_push($b,$c['cod_fuente']." ".$c['nombre_fuente']);
	}	
	$lista=array_combine($a,$b);
	return $lista;
}

function get_nombre_fuente($id)  /// Obtiene un Listado de las partidas generales
{
	$resulta=queryDB("SELECT cod_fuente, nombre_fuente FROM fuente_presup WHERE id_fuente=".$id);
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	if (pg_num_rows($resulta)>0)
	{
		$c= pg_fetch_assoc($resulta);
		$fuente=$c['cod_fuente']." ".$c['nombre_fuente']; 
		return $fuente;
	}
	else
	{
		return "Seleccione Fuente"; // no se encontró fuente
	}
}

function validarcampos($array)
{
	foreach ($array as $key=>$value)
	{
		if (is_null($array[$key])){return false;}
	}
	return true;
}

function get_presupuestos($id_ac, $id_ae, $id_ue, $anho, $idFuente)
{
	$sExt=$_SESSION['s_ext'];
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
$resulta=queryDB($sql);
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS-get_presupuestos";return false;}
	if (pg_num_rows($resulta)>0) 
	{
		$arrPresupuestos=pg_fetch_all($resulta);	
		return $arrPresupuestos;
	}
	else
	{
		return false; // 'NO SE ENCONTRO NINGUN REGISTRO'
	}	
}

function get_gastos($id_ac, $id_ae, $id_ue, $anho, $idFuente)
{
	$sExt=$_SESSION['s_ext'];
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
	$resulta=queryDB($sql);
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS-get_gastos";return false;}
	if (pg_num_rows($resulta)>0) 
	{
		$arrGasto=pg_fetch_all($resulta);	
		return $arrGasto;
	}
	else
	{
		return false; // 'NO SE ENCONTRO NINGUN REGISTRO'
	}	
}

function getNombreCuentaTot()
{
	$sql="select cast(cod_pgen || cod_psub || cod_pespe || coalesce(cod_pespe_sub,'00') as integer) as cuenta,  case when cod_pespe_sub is null then  nombre_pespe else nombre_pespe_sub  end as nombre
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

/*function get_tabla_presupuesto($id_ac, $id_ae, $id_ue, $year_poa, $id_fuente)
{
	// CLASIFICACION SEGUN SELECCION
	
	if ($id_ac!=0 && $id_ae!=0 && $id_ue!=0)
	{
		$resulta=queryDB("SELECT presupuestos.id_pgen, presupuestos.id_psub, presupuestos.id_pespe, presupuestos.id_pespe_sub, presupuestos.monto_presup, cast(partidas_generales.cod_pgen||partidas_sub.cod_psub||partidas_especificas.cod_pespe||partidas_espe_sub.cod_pespe_sub as integer) as orden
FROM presupuestos INNER JOIN partidas_generales ON presupuestos.id_pgen=partidas_generales.id_pgen
INNER JOIN partidas_sub ON presupuestos.id_psub=partidas_sub.id_psub
INNER JOIN partidas_especificas ON presupuestos.id_pespe=partidas_especificas.id_pespe
INNER JOIN partidas_espe_sub ON presupuestos.id_pespe_sub=partidas_espe_sub.id_pespe_sub
WHERE (((presupuestos.id_ac)=".$id_ac.") AND ((presupuestos.id_ae)=".$id_ae.") 
AND ((presupuestos.id_ue)=".$id_ue.") 
AND ((presupuestos.id_fuente)=".$id_fuente.") 
AND ((presupuestos.year_presup)= '01/01/".$year_poa."'))
ORDER BY partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub;");
				
	}
	
	if ($id_ac==0 && $id_ae==0 && $id_ue==0)
	{
		$resulta=queryDB("SELECT presupuestos.id_pgen, presupuestos.id_psub, presupuestos.id_pespe, presupuestos.id_pespe_sub, presupuestos.monto_presup, cast(partidas_generales.cod_pgen||partidas_sub.cod_psub||partidas_especificas.cod_pespe||partidas_espe_sub.cod_pespe_sub as integer) as orden
FROM ((presupuestos INNER JOIN acciones_centralizadas ON presupuestos.id_ac = acciones_centralizadas.id_ac) INNER JOIN acciones_especificas ON (acciones_centralizadas.id_ac = acciones_especificas.id_ac) AND (presupuestos.id_ae = acciones_especificas.id_ae)) INNER JOIN un_ejec_locales ON (presupuestos.id_ue = un_ejec_locales.id_ue) AND (acciones_especificas.id_ae = un_ejec_locales.id_ae)
INNER JOIN partidas_generales ON presupuestos.id_pgen=partidas_generales.id_pgen
INNER JOIN partidas_sub ON presupuestos.id_psub=partidas_sub.id_psub
INNER JOIN partidas_especificas ON presupuestos.id_pespe=partidas_especificas.id_pespe
INNER JOIN partidas_espe_sub ON presupuestos.id_pespe_sub=partidas_espe_sub.id_pespe_sub
WHERE (((un_ejec_locales.s_ext)=".$_SESSION['s_ext'].") AND ((presupuestos.id_fuente)=".$id_fuente.") AND ((presupuestos.year_presup)= '01/01/".$year_poa."'))
ORDER BY partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub;");	
	}
	
	if ($id_ac!=0 && $id_ae==0 && $id_ue==0)
	{
		$resulta=queryDB("SELECT presupuestos.id_pgen, presupuestos.id_psub, presupuestos.id_pespe, presupuestos.id_pespe_sub, presupuestos.monto_presup, cast(partidas_generales.cod_pgen||partidas_sub.cod_psub||partidas_especificas.cod_pespe||partidas_espe_sub.cod_pespe_sub as integer) as orden
FROM ((presupuestos INNER JOIN acciones_centralizadas ON presupuestos.id_ac = acciones_centralizadas.id_ac) INNER JOIN acciones_especificas ON (acciones_centralizadas.id_ac = acciones_especificas.id_ac) AND (presupuestos.id_ae = acciones_especificas.id_ae)) INNER JOIN un_ejec_locales ON (presupuestos.id_ue = un_ejec_locales.id_ue) AND (acciones_especificas.id_ae = un_ejec_locales.id_ae)
INNER JOIN partidas_generales ON presupuestos.id_pgen=partidas_generales.id_pgen
INNER JOIN partidas_sub ON presupuestos.id_psub=partidas_sub.id_psub
INNER JOIN partidas_especificas ON presupuestos.id_pespe=partidas_especificas.id_pespe
INNER JOIN partidas_espe_sub ON presupuestos.id_pespe_sub=partidas_espe_sub.id_pespe_sub
WHERE (((un_ejec_locales.s_ext)=".$_SESSION['s_ext'].") AND ((presupuestos.id_ac)=".$id_ac.") AND ((presupuestos.id_fuente)=".$id_fuente.") AND ((presupuestos.year_presup)= '01/01/".$year_poa."'))
ORDER BY partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub;");	
	}
	
	if ($id_ac!=0 && $id_ae!=0 && $id_ue==0)
	{
		$resulta=queryDB("SELECT presupuestos.id_pgen, presupuestos.id_psub, presupuestos.id_pespe, presupuestos.id_pespe_sub, presupuestos.monto_presup, cast(partidas_generales.cod_pgen||partidas_sub.cod_psub||partidas_especificas.cod_pespe||partidas_espe_sub.cod_pespe_sub as integer) as orden
FROM ((presupuestos INNER JOIN acciones_centralizadas ON presupuestos.id_ac = acciones_centralizadas.id_ac) INNER JOIN acciones_especificas ON (acciones_centralizadas.id_ac = acciones_especificas.id_ac) AND (presupuestos.id_ae = acciones_especificas.id_ae)) INNER JOIN un_ejec_locales ON (presupuestos.id_ue = un_ejec_locales.id_ue) AND (acciones_especificas.id_ae = un_ejec_locales.id_ae)
INNER JOIN partidas_generales ON presupuestos.id_pgen=partidas_generales.id_pgen
INNER JOIN partidas_sub ON presupuestos.id_psub=partidas_sub.id_psub
INNER JOIN partidas_especificas ON presupuestos.id_pespe=partidas_especificas.id_pespe
INNER JOIN partidas_espe_sub ON presupuestos.id_pespe_sub=partidas_espe_sub.id_pespe_sub
WHERE (((un_ejec_locales.s_ext)=".$_SESSION['s_ext'].") AND ((presupuestos.id_fuente)=".$id_fuente.") AND ((presupuestos.id_ac)=".$id_ac.") AND ((presupuestos.id_ae)=".$id_ae.") AND ((presupuestos.year_presup)= '01/01/".$year_poa."'))
ORDER BY partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub;");	
	}
	
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS-get_tabla_presup";return false;}
	if (pg_num_rows($resulta)>0) 
	{
		$i=1;
		while ($row=pg_fetch_assoc($resulta))
		{
			$tabla[$i]['id_pgen']=$row['id_pgen'];
			$tabla[$i]['id_psub']=$row['id_psub'];
			$tabla[$i]['id_pespe']=$row['id_pespe'];
			$tabla[$i]['id_pespe_sub']=$row['id_pespe_sub'];
			$tabla[$i]['orden']=$row['orden'];
			$tabla[$i]['monto_presup']=$row['monto_presup'];
			$i++;
		}	
		return $tabla;
	}
	else
	{
		return false; // 'NO SE ENCONTRO NINGUN REGISTRO'
	}	
}

function get_tabla_consulta($id_ac, $id_ae, $id_ue, $year_poa, $id_fuente)
{
	if ($id_ac!=0 && $id_ae!=0 && $id_ue!=0)
	{
		$resulta=queryDB("SELECT registros_gastos.id_pgen, registros_gastos.id_psub, registros_gastos.id_pespe, registros_gastos.id_pespe_sub,
registros_gastos.fecha_gasto, registros_gastos.monto_gasto, cast(partidas_generales.cod_pgen||partidas_sub.cod_psub||partidas_especificas.cod_pespe||partidas_espe_sub.cod_pespe_sub as integer) as orden
FROM registros_gastos
INNER JOIN partidas_generales ON registros_gastos.id_pgen=partidas_generales.id_pgen
INNER JOIN partidas_sub ON registros_gastos.id_psub=partidas_sub.id_psub
INNER JOIN partidas_especificas ON registros_gastos.id_pespe=partidas_especificas.id_pespe
INNER JOIN partidas_espe_sub ON registros_gastos.id_pespe_sub=partidas_espe_sub.id_pespe_sub
WHERE (((registros_gastos.id_ac)=".$id_ac.") AND ((registros_gastos.id_ae)=".$id_ae.") 
AND ((registros_gastos.id_ue)=".$id_ue.") 
AND ((registros_gastos.id_fuente)=".$id_fuente.")
AND ((registros_gastos.fecha_gasto) 
BETWEEN to_date('1/1/".$year_poa."','DD/MM/YYYY') AND to_date('31/12/".$year_poa."','DD/MM/YYYY')))
ORDER BY partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub, registros_gastos.fecha_gasto;");
	}
	
	if ($id_ac==0 && $id_ae==0 && $id_ue==0)
	{
		$resulta=queryDB("SELECT registros_gastos.id_pgen, registros_gastos.id_psub, registros_gastos.id_pespe, registros_gastos.id_pespe_sub, registros_gastos.monto_gasto, registros_gastos.fecha_gasto, cast(partidas_generales.cod_pgen||partidas_sub.cod_psub||partidas_especificas.cod_pespe||partidas_espe_sub.cod_pespe_sub as integer) as orden
FROM ((acciones_centralizadas INNER JOIN acciones_especificas ON acciones_centralizadas.id_ac = acciones_especificas.id_ac) INNER JOIN un_ejec_locales ON acciones_especificas.id_ae = un_ejec_locales.id_ae) INNER JOIN registros_gastos ON (un_ejec_locales.id_ue = registros_gastos.id_ue) AND (acciones_especificas.id_ae = registros_gastos.id_ae) AND (acciones_centralizadas.id_ac = registros_gastos.id_ac)
INNER JOIN partidas_generales ON registros_gastos.id_pgen=partidas_generales.id_pgen
INNER JOIN partidas_sub ON registros_gastos.id_psub=partidas_sub.id_psub
INNER JOIN partidas_especificas ON registros_gastos.id_pespe=partidas_especificas.id_pespe
INNER JOIN partidas_espe_sub ON registros_gastos.id_pespe_sub=partidas_espe_sub.id_pespe_sub
WHERE (((un_ejec_locales.s_ext)=".$_SESSION['s_ext'].") AND ((registros_gastos.id_fuente)=".$id_fuente.") AND ((registros_gastos.fecha_gasto) BETWEEN to_date('1/1/".$year_poa."','DD/MM/YYYY') AND to_date('31/12/".$year_poa."','DD/MM/YYYY')))
ORDER BY partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub, registros_gastos.fecha_gasto;");
	}
	
	if ($id_ac!=0 && $id_ae==0 && $id_ue==0)
	{
		$resulta=queryDB("SELECT registros_gastos.id_pgen, registros_gastos.id_psub, registros_gastos.id_pespe, registros_gastos.id_pespe_sub, registros_gastos.monto_gasto, registros_gastos.fecha_gasto, cast(partidas_generales.cod_pgen||partidas_sub.cod_psub||partidas_especificas.cod_pespe||partidas_espe_sub.cod_pespe_sub as integer) as orden
FROM ((acciones_centralizadas INNER JOIN acciones_especificas ON acciones_centralizadas.id_ac = acciones_especificas.id_ac) INNER JOIN un_ejec_locales ON acciones_especificas.id_ae = un_ejec_locales.id_ae) INNER JOIN registros_gastos ON (un_ejec_locales.id_ue = registros_gastos.id_ue) AND (acciones_especificas.id_ae = registros_gastos.id_ae) AND (acciones_centralizadas.id_ac = registros_gastos.id_ac)
INNER JOIN partidas_generales ON registros_gastos.id_pgen=partidas_generales.id_pgen
INNER JOIN partidas_sub ON registros_gastos.id_psub=partidas_sub.id_psub
INNER JOIN partidas_especificas ON registros_gastos.id_pespe=partidas_especificas.id_pespe
INNER JOIN partidas_espe_sub ON registros_gastos.id_pespe_sub=partidas_espe_sub.id_pespe_sub
WHERE (((un_ejec_locales.s_ext)=".$_SESSION['s_ext'].") AND ((registros_gastos.id_fuente)=".$id_fuente.") AND ((registros_gastos.id_ac)=".$id_ac.") AND ((registros_gastos.fecha_gasto) BETWEEN to_date('1/1/".$year_poa."','DD/MM/YYYY') AND to_date('31/12/".$year_poa."','DD/MM/YYYY')))
ORDER BY partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub;");
	}
	
	if ($id_ac!=0 && $id_ae!=0 && $id_ue==0)
	{
		$resulta=queryDB("SELECT registros_gastos.id_pgen, registros_gastos.id_psub, registros_gastos.id_pespe, registros_gastos.id_pespe_sub, registros_gastos.monto_gasto, registros_gastos.fecha_gasto, cast(partidas_generales.cod_pgen||partidas_sub.cod_psub||partidas_especificas.cod_pespe||partidas_espe_sub.cod_pespe_sub as integer) as orden
FROM ((acciones_centralizadas INNER JOIN acciones_especificas ON acciones_centralizadas.id_ac = acciones_especificas.id_ac) INNER JOIN un_ejec_locales ON acciones_especificas.id_ae = un_ejec_locales.id_ae) INNER JOIN registros_gastos ON (un_ejec_locales.id_ue = registros_gastos.id_ue) AND (acciones_especificas.id_ae = registros_gastos.id_ae) AND (acciones_centralizadas.id_ac = registros_gastos.id_ac)
INNER JOIN partidas_generales ON registros_gastos.id_pgen=partidas_generales.id_pgen
INNER JOIN partidas_sub ON registros_gastos.id_psub=partidas_sub.id_psub
INNER JOIN partidas_especificas ON registros_gastos.id_pespe=partidas_especificas.id_pespe
INNER JOIN partidas_espe_sub ON registros_gastos.id_pespe_sub=partidas_espe_sub.id_pespe_sub
WHERE (((un_ejec_locales.s_ext)=".$_SESSION['s_ext'].") AND ((registros_gastos.id_fuente)=".$id_fuente.") AND ((registros_gastos.id_ac)=".$id_ac.") AND ((registros_gastos.id_ae)=".$id_ae.") AND ((registros_gastos.fecha_gasto) BETWEEN to_date('1/1/".$year_poa."','DD/MM/YYYY') AND to_date('31/12/".$year_poa."','DD/MM/YYYY')))
ORDER BY partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub;");
	}
				
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS-get_tabla_consulta";return false;}
	if (pg_num_rows($resulta)>0) 
	{
		$i=1;
		while ($row=pg_fetch_assoc($resulta))
		{
			$tabla[$i]['id_pgen']=$row['id_pgen'];
			$tabla[$i]['id_psub']=$row['id_psub'];
			$tabla[$i]['id_pespe']=$row['id_pespe'];
			$tabla[$i]['id_pespe_sub']=$row['id_pespe_sub'];	
			$tabla[$i]['orden']=$row['orden'];		
			$tabla[$i]['fecha_gasto']=$row['fecha_gasto'];			
			$tabla[$i]['monto_gasto']=$row['monto_gasto'];
			$i++;
		}	
		return $tabla;		
	}
	else
	{
		return false; // 'NO SE ENCONTRO NINGUN REGISTRO'
	}	
}

function get_matrix($tabla,$presupuesto, $id)  //Obtiene matriz de totalizados por mes
{
	if ($tabla)
	{
		$i=1;
		$m=1;
		while ($i<=count($tabla))
		{			
			$matrix[$m]['id_pgen']=$tabla[$i]['id_pgen'];
			$matrix[$m]['id_psub']=$tabla[$i]['id_psub'];
			$matrix[$m]['id_pespe']=$tabla[$i]['id_pespe'];
			$matrix[$m]['id_pespe_sub']=$tabla[$i]['id_pespe_sub'];
			$matrix[$m]['orden']=$tabla[$i]['orden'];
			while ($tabla[$i][$id]==$matrix[$m][$id])
			{
				switch (date('m', strtotime($tabla[$i]['fecha_gasto'])))
				{
					case "01": $matrix[$m]['01']=$matrix[$m]['01']+$tabla[$i]['monto_gasto'];break;
					case "02": $matrix[$m]['02']=$matrix[$m]['02']+$tabla[$i]['monto_gasto'];break;	
					case "03": $matrix[$m]['03']=$matrix[$m]['03']+$tabla[$i]['monto_gasto'];break;
					case "04": $matrix[$m]['04']=$matrix[$m]['04']+$tabla[$i]['monto_gasto'];break;
					case "05": $matrix[$m]['05']=$matrix[$m]['05']+$tabla[$i]['monto_gasto'];break;
					case "06": $matrix[$m]['06']=$matrix[$m]['06']+$tabla[$i]['monto_gasto'];break;
					case "07": $matrix[$m]['07']=$matrix[$m]['07']+$tabla[$i]['monto_gasto'];break;
					case "08": $matrix[$m]['08']=$matrix[$m]['08']+$tabla[$i]['monto_gasto'];break;
					case "09": $matrix[$m]['09']=$matrix[$m]['09']+$tabla[$i]['monto_gasto'];break;
					case "10": $matrix[$m]['10']=$matrix[$m]['10']+$tabla[$i]['monto_gasto'];break;				
					case "11": $matrix[$m]['11']=$matrix[$m]['11']+$tabla[$i]['monto_gasto'];break;
					case "12": $matrix[$m]['12']=$matrix[$m]['12']+$tabla[$i]['monto_gasto'];break;
				}														
				$i++;	
			}
			$m++;			
		}
	}
	// LE AGREGAMOS EL PRESUPUESTO A LA MATRIZ
	if ($presupuesto)
	{
		$m=1;		
		while ($m<=count($matrix))
		{				
			$p=1;
			while ($p<=count($presupuesto))
			{
				if ($matrix[$m][$id]==$presupuesto[$p][$id])
				{
					$matrix[$m]['monto_presup']=$matrix[$m]['monto_presup']+$presupuesto[$p]['monto_presup'];				
				}
				$p++;
			}
			$m++;
		}	
		//VERIFICAMOS QUE NO QUEDE PRESUPUESTO POR FUERA
		if ($matrix)
		{
			$p=1;
			while ($p<=count($presupuesto))
			{
				$m=1;		
				$matchpresup="no";
				while ($m<=count($matrix))
				{
					if ($matrix[$m][$id]==$presupuesto[$p][$id])
					{
						$matchpresup="si";
					}
					$m++;
				}
				if ($matchpresup=="no")
				{
					$matrix[$m]['id_pgen']=$presupuesto[$p]['id_pgen'];
					$matrix[$m]['id_psub']=$presupuesto[$p]['id_psub'];
					$matrix[$m]['id_pespe']=$presupuesto[$p]['id_pespe'];
					$matrix[$m]['id_pespe_sub']=$presupuesto[$p]['id_pespe_sub'];
					$matrix[$m]['orden']=$presupuesto[$p]['orden'];				
					while ($matrix[$m][$id]==$presupuesto[$p][$id])
					{
						$matrix[$m]['monto_presup']=$matrix[$m]['monto_presup']+$presupuesto[$p]['monto_presup'];
						$p++;
					}
				}
				else
				{
					$p++;		
				}
			}
			// ORDENAMOS LA MATRIX
			$m=1;
			$orden[0]=0;
			while ($m<=count($matrix))
			{
				$orden[$m]=$matrix[$m]['orden'];
				$m++;
			}
			sort($orden); // CREADO EL INDICE DE ORDEN
			unset($orden[0]);
			$o=1;
			while ($o<=count($orden))
			{
				$m=1;
				while ($m<=count($matrix))
				{
					if ($orden[$o]==$matrix[$m]['orden'])
					{
						foreach ($matrix[$m] as $key => $value)
						{
							$matrix_o[$o][$key]=$matrix[$m][$key];
						}	
					}
					$m++;
				}
				$o++;
			}
			unset($matrix);			
			$matrix=$matrix_o;
		}
		else
		{
			$m=1;
			$p=1;		
			while ($p<=count($presupuesto))
			{			
				$matrix[$m]['id_pgen']=$presupuesto[$p]['id_pgen'];
				$matrix[$m]['id_psub']=$presupuesto[$p]['id_psub'];
				$matrix[$m]['id_pespe']=$presupuesto[$p]['id_pespe'];
				$matrix[$m]['id_pespe_sub']=$presupuesto[$p]['id_pespe_sub'];
				$matrix[$m]['orden']=$presupuesto[$p]['orden'];		
				while ($presupuesto[$p][$id]==$matrix[$m][$id])
				{				
					$matrix[$m]['monto_presup']=$matrix[$m]['monto_presup']+$presupuesto[$p]['monto_presup'];	
					$p++;					
				}
				$m++;
			}		
		}
	}
	return $matrix; 
}
*/

function get_fila_tabla($array)
{
	$presup=(isset($array['monto_presup']))? $array['monto_presup']:0;
	$html=(isset($array['monto_presup']))? "<td align='right'>".number_format($array['monto_presup'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>";	
	$html=$html.((isset($array['01']))? "<td align='right'>".number_format($array['01'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
  $html=$html.((isset($array['02']))? "<td align='right'>".number_format($array['02'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html.((isset($array['03']))? "<td align='right'>".number_format($array['03'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html."<td align='right'>".number_format(($array['01']+$array['02']+$array['03']), 2, ',', '.')."</td>";
	$html=$html.((isset($array['04']))? "<td align='right'>".number_format($array['04'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html.((isset($array['05']))? "<td align='right'>".number_format($array['05'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html.((isset($array['06']))? "<td align='right'>".number_format($array['06'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html."<td align='right'>".number_format(($array['04']+$array['05']+$array['06']), 2, ',', '.')."</td>";
	$html=$html.((isset($array['07']))? "<td align='right'>".number_format($array['07'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html.((isset($array['08']))? "<td align='right'>".number_format($array['08'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html.((isset($array['09']))? "<td align='right'>".number_format($array['09'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html."<td align='right'>".number_format(($array['07']+$array['08']+$array['09']), 2, ',', '.')."</td>";
	$html=$html.((isset($array['10']))? "<td align='right'>".number_format($array['10'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html.((isset($array['11']))? "<td align='right'>".number_format($array['11'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html.((isset($array['12']))? "<td align='right'>".number_format($array['12'], 2, ',', '.')."</td>": "<td align='right'> 0,00</td>");	
	$html=$html."<td align='right'>".number_format(($array['10']+$array['11']+$array['12']), 2, ',', '.')."</td>";
	$gastotal=$array['01']+$array['02']+$array['03']+$array['04']+$array['05']+$array['06']+$array['07']+$array['08']+$array['09']+$array['10']+$array['11']+$array['12'];
	$html=$html."<td align='right'>".number_format($gastotal, 2, ',', '.')."</td>";
	$html=$html."<td align='right'>".number_format(($presup-$gastotal), 2, ',', '.')."</td>";
	return $html;
}

function get_registros_gastos($query)  /// Obtiene un Listado de todos los registros en base de datos
{
	$resulta=queryDB($query);
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	
	$campos=pg_num_fields($resulta);
	$registros=pg_num_rows($resulta);
	if ($registros==0){echo "<div><center><h1><b>NO HAY REGISTROS</b></h1></center></div>";return false;}
	
	$col=pg_fetch_assoc($resulta);
	
	echo '<table cellpadding="0" cellspacing="1" border="0" class="display" id="example">	<thead>	<tr>';
	// Cabecera y Pie de la tabla
	foreach ($col as $key=>$value)
	{
		switch ($key)
		{
			case "id_registros_gastos":echo '<th width="20px"></th><th align="center">R</th>';break;
			case "cod_fuente":echo '<th align="center">FF</th>';break;
			case "nombre_fuente":break;
			case "cod_ac":echo '<th width="150px">UNIDAD</br>EJECUTORA</th>';break;
			case "cod_ae":break;
			case "cod_ue":break;
			case "cod_pgen":echo '<th width="120px">PARTIDA</th>';break;
			case "cod_psub":break;
			case "cod_pespe":break;
			case "cod_pespe_sub":break;
			case "fecha_gasto":echo '<th width="100px">FECHA<br/>(AAAA/MM/DD)</th>';break;
			case "monto_gasto":echo '<th width="120px" >MONTO (BsF)</th>';break;
			case "tipodoc":echo '<th>DESCRIPCION</th>';break;
			case "nrodoc":break;
			case "nombre_user":echo '<th width="200px">ANALISTA</th>';break;
		}		
	}
	unset ($key,$value);	
	echo'</tr></thead><tfoot><tr>';
	
	foreach ($col as $key=>$value)
	{
		switch ($key)
		{
			case "id_registros_gastos":	echo '<th align="center">D</th><th align="center">R</th>';break;
			case "cod_fuente":echo '<th align="center">FF</th>';break;
			case "nombre_fuente":break;
			case "cod_ac":echo '<th>UNIDAD</br>EJECUTORA</th>';break;
			case "cod_ae":break;
			case "cod_ue":break;
			case "cod_pgen":echo '<th>PARTIDA</th>';break;
			case "cod_psub":break;
			case "cod_pespe":break;
			case "cod_pespe_sub":break;
			case "fecha_gasto":echo '<th>FECHA</th>';break;
			case "monto_gasto":echo '<th>MONTO (BsF)</th>';break;
			case "tipodoc":echo '<th>DESCRIPCION</th>';break;
			case "nrodoc":break;
			case "nombre_user":echo '<th>ANALISTA</th>';break;
		}	
	}
	unset ($key,$value);
	echo'</tr></tfoot>';
	
	// Cuerpo de la tabla
	echo '<tbody>';	
	do 
	{
		echo '<tr>';
		foreach ($col as $key=>$value)
		{
			switch ($key)
			{
				case "id_registros_gastos": echo '<td><a href="zoom_gasto.php?id='.$col[$key].'"><img src="images/lupa.png"/></a></td>
				<td align="center">'.$col[$key].'</td>';break;				
				case "cod_fuente":echo '<td align="center">'.$col[$key].'</td>';break;
 				case "nombre_fuente":break;
				case "cod_ac":echo '<td align="center">'.$col[$key].'-'.$col['cod_ae'].'-'.$col['cod_ue'].'</td>';break;
				case "cod_ae":break;
				case "cod_ue": break;
				case "cod_pgen":echo '<td align="center">'.$col[$key].' '.$col['cod_psub'].' '.$col['cod_pespe'].' '.$col['cod_pespe_sub'].'</td>';break;
				case "cod_psub":break;
				case "cod_pespe":break;
				case "cod_pespe_sub":break;
				case "fecha_gasto":echo '<td align="center">'.Date("Y/m/d",strtotime($col[$key])).'</td>';break;
				case "monto_gasto":echo '<td align="right">'.trim(number_format($col[$key], 2, ',', '.')).'</td>';break;
				case "tipodoc": echo '<td>'.$col[$key].' Nro: '.$col['nrodoc'].'</td>';break;
				case "nrodoc":break;
				case "nombre_user":echo '<td align="center">'.$col[$key].'</td>';break;
				default: echo '<td>'.$col[$key].'</td>';break;
			}				
		}
		unset ($key,$value);
		echo '</tr>';
	}while ($col=pg_fetch_assoc($resulta));	
	echo '</tbody></table>';
}

function get_usuarios($query)  /// Obtiene un Listado de todos los registros en base de datos
{
	$resulta=queryDB($query);
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}

	if (pg_num_rows($resulta)==0){echo "<div><center><h1><b>NO HAY USUARIOS ACTIVOS</b></h1></center></div>";return false;}
	
	$col=pg_fetch_assoc($resulta);
	
	echo '<table cellpadding="0" cellspacing="1" border="0" class="display" id="example">	<thead>	<tr>';
	// Cabecera y Pie de la tabla
	foreach ($col as $key=>$value)
	{
		switch ($key)
		{
			case "id_user":echo '<th width="20px"></th>';break;
			case "nombre_user":echo '<th width="150px">NOMBRE DE USUARIO</th>';break;
			case "email":echo '<th width="150px">EMAIL</th>';break;
			case "admin":echo '<th width="100px">TIPO DE USUARIO</th>';break;
		}		
	}
	unset ($key,$value);	
	echo'</tr></thead><tfoot><tr>';
	
	foreach ($col as $key=>$value)
	{
		switch ($key)
		{
			case "id_user":echo '<th width="20px"></th>';break;
			case "nombre_user":echo '<th width="150px">NOMBRE DE USUARIO</th>';break;
			case "email":echo '<th width="150px">EMAIL</th>';break;
			case "admin":echo '<th width="100px">TIPO DE USUARIO</th>';break;
		}	
	}
	unset ($key,$value);
	echo'</tr></tfoot>';
	
	// Cuerpo de la tabla
	echo '<tbody>';	
	do 
	{
		echo '<tr>';
		foreach ($col as $key=>$value)
		{
			switch ($key)
			{
				case "id_user": echo '<td align="center"><a href="zoom_usuario.php?id='.$col[$key].'"><img src="images/lupa.png"/></a></td>';break;				
				case "nombre_user":echo '<td align="left">'.$col[$key].'</td>';break;
				case "email":echo '<td align="left">'.$col[$key].'</td>';break;
				case "admin":echo '<td align="center">';echo (($col[$key]=="t")? 'ADMINISTRADOR': 'ANALISTA'); echo '</td>';break;
			}				
		}
		unset ($key,$value);
		echo '</tr>';
	}while ($col=pg_fetch_assoc($resulta));
	
	echo '</tbody></table>';
}

function get_listado_analistas()  /// Obtiene un Listado de los analistas en base de datos
{
	$resulta=queryDB("SELECT id_user, nombre FROM usuarios ORDER BY nombre");
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	$a=array('0'); $b=array('[Seleccione]');
	while ($c= pg_fetch_assoc($resulta)) 
	{
		array_push($a,$c['id_user']) ; 
		array_push($b,$c['nombre_user']);
	}	
	$analistas_list=array_combine($a,$b);
	return $analistas_list;
}

function get_nombre_analista($id_analista)  /// Obtiene el nombre de un analista
{
	$resulta=queryDB("SELECT nombre_user FROM usuarios WHERE id_user=".$id_analista);
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	if (pg_num_rows($resulta)>0)
	{
		$c= pg_fetch_assoc($resulta);
		$analista=$c['nombre_user']; 
		return $analista;
	}
	else
	{
		return false; // no se encontró analista
	}
}

function get_codigos($array)  /// Obtiene los códigos de un array de id's
{
	// Obtenemos cod_ac
	if (isset($array['id_ac']))
	{
		$resulta=queryDB("SELECT cod_ac FROM acciones_centralizadas WHERE id_ac='".$array['id_ac']."'");
		if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		if (pg_num_rows($resulta)>0)
		{
			$c= pg_fetch_assoc($resulta);
			$salida['cod_ac']=$c['cod_ac']; 
		}
		else
		{
		$salida['cod_ac']=NULL; // no se encontró id_ac
		}
	}
	// Obtenemos cod_ae
	if (isset($array['id_ae']))
	{
		$resulta=queryDB("SELECT cod_ae FROM acciones_especificas WHERE id_ae='".$array['id_ae']."'");
		if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		if (pg_num_rows($resulta)>0)
		{
			$c= pg_fetch_assoc($resulta);
			$salida['cod_ae']=$c['cod_ae']; 
		}
		else
		{
		$salida['cod_ae']=NULL; // no se encontró id_ae
		}
	}
	// Obtenemos cod_ue
	if (isset($array['id_ue']))
	{
		$resulta=queryDB("SELECT cod_ue FROM un_ejec_locales WHERE id_ue='".$array['id_ue']."'");
		if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		if (pg_num_rows($resulta)>0)
		{
			$c= pg_fetch_assoc($resulta);
			$salida['cod_ue']=$c['cod_ue']; 
		}
		else
		{
		$salida['cod_ue']=NULL; // no se encontró id_ue
		}
	}
	// Obtenemos cod_pgen
	if (isset($array['id_pgen']))
	{
		$resulta=queryDB("SELECT cod_pgen, nombre_pgen FROM partidas_generales WHERE id_pgen='".$array['id_pgen']."'");
		if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		if (pg_num_rows($resulta)>0)
		{
			$c= pg_fetch_assoc($resulta);
			$salida['cod_pgen']=$c['cod_pgen']; 
			$salida['nombre_pgen']=$c['nombre_pgen'];
		}
		else
		{
		$salida['cod_pgen']=NULL; // no se encontró id_pgen
		}
	}
	// Obtenemos cod_psub
	if (isset($array['id_psub']))
	{
		$resulta=queryDB("SELECT cod_psub, nombre_psub FROM partidas_sub WHERE id_psub='".$array['id_psub']."'");
		if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		if (pg_num_rows($resulta)>0)
		{
			$c= pg_fetch_assoc($resulta);
			$salida['cod_psub']=$c['cod_psub']; 
			$salida['nombre_psub']=$c['nombre_psub'];
		}
		else
		{
		$salida['cod_psub']=NULL; // no se encontró id_psub
		}
	}
	// Obtenemos cod_pespe
	if (isset($array['id_pespe']))
	{
		$resulta=queryDB("SELECT cod_pespe, nombre_pespe FROM partidas_especificas WHERE id_pespe='".$array['id_pespe']."'");
		if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		if (pg_num_rows($resulta)>0)
		{
			$c= pg_fetch_assoc($resulta);
			$salida['cod_pespe']=$c['cod_pespe']; 
			$salida['nombre_pespe']=$c['nombre_pespe'];
		}
		else
		{
		$salida['cod_pespe']=NULL; // no se encontró id_pespe
		}
	}
	// Obtenemos cod_pespe_sub
	if (isset($array['id_pespe_sub']))
	{
		$resulta=queryDB("SELECT cod_pespe_sub, nombre_pespe_sub FROM partidas_espe_sub WHERE id_pespe_sub='".$array['id_pespe_sub']."'");
		if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";}
		if (pg_num_rows($resulta)>0)
		{
			$c= pg_fetch_assoc($resulta);
			$salida['cod_pespe_sub']=$c['cod_pespe_sub']; 
			$salida['nombre_pespe_sub']=$c['nombre_pespe_sub'];
		}
		else
		{
		$salida['cod_pespe_sub']=NULL; // no se encontró id_pespe_sub
		}
	}
	return $salida;
}

function get_gasto($id)
{
	$query="SELECT registros_gastos.id_registros_gastos, fuente_presup.cod_fuente, fuente_presup.nombre_fuente, acciones_centralizadas.cod_ac, acciones_especificas.cod_ae, un_ejec_locales.cod_ue, partidas_generales.cod_pgen, partidas_sub.cod_psub, partidas_especificas.cod_pespe, partidas_espe_sub.cod_pespe_sub, registros_gastos.fecha_gasto, registros_gastos.monto_gasto, tipodoc.tipodoc, registros_gastos.nrodoc, registros_gastos.historial, usuarios.id_user
FROM ((partidas_generales INNER JOIN (((((((registros_gastos INNER JOIN usuarios ON registros_gastos.id_user = usuarios.id_user) INNER JOIN acciones_centralizadas ON registros_gastos.id_ac = acciones_centralizadas.id_ac) INNER JOIN acciones_especificas ON registros_gastos.id_ae = acciones_especificas.id_ae) INNER JOIN un_ejec_locales ON registros_gastos.id_ue = un_ejec_locales.id_ue) INNER JOIN partidas_espe_sub ON registros_gastos.id_pespe_sub = partidas_espe_sub.id_pespe_sub) INNER JOIN partidas_especificas ON registros_gastos.id_pespe = partidas_especificas.id_pespe) INNER JOIN partidas_sub ON registros_gastos.id_psub = partidas_sub.id_psub) ON partidas_generales.id_pgen = registros_gastos.id_pgen) INNER JOIN tipodoc ON registros_gastos.id_tipodoc = tipodoc.id_tipodoc) INNER JOIN fuente_presup ON registros_gastos.id_fuente = fuente_presup.id_fuente
WHERE (((registros_gastos.id_registros_gastos)='".$id."'));";

	$resulta=queryDB($query);
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	if (pg_num_rows($resulta)>0)
	{
		$array= pg_fetch_assoc($resulta);
		
		return $array;
	}
	else
	{
		return false; // no se encontró correspondencia
	}
}

function get_usuario_detalle($id)
{
	$query="SELECT usuarios.id_user ,usuarios.nombre_user, usuarios.email, usuarios.admin
FROM usuarios
WHERE (usuarios.id_user='".$id."');";

	$resulta=queryDB($query);
	if (!$resulta){ echo "ERROR CONSULTANDO BASE DE DATOS";return false;}
	if (pg_num_rows($resulta)>0)
	{
		$array= pg_fetch_assoc($resulta);
		
		return $array;
	}
	else
	{
		return false; // no se encontró correspondencia
	}
}

?>