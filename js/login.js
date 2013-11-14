// Script de analisis de Login

function compruebaClaves(tuinfo)
{
  if(tuinfo.usuario.value == "" || tuinfo.usuario.value == null || tuinfo.clave1.value=="" || tuinfo.clave1.value== null
	   												 || tuinfo.clave2.value== "" || tuinfo.clave2.value== null)
	{
    document.getElementById("Mensaje").firstChild.nodeValue ="¡Debe llenar todos los campos!";
		tuinfo.usuario.value=null;
	  tuinfo.clave1.value=null;
	  tuinfo.clave2.value=null;
		return(false);
  }
  else if(tuinfo.clave1.value==tuinfo.clave2.value && tuinfo.clave2.value!="" && tuinfo.clave2.value!=null)
	{
		return(true);
	}
	else
	{
  	document.getElementById("Mensaje").firstChild.nodeValue ="¡Las contraseñas nuevas deben ser iguales!";
		tuinfo.usuario.value="";
  	tuinfo.clave1.value="";
	  tuinfo.clave2.value="";
		return(false);
	}
	
 }

function compruebaFormulario(tuinfo)
{
           if(tuinfo.usuario.value == "" || tuinfo.usuario.value == null || tuinfo.clave.value=="" || tuinfo.clave.value== null)
		    {
              document.getElementById("Mensaje").firstChild.nodeValue ="Escriba su email y contraseña";
							return(false);
            }
            else{
              return(true);
            }
 }
 
 function compruebaNewUser(tuinfo)
{
           if(trim(tuinfo.nombre.value) == "" || tuinfo.nombre.value == null || trim(tuinfo.email.value)=="" || tuinfo.email.value== null)
		    {
              document.getElementById("Mensaje").firstChild.nodeValue ="No Puede Haber Campos Vacíos";
							return(false);
            }
            else{
              return(true);
            }
 }
 
 function trim (myString)
 {
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
 }
 
$(document).ready(function() {
		$('#example').dataTable( {
		"sPaginationType": "full_numbers"
			} );
	} );
