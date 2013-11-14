<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prueba de LDPA</title>
</head>
<body>
<form method="post" action="pruebaLDAP.php">
Verificar Cédula: <br/>
<input name="cedula" type="text" />
<input type="submit" />
</form>
<?php
if ($_POST['cedula']!='')
{ 	
	$buscarcedula=$_POST['cedula'];
	$ldaphost="repldap.mppre.gob.ve";
	$ldapport="389";
	$resLDAP=ldap_connect($ldaphost, $ldapport) ;
	if ($resLDAP)
  {
		echo "paso 1 aprobado<br/>";
		ldap_set_option($resLDAP,LDAP_OPT_PROTOCOL_VERSION,3);
		$ldapbin=@ldap_bind($resLDAP,"cn=admin,dc=gob,dc=ve","12wsxzaq");
		if(!$ldapbin)
		{
			echo $mensaje="Usuario incorrecto";
			return false;
		}
		else
		{
			echo "si se conectó<br/>";
			$dn="ou=people,dc=mppre,dc=gob,dc=ve";
			//$filtro="(&(mail=$buscarcedula))";
			$filtro="(&(description=$buscarcedula))";
			$solonecesito=array("givenname","sn","mail","description");
			$busqueda= ldap_search($resLDAP,$dn,$filtro,$solonecesito);
			$resultado= ldap_get_entries($resLDAP,$busqueda);		
			if($resultado["count"]==0){echo "No se encontró el email de esta persona<br/>"; return false;}
			else
			{
				$nombre=$resultado[0]["givenname"];
				$apellido=$resultado[0]["sn"];
				$mail=$resultado[0]["mail"];
				$cedula=$resultado[0]["description"];
				echo $nombre[0].' '.$apellido[0].' '.$mail[0].' '. $cedula[0].'<br/>';
				//var_dump($resultado);
			}
		} 
	}
	else{ echo "No se pudo conectar con $ldaphost";}
	
}
/*
Base DN = dc=gob,dc=ve
User DN = cn=admin,dc=gob,dc=ve
user PW = 12wsxzaq
*/

/*function loginLDAP($cedula)
{  
	$buscarcedula=$cedula;
	$ldaphost="replica-ldap.mppre.gob.ve";
	$ldapport="389";
	$resLDAP=ldap_connect($ldaphost, $ldapport) or die("No se pudo conectar con $ldaphost");
	if ($resLDAP)
        {
		ldap_set_option($resLDAP,LDAP_OPT_PROTOCOL_VERSION,3);
		$ldapbin=@ldap_bind($resLDAP,"uid=zimbra,cn=admins,cn=zimbra","12wsxzaq");
		if(!$ldapbin){
			echo $mensaje="Usuario incorrecto";
			return false;
		}//else{echo "si se conecto";}
		else{
			$dn="ou=people,dc=mppre,dc=gob,dc=ve";
			$filtro="(&(description=$buscarcedula))";
			$solonecesito=array("givenname","sn","mail");
			$busqueda= ldap_search($resLDAP,$dn,$filtro,$solonecesito);
			$resultado= ldap_get_entries($resLDAP,$busqueda);
			//echo "entradas devueltas ".$resultado["count"];
			if($resultado["count"]==0){echo "No se encontr&oacute; el email de esta persona"; return false;}
			else{
				return $resultado;

				/*$nombre=$resultado[0]["givenname"];
				$apellido=$resultado[0]["sn"];
				$email=$resultado[0]["mail"];
				$_SESSION['nombre']=$nombre[0];
				$_SESSION['apellido']=$apellido[0];
				$email=$email[0];
 				$email=explode("@",$email);
				$_SESSION['email']=$email[0];
				$_SESSION['encontro']='si';
				//echo 'si encontro'.$email[0];				
			}
		}
		
	}
	else{ echo "No se pudo establecer la conexion"; }	
} */
?>
</body>
</html>
