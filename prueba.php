<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Prueba de Servidor</title>
	
    </head>
    
    <body>

    <?php
						echo '<h1>Prueba </h1>';
            echo $_SERVER['SERVER_NAME']."<br/>";
            echo $_SERVER['HTTP_USER_AGENT']."<br/>";
            echo phpinfo()."<br/>";

    ?>
    </body>

</html>