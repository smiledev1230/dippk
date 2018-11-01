<?
$path = 'courseware/courses/' . $crs->course['ID'] . '/' . $lesson['ID'] . '/' . str_replace( ' ', '_', $chapter['title'] ) . '.pdf';
?>
<object data="<?=$path?>#toolbar=0" type="application/x-pdf" title="<?=$chapter['title']?>">
	<a href="<?=$path?>">Link to PDF</a> 
</object>