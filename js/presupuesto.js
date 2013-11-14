// Script Para escribir en el Div Mensaje:
// document.getElementById("Mensaje").firstChild.nodeValue = "Nuevo Mensaje";

// SECCION PARA INICIALIZAR LOS CAMPOS DE FECHA
function DiaHoy(opcion)
	{
		var Hoy= new Array(2);
		var fecha=new Date();
		Hoy[1]= fecha.getFullYear();
		Hoy[0]=(fecha.getDate()<10)? '0'+fecha.getDate(): fecha.getDate();
		Hoy[0]+=((fecha.getMonth()+1)<10)? '/0'+(fecha.getMonth()+1):'/'+(fecha.getMonth()+1);
		Hoy[0]+= '/'+Hoy[1];
		if (opcion==1){return Hoy[1];}
		else {return Hoy[0];}
	}
	
function inicializacion()
	{
			$(function()
					{
			$('.date-pick').datePicker({startDate:'01/01/1998'});
					});
		document.getElementById("acc_cent").focus();		  		
}		
	
function DesbloquearBotonRegistrar()
	{
 	 if (ChequeoCampos()){document.getElementById("BotonRegistrar").disabled=false;}
	 else {document.getElementById("BotonRegistrar").disabled="disabled";}
	}
	
function ChequeoCampos()
{
	if (
			document.getElementById("tipodoc").value!=0
			&& 		    		
			trim(document.getElementById("nrodoc").value)!=""
			&&
			document.getElementById("fuente").value!=0
			&& 
			document.getElementById("acc_cent").value!=0
			&& 
			document.getElementById("acc_esp").value!=0
			&& 
			document.getElementById("un_eje").value!=0
			&&
			document.getElementById("pgen").value!=0
			&&
			document.getElementById("psub").value!=0
			&&
			document.getElementById("pespe").value!=0
			&&
			document.getElementById("pespe_sub").value!=0
			&&
			document.getElementById("fecha").value!=0
			&&
			document.getElementById("monto").value!=0
	 	 )	  				
	{
		return true;
	}
	//alert("UN CAMPO FALLO");
	return false;
}

function trim (myString)
 {
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
 }
	
	
	// Constructor de cadena a fecha
	function fecha(cadena) 
	{  
	 //Separador para la introduccion de las fechas  
	 var separador = "/"; 
		//Separa por dia, mes y año  
	 if ( cadena.indexOf( separador ) != -1 ) 
	 {
		 var posi1 = 0;  
		 var posi2 = cadena.indexOf( separador, posi1 + 1 );  
		 var posi3 = cadena.indexOf( separador, posi2 + 1 );  
		 this.dia = cadena.substring( posi1, posi2 );  
		 this.mes = cadena.substring( posi2 + 1, posi3 );  
		 this.mes =this.mes -1
	   this.anio = cadena.substring( posi3 + 1, cadena.length );  
	 } 
	 else
	 {  
		 this.dia = 0;  
		 this.mes = 0;  
		 this.anio = 0;     
	 }  
	}
	
	function formatMoneda(num, decSep, thousandSep, decLength)
	{
		if(num == '') return '';
		var arg, entero, decLengthpow, sign = true;cents = '';
		if(typeof(num) == 'undefined') return;
		if(typeof(decLength) == 'undefined') decLength = 2;
		if(typeof(decSep) == 'undefined') decSep = ',';
		if(typeof(thousandSep) == 'undefined') thousandSep = '.';
		if(thousandSep == '.') arg=/\./g;
		else if(thousandSep == ',') arg=/\,/g;
		if(typeof(arg) != 'undefined') num = num.toString().replace(arg, '');
		num = num.toString().replace(/,/g,'.');
		if(num.indexOf('.') != -1)
		entero = num.substring(0, num.indexOf('.'));
		else
		entero = num;
		if(isNaN(num))return "0";
		if(decLength > 0)
		{
			decLengthpow = Math.pow(10, decLength);
			sign = (num == (num = Math.abs(num)));
			num = Math.round(num * decLengthpow);
			cents = num % decLengthpow;
			num = Math.floor(num / decLengthpow).toString();
			if(cents < 10)cents = "0" + cents;
		}
		if(thousandSep != '')
		{ for(var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) 
			num = num.substring(0, num.length - (4 * i + 3)) + thousandSep + num.substring(	num.length - (4 * i + 3)); 
		}	
		else
		{
			 for(var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) 
			 num = num.substring(0, num.length - (4 * i + 3)) + num.substring(num.length - (4 * i + 3));
		}
		if(decLength > 0) return (((sign) ? '' : '-') + num + decSep + cents); else return (((sign) ? '' : '-') + num);
	}

	function onlyDigits(e, value, allowDecimal, allowNegative, allowThousand, decSep, thousandSep, decLength)
	{
		var _ret = true, key;
		if(window.event) { key = window.event.keyCode; isCtrl = window.event.ctrlKey }
		else if(e) { if(e.which) { key = e.which; isCtrl = e.ctrlKey; }}
		if(key == 8) return true;
		if(isNaN(key)) return true;
		if(key < 44 || key > 57) { return false; }
		keychar = String.fromCharCode(key);
		if(decLength == 0) allowDecimal = false;
		if(!allowDecimal && keychar == decSep || !allowNegative && keychar == '-' || !allowThousand && keychar == thousandSep) return false;
		return _ret;
	}
	
	function borrar_user()
	{
		var r=confirm("¿Está Seguro que desea Desincorporar al Usuario?");
		if (r==true)
  	{
  		window.location='borrar_usuario.php?id='+document.getElementById("id_usuario").value;
			alert("¡Usuario Eliminado!");
 		}
	}
	
	function reiniciar()
	{
		var r=confirm("¿Está Seguro que desea Reiniciar la clave del Usuario?");
		if (r==true)
  	{
  		window.location='reiniciar_clave.php?id='+document.getElementById("id_usuario").value;
			alert("¡Clave Reiniciada!");
 		}
	}
	
	function confirmar_borrar()
	{
		var r=confirm("¿Está Seguro que desea Eliminar el Registro?");
		if (r==true)
  	{
  		window.location='borrar_registro.php?id='+document.getElementById("id_registro").value+"&usuario="+document.getElementById("id_usuario").value;
			alert("¡Registro Eliminado!");
 		}
	}
				
// SECCION jquery 
    $(document).ready(function()
		{
			//if($("#_ac").val()!=null){alert($("#_ac").val())}
			
		$("#BotonRegistrar").click(function()
			{
				document.getElementById("BotonRegistrar").disabled="disabled";
				$.post("nuevo_registro.php", $("#forma").serialize(), function(data){
				$("#Mensaje").html(data);
				//$("#fecha").val("");
				$("#monto").val("");
				$("#nrodoc").val('');
				
					});												
			})
			
			$("#BotonPresupuestar").click(function()
			{
				document.getElementById("BotonPresupuestar").disabled="disabled";
				$.post("nuevo_presupuesto.php", $("#forma").serialize(), function(data){
				$("#Mensaje").html(data);
				//$("#fecha").val("");
				$("#monto").val("");
				
					});												
			})
			
			// SECCION jquery QUE AUTOCARGA LOS COMBOS-SELECT: paises, Analistas, id_misiones Dip, Despachos		
			$("select").change(function()
			{
			  // Vector para saber cuál es el siguiente combo a llenar
        var combos = new Array();
        combos['acc_cent'] = "acc_esp";
        combos['acc_esp'] = "un_eje";
				combos['pgen']="psub";
				combos['psub']="pespe";
				combos['pespe']="pespe_sub";
        // Tomo el nombre del combo al que se le ha dado el clic por ejemplo: país
        posicion = $(this).attr("name");
        // Tomo el valor de la opción seleccionada
        valor = $(this).val()       
  			// Evaluó  si es acc_cent y el valor es 0, vaciar los combos de acc_esp y un_eje
            if(posicion == 'acc_cent' && valor=="0"){
                $("#acc_esp").html('<option value="0" selected="selected">--</option>')
                $("#un_eje").html('<option value="0" selected="selected">--</option>')
								$("#cod_acc_esp").val('')
								$("#cod_acc_cent").val('')
								$("#cod_un_eje").val('')
            }else{
            /* En caso contrario agregado el letrero de cargando a el combo siguiente
            Ejemplo: Si seleccione acc_cent voy a tener que el siguiente según mi vector combos es: acc_esp  porque  combos[acc_cent] = acc_esp
                */
                $("#"+combos[posicion]).html('<option selected="selected" value="0">Cargando...</option>');
								$("#"+combos[combos[posicion]]).html('<option selected="selected" value="0">Cargando...</option>');
								$("#"+combos[combos[combos[posicion]]]).html('<option selected="selected" value="0">Cargando...</option>');
								$("#cod_"+combos[posicion]).val("");
								$("#cod_"+combos[combos[posicion]]).val("");
								$("#cod_"+combos[combos[combos[posicion]]]).val("");
                /* Verificamos si el valor seleccionado es diferente de 0 y si el combo es diferente de un_eje, esto porque no tendría caso hacer la consulta a un_eje porque no existe un combo dependiente de este */
  							if(valor!="0" || posicion !='un_eje' || posicion !='pespe_sub')
								{
									$.post("funciones/get_codigo.php",{
																					 combo:$(this).attr("name"), // Nombre del combo
		                                       id:$(this).val() // Valor seleccionado
                                     			 },function(data){
                                                    $("#cod_"+posicion).val(data);           
                                                     })   
									 $.post("funciones/combos.php",{
                                      combo:$(this).attr("name"), // Nombre del combo
                                      id:$(this).val() // Valor seleccionado
                                      },function(data){
                                                    $("#"+combos[posicion]).html(data); //Tomo el resultado de pagina e inserto los datos en el combo indicado                                                                               
                                                     })                              
								}							   
            }
        })       
   
	// AUTOCARGA DE VALORES POR SESSION
	/*	 if($("#_ac").val()!=null){
			 $("#acc_cent").trigger('change');
			 //alert($("#acc_esp").html());
 			 //$("#acc_esp").trigger('change');
			 }*/
	  })