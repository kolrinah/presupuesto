<?php 
	header("Content-type: application/vnd.ms-excel; name='excel'");  
	header("Content-Disposition: filename=HojaExcel.xls");  
  header("Pragma: no-cache");
	header("Expires: 0");  
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false); // required for certain browsers 
	echo $_POST['datos_a_enviar'];  
?>