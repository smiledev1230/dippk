<?
session_start();
//unset($_SESSION['view']);
session_unset();
session_destroy();
header("location: index.php");
?>