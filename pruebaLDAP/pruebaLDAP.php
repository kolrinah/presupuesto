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
	var_dump(buscarLDAP($_POST['cedula']));
	
}

function buscarLDAP($patron)
{       
       $ldaphost="repldap.mppre.gob.ve";
       $ldapport="389";
       $resLDAP=ldap_connect($ldaphost, $ldapport) ;
       if(!$resLDAP){return false;}
       //echo "paso 1 aprobado<br/>";
       ldap_set_option($resLDAP,LDAP_OPT_PROTOCOL_VERSION,3);
       $ldapbin=@ldap_bind($resLDAP,"cn=admin,dc=gob,dc=ve","12wsxzaq");
       if(!$ldapbin) {return false;}
       //echo "si se conectó<br/>";
       $dn="ou=people,dc=mppre,dc=gob,dc=ve";       
       $necesito=array("givenname","sn","mail","description");
       // PRIMERO BUSCAMOS POR CI
       $filtro="(&(description=$patron))";
       $busqueda= ldap_search($resLDAP,$dn,$filtro,$necesito);
       $resultado= ldap_get_entries($resLDAP,$busqueda);		
       if($resultado["count"]==0)  // USUARIO NO ENCONTRADO
       {
         //AHORA BUSCAMOS POR MAIL
         unset ($resultado);  
         $filtro="(&(mail=$patron))";
         $busqueda= ldap_search($resLDAP,$dn,$filtro,$necesito);
         $resultado= ldap_get_entries($resLDAP,$busqueda);
         if($resultado["count"]==0){return false;} // USUARIO NO ENCOTRADO             
       }
       // USUARIO ENCONTRADO
       $nombre=$resultado[0]["givenname"];
       $apellido=$resultado[0]["sn"];
       $mail=$resultado[0]["mail"];
       $cedula=$resultado[0]["description"];
       $usuario=$cedula[0].';'.strtolower($mail[0]).';'.ucwords(strtolower($nombre[0])).';'.ucwords(strtolower($apellido[0]));
       return $usuario;
}
?>
</body>
</html>
