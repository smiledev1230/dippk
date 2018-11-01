<?
$docs = new Documents();
$doc_counts = $docs->get_counts($_SESSION['sect']);
// hard-coding for demo
$doc_counts['Courses'] = 9;
$doc_counts['Videos'] = 9;
// end demo
$categories = array();
$category_view = array();
foreach( $docs->categories as $doc_category ) {
	$categories[$doc_category['title']] = $doc_category['link_type'];
	$category_view[$doc_category['link_type']] = $doc_category['view'];
}
?>
<div class="browse_head">BROWSE CATEGORIES A</div>
<?
foreach( $categories as $view_name => $view_link ) {
	$display_name = ( $doc_counts[$view_name] ) ? $view_name . ' <span class="count">(' . $doc_counts[$view_name] . ')</span>': $view_name;
	if( ( !$req['view'] && $_SESSION['page'] == 'section' && $view_name == 'Featured' ) || $req['view'] == $view_link ) {
		?><div class="browse_active"><?=$display_name?></div><?
	} else {
		?>
		<div class="browse_link">
			<a class="link_<?=$view_link?>"><?=$display_name?></a>
		</div>
		<?
	}
}
?>