<?
/* Start Session */
ob_start();
@session_start();
unset( $_SESSION['debug'] );

/* Autoload function for classes */
 function __autoload($class) {
     $filename = 'classes/class.' . strtolower($class) . '.php';
     if ( file_exists($filename)) {
         include_once($filename);
     }
 }

/* Load platform */
 $platform = new Platform();

/* Load DB */
 require_once 'inc/db.php';
 $database = new Database($db, $platform->environment);
 $conn = $database->connect();

/* Load Variables */
 require_once 'inc/tables.php';
 include 'inc/common.php';
 include 'inc/session_vars.php';
?>