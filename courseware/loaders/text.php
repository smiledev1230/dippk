<div id="lsn_scroll" class="crs_text_lg">
<?
$filepath = 'courseware/courses/' . $crs->course['ID'] . '/' . $lesson['ID'] . '/' . str_replace( ' ', '_', $chapter['title'] ) . '.txt';
$data = file_get_contents($filepath);
$data = str_replace( "\r\n", '<br>', $data );
$data = str_replace( array("\r","\n"), '<br>', $data );
echo $data;
?>
</div>