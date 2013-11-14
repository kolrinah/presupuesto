<?php
//Definición de la Clase loginSistema 

class sistemaLogin
 {

    protected $dir_ip_usuario, $browser_usuario, $sesion_usuario, $sesion_browser, $sesion_puerto;
	protected $sesion_ip_usuario, $sesion_nivel_acceso,$sesion_id_unica, $sesion_ticket, $cookie_ticket;

    function  __construct() 
    {
	    $this->dir_ip_usuario = $_SERVER['REMOTE_ADDR']; // Obtiene IP del usuario
    	$this->browser_usuario= $_SERVER['HTTP_USER_AGENT']; // Obtiene Browser del usuario
		$this->sesion_usuario = $_SESSION['usuario']; // Sesion de usuario
	    $this->sesion_browser = $_SESSION['browser_usuario']; // Browser de Sesion
	    $this->sesion_puerto = $_SESSION ['puerto']; // Puerto de Sesion
	    $this->sesion_ip_usuario = $_SESSION ['ip_remota']; // IP de Sesion
	    $this->sesion_nivel_acceso = $_SESSION ['nivel_acceso']; // Nivel de Acceso
	    $this->sesion_id_unica = $_SESSION ['id_unica']; 
	    $this->sesion_ticket = $_SESSION ['ticket']; // Numero aleatoria para sesion
	    $this->cookie_ticket = $_COOKIE['ticket'];  // Numero aleatorio de la cookie
    }
   
    function conectarDB()  // Conexion a la Base de Datos Postgres
    {    
    	$cadena_conexion = "host=localhost port=5432 dbname=correspondenciaDB user=correspondencia password=shalom";
		return (pg_connect($cadena_conexion))? true : false;
	}

	function buscar_usuario($chequea_usuario, $chequea_clave)
	{
		$query_login = "SELECT * FROM usuarios WHERE email=$chequea_usuario AND clave=$shequea_clave";
		
		
		
	}
/*
    function login_exitoso($chequear_usuario,$chequear_clave_md) {

    $query_user_login = sprintf("SELECT * FROM `admin_information` WHERE admin_usuario=%s AND admin_password=%s",
    $this->GetSQLValueString($chequear_usuario, "text"),
    $this->GetSQLValueString($chequear_clave_md, "text"));
    $user_login = mysql_query($query_user_login) or die(mysql_error());
    $row_user_login = mysql_fetch_assoc($user_login);
    $totalRows_user_login = mysql_num_rows($user_login);
    if ($totalRows_user_login == 0) { // Show if recordset empty
    return 1; //not logged In
    } // Show if recordset empty
    else if ($totalRows_user_login == 1) { // Show if recordset not empty
    switch($row_user_login['admin_active_id']){ //errors error no in login_error
    case 5 : return 5; break;
    case 6 : return 6; break;
    case 7 : return 7; break;
    case 8 : return 8; break;
    case 9 : return 9; break;
    // Login case-1
    case 1: return $this->login_proceed($row_user_login['admin_usuario'],$row_user_login['admin_permission_id'],$row_user_login['abdohoo_app_id']); break;
    }
    } 
    }


    function check_if_already_logged_in() {
    if ((isset ( $this->sesion_usuario, $this->sesion_browser, $this->sesion_ip_usuario )) && ($this->sesion_ip_usuario == $this->dir_ip_usuario) && ($this->sesion_browser == $this->browser_usuario)) {
    $query_user_checkin = sprintf("SELECT
    *
    FROM
    login_information
    WHERE
    login_information.login_session_id =  %s AND
    login_information.login_remoteip =  %s AND
    login_information.login_randomnumber =  %s AND
    login_information.login_user_name =  %s AND
    login_information.login_nivel_acceso =  %s AND
    login_information.login_date_time <  %s
    ",
    $this->GetSQLValueString(session_id(), "text"),
    $this->GetSQLValueString($_SERVER ['REMOTE_ADDR'], "text"),
    $this->GetSQLValueString($this->cookie_ticket, "int"),
    $this->GetSQLValueString($this->sesion_usuario, "text"),
    $this->GetSQLValueString(md5($this->sesion_nivel_acceso), "text"),
    $this->GetSQLValueString(time(), "int"));
    $user_checkin = mysql_query($query_user_checkin) or die(mysql_error());
    $row_user_login = mysql_fetch_assoc($user_checkin);
    $totalRows_user_checkin = mysql_num_rows($user_checkin);
    if ($totalRows_user_checkin == 0) { // Show if recordset empty
    $this->session_end(1);
    return false; //not logged In
    } //========================================================
    else if ($totalRows_user_checkin == 1) { // successful

    return true;
    } // else 
    $this->session_end(1);
    return false;
    }
    else {
    return false;
    }
    }
    function login_proceed($usuario, $nivel_acceso, $abdohoo_unique_id) {
    $_SESSION ['usuario'] = $usuario; // only database have information
    $_SESSION ['nivel_acceso'] = $nivel_acceso; //only database have information
    $_SESSION ['browser_usuario'] = $_SERVER ['HTTP_USER_AGENT'];
    $_SESSION ['ip_remota'] = $_SERVER ['REMOTE_ADDR'];
    $_SESSION ['puerto'] = $_SERVER ['REMOTE_PORT'];
    $_SESSION ['id_unica'] = $abdohoo_unique_id; // only database have information
    $_SESSION ['ticket'] = rand ( 1, 9999999999 );
    setcookie ( 'ticket', $_SESSION ['ticket'], time () + 3600 );
    // --------------------------------------------------------------------------------------------
    return $this->log_to_database ();

    }

    function log_to_database() {
    // $this->db_connect();

    $insertSQL = sprintf ( "INSERT INTO login_information (abdohoo_unique_id, login_user_name, login_session_id, login_date_time, login_randomnumber, login_remoteip, login_useragent, login_remoteport, login_nivel_acceso) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
    $this->GetSQLValueString ( $_SESSION ['id_unica'], "int" ),
    $this->GetSQLValueString ( $_SESSION ['usuario'], "text" ),
    $this->GetSQLValueString ( session_id (), "text" ),
    $this->GetSQLValueString ( time (), "text" ),
    $this->GetSQLValueString ( $_SESSION ['ticket'], "int" ),
    $this->GetSQLValueString ( $_SESSION ['ip_remota'], "text" ),
    $this->GetSQLValueString ( $_SESSION ['browser_usuario'], "text" ),
    $this->GetSQLValueString ( $_SESSION ['puerto'], "int" ),
    $this->GetSQLValueString ( md5($_SESSION ['nivel_acceso']), "text" ) );

    $result_itd = mysql_query ( $insertSQL ) or die ( mysql_error () );
    if (! ($result_itd)) {
    //    echo "failed";
    }
    else {
    self::__construct();
    return true;
    }
    }

    function clean_login_db($logout="") {
    $cdtime = time() - (1* 01 * 60 * 60);
    // time, if one hour completes, it elans the db and logout process
    if (isset($logout) && ($logout!=""))
    {

    $clean_db_sql = "Delete from login_information WHERE login_session_id = '" .session_id(). "'";
    }
    else {
    $clean_db_sql = "Delete from login_information WHERE login_date_time <= $cdtime  ";
    }
    mysql_query($clean_db_sql) or die(mysql_error());
    }

    function session_end($destroy="") {
    $this->clean_login_db(1);

    $_SESSION = array ();
    if (isset ( $_COOKIE [session_name ()] )) {
    setcookie ( session_name (), '', time () - 42000, '/' );
    setcookie ("ticket",'',time()-4200);
    }
    if ($destroy == "") //not to destroy session if any other session is going on
    {
    session_destroy ();
    //session_start();
    }

    }


    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
    if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc () ? stripslashes ( $theValue ) : $theValue;
    }

    $theValue = function_exists ( "mysql_real_escape_string" ) ? mysql_real_escape_string ( $theValue ) : mysql_escape_string ( $theValue );

    switch ($theType) {
    case "text" :
    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
    break;
    case "long" :
    case "int" :
    $theValue = ($theValue != "") ? intval ( $theValue ) : "NULL";
    break;
    case "double" :
    $theValue = ($theValue != "") ? doubleval ( $theValue ) : "NULL";
    break;
    case "date" :
    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
    break;
    case "defined" :
    $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
    break;
    }
    return $theValue;
    }
*/

    } // end of class


    ?>