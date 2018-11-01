<?php
$filename = $_GET['fn'];
$path_arr = array_reverse( explode( '/', $filename ) );
header('Content-disposition: attachment; filename="' . $path_arr[0] . '"' );
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
readfile($filename);
?>