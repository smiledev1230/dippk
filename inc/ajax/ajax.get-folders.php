<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../app_top.php';
$options = $upload->get_folders();
echo json_encode($options);
?>