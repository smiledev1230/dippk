<?
require 'inc/app_top.php';

$vimeo = new Vimeo();
//$vimeo->update_database();
$video_id = 5426653;

$vimeo->update_album_videos($video_id);
echo "Update Complete.";

?>