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
