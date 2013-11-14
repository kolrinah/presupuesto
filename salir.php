<?php
session_start();

if(isset($_SESSION['nombre'])){session_destroy();}

header ("Location: index.php");
exit();
?>
